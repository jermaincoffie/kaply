<?php

namespace Database\Seeders;

use App\Models\Beschikbaarheid;
use App\Models\Dienst;
use App\Models\Kapper;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoKapperSeeder extends Seeder
{
    public function run(): void
    {
        User::where('email', 'demo@kaply.nl')->delete();

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

        foreach ([
            ['naam' => 'Klassiek knippen',  'prijs' => 2500, 'duur_minuten' => 30],
            ['naam' => 'Fade & blend',       'prijs' => 3000, 'duur_minuten' => 45],
            ['naam' => 'Knippen + baard',    'prijs' => 4000, 'duur_minuten' => 60],
            ['naam' => 'Baard trimmen',      'prijs' => 1500, 'duur_minuten' => 20],
            ['naam' => 'Kinderen knippen',   'prijs' => 1800, 'duur_minuten' => 25],
        ] as $d) {
            Dienst::create(array_merge($d, ['kapper_id' => $kapper->id]));
        }

        // Ma t/m za 09:00-18:00 (0=maandag)
        foreach (range(0, 5) as $dag) {
            Beschikbaarheid::create([
                'kapper_id'    => $kapper->id,
                'dag_van_week' => $dag,
                'start_tijd'   => '09:00',
                'eind_tijd'    => '18:00',
            ]);
        }
    }
}
