<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use NotificationChannels\WebPush\Events\NotificationFailed as WebPushFailed;
use NotificationChannels\WebPush\Events\NotificationSent as WebPushSent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('nl');

        Password::defaults(fn () => Password::min(8)->mixedCase()->numbers());

        // Register the anonymous Blade layout for the Stripe Connect demo
        Blade::component('stripe-demo.layout', 'stripe-demo-layout');

        // Push debug logging — luistert naar WebPush package events (niet Laravel's eigen events)
        Event::listen(WebPushSent::class, function ($e) {
            Log::info('WEBPUSH SENT OK', [
                'endpoint' => substr($e->report->getEndpoint(), -30),
                'response' => $e->report->getResponse()?->getStatusCode(),
            ]);
        });
        Event::listen(WebPushFailed::class, function ($e) {
            Log::error('WEBPUSH FAILED', [
                'endpoint' => substr($e->report->getEndpoint(), -30),
                'expired'  => $e->report->isSubscriptionExpired(),
                'reason'   => $e->report->getReason(),
                'response' => $e->report->getResponse()?->getStatusCode(),
                'body'     => (string) $e->report->getResponse()?->getBody(),
            ]);
        });
    }
}
