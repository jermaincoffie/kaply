<?php

use App\Models\Kapper;
use Livewire\Livewire;
use App\Livewire\Klant\KapperZoeken;

it('klant kan kappers zoeken op stad', function () {
    Kapper::factory()->create(['stad' => 'Amsterdam', 'actief' => true, 'abonnement_status' => 'actief']);
    Kapper::factory()->create(['stad' => 'Rotterdam', 'actief' => true, 'abonnement_status' => 'actief']);

    Livewire::test(KapperZoeken::class)
        ->set('zoekterm', 'Amsterdam')
        ->assertSee('Amsterdam')
        ->assertDontSee('Rotterdam');
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
