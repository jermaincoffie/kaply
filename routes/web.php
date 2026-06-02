<?php

use App\Livewire\Kapper\Registratie as KapperRegistratie;
use App\Livewire\Kapper\DienstenBeheer;
use App\Livewire\Kapper\BeschikbaarheidBeheer;
use App\Livewire\Klant\KapperZoeken;
use Illuminate\Support\Facades\Route;

// Homepage — publieke zoekopdracht
Route::get('/', KapperZoeken::class)->name('home');

// Publiek — kapper registratie (MUST be before /{slug} route)
Route::get('/kapper/registreer', KapperRegistratie::class)->name('kapper.registreer');

// Kapper profielpagina
Route::get('/kapper/{slug}', function ($slug) {
    $kapper = \App\Models\Kapper::where('slug', $slug)
        ->where('actief', true)
        ->where('abonnement_status', 'actief')
        ->with('diensten')
        ->firstOrFail();
    return view('pages.kapper-profiel', compact('kapper'));
})->name('kapper.profiel');

// General dashboard — redirects based on role (required by Jetstream auth flow)
Route::middleware(['auth'])->get('/dashboard', function () {
    if (auth()->user()->isKapper()) {
        return redirect()->route('kapper.dashboard');
    }
    return redirect()->route('klant.afspraken');
})->name('dashboard');

// Klant routes
Route::middleware(['auth'])->get('/mijn-afspraken', fn() => 'klant dashboard placeholder')->name('klant.afspraken');

// Kapper dashboard (placeholder — volledig gebouwd in Task 10)
Route::middleware(['auth', 'role:kapper'])->prefix('kapper')->name('kapper.')->group(function () {
    Route::get('/dashboard', fn() => 'dashboard placeholder')->name('dashboard');
    Route::get('/diensten', DienstenBeheer::class)->name('diensten');
    Route::get('/beschikbaarheid', BeschikbaarheidBeheer::class)->name('beschikbaarheid');
});
