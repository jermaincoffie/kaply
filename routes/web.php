<?php

use App\Livewire\Admin\AfsprakenOverzicht as AdminAfspraken;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\FacturatieOverzicht as AdminFacturatie;
use App\Livewire\Kapper\FacturatieOverzicht as KapperFacturatie;
use App\Livewire\Admin\KappersOverzicht;
use App\Livewire\Admin\KlantenOverzicht as AdminKlanten;
use App\Livewire\Kapper\AfsprakenOverzicht as KapperAfspraken;
use App\Livewire\Kapper\AgendaOverzicht;
use App\Livewire\Kapper\KapperDashboard;
use App\Livewire\Kapper\BeschikbaarheidBeheer;
use App\Livewire\Kapper\DienstenBeheer;
use App\Livewire\Kapper\KlantenOverzicht as KapperKlanten;
use App\Livewire\Kapper\MedewerkersBeheer;
use App\Livewire\Kapper\GalerijBeheer;
use App\Livewire\Kapper\ProfielBeheer;
use App\Livewire\Kapper\Registratie as KapperRegistratie;
use App\Livewire\Kapper\AbonnementBeheer;
use App\Livewire\Kapper\AccountBeheer as KapperAccount;
use App\Livewire\Kapper\StatistiekenOverzicht;
use App\Livewire\Kapper\AbonnementSucces;
use App\Livewire\Kapper\AbonnementCancel;
use App\Livewire\Kapper\KortingscodesBeheer;
use App\Livewire\Kapper\OnboardingWizard;
use App\Livewire\Kapper\ReviewsOverzicht as KapperReviews;
use App\Http\Controllers\AfspraakBetaalController;
use App\Http\Controllers\KlantDataController;
use App\Http\Controllers\IcalController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\StripeConnectController;
use App\Http\Controllers\StripeWebhookController;
use App\Livewire\Klant\AccountBeheer;
use App\Livewire\Klant\Inloggen;
use App\Livewire\Klant\KapperProfiel;
use App\Livewire\Klant\KappersPerStad;
use App\Livewire\Klant\KapperZoeken;
use App\Livewire\Klant\MijnAfspraken;
use App\Mail\OtpCodeMail;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

// Notificatie uitschrijven (signed URL, geen auth nodig)
Route::get('/notificaties/uitschrijven/{user}', function (\App\Models\User $user) {
    $user->kapper?->update(['notificatie_email' => false]);
    return view('emails.uitschrijven-bevestiging');
})->name('notificaties.uitschrijven')->middleware('signed');

// Publiek
Route::get('/', KapperZoeken::class)->name('home');
Route::get('/kappers/{stad}', KappersPerStad::class)->name('stad.kappers');
Route::get('/voor-kappers', fn() => view('voor-kappers'))->name('voor-kappers');
Route::get('/prijzen', fn() => view('prijzen'))->name('prijzen');
Route::get('/privacy', fn() => view('legal.privacy'))->name('privacy');
Route::get('/algemene-voorwaarden', fn() => view('legal.voorwaarden'))->name('voorwaarden');
Route::get('/inloggen', Inloggen::class)->name('klant.inloggen')->middleware('guest');

Route::post('/inloggen/profiel', function (Request $request) {
    $email = session('klant_inloggen_email');
    if (!$email) return redirect()->route('klant.inloggen');

    $request->validate([
        'voornaam'   => 'required|string|min:2',
        'achternaam' => 'required|string|min:2',
        'telefoon'   => 'required|string|min:8|max:20',
    ]);

    session(['klant_profiel' => $request->only('voornaam', 'achternaam', 'telefoon')]);

    $sleutel = 'otp-verstuur:' . $request->ip();
    if (RateLimiter::tooManyAttempts($sleutel, 5)) {
        return back()->with('fout', 'Te veel pogingen. Probeer over een minuut opnieuw.');
    }
    RateLimiter::hit($sleutel, 60);

    OtpCode::where('email', $email)->delete();
    $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    OtpCode::create(['email' => $email, 'code' => $code, 'expires_at' => now()->addMinutes(5)]);

    try {
        Mail::to($email)->send(new OtpCodeMail($code));
        Log::info('OTP: mail verzonden via profiel POST', ['email' => $email]);
    } catch (\Throwable $e) {
        Log::error('OTP: mail mislukt via profiel POST', ['error' => $e->getMessage()]);
        return back()->with('fout', 'Kon geen e-mail versturen. Probeer later opnieuw.');
    }

    return redirect()->route('klant.inloggen', ['stap' => 'code']);
})->name('klant.inloggen.profiel')->middleware('guest');
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

        // Abonnement routes + profiel + account — geen abonnement-check (anders redirect loop)
        Route::get('/abonnement', AbonnementBeheer::class)->name('abonnement');
        Route::get('/profiel', ProfielBeheer::class)->name('profiel-beheer');
        Route::get('/account', KapperAccount::class)->name('account');
        Route::post('/push/subscribe', [\App\Http\Controllers\PushSubscriptionController::class, 'store'])->name('push.subscribe');
        Route::post('/abonnement/activeer', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
        Route::get('/abonnement/portal', [SubscriptionController::class, 'portal'])->name('subscription.portal');
        Route::get('/abonnement/succes', AbonnementSucces::class)->name('subscription.succes');
        Route::get('/abonnement/geannuleerd', AbonnementCancel::class)->name('subscription.cancel');
        Route::get('/facturatie', KapperFacturatie::class)->name('facturatie');

        // Overige routes — vereisen actief abonnement
        Route::middleware(['abonnement'])->group(function () {
            Route::get('/dashboard', KapperDashboard::class)->name('dashboard');
            Route::get('/agenda', AgendaOverzicht::class)->name('agenda');
            Route::get('/afspraken', KapperAfspraken::class)->name('afspraken');
            Route::get('/klanten', KapperKlanten::class)->name('klanten');
            Route::get('/statistieken', StatistiekenOverzicht::class)->name('statistieken');
            Route::get('/diensten', DienstenBeheer::class)->name('diensten');
            Route::get('/beschikbaarheid', BeschikbaarheidBeheer::class)->name('beschikbaarheid');
            Route::get('/medewerkers', MedewerkersBeheer::class)->name('medewerkers');
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
        return redirect()->route('kapper.agenda');
    }
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('klant.afspraken');
})->name('dashboard');


