<?php

use App\Livewire\Kapper\Registratie as KapperRegistratie;
use App\Livewire\Kapper\DienstenBeheer;
use App\Livewire\Kapper\BeschikbaarheidBeheer;
use Illuminate\Support\Facades\Route;

// Publiek
Route::get('/kapper/registreer', KapperRegistratie::class)->name('kapper.registreer');

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
