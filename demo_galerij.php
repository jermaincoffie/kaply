<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'jermain_coffie@live.nl')->first();
$kapper = App\Models\Kapper::where('user_id', $user->id)->first();

echo "Kapper id: {$kapper->id}\n";

// Verwijder bestaande galerij
DB::table('galerij')->where('kapper_id', $kapper->id)->delete();

$fotos = [
    'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1605497788044-5a32c7078486?w=800&h=600&fit=crop',
];

foreach ($fotos as $i => $url) {
    DB::table('galerij')->insert([
        'kapper_id' => $kapper->id,
        'foto_pad' => $url,
        'volgorde' => $i + 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

echo count($fotos) . " foto's toegevoegd!\n";
