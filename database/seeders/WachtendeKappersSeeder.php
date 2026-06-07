<?php

namespace Database\Seeders;

use App\Models\Kapper;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WachtendeKappersSeeder extends Seeder
{
    public function run(): void
    {
        $kappers = [
            [
                'name'       => 'Mohammed El Amrani',
                'email'      => 'mohammed@fademaster.nl',
                'salon_naam' => 'Fade Master',
                'stad'       => 'Amsterdam',
                'telefoon'   => '0612345678',
                'adres'      => 'Kalverstraat 12',
            ],
            [
                'name'       => 'Daan Visser',
                'email'      => 'daan@thebarbershop.nl',
                'salon_naam' => 'The Barber Shop',
                'stad'       => 'Rotterdam',
                'telefoon'   => '0698765432',
                'adres'      => 'Coolsingel 45',
            ],
            [
                'name'       => 'Yusuf Karatas',
                'email'      => 'yusuf@goldencutz.nl',
                'salon_naam' => 'Golden Cutz',
                'stad'       => 'Den Haag',
                'telefoon'   => null,
                'adres'      => null,
            ],
        ];

        foreach ($kappers as $data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make('password'),
                'role'     => 'kapper',
            ]);

            Kapper::create([
                'user_id'           => $user->id,
                'salon_naam'        => $data['salon_naam'],
                'slug'              => Kapper::generateSlug($data['salon_naam']),
                'stad'              => $data['stad'],
                'adres'             => $data['adres'],
                'telefoon'          => $data['telefoon'],
                'abonnement_status' => 'geen',
                'actief'            => false,
            ]);
        }
    }
}
