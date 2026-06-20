<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
    }
}
