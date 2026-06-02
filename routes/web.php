<?php

use App\Livewire\Admin\KappersOverzicht;
use App\Livewire\Kapper\AgendaOverzicht;
use App\Livewire\Kapper\BeschikbaarheidBeheer;
use App\Livewire\Kapper\DienstenBeheer;
use App\Livewire\Kapper\ProfielBeheer;
use App\Livewire\Kapper\Registratie as KapperRegistratie;
use App\Livewire\Klant\BoekingWizard;
use App\Livewire\Klant\KapperZoeken;
use App\Livewire\Klant\MijnAfspraken;
use Illuminate\Support\Facades\Route;

// Publiek
Route::get('/', KapperZoeken::class)->name('home');
Route::get('/kapper/registreer', KapperRegistratie::class)->name('kapper.registreer');

// Kapper dashboard (MOET vóór /kapper/{slug} staan — anders vangt slug 'dashboard' af)
Route::middleware(['auth', 'role:kapper'])->prefix('kapper')->name('kapper.')->group(function () {
    Route::get('/dashboard', AgendaOverzicht::class)->name('dashboard');
    Route::get('/diensten', DienstenBeheer::class)->name('diensten');
    Route::get('/beschikbaarheid', BeschikbaarheidBeheer::class)->name('beschikbaarheid');
    Route::get('/profiel', ProfielBeheer::class)->name('profiel-beheer');
});

// Publieke kapper profielpagina (wildcard — altijd als LAATSTE /kapper/* route)
Route::get('/kapper/{slug}', function ($slug) {
    $kapper = \App\Models\Kapper::where('slug', $slug)
        ->where('actief', true)
        ->where('abonnement_status', 'actief')
        ->with('diensten')
        ->firstOrFail();
    return view('pages.kapper-profiel', compact('kapper'));
})->name('kapper.profiel');

// Algemeen dashboard (Jetstream redirect na login)
Route::middleware(['auth'])->get('/dashboard', function () {
    if (auth()->user()->isKapper()) {
        return redirect()->route('kapper.dashboard');
    }
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.kappers');
    }
    return redirect()->route('klant.afspraken');
})->name('dashboard');


// Klant
Route::middleware(['auth'])->group(function () {
    Route::get('/mijn-afspraken', MijnAfspraken::class)->name('klant.afspraken');
    Route::get('/boeken/{kapperSlug}/{dienstId}', BoekingWizard::class)->name('boeken');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/kappers', KappersOverzicht::class)->name('kappers');
});
