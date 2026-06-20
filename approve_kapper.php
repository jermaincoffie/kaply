<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'jermain_coffie@live.nl')->first();
if (!$user) { echo "Niet gevonden\n"; exit; }

$kapper = App\Models\Kapper::where('user_id', $user->id)->first();
if (!$kapper) { echo "Geen kapper record\n"; exit; }

echo "Voor: actief={$kapper->actief}, onboarding={$kapper->onboarding_voltooid}\n";
$kapper->actief = true;
$kapper->onboarding_voltooid = true;
$kapper->save();
echo "Goedgekeurd!\n";
