<?php

namespace Database\Seeders;

use App\Models\Afspraak;
use App\Models\Kapper;
use App\Models\User;
use App\Notifications\NieuweAfspraakNotificatie;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KnippaTestBoekingSeeder extends Seeder
{
    public function run(): void
    {
        $kapper = Kapper::where('slug', 'barbershop-knippa')->first();

        if (!$kapper) {
            $this->command->error('Kapper barbershop-knippa niet gevonden.');
            return;
        }

        $diensten = $kapper->diensten()->get();

        if ($diensten->isEmpty()) {
            $this->command->error('Geen diensten gevonden voor deze kapper.');
            return;
        }

        $klanten = [
            ['name' => 'Sander de Vries',  'email' => 'sander.test@kaply.nl'],
            ['name' => 'Fatima Yilmaz',    'email' => 'fatima.test@kaply.nl'],
            ['name' => 'Daan Vermeer',     'email' => 'daan.test@kaply.nl'],
        ];

        $tijden = ['10:00', '11:30', '14:00'];

        foreach ($klanten as $i => $klantData) {
            $klant = User::firstOrCreate(
                ['email' => $klantData['email']],
                [
                    'name'     => $klantData['name'],
                    'password' => Hash::make('password'),
                    'role'     => 'klant',
                ]
            );

            $dienst = $diensten->values()->get($i % $diensten->count());
            $datum  = Carbon::today()->addDays($i + 1)->toDateString();
            $tijd   = $tijden[$i];
            $eind   = Carbon::parse($datum . ' ' . $tijd)->addMinutes($dienst->duur_minuten)->format('H:i');

            $afspraak = Afspraak::firstOrCreate(
                ['klant_id' => $klant->id, 'kapper_id' => $kapper->id, 'datum' => $datum, 'start_tijd' => $tijd],
                [
                    'dienst_id'    => $dienst->id,
                    'eind_tijd'    => $eind,
                    'status'       => 'gepland',
                    'betaalmethode'=> 'in_zaak',
                ]
            );

            $kapper->user->notify(new NieuweAfspraakNotificatie($afspraak));

            $this->command->info("✓ {$klant->name} → {$dienst->naam} op {$datum} {$tijd}");
        }

        $this->command->info('Klaar — check de bel in je dashboard.');
    }
}
