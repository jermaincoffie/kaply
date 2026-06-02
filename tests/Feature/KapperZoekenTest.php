<?php

use App\Models\Kapper;
use Livewire\Livewire;
use App\Livewire\Klant\KapperZoeken;

it('klant kan kappers zoeken op stad', function () {
    $amsterdam = Kapper::factory()->create(['stad' => 'Amsterdam', 'actief' => true, 'abonnement_status' => 'actief']);
    $rotterdam = Kapper::factory()->create(['stad' => 'Rotterdam', 'actief' => true, 'abonnement_status' => 'actief']);

    Livewire::test(KapperZoeken::class)
        ->set('zoekterm', 'Amsterdam')
        ->assertSee($amsterdam->salon_naam)
        ->assertDontSee($rotterdam->salon_naam);
});

it('inactieve kappers zijn niet zichtbaar', function () {
    $kapper = Kapper::factory()->create(['stad' => 'Amsterdam', 'actief' => false]);

    Livewire::test(KapperZoeken::class)
        ->set('zoekterm', 'Amsterdam')
        ->assertDontSee($kapper->salon_naam);
});

it('kapper profielpagina is bereikbaar via slug', function () {
    $kapper = Kapper::factory()->create(['actief' => true, 'abonnement_status' => 'actief']);

    $this->get("/kapper/{$kapper->slug}")
        ->assertOk()
        ->assertSee($kapper->salon_naam);
});

it('kapper met verlopen abonnement is niet zichtbaar', function () {
    $kapper = Kapper::factory()->create([
        'stad' => 'Amsterdam',
        'actief' => true,
        'abonnement_status' => 'gepauzeerd',
    ]);

    Livewire::test(KapperZoeken::class)
        ->set('zoekterm', 'Amsterdam')
        ->assertDontSee($kapper->salon_naam);
});

it('profielpagina van inactieve kapper geeft 404', function () {
    $kapper = Kapper::factory()->create(['actief' => false]);

    $this->get("/kapper/{$kapper->slug}")
        ->assertNotFound();
});
