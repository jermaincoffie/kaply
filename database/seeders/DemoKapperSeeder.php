<?php

namespace Database\Seeders;

use App\Models\Afspraak;
use App\Models\Beschikbaarheid;
use App\Models\Dienst;
use App\Models\Kapper;
use App\Models\KapperGalerij;
use App\Models\Medewerker;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DemoKapperSeeder extends Seeder
{
    public function run(): void
    {
        // Cleanup
        $bestaand = Kapper::where('slug', 'demo-salon')->first();
        if ($bestaand) {
            $bestaand->afspraken()->delete();
            $bestaand->reviews()->each(fn($r) => User::find($r->klant_id)?->delete());
            $bestaand->reviews()->delete();
            $bestaand->galerij()->delete();
            $bestaand->medewerkers()->delete();
            $bestaand->diensten()->delete();
            $bestaand->beschikbaarheden()->delete();
            $bestaand->user?->delete();
            $bestaand->delete();
        }
        Storage::disk('public')->deleteDirectory('demo/galerij');
        User::where('email', 'like', 'demo-klant-%@kaply.nl')->delete();

        $user = User::create([
            'name'     => 'Marco van den Berg',
            'email'    => 'demo@kaply.nl',
            'password' => Hash::make(str()->random(24)),
            'role'     => 'kapper',
        ]);

        $kapper = Kapper::create([
            'user_id'             => $user->id,
            'salon_naam'          => 'Salon Marco',
            'slug'                => 'demo-salon',
            'stad'                => 'Amsterdam',
            'adres'               => 'Haarlemmerdijk 42',
            'telefoon'            => '0612345678',
            'bio'                 => 'Al meer dan 10 jaar dé kapper van Amsterdam-West. Gespecialiseerd in klassieke herenknipsels, fades en baardverzorging. Elke klant verdient een perfecte coupe.',
            'abonnement_status'   => 'actief',
            'actief'              => true,
            'onboarding_voltooid' => true,
        ]);

        // Diensten
        $dienstData = [
            ['naam' => 'Klassiek knippen',  'prijs' => 2500, 'duur_minuten' => 30],
            ['naam' => 'Fade & blend',       'prijs' => 3000, 'duur_minuten' => 45],
            ['naam' => 'Knippen + baard',    'prijs' => 4000, 'duur_minuten' => 60],
            ['naam' => 'Baard trimmen',      'prijs' => 1500, 'duur_minuten' => 20],
            ['naam' => 'Kinderen knippen',   'prijs' => 1800, 'duur_minuten' => 25],
        ];

        $diensten = [];
        foreach ($dienstData as $d) {
            $diensten[] = Dienst::create(array_merge($d, ['kapper_id' => $kapper->id]));
        }

        // Beschikbaarheid ma t/m za 09:00-18:00 (0=maandag)
        foreach (range(0, 5) as $dag) {
            Beschikbaarheid::create([
                'kapper_id'    => $kapper->id,
                'dag_van_week' => $dag,
                'start_tijd'   => '09:00',
                'eind_tijd'    => '18:00',
            ]);
        }

        // Medewerkers
        foreach (['Jayden', 'Sven', 'Tymo'] as $naam) {
            Medewerker::create([
                'kapper_id' => $kapper->id,
                'naam'      => $naam,
                'actief'    => true,
            ]);
        }

        // Galerij
        foreach ([
            'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1605497788044-5a32c7078486?w=800&h=600&fit=crop',
        ] as $i => $url) {
            KapperGalerij::create([
                'kapper_id' => $kapper->id,
                'pad'       => $url,
                'volgorde'  => $i + 1,
            ]);
        }

        // Reviews met gekoppelde afspraken
        $reviewData = [
            ['naam' => 'Kevin de Vries',    'rating' => 5, 'tekst' => 'Top kapper! Al jaren mijn vaste plek. Marco weet precies wat ik wil zonder dat ik het hoef uit te leggen.'],
            ['naam' => 'Thomas Brouwer',    'rating' => 5, 'tekst' => 'Geweldige fade, scherpe lijn en relaxte sfeer. Ben hier voor het eerst maar kom zeker terug.'],
            ['naam' => 'Mikael Janssen',    'rating' => 4, 'tekst' => 'Goed resultaat en vlotte service. Kon snel een afspraak maken via de app.'],
            ['naam' => 'Daan van der Berg', 'rating' => 5, 'tekst' => 'Beste kapper van Amsterdam. Baard en knipbeurt allebei perfect verzorgd.'],
        ];

        $reviewKlantIds = [];
        foreach ($reviewData as $idx => $r) {
            $klant = User::create([
                'name'     => $r['naam'],
                'email'    => "demo-klant-{$idx}@kaply.nl",
                'password' => Hash::make(str()->random(20)),
                'role'     => 'klant',
            ]);
            $reviewKlantIds[] = $klant->id;

            $afspraak = Afspraak::create([
                'klant_id'      => $klant->id,
                'kapper_id'     => $kapper->id,
                'dienst_id'     => $diensten[0]->id,
                'datum'         => now()->subDays(($idx + 1) * 14)->toDateString(),
                'start_tijd'    => '10:00',
                'eind_tijd'     => '10:30',
                'status'        => 'voltooid',
                'betaalmethode' => 'in_zaak',
            ]);

            Review::create([
                'kapper_id'   => $kapper->id,
                'klant_id'    => $klant->id,
                'afspraak_id' => $afspraak->id,
                'rating'      => $r['rating'],
                'tekst'       => $r['tekst'],
                'zichtbaar'   => true,
            ]);
        }

        // Extra vaste klanten voor historische afspraken
        $extraKlantNamen = [
            'Ahmed Yilmaz', 'Niels de Groot', 'Luuk Smit', 'Jesse Peters',
            'Rick van Dam', 'Omar Hassan', 'Stijn Kuiper', 'Lars Visser',
        ];
        $extraKlantIds = [];
        foreach ($extraKlantNamen as $i => $naam) {
            $klant = User::create([
                'name'     => $naam,
                'email'    => 'demo-klant-' . ($i + 4) . '@kaply.nl',
                'password' => Hash::make(str()->random(20)),
                'role'     => 'klant',
            ]);
            $extraKlantIds[] = $klant->id;
        }

        $alleKlantIds = array_merge($reviewKlantIds, $extraKlantIds);

        // Historische + toekomstige afspraken
        $this->historieSeeden($kapper, $diensten, $alleKlantIds);
    }

    private function historieSeeden(Kapper $kapper, array $diensten, array $klantIds): void
    {
        $walkInNamen = [
            'Milan Boer', 'Max Konings', 'Tim Hoekstra', 'Bram Schouten', 'Roy Janssen',
            'Finn van Dijk', 'Mats de Boer', 'Dylan Wolters', 'Sander Meijer', 'Bas Postma',
            'Joris van Leeuwen', 'Robin van der Berg', 'Koen Martens', 'Bram Kok', 'Sam Huisman',
            'Dries van Aert', 'Nathan de Wit', 'Roan Peters', 'Cas Mulder', 'Bo Vermeer',
        ];

        $tijdslots = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30',
                      '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00'];

        // Deterministische pseudo-random (herhaalbaar)
        $seed = 1337;
        $rnd  = function (int $min, int $max) use (&$seed): int {
            $seed = (($seed * 1103515245) + 12345) & 0x7fffffff;
            return $min + ($seed % ($max - $min + 1));
        };

        $gebruikte = []; // track [datum => [start_tijd => true]] om dubbelen te voorkomen

        for ($dag = -90; $dag <= 14; $dag++) {
            $datum = Carbon::today()->addDays($dag);

            // Zondag overslaan (Carbon: 0=Sunday)
            if ($datum->dayOfWeek === 0) {
                continue;
            }

            $aantalDag = $rnd(2, 5);
            $datumStr  = $datum->toDateString();
            $gebruikte[$datumStr] = $gebruikte[$datumStr] ?? [];

            // Houd beschikbare tijdslots bij per dag
            $beschikbareTijden = $tijdslots;

            for ($i = 0; $i < $aantalDag; $i++) {
                if (empty($beschikbareTijden)) {
                    break;
                }

                // Kies random tijdslot
                $tijdIdx = array_keys($beschikbareTijden)[$rnd(0, count($beschikbareTijden) - 1)];
                $tijd    = $beschikbareTijden[$tijdIdx];
                unset($beschikbareTijden[$tijdIdx]);
                $beschikbareTijden = array_values($beschikbareTijden);

                // Kies dienst
                $dienst = $diensten[$rnd(0, count($diensten) - 1)];
                $eind   = Carbon::parse($datumStr . ' ' . $tijd)
                    ->addMinutes($dienst->duur_minuten)
                    ->format('H:i');

                // Status: verleden = realistisch, toekomst = gepland
                if ($dag < 0) {
                    $r      = $rnd(0, 99);
                    $status = $r < 82 ? 'voltooid' : ($r < 93 ? 'no_show' : 'geannuleerd');
                } elseif ($dag === 0) {
                    $r      = $rnd(0, 99);
                    $status = $r < 70 ? 'gepland' : 'voltooid';
                } else {
                    $status = 'gepland';
                }

                $betaal = $rnd(0, 2) === 0 ? 'online' : 'in_zaak';

                // 55% walk-in, 45% vaste klant
                if ($rnd(0, 9) < 5) {
                    Afspraak::create([
                        'klant_id'      => null,
                        'walk_in_naam'  => $walkInNamen[$rnd(0, count($walkInNamen) - 1)],
                        'kapper_id'     => $kapper->id,
                        'dienst_id'     => $dienst->id,
                        'datum'         => $datumStr,
                        'start_tijd'    => $tijd,
                        'eind_tijd'     => $eind,
                        'status'        => $status,
                        'betaalmethode' => 'in_zaak',
                    ]);
                } else {
                    $klantId = $klantIds[$rnd(0, count($klantIds) - 1)];

                    // Voorkom dubbel (zelfde klant op zelfde dag)
                    $key = $klantId . '-' . $datumStr;
                    if (isset($gebruikte[$key])) {
                        // Gebruik walk-in als fallback
                        Afspraak::create([
                            'klant_id'      => null,
                            'walk_in_naam'  => $walkInNamen[$rnd(0, count($walkInNamen) - 1)],
                            'kapper_id'     => $kapper->id,
                            'dienst_id'     => $dienst->id,
                            'datum'         => $datumStr,
                            'start_tijd'    => $tijd,
                            'eind_tijd'     => $eind,
                            'status'        => $status,
                            'betaalmethode' => 'in_zaak',
                        ]);
                    } else {
                        $gebruikte[$key] = true;
                        Afspraak::create([
                            'klant_id'      => $klantId,
                            'kapper_id'     => $kapper->id,
                            'dienst_id'     => $dienst->id,
                            'datum'         => $datumStr,
                            'start_tijd'    => $tijd,
                            'eind_tijd'     => $eind,
                            'status'        => $status,
                            'betaalmethode' => $betaal,
                        ]);
                    }
                }
            }
        }
    }
}
