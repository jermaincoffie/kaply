<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'jermain_coffie@live.nl')->first();
$kapper = App\Models\Kapper::where('user_id', $user->id)->first();
$klant = App\Models\User::where('email', 'demo.klant@kaply.nl')->first();
$dienst = DB::table('diensten')->where('kapper_id', $kapper->id)->first();

DB::table('reviews')->where('kapper_id', $kapper->id)->delete();

// Maak 3 aparte voltooide afspraken voor de reviews
$reviewAfspraken = [];
foreach ([14, 21, 28] as $daysAgo) {
    $reviewAfspraken[] = DB::table('afspraken')->insertGetId([
        'kapper_id'    => $kapper->id,
        'klant_id'     => $klant->id,
        'dienst_id'    => $dienst->id,
        'walk_in_naam' => 'Review Klant',
        'datum'        => now()->subDays($daysAgo)->format('Y-m-d'),
        'start_tijd'   => '12:00',
        'eind_tijd'    => '12:30',
        'status'       => 'voltooid',
        'created_at'   => now()->subDays($daysAgo),
        'updated_at'   => now(),
    ]);
}

$reviews = [
    ['rating' => 5, 'tekst' => 'Geweldige kapper! Altijd tevreden met het resultaat.'],
    ['rating' => 5, 'tekst' => 'Scherpe lijnen, fijne sfeer. Kom zeker terug.'],
    ['rating' => 4, 'tekst' => 'Goede service, snel geholpen. Aanrader!'],
];

foreach ($reviews as $i => $r) {
    DB::table('reviews')->insert([
        'kapper_id'   => $kapper->id,
        'klant_id'    => $klant->id,
        'afspraak_id' => $reviewAfspraken[$i],
        'rating'      => $r['rating'],
        'tekst'       => $r['tekst'],
        'zichtbaar'   => true,
        'created_at'  => now()->subDays([14, 21, 28][$i]),
        'updated_at'  => now(),
    ]);
}
echo count($reviews) . " reviews aangemaakt\nKlaar!\n";
