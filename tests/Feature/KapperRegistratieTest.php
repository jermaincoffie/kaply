<?php

use App\Models\User;
use App\Models\Kapper;
use Livewire\Livewire;
use App\Livewire\Kapper\Registratie;

it('kapper kan zich registreren', function () {
    Livewire::test(Registratie::class)
        ->set('name', 'Jan Jansen')
        ->set('email', 'jan@salon.nl')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->set('salon_naam', 'Salon Jan')
        ->set('stad', 'Amsterdam')
        ->set('telefoon', '0612345678')
        ->call('registreer')
        ->assertHasNoErrors()
        ->assertRedirect(route('kapper.dashboard'));

    $user = User::where('email', 'jan@salon.nl')->first();
    expect($user->role)->toBe('kapper');
    expect($user->kapper->salon_naam)->toBe('Salon Jan');
    expect($user->kapper->slug)->toBe('salon-jan');
});

it('kapper registratie vereist salon_naam en stad', function () {
    Livewire::test(Registratie::class)
        ->set('name', 'Jan')
        ->set('email', 'jan@salon.nl')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('registreer')
        ->assertHasErrors(['salon_naam', 'stad']);
});
