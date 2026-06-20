<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$deleted = App\Models\User::where('email', 'jermain_coffie@live.nl')->delete();
echo $deleted ? "Account verwijderd\n" : "Niet gevonden\n";
