<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'jermain_coffie@live.nl')->first();
$kapper = App\Models\Kapper::where('user_id', $user->id)->first();
echo "Kapper: {$kapper->salon_naam} (id: {$kapper->id})\n";

// === GALERIJ ===
DB::table('kapper_galerij')->where('kapper_id', $kapper->id)->delete();
$fotos = [
    'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1605497788044-5a32c7078486?w=800&h=600&fit=crop',
];
foreach ($fotos as $i => $url) {
    DB::table('kapper_galerij')->insert([
        'kapper_id' => $kapper->id,
        'pad'       => $url,
        'volgorde'  => $i + 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
echo count($fotos) . " galerij foto's toegevoegd\n";

// === KLANT voor demo ===
$klant = App\Models\User::firstOrCreate(
    ['email' => 'demo.klant@kaply.nl'],
    ['name' => 'Demo Klant', 'password' => bcrypt('password'), 'role' => 'klant']
);

// === DIENST ophalen of aanmaken ===
$dienst = DB::table('diensten')->where('kapper_id', $kapper->id)->first();
if (!$dienst) {
    $dienstId = DB::table('diensten')->insertGetId([
        'kapper_id' => $kapper->id,
        'naam'      => 'Knippen',
        'duur'      => 30,
        'prijs'     => 2500,
        'actief'    => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
} else {
    $dienstId = $dienst->id;
}

// === AFSPRAKEN ===
DB::table('afspraken')->where('kapper_id', $kapper->id)->delete();
$afspraken = [
    ['naam' => 'Mohammed El Amin', 'datum' => now()->addDays(1)->format('Y-m-d'), 'tijd' => '10:00', 'status' => 'bevestigd'],
    ['naam' => 'Jayden Martina',   'datum' => now()->addDays(1)->format('Y-m-d'), 'tijd' => '11:00', 'status' => 'bevestigd'],
    ['naam' => 'Kevin Boateng',    'datum' => now()->addDays(2)->format('Y-m-d'), 'tijd' => '14:00', 'status' => 'bevestigd'],
    ['naam' => 'Demo Klant',       'datum' => now()->subDays(3)->format('Y-m-d'), 'tijd' => '09:30', 'status' => 'voltooid'],
    ['naam' => 'Timo Visser',      'datum' => now()->subDays(7)->format('Y-m-d'), 'tijd' => '15:00', 'status' => 'voltooid'],
];
foreach ($afspraken as $a) {
    DB::table('afspraken')->insert([
        'kapper_id'    => $kapper->id,
        'klant_id'     => $klant->id,
        'dienst_id'    => $dienstId,
        'walk_in_naam' => $a['naam'],
        'datum'        => $a['datum'],
        'start_tijd'   => $a['tijd'],
        'eind_tijd'    => date('H:i', strtotime($a['tijd']) + 1800),
        'status'       => $a['status'],
        'created_at'   => now(),
        'updated_at'   => now(),
    ]);
}
echo count($afspraken) . " afspraken aangemaakt\n";

// === REVIEWS ===
DB::table('reviews')->where('kapper_id', $kapper->id)->delete();
$reviews = [
    ['rating' => 5, 'tekst' => 'Geweldige kapper! Altijd tevreden met het resultaat.'],
    ['rating' => 5, 'tekst' => 'Scherpe lijnen, fijne sfeer. Kom zeker terug.'],
    ['rating' => 4, 'tekst' => 'Goede service, snel geholpen. Aanrader!'],
];
foreach ($reviews as $r) {
    DB::table('reviews')->insert([
        'kapper_id'  => $kapper->id,
        'klant_id'   => $klant->id,
        'rating'     => $r['rating'],
        'tekst'      => $r['tekst'],
        'zichtbaar'  => true,
        'created_at' => now()->subDays(rand(1, 14)),
        'updated_at' => now(),
    ]);
}
echo count($reviews) . " reviews aangemaakt\n";
echo "Klaar!\n";
