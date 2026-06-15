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
        foreach ([
            ['naam' => 'Klassiek knippen',  'prijs' => 2500, 'duur_minuten' => 30],
            ['naam' => 'Fade & blend',       'prijs' => 3000, 'duur_minuten' => 45],
            ['naam' => 'Knippen + baard',    'prijs' => 4000, 'duur_minuten' => 60],
            ['naam' => 'Baard trimmen',      'prijs' => 1500, 'duur_minuten' => 20],
            ['naam' => 'Kinderen knippen',   'prijs' => 1800, 'duur_minuten' => 25],
        ] as $d) {
            Dienst::create(array_merge($d, ['kapper_id' => $kapper->id]));
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

        // Galerij — Unsplash foto's van kapsels/kapperzaken
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

        // Reviews — vereisen een gekoppelde afspraak (afspraak_id NOT NULL)
        $eerstedienst = $kapper->diensten()->first();
        foreach ([
            ['naam' => 'Kevin de Vries',    'rating' => 5, 'tekst' => 'Top kapper! Al jaren mijn vaste plek. Marco weet precies wat ik wil zonder dat ik het hoef uit te leggen.'],
            ['naam' => 'Thomas Brouwer',    'rating' => 5, 'tekst' => 'Geweldige fade, scherpe lijn en relaxte sfeer. Ben hier voor het eerst maar kom zeker terug.'],
            ['naam' => 'Mikael Janssen',    'rating' => 4, 'tekst' => 'Goed resultaat en vlotte service. Kon snel een afspraak maken via de app.'],
            ['naam' => 'Daan van der Berg', 'rating' => 5, 'tekst' => 'Beste kapper van Amsterdam. Baard en knipbeurt allebei perfect verzorgd.'],
        ] as $idx => $r) {
            $klant = User::create([
                'name'     => $r['naam'],
                'email'    => "demo-klant-{$idx}@kaply.nl",
                'password' => Hash::make(str()->random(20)),
                'role'     => 'klant',
            ]);
            $afspraak = Afspraak::create([
                'klant_id'      => $klant->id,
                'kapper_id'     => $kapper->id,
                'dienst_id'     => $eerstedienst->id,
                'datum'         => now()->subDays(($idx + 1) * 14)->toDateString(),
                'start_tijd'    => '10:00',
                'eind_tijd'     => '10:30',
                'status'        => 'voltooid',
                'betaalmethode' => 'in_zaak',
            ]);
            Review::create([
                'kapper_id'  => $kapper->id,
                'klant_id'   => $klant->id,
                'afspraak_id' => $afspraak->id,
                'rating'     => $r['rating'],
                'tekst'      => $r['tekst'],
                'zichtbaar'  => true,
            ]);
        }
    }
}
