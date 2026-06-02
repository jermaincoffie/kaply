<?php

namespace Database\Seeders;

use App\Models\Afspraak;
use App\Models\Dienst;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BorisBarberSeeder extends Seeder
{
    public function run(): void
    {
        $kapper_id = 1;
        $diensten = Dienst::where('kapper_id', $kapper_id)->get();
        $klanten = User::where('role', 'klant')->get();

        $data = [
            ['klant' => 0, 'dienst' => 0, 'dagen' => -8, 'tijd' => '10:00', 'status' => 'voltooid',    'betaal' => 'in_zaak'],
            ['klant' => 1, 'dienst' => 1, 'dagen' => -6, 'tijd' => '11:30', 'status' => 'voltooid',    'betaal' => 'online'],
            ['klant' => 2, 'dienst' => 3, 'dagen' => -5, 'tijd' => '14:00', 'status' => 'voltooid',    'betaal' => 'in_zaak'],
            ['klant' => 3, 'dienst' => 2, 'dagen' => -4, 'tijd' => '09:30', 'status' => 'no_show',     'betaal' => 'in_zaak'],
            ['klant' => 4, 'dienst' => 4, 'dagen' => -3, 'tijd' => '15:00', 'status' => 'voltooid',    'betaal' => 'online'],
            ['klant' => 0, 'dienst' => 1, 'dagen' => -2, 'tijd' => '10:30', 'status' => 'voltooid',    'betaal' => 'in_zaak'],
            ['klant' => 1, 'dienst' => 0, 'dagen' => -1, 'tijd' => '13:00', 'status' => 'geannuleerd', 'betaal' => 'online'],
            ['klant' => 2, 'dienst' => 3, 'dagen' =>  0, 'tijd' => '09:00', 'status' => 'gepland',     'betaal' => 'in_zaak'],
            ['klant' => 3, 'dienst' => 1, 'dagen' =>  0, 'tijd' => '11:00', 'status' => 'gepland',     'betaal' => 'online'],
            ['klant' => 4, 'dienst' => 5, 'dagen' =>  0, 'tijd' => '14:30', 'status' => 'gepland',     'betaal' => 'in_zaak'],
            ['klant' => 0, 'dienst' => 2, 'dagen' =>  1, 'tijd' => '10:00', 'status' => 'gepland',     'betaal' => 'online'],
            ['klant' => 1, 'dienst' => 3, 'dagen' =>  2, 'tijd' => '15:00', 'status' => 'gepland',     'betaal' => 'in_zaak'],
            ['klant' => 2, 'dienst' => 0, 'dagen' =>  3, 'tijd' => '11:30', 'status' => 'gepland',     'betaal' => 'online'],
            ['klant' => 3, 'dienst' => 4, 'dagen' =>  5, 'tijd' => '09:30', 'status' => 'gepland',     'betaal' => 'in_zaak'],
        ];

        foreach ($data as $a) {
            $klant  = $klanten->values()[$a['klant'] % $klanten->count()];
            $dienst = $diensten->values()[$a['dienst'] % $diensten->count()];
            $datum  = Carbon::today()->addDays($a['dagen'])->toDateString();
            $eind   = Carbon::parse($datum . ' ' . $a['tijd'])->addMinutes($dienst->duur_minuten)->format('H:i');

            Afspraak::firstOrCreate(
                ['klant_id' => $klant->id, 'kapper_id' => $kapper_id, 'datum' => $datum, 'start_tijd' => $a['tijd']],
                ['dienst_id' => $dienst->id, 'eind_tijd' => $eind, 'status' => $a['status'], 'betaalmethode' => $a['betaal']]
            );
        }

        $this->command->info('Boris Barber: ' . count($data) . ' afspraken geseed');
    }
}