// Klant
Route::middleware(['klant.auth'])->group(function () {
    Route::get('/mijn-afspraken', MijnAfspraken::class)->name('klant.afspraken');
    Route::get('/mijn-gegevens/download', [KlantDataController::class, 'download'])->name('klant.data.download');
    Route::get('/mijn-account', AccountBeheer::class)->name('klant.account');
    Route::get('/afspraak/betaling/checkout', [AfspraakBetaalController::class, 'checkout'])->name('afspraak.betaling.checkout');
    Route::get('/afspraak/betaling/succes', [AfspraakBetaalController::class, 'succes'])->name('afspraak.betaling.succes');
    Route::get('/afspraak/betaling/annuleren', [AfspraakBetaalController::class, 'annuleren'])->name('afspraak.betaling.annuleren');
    Route::post('/afspraak/annulering/checkout', [AfspraakBetaalController::class, 'annuleringCheckout'])->name('afspraak.annulering.checkout');
    Route::get('/afspraak/annulering/succes', [AfspraakBetaalController::class, 'annuleringSucces'])->name('afspraak.annulering.succes');
});

// Stripe webhook (geen auth middleware!)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('cashier.webhook');

// ──────────────────────────────────────────────────────────────────────────────
// Stripe Connect Demo — Sample integration (no auth required for demo purposes)
// In production, protect the manage/onboard routes with auth middleware.
// ──────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('stripe-demo')->name('stripe-demo.')->group(function () {
    // Dashboard: create connected accounts
    Route::get('/', [\App\Http\Controllers\StripeConnectDemoController::class, 'dashboard'])->name('dashboard');
    Route::post('/account', [\App\Http\Controllers\StripeConnectDemoController::class, 'createAccount'])->name('create-account');

    // Onboarding: show status and create account links
    Route::get('/onboard/{accountId}', [\App\Http\Controllers\StripeConnectDemoController::class, 'onboard'])->name('onboard');
    Route::post('/onboard/{accountId}/link', [\App\Http\Controllers\StripeConnectDemoController::class, 'createAccountLink'])->name('create-account-link');

    // Manage: products + subscription
    Route::get('/manage/{accountId}', [\App\Http\Controllers\StripeConnectDemoController::class, 'manage'])->name('manage');
    Route::post('/manage/{accountId}/product', [\App\Http\Controllers\StripeConnectDemoController::class, 'createProduct'])->name('create-product');
    Route::post('/manage/{accountId}/subscribe', [\App\Http\Controllers\StripeConnectDemoController::class, 'subscribe'])->name('subscribe');
    Route::get('/manage/{accountId}/portal', [\App\Http\Controllers\StripeConnectDemoController::class, 'billingPortal'])->name('billing-portal');

    // Storefront + checkout (customer-facing)
    Route::get('/store/{accountId}', [\App\Http\Controllers\StripeConnectDemoController::class, 'store'])->name('store');
    Route::post('/checkout/{accountId}/{priceId}', [\App\Http\Controllers\StripeConnectDemoController::class, 'checkout'])->name('checkout');
    Route::get('/success', [\App\Http\Controllers\StripeConnectDemoController::class, 'success'])->name('success');

    // Webhooks — excluded from CSRF in bootstrap/app.php (see below)
    Route::post('/webhook/connect', [\App\Http\Controllers\StripeConnectDemoWebhookController::class, 'handleConnect'])->name('webhook.connect');
    Route::post('/webhook/subscriptions', [\App\Http\Controllers\StripeConnectDemoWebhookController::class, 'handleSubscriptions'])->name('webhook.subscriptions');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/kappers', KappersOverzicht::class)->name('kappers');
    Route::get('/afspraken', AdminAfspraken::class)->name('afspraken');
    Route::get('/klanten', AdminKlanten::class)->name('klanten');
    Route::get('/facturatie', AdminFacturatie::class)->name('facturatie');
});
