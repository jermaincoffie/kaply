<?php

use App\Models\Beschikbaarheid;
use App\Models\Kapper;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Kapper\BeschikbaarheidBeheer;

it('kapper kan beschikbaarheid opslaan', function () {
    $user = User::factory()->create(['role' => 'kapper']);
    $kapper = Kapper::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(BeschikbaarheidBeheer::class)
        ->set('rooster.0.actief', true)
        ->set('rooster.0.start_tijd', '09:00')
        ->set('rooster.0.eind_tijd', '17:00')
        ->call('opslaan')
        ->assertHasNoErrors();

    expect(Beschikbaarheid::where('kapper_id', $kapper->id)->where('dag_van_week', 0)->exists())->toBeTrue();
});

it('kapper kan sluitingsdag toevoegen', function () {
    $user = User::factory()->create(['role' => 'kapper']);
    $kapper = Kapper::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(BeschikbaarheidBeheer::class)
        ->set('sluitingsDatum', now()->addDays(7)->toDateString())
        ->set('sluitingsReden', 'Vakantie')
        ->call('sluitingsdagToevoegen')
        ->assertHasNoErrors();

    expect($kapper->sluitingsdagen()->where('reden', 'Vakantie')->exists())->toBeTrue();
});
