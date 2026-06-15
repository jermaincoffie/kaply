<?php

namespace Database\Seeders;

use App\Models\Afspraak;
use App\Models\Beschikbaarheid;
use App\Models\Dienst;
use App\Models\Kapper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VerzetTestSeeder extends Seeder
{
    public function run(): void
    {
        // Testklant
        $klant = User::firstOrCreate(
            ['email' => 'testklant@kaply.nl'],
            [
                'name'     => 'Test Klant',
                'password' => Hash::make('password'),
                'role'     => 'klant',
            ]
        );

        // Gebruik eerste actieve kapper
        $kapper = Kapper::where('actief', true)->with('diensten')->first();

        if (!$kapper) {
            $this->command->error('Geen actieve kapper gevonden. Draai eerst DemoSeeder.');
            return;
        }

        $dienst = $kapper->diensten->first();

        if (!$dienst) {
            $this->command->error('Kapper heeft geen diensten.');
            return;
        }

        // Zoek een dag waarop de kapper beschikbaar is (volgende 7 dagen)
        $beschikbareDag = null;
        for ($i = 1; $i <= 7; $i++) {
            $dag = Carbon::today()->addDays($i);
            $dagVanWeek = $dag->dayOfWeekIso - 1; // 0=ma, 6=zo
            $beschikbaar = Beschikbaarheid::where('kapper_id', $kapper->id)
                ->where('dag_van_week', $dagVanWeek)
                ->first();
            if ($beschikbaar) {
                $beschikbareDag = ['datum' => $dag, 'beschikbaarheid' => $beschikbaar];
                break;
            }
        }

        if (!$beschikbareDag) {
            $this->command->error('Geen beschikbare dag gevonden voor kapper in komende 7 dagen.');
            return;
        }

        $datum     = $beschikbareDag['datum']->toDateString();
        $startTijd = substr($beschikbareDag['beschikbaarheid']->start_tijd, 0, 5);
        // Plan om 1 uur na opening
        $start = Carbon::parse($datum . ' ' . $startTijd)->addHour();
        $eind  = $start->copy()->addMinutes($dienst->duur_minuten);

        $afspraak = Afspraak::firstOrCreate(
            [
                'klant_id'   => $klant->id,
                'kapper_id'  => $kapper->id,
                'datum'      => $datum,
                'start_tijd' => $start->format('H:i'),
            ],
            [
                'dienst_id'   => $dienst->id,
                'eind_tijd'   => $eind->format('H:i'),
                'status'      => 'gepland',
                'betaalmethode' => 'in_zaak',
            ]
        );

        $this->command->info('✅ Testklant aangemaakt:');
        $this->command->info('   Email:    testklant@kaply.nl');
        $this->command->info('   Wachtwoord: password');
        $this->command->info('');
        $this->command->info('✅ Afspraak aangemaakt:');
        $this->command->info('   Salon:    ' . $kapper->salon_naam);
        $this->command->info('   Dienst:   ' . $dienst->naam);
        $this->command->info('   Datum:    ' . $datum);
        $this->command->info('   Tijd:     ' . $start->format('H:i') . ' - ' . $eind->format('H:i'));
        $this->command->info('');
        $this->command->info('👉 Login op /klant/inloggen en ga naar Mijn Afspraken om te verzetten.');
    }
}
