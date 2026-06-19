<?php

use App\Livewire\Admin\AfsprakenOverzicht as AdminAfspraken;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\FacturatieOverzicht as AdminFacturatie;
use App\Livewire\Kapper\FacturatieOverzicht as KapperFacturatie;
use App\Livewire\Admin\KappersOverzicht;
use App\Livewire\Admin\KlantenOverzicht as AdminKlanten;
use App\Livewire\Kapper\AfsprakenOverzicht as KapperAfspraken;
use App\Livewire\Kapper\AgendaOverzicht;
use App\Livewire\Kapper\BeschikbaarheidBeheer;
use App\Livewire\Kapper\DienstenBeheer;
use App\Livewire\Kapper\KlantenOverzicht as KapperKlanten;
use App\Livewire\Kapper\MedewerkersBeheer;
use App\Livewire\Kapper\GalerijBeheer;
use App\Livewire\Kapper\ProfielBeheer;
use App\Livewire\Kapper\Registratie as KapperRegistratie;
use App\Livewire\Kapper\AbonnementBeheer;
use App\Livewire\Kapper\KortingscodesBeheer;
use App\Livewire\Kapper\OnboardingWizard;
use App\Livewire\Kapper\ReviewsOverzicht as KapperReviews;
use App\Http\Controllers\AfspraakBetaalController;
use App\Http\Controllers\IcalController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\StripeConnectController;
use App\Http\Controllers\StripeWebhookController;
use App\Livewire\Klant\AccountBeheer;
use App\Livewire\Klant\BoekingWizard;
use App\Livewire\Klant\Inloggen;
use App\Livewire\Klant\KapperProfiel;
use App\Livewire\Klant\KapperZoeken;
use App\Livewire\Klant\MijnAfspraken;
use Illuminate\Support\Facades\Route;

// Publiek
Route::get('/', KapperZoeken::class)->name('home');
Route::get('/voor-kappers', fn() => view('voor-kappers'))->name('voor-kappers');
Route::get('/prijzen', fn() => view('prijzen'))->name('prijzen');
Route::get('/privacy', fn() => view('legal.privacy'))->name('privacy');
Route::get('/algemene-voorwaarden', fn() => view('legal.voorwaarden'))->name('voorwaarden');
Route::get('/inloggen', Inloggen::class)->name('klant.inloggen')->middleware('guest');
Route::get('/kapper/registreer', KapperRegistratie::class)->name('kapper.registreer');

// Kapper dashboard (MOET vóór /kapper/{slug} staan — anders vangt slug 'dashboard' af)
Route::middleware(['auth', 'role:kapper'])->prefix('kapper')->name('kapper.')->group(function () {
    // Onboarding — geen onboarding-check (anders redirect loop)
    Route::get('/onboarding', OnboardingWizard::class)->name('onboarding');

    // Alle overige kapper routes — redirect naar onboarding als niet voltooid
    Route::middleware(['onboarding'])->group(function () {
        // Stripe Connect — geen abonnement-check nodig
        Route::get('/stripe/koppelen', [StripeConnectController::class, 'onboard'])->name('stripe.onboard');
        Route::get('/stripe/return',   [StripeConnectController::class, 'return'])->name('stripe.return');
        Route::get('/stripe/refresh',  [StripeConnectController::class, 'refresh'])->name('stripe.refresh');
        Route::get('/stripe/dashboard',[StripeConnectController::class, 'dashboard'])->name('stripe.dashboard');

        // Abonnement routes — geen abonnement-check (anders redirect loop)
        Route::get('/abonnement', AbonnementBeheer::class)->name('abonnement');
        Route::get('/abonnement/checkout', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
        Route::get('/abonnement/portal', [SubscriptionController::class, 'portal'])->name('subscription.portal');
        Route::get('/facturatie', KapperFacturatie::class)->name('facturatie');

        // Overige routes — vereisen actief abonnement
        Route::middleware(['abonnement'])->group(function () {
            Route::get('/dashboard', AgendaOverzicht::class)->name('dashboard');
            Route::get('/afspraken', KapperAfspraken::class)->name('afspraken');
            Route::get('/klanten', KapperKlanten::class)->name('klanten');
            Route::get('/diensten', DienstenBeheer::class)->name('diensten');
            Route::get('/beschikbaarheid', BeschikbaarheidBeheer::class)->name('beschikbaarheid');
            Route::get('/medewerkers', MedewerkersBeheer::class)->name('medewerkers');
            Route::get('/profiel', ProfielBeheer::class)->name('profiel-beheer');
            Route::get('/galerij', GalerijBeheer::class)->name('galerij');
            Route::get('/reviews', KapperReviews::class)->name('reviews');
            Route::get('/kortingscodes', KortingscodesBeheer::class)->name('kortingscodes');
        });
    });
});

// iCal feed (publiek via geheim token)
Route::get('/kalender/{token}.ics', [IcalController::class, 'feed'])->name('kapper.ical');

// Publieke kapper profielpagina
Route::get('/kapper/{slug}', KapperProfiel::class)->name('kapper.profiel')->middleware('allow.embed');

// Algemeen dashboard (Jetstream redirect na login)
Route::middleware(['auth'])->get('/dashboard', function () {
    if (auth()->user()->isKapper()) {
        return redirect()->route('kapper.dashboard');
    }
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('klant.afspraken');
})->name('dashboard');


// Klant
Route::middleware(['klant.auth'])->group(function () {
    Route::get('/mijn-afspraken', MijnAfspraken::class)->name('klant.afspraken');
    Route::get('/mijn-account', AccountBeheer::class)->name('klant.account');
    Route::get('/boeken/{kapperSlug}/{dienstId}', BoekingWizard::class)->name('boeken');
    Route::get('/afspraak/betaling/checkout', [AfspraakBetaalController::class, 'checkout'])->name('afspraak.betaling.checkout');
    Route::get('/afspraak/betaling/succes', [AfspraakBetaalController::class, 'succes'])->name('afspraak.betaling.succes');
    Route::get('/afspraak/betaling/annuleren', [AfspraakBetaalController::class, 'annuleren'])->name('afspraak.betaling.annuleren');
    Route::post('/afspraak/annulering/checkout', [AfspraakBetaalController::class, 'annuleringCheckout'])->name('afspraak.annulering.checkout');
    Route::get('/afspraak/annulering/succes', [AfspraakBetaalController::class, 'annuleringSucces'])->name('afspraak.annulering.succes');
});

// Stripe webhook (geen auth middleware!)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('cashier.webhook');

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/kappers', KappersOverzicht::class)->name('kappers');
    Route::get('/afspraken', AdminAfspraken::class)->name('afspraken');
    Route::get('/klanten', AdminKlanten::class)->name('klanten');
    Route::get('/facturatie', AdminFacturatie::class)->name('facturatie');
});
