<?php

namespace Database\Seeders;

use App\Models\Afspraak;
use App\Models\Beschikbaarheid;
use App\Models\Dienst;
use App\Models\Kapper;
use App\Models\Medewerker;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KapperDemoSeeder extends Seeder
{
    public function run(): void
    {
        $demoEmails = [
            'demo@kaply.nl',
            'emma.demo@kaply.nl',
            'liam.demo@kaply.nl',
            'sophie.demo@kaply.nl',
            'noah.demo@kaply.nl',
            'mia.demo@kaply.nl',
            'daan.demo@kaply.nl',
        ];

        // Opruimen
        $demoUser = User::where('email', 'demo@kaply.nl')->first();
        if ($demoUser?->kapper) {
            $k = $demoUser->kapper;
            Review::where('kapper_id', $k->id)->delete();
            Afspraak::where('kapper_id', $k->id)->delete();
            Medewerker::where('kapper_id', $k->id)->delete();
            Dienst::where('kapper_id', $k->id)->delete();
            Beschikbaarheid::where('kapper_id', $k->id)->delete();
            $k->delete();
        }
        User::whereIn('email', $demoEmails)->delete();

        // Kapper account
        $user = User::create([
            'name'     => 'Boris Osei',
            'email'    => 'demo@kaply.nl',
            'password' => Hash::make('Demo2026!'),
            'role'     => 'kapper',
        ]);

        $kapper = Kapper::create([
            'user_id'        => $user->id,
            'salon_naam'     => 'Boris Barber Studio',
            'slug'           => Kapper::generateSlug('Boris Barber Studio'),
            'adres'          => 'Kalverstraat 88',
            'stad'           => 'Amsterdam',
            'telefoon'       => '020-1234567',
            'bio'            => 'Meer dan 10 jaar ervaring in heren- en damesknippen. Gespecialiseerd in moderne stijlen en klassiek barbieren.',
            'actief'         => true,
            'buffer_minuten' => 10,
        ]);

        // Beschikbaarheid ma–za 09:00–18:00 (dag_van_week 0=ma, 5=za)
        for ($dag = 0; $dag <= 5; $dag++) {
            Beschikbaarheid::create([
                'kapper_id'    => $kapper->id,
                'dag_van_week' => $dag,
                'start_tijd'   => '09:00',
                'eind_tijd'    => '18:00',
            ]);
        }

        // Medewerkers
        $ravi  = Medewerker::create(['kapper_id' => $kapper->id, 'naam' => 'Ravi Sharma',  'actief' => true]);
        $sofia = Medewerker::create(['kapper_id' => $kapper->id, 'naam' => 'Sofia Meijer', 'actief' => true]);

        // Diensten (prijs in centen)
        $knippen_heren  = Dienst::create(['kapper_id' => $kapper->id, 'naam' => 'Knippen heren',      'duur_minuten' => 30,  'prijs' => 1800]);
        $knippen_dames  = Dienst::create(['kapper_id' => $kapper->id, 'naam' => 'Knippen dames',      'duur_minuten' => 45,  'prijs' => 2800]);
        $knippen_wassen = Dienst::create(['kapper_id' => $kapper->id, 'naam' => 'Knippen + Wassen',   'duur_minuten' => 45,  'prijs' => 2500]);
        $kleuren        = Dienst::create(['kapper_id' => $kapper->id, 'naam' => 'Kleuren (volledig)', 'duur_minuten' => 90,  'prijs' => 6500]);
        $highlights     = Dienst::create(['kapper_id' => $kapper->id, 'naam' => 'Highlights',         'duur_minuten' => 120, 'prijs' => 8500]);
        $baard          = Dienst::create(['kapper_id' => $kapper->id, 'naam' => 'Baard bijwerken',    'duur_minuten' => 20,  'prijs' => 1200]);

        // Klanten
        $klanten = [
            User::create(['name' => 'Emma de Vries',    'email' => 'emma.demo@kaply.nl',   'password' => Hash::make('password'), 'role' => 'klant']),
            User::create(['name' => 'Liam Bakker',      'email' => 'liam.demo@kaply.nl',   'password' => Hash::make('password'), 'role' => 'klant']),
            User::create(['name' => 'Sophie Smit',      'email' => 'sophie.demo@kaply.nl', 'password' => Hash::make('password'), 'role' => 'klant']),
            User::create(['name' => 'Noah Jansen',      'email' => 'noah.demo@kaply.nl',   'password' => Hash::make('password'), 'role' => 'klant']),
            User::create(['name' => 'Mia van den Berg', 'email' => 'mia.demo@kaply.nl',    'password' => Hash::make('password'), 'role' => 'klant']),
            User::create(['name' => 'Daan Visser',      'email' => 'daan.demo@kaply.nl',   'password' => Hash::make('password'), 'role' => 'klant']),
        ];

        // Helper: maak afspraak aan
        $maakAfspraak = function (User $klant, Dienst $dienst, Medewerker $medewerker, string $datum, string $start, string $status, string $betaalmethode = 'in_zaak') use ($kapper): Afspraak {
            $eind = Carbon::parse("{$datum} {$start}")->addMinutes($dienst->duur_minuten)->format('H:i');
            return Afspraak::create([
                'klant_id'      => $klant->id,
                'kapper_id'     => $kapper->id,
                'dienst_id'     => $dienst->id,
                'medewerker_id' => $medewerker->id,
                'datum'         => $datum,
                'start_tijd'    => $start,
                'eind_tijd'     => $eind,
                'status'        => $status,
                'betaalmethode' => $betaalmethode,
            ]);
        };

        // Vind werkdagen (niet zondag) in het verleden deze maand
        $vergadenDagen = collect();
        $d = today()->copy()->subDay();
        while ($vergadenDagen->count() < 12 && $d->month === today()->month && $d->year === today()->year) {
            if ($d->dayOfWeek !== Carbon::SUNDAY) {
                $vergadenDagen->push($d->copy());
            }
            $d->subDay();
        }

        // Verleden afspraken config
        $vergadenConfig = [
            [$klanten[0], $knippen_heren,  $ravi,  '09:00', 'voltooid'],
            [$klanten[1], $knippen_wassen, $sofia, '10:00', 'voltooid'],
            [$klanten[3], $baard,          $ravi,  '11:00', 'voltooid'],
            [$klanten[2], $knippen_dames,  $sofia, '13:00', 'voltooid'],
            [$klanten[4], $kleuren,        $sofia, '14:00', 'voltooid'],
            [$klanten[5], $knippen_heren,  $ravi,  '09:30', 'voltooid'],
            [$klanten[2], $highlights,     $sofia, '10:00', 'voltooid'],
            [$klanten[0], $knippen_heren,  $ravi,  '15:30', 'voltooid'],
            [$klanten[1], $knippen_wassen, $sofia, '13:30', 'voltooid'],
            [$klanten[3], $baard,          $ravi,  '16:00', 'voltooid'],
            [$klanten[5], $knippen_heren,  $ravi,  '11:00', 'no_show'],
            [$klanten[4], $knippen_dames,  $sofia, '14:30', 'no_show'],
        ];

        $voltooideAfspraken = [];
        foreach ($vergadenDagen as $i => $dag) {
            if (!isset($vergadenConfig[$i])) break;
            [$klant, $dienst, $medewerker, $tijd, $status] = $vergadenConfig[$i];
            $afspraak = $maakAfspraak($klant, $dienst, $medewerker, $dag->toDateString(), $tijd, $status);
            if ($status === 'voltooid') {
                $voltooideAfspraken[] = ['afspraak' => $afspraak, 'klant' => $klant];
            }
        }

        // Vandaag (alleen als geen zondag)
        if (today()->dayOfWeek !== Carbon::SUNDAY) {
            $maakAfspraak($klanten[0], $knippen_heren, $ravi,  today()->toDateString(), '10:00', 'gepland');
            $maakAfspraak($klanten[2], $kleuren,       $sofia, today()->toDateString(), '13:00', 'gepland', 'online');
        }

        // Komende 5 werkdagen
        $toekomstigeDagen = collect();
        $d = today()->copy()->addDay();
        while ($toekomstigeDagen->count() < 5) {
            if ($d->dayOfWeek !== Carbon::SUNDAY) {
                $toekomstigeDagen->push($d->copy());
            }
            $d->addDay();
        }

        $komendeCfg = [
            [$klanten[1], $knippen_wassen, $sofia, '09:30'],
            [$klanten[2], $highlights,     $sofia, '10:00'],
            [$klanten[0], $knippen_heren,  $ravi,  '14:00'],
            [$klanten[4], $baard,          $ravi,  '11:00'],
            [$klanten[3], $knippen_dames,  $sofia, '15:30'],
        ];

        foreach ($toekomstigeDagen as $i => $dag) {
            [$klant, $dienst, $medewerker, $tijd] = $komendeCfg[$i];
            $maakAfspraak($klant, $dienst, $medewerker, $dag->toDateString(), $tijd, 'gepland');
        }

        // Reviews op 5 voltooide afspraken
        $reviewData = [
            [5, 'Geweldige kapper! Altijd blij als ik hier vandaan kom.'],
            [5, 'Super vriendelijk en precies wat ik vroeg. Aanrader!'],
            [4, 'Professioneel en snel. Ga zeker terug.'],
            [5, 'Top resultaat, mijn haar ziet er fantastisch uit.'],
            [4, 'Altijd een topervaring bij Boris Barber Studio.'],
        ];
        foreach (array_slice($voltooideAfspraken, 0, count($reviewData)) as $i => $item) {
            Review::create([
                'kapper_id'   => $kapper->id,
                'klant_id'    => $item['klant']->id,
                'afspraak_id' => $item['afspraak']->id,
                'rating'      => $reviewData[$i][0],
                'tekst'       => $reviewData[$i][1],
                'zichtbaar'   => true,
            ]);
        }

        $this->command->info('');
        $this->command->info('✓ Demo seed klaar!');
        $this->command->info('  Login:  demo@kaply.nl');
        $this->command->info('  Wacht:  Demo2026!');
        $this->command->info('  Salon:  Boris Barber Studio, Amsterdam');
        $this->command->info('');
    }
}
