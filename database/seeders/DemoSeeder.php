<?php

namespace Database\Seeders;

use App\Models\Afspraak;
use App\Models\Beschikbaarheid;
use App\Models\Kapper;
use App\Models\Klant;
use App\Models\Dienst;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Kappers
        $kappers = [
            [
                'user' => ['name' => 'Mohammed El Amrani', 'email' => 'mohammed@kapper.nl'],
                'kapper' => [
                    'salon_naam' => 'Barber Palace',
                    'slug' => 'barber-palace',
                    'stad' => 'Amsterdam',
                    'adres' => 'Kalverstraat 12',
                    'telefoon' => '0612345678',
                    'bio' => 'Gespecialiseerd in klassieke herenkapsels en baard trimmen. Al 10 jaar actief in Amsterdam.',
                ],
                'diensten' => [
                    ['naam' => 'Knippen', 'duur_minuten' => 30, 'prijs' => 1500, 'no_show_bedrag' => 500],
                    ['naam' => 'Knippen + Wassen', 'duur_minuten' => 45, 'prijs' => 2000, 'no_show_bedrag' => 500],
                    ['naam' => 'Baard trimmen', 'duur_minuten' => 20, 'prijs' => 1000, 'no_show_bedrag' => 300],
                    ['naam' => 'Knippen + Baard', 'duur_minuten' => 50, 'prijs' => 2500, 'no_show_bedrag' => 800],
                ],
                'beschikbaarheid' => [
                    [1, '09:00', '18:00'], // dinsdag
                    [2, '09:00', '18:00'], // woensdag
                    [3, '09:00', '18:00'], // donderdag
                    [4, '09:00', '18:00'], // vrijdag
                    [5, '09:00', '17:00'], // zaterdag
                ],
            ],
            [
                'user' => ['name' => 'Sandra Visser', 'email' => 'sandra@kapper.nl'],
                'kapper' => [
                    'salon_naam' => 'Studio Sandra',
                    'slug' => 'studio-sandra',
                    'stad' => 'Rotterdam',
                    'adres' => 'Westblaak 45',
                    'telefoon' => '0698765432',
                    'bio' => 'Dames- en herenkapper met passie voor kleur en stijl. Specialist in balayage en highlights.',
                ],
                'diensten' => [
                    ['naam' => 'Knippen dames', 'duur_minuten' => 45, 'prijs' => 2500, 'no_show_bedrag' => 800],
                    ['naam' => 'Knippen heren', 'duur_minuten' => 30, 'prijs' => 1800, 'no_show_bedrag' => 500],
                    ['naam' => 'Highlights', 'duur_minuten' => 90, 'prijs' => 6500, 'no_show_bedrag' => 2000],
                    ['naam' => 'Balayage', 'duur_minuten' => 120, 'prijs' => 8500, 'no_show_bedrag' => 2500],
                    ['naam' => 'Föhnen', 'duur_minuten' => 30, 'prijs' => 1500, 'no_show_bedrag' => 500],
                ],
                'beschikbaarheid' => [
                    [0, '08:30', '17:00'], // maandag
                    [1, '08:30', '17:00'], // dinsdag
                    [2, '08:30', '17:00'], // woensdag
                    [3, '08:30', '17:00'], // donderdag
                    [5, '09:00', '15:00'], // zaterdag
                ],
            ],
            [
                'user' => ['name' => 'Kevin de Boer', 'email' => 'kevin@kapper.nl'],
                'kapper' => [
                    'salon_naam' => 'Fresh Cuts',
                    'slug' => 'fresh-cuts',
                    'stad' => 'Utrecht',
                    'adres' => 'Oudegracht 88',
                    'telefoon' => '0645678901',
                    'bio' => 'Moderne barber shop voor de man die er goed uit wil zien. Fade, skin fade, of klassiek — alles is mogelijk.',
                ],
                'diensten' => [
                    ['naam' => 'Fade', 'duur_minuten' => 35, 'prijs' => 1800, 'no_show_bedrag' => 500],
                    ['naam' => 'Skin Fade', 'duur_minuten' => 40, 'prijs' => 2200, 'no_show_bedrag' => 700],
                    ['naam' => 'Knippen', 'duur_minuten' => 30, 'prijs' => 1600, 'no_show_bedrag' => 500],
                    ['naam' => 'Baard scheren', 'duur_minuten' => 30, 'prijs' => 1400, 'no_show_bedrag' => 400],
                ],
                'beschikbaarheid' => [
                    [0, '10:00', '19:00'],
                    [1, '10:00', '19:00'],
                    [2, '10:00', '19:00'],
                    [3, '10:00', '19:00'],
                    [4, '10:00', '19:00'],
                ],
            ],
        ];

        $kapperModels = [];

        foreach ($kappers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['user']['email']],
                [
                    'name' => $data['user']['name'],
                    'password' => Hash::make('password'),
                    'role' => 'kapper',
                ]
            );

            $kapper = Kapper::firstOrCreate(
                ['slug' => $data['kapper']['slug']],
                array_merge($data['kapper'], [
                    'user_id' => $user->id,
                    'abonnement_status' => 'actief',
                    'actief' => true,
                ])
            );

            foreach ($data['diensten'] as $d) {
                Dienst::firstOrCreate(
                    ['kapper_id' => $kapper->id, 'naam' => $d['naam']],
                    $d
                );
            }

            foreach ($data['beschikbaarheid'] as [$dag, $start, $eind]) {
                Beschikbaarheid::firstOrCreate(
                    ['kapper_id' => $kapper->id, 'dag_van_week' => $dag],
                    ['start_tijd' => $start, 'eind_tijd' => $eind]
                );
            }

            $kapperModels[] = $kapper;
        }

        // Klanten
        $klanten = [
            ['name' => 'Thomas Bakker', 'email' => 'thomas@klant.nl'],
            ['name' => 'Aisha Osman', 'email' => 'aisha@klant.nl'],
            ['name' => 'Pieter van Dam', 'email' => 'pieter@klant.nl'],
            ['name' => 'Fatima Yilmaz', 'email' => 'fatima@klant.nl'],
            ['name' => 'Daan Smit', 'email' => 'daan@klant.nl'],
        ];

        $klantUsers = [];
        foreach ($klanten as $k) {
            $klantUsers[] = User::firstOrCreate(
                ['email' => $k['email']],
                ['name' => $k['name'], 'password' => Hash::make('password'), 'role' => 'klant']
            );
        }

        // Afspraken — mix van statussen over afgelopen + komende dagen
        $afspraakData = [
            // Barber Palace afspraken
            ['kapper' => 0, 'klant' => 0, 'dienst_idx' => 0, 'dagen' => -5,  'tijd' => '10:00', 'status' => 'voltooid',    'betaalmethode' => 'in_zaak'],
            ['kapper' => 0, 'klant' => 1, 'dienst_idx' => 3, 'dagen' => -3,  'tijd' => '11:00', 'status' => 'voltooid',    'betaalmethode' => 'online'],
            ['kapper' => 0, 'klant' => 2, 'dienst_idx' => 2, 'dagen' => -1,  'tijd' => '14:00', 'status' => 'no_show',     'betaalmethode' => 'in_zaak'],
            ['kapper' => 0, 'klant' => 3, 'dienst_idx' => 0, 'dagen' =>  1,  'tijd' => '09:00', 'status' => 'gepland',     'betaalmethode' => 'in_zaak'],
            ['kapper' => 0, 'klant' => 4, 'dienst_idx' => 1, 'dagen' =>  2,  'tijd' => '15:30', 'status' => 'gepland',     'betaalmethode' => 'online'],
            ['kapper' => 0, 'klant' => 0, 'dienst_idx' => 3, 'dagen' =>  5,  'tijd' => '10:30', 'status' => 'gepland',     'betaalmethode' => 'in_zaak'],
            // Studio Sandra
            ['kapper' => 1, 'klant' => 1, 'dienst_idx' => 0, 'dagen' => -4,  'tijd' => '09:00', 'status' => 'voltooid',    'betaalmethode' => 'online'],
            ['kapper' => 1, 'klant' => 3, 'dienst_idx' => 2, 'dagen' => -2,  'tijd' => '13:00', 'status' => 'geannuleerd', 'betaalmethode' => 'online'],
            ['kapper' => 1, 'klant' => 2, 'dienst_idx' => 1, 'dagen' =>  1,  'tijd' => '11:00', 'status' => 'gepland',     'betaalmethode' => 'in_zaak'],
            ['kapper' => 1, 'klant' => 4, 'dienst_idx' => 3, 'dagen' =>  3,  'tijd' => '14:00', 'status' => 'gepland',     'betaalmethode' => 'online'],
            // Fresh Cuts
            ['kapper' => 2, 'klant' => 0, 'dienst_idx' => 0, 'dagen' => -6,  'tijd' => '12:00', 'status' => 'voltooid',    'betaalmethode' => 'in_zaak'],
            ['kapper' => 2, 'klant' => 4, 'dienst_idx' => 1, 'dagen' => -2,  'tijd' => '16:00', 'status' => 'voltooid',    'betaalmethode' => 'in_zaak'],
            ['kapper' => 2, 'klant' => 1, 'dienst_idx' => 2, 'dagen' =>  0,  'tijd' => '10:00', 'status' => 'gepland',     'betaalmethode' => 'in_zaak'],
            ['kapper' => 2, 'klant' => 3, 'dienst_idx' => 0, 'dagen' =>  4,  'tijd' => '13:00', 'status' => 'gepland',     'betaalmethode' => 'online'],
        ];

        foreach ($afspraakData as $a) {
            $kapper = $kapperModels[$a['kapper']];
            $klant  = $klantUsers[$a['klant']];
            $dienst = $kapper->diensten->values()[$a['dienst_idx']] ?? $kapper->diensten->first();
            $datum  = Carbon::today()->addDays($a['dagen'])->toDateString();
            $start  = $a['tijd'];
            $eind   = Carbon::parse("{$datum} {$start}")->addMinutes($dienst->duur_minuten)->format('H:i');

            Afspraak::firstOrCreate(
                ['klant_id' => $klant->id, 'kapper_id' => $kapper->id, 'datum' => $datum, 'start_tijd' => $start],
                [
                    'dienst_id'    => $dienst->id,
                    'eind_tijd'    => $eind,
                    'status'       => $a['status'],
                    'betaalmethode' => $a['betaalmethode'],
                ]
            );
        }

        $this->command->info('Demo data aangemaakt:');
        $this->command->info('  Kappers: ' . count($kappers) . ' (wachtwoord: password)');
        $this->command->info('  Klanten: ' . count($klanten) . ' (wachtwoord: password)');
        $this->command->info('  Afspraken: ' . count($afspraakData));
    }
}
