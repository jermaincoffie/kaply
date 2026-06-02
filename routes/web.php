<?php

use App\Livewire\Admin\AfsprakenOverzicht as AdminAfspraken;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\KappersOverzicht;
use App\Livewire\Admin\KlantenOverzicht as AdminKlanten;
use App\Livewire\Kapper\AfsprakenOverzicht as KapperAfspraken;
use App\Livewire\Kapper\AgendaOverzicht;
use App\Livewire\Kapper\BeschikbaarheidBeheer;
use App\Livewire\Kapper\DienstenBeheer;
use App\Livewire\Kapper\KlantenOverzicht as KapperKlanten;
use App\Livewire\Kapper\ProfielBeheer;
use App\Livewire\Kapper\Registratie as KapperRegistratie;
use App\Livewire\Klant\BoekingWizard;
use App\Livewire\Klant\KapperProfiel;
use App\Livewire\Klant\KapperZoeken;
use App\Livewire\Klant\MijnAfspraken;
use Illuminate\Support\Facades\Route;

// Publiek
Route::get('/', KapperZoeken::class)->name('home');
Route::get('/kapper/registreer', KapperRegistratie::class)->name('kapper.registreer');

// Kapper dashboard (MOET vóór /kapper/{slug} staan — anders vangt slug 'dashboard' af)
Route::middleware(['auth', 'role:kapper'])->prefix('kapper')->name('kapper.')->group(function () {
    Route::get('/dashboard', AgendaOverzicht::class)->name('dashboard');
    Route::get('/afspraken', KapperAfspraken::class)->name('afspraken');
    Route::get('/klanten', KapperKlanten::class)->name('klanten');
    Route::get('/diensten', DienstenBeheer::class)->name('diensten');
    Route::get('/beschikbaarheid', BeschikbaarheidBeheer::class)->name('beschikbaarheid');
    Route::get('/profiel', ProfielBeheer::class)->name('profiel-beheer');
});

// Publieke kapper profielpagina
Route::get('/kapper/{slug}', KapperProfiel::class)->name('kapper.profiel');

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
Route::middleware(['auth'])->group(function () {
    Route::get('/mijn-afspraken', MijnAfspraken::class)->name('klant.afspraken');
    Route::get('/boeken/{kapperSlug}/{dienstId}', BoekingWizard::class)->name('boeken');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/kappers', KappersOverzicht::class)->name('kappers');
    Route::get('/afspraken', AdminAfspraken::class)->name('afspraken');
    Route::get('/klanten', AdminKlanten::class)->name('klanten');
});
