<?php

use App\Models\User;
use App\Models\Kapper;
use Livewire\Livewire;
use App\Livewire\Kapper\Registratie;

it('stap 1 validatie werkt', function () {
    Livewire::test(Registratie::class)
        ->call('volgende')
        ->assertHasErrors(['name', 'email', 'password']);
});

it('stap 1 gaat naar stap 2 bij geldige gegevens', function () {
    Livewire::test(Registratie::class)
        ->set('name', 'Jan Jansen')
        ->set('email', 'jan@salon.nl')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('volgende')
        ->assertHasNoErrors()
        ->assertSet('stap', 2);
});

it('kapper kan zich volledig registreren', function () {
    Livewire::test(Registratie::class)
        ->set('name', 'Jan Jansen')
        ->set('email', 'jan@salon.nl')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->set('stap', 2)
        ->set('salon_naam', 'Salon Jan')
        ->set('stad', 'Amsterdam')
        ->set('telefoon', '0612345678')
        ->call('registreer')
        ->assertHasNoErrors()
        ->assertSet('stap', 3);

    $user = User::where('email', 'jan@salon.nl')->first();
    expect($user->role)->toBe('kapper');
    expect($user->kapper->salon_naam)->toBe('Salon Jan');
    expect($user->kapper->slug)->toBe('salon-jan');
});

it('stap 2 vereist salon_naam en stad', function () {
    Livewire::test(Registratie::class)
        ->set('stap', 2)
        ->call('registreer')
        ->assertHasErrors(['salon_naam', 'stad']);
});
