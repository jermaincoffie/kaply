<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'jermain_coffie@live.nl')->first();
if (!$user) { echo "Niet gevonden\n"; exit; }

$kapper = App\Models\Kapper::where('user_id', $user->id)->first();
$kapper->abonnement_status = 'actief';
$kapper->save();

// Voeg ook subscription record toe als dat bestaat
try {
    DB::table('subscriptions')->updateOrInsert(
        ['user_id' => $user->id],
        [
            'user_id' => $user->id,
            'name' => 'default',
            'stripe_id' => 'sub_demo_' . $user->id,
            'stripe_status' => 'active',
            'stripe_price' => 'price_1TifWx3aCXvnaHbBjQko5Lxh',
            'quantity' => 1,
            'trial_ends_at' => null,
            'ends_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );
    echo "Subscription record aangemaakt\n";
} catch (\Exception $e) {
    echo "Geen subscriptions tabel: " . $e->getMessage() . "\n";
}

echo "Abonnement actief gezet!\n";
