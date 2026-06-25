<?php
require __DIR__ . '/../bootstrap/app.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$barry = App\Models\Medewerker::where('naam', 'barry')->first();
$kapper = $barry->kapper;
$dienst = $kapper->diensten()->first();
$service = new App\Services\BeschikbaarheidsService();

$maandag   = '2026-06-29'; // maandag
$woensdag  = '2026-07-01'; // woensdag

$slotsM = $service->getVrijeTijdslots($kapper, $dienst, $maandag, $barry->id);
$slotsW = $service->getVrijeTijdslots($kapper, $dienst, $woensdag, $barry->id);

echo "Barry maandag slots: " . count($slotsM) . "\n";
echo "Barry woensdag slots: " . count($slotsW) . "\n";
echo "Verwacht: 0 maandag, >0 woensdag\n";

// Cleanup
unlink(__FILE__);
