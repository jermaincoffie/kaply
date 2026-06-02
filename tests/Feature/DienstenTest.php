<?php

use App\Models\Dienst;
use App\Models\Kapper;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Kapper\DienstenBeheer;

it('kapper kan dienst toevoegen', function () {
    $user = User::factory()->create(['role' => 'kapper']);
    $kapper = Kapper::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(DienstenBeheer::class)
        ->set('naam', 'Knippen')
        ->set('duur_minuten', 30)
        ->set('prijs', '15.00')
        ->set('no_show_bedrag', '5.00')
        ->call('opslaan')
        ->assertHasNoErrors();

    expect(Dienst::where('kapper_id', $kapper->id)->where('naam', 'Knippen')->exists())->toBeTrue();
});

it('kapper kan dienst verwijderen', function () {
    $user = User::factory()->create(['role' => 'kapper']);
    $kapper = Kapper::factory()->create(['user_id' => $user->id]);
    $dienst = Dienst::factory()->create(['kapper_id' => $kapper->id]);

    Livewire::actingAs($user)
        ->test(DienstenBeheer::class)
        ->call('verwijder', $dienst->id)
        ->assertHasNoErrors();

    expect(Dienst::find($dienst->id))->toBeNull();
});
