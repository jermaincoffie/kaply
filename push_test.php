<?php
// Tijdelijk testscript — verwijder na gebruik
chdir(__DIR__);
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$subs = DB::table('push_subscriptions')->where('subscribable_id', 32)->get();
echo 'Subscriptions: ' . $subs->count() . PHP_EOL;

$auth = [
    'VAPID' => [
        'subject'    => config('webpush.vapid.subject') ?? 'mailto:info@kaply.nl',
        'publicKey'  => config('webpush.vapid.public_key'),
        'privateKey' => config('webpush.vapid.private_key'),
    ]
];
echo 'Subject: ' . $auth['VAPID']['subject'] . PHP_EOL;
echo 'PubKey: ' . substr($auth['VAPID']['publicKey'], 0, 20) . '...' . PHP_EOL;

$push = new \Minishlink\WebPush\WebPush($auth);

foreach ($subs as $sub) {
    echo 'Sending to: ' . substr($sub->endpoint, 0, 60) . PHP_EOL;
    $subscription = \Minishlink\WebPush\Subscription::create([
        'endpoint' => $sub->endpoint,
        'keys'     => ['p256dh' => $sub->public_key, 'auth' => $sub->auth_token],
    ]);
    $push->queueNotification($subscription, null);
}

$reports = $push->flush();
foreach ($reports as $report) {
    echo ($report->isSuccess() ? 'OK' : 'FAIL') . ' endpoint=' . substr($report->getEndpoint(), 0, 40);
    if (!$report->isSuccess()) {
        echo ' reason=' . $report->getReason();
        echo ' status=' . $report->getResponse()?->getStatusCode();
    }
    echo PHP_EOL;
}
echo 'Klaar.' . PHP_EOL;
