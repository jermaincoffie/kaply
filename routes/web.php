<?php

use App\Livewire\Kapper\Registratie as KapperRegistratie;
use Illuminate\Support\Facades\Route;

// Publiek
Route::get('/kapper/registreer', KapperRegistratie::class)->name('kapper.registreer');

// Kapper dashboard (placeholder — volledig gebouwd in Task 10)
Route::middleware(['auth'])->prefix('kapper')->name('kapper.')->group(function () {
    Route::get('/dashboard', fn() => 'dashboard placeholder')->name('dashboard');
});
