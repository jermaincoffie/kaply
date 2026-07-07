<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use NotificationChannels\WebPush\WebPushChannel;

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

        // Tijdelijk: push debug logging
        Event::listen(NotificationSent::class, function ($e) {
            if ($e->channel === WebPushChannel::class) {
                Log::info('PUSH SENT OK', ['notification' => class_basename($e->notification)]);
            }
        });
        Event::listen(NotificationFailed::class, function ($e) {
            if ($e->channel === WebPushChannel::class) {
                Log::error('PUSH FAILED', [
                    'notification' => class_basename($e->notification),
                    'error' => $e->data['message'] ?? json_encode($e->data),
                ]);
            }
        });
    }
}
