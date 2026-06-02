<?php

use App\Livewire\Kapper\Registratie as KapperRegistratie;
use App\Livewire\Kapper\DienstenBeheer;
use App\Livewire\Kapper\BeschikbaarheidBeheer;
use Illuminate\Support\Facades\Route;

// Publiek
Route::get('/kapper/registreer', KapperRegistratie::class)->name('kapper.registreer');

// Kapper dashboard (placeholder — volledig gebouwd in Task 10)
Route::middleware(['auth', 'role:kapper'])->prefix('kapper')->name('kapper.')->group(function () {
    Route::get('/dashboard', fn() => 'dashboard placeholder')->name('dashboard');
    Route::get('/diensten', DienstenBeheer::class)->name('diensten');
    Route::get('/beschikbaarheid', BeschikbaarheidBeheer::class)->name('beschikbaarheid');
});
