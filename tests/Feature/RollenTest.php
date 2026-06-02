<?php

use App\Models\User;

it('user heeft standaard rol klant', function () {
    $user = User::factory()->create();
    expect($user->role)->toBe('klant');
});

it('user kan kapper rol hebben', function () {
    $user = User::factory()->create(['role' => 'kapper']);
    expect($user->isKapper())->toBeTrue();
});

it('user kan admin rol hebben', function () {
    $user = User::factory()->create(['role' => 'admin']);
    expect($user->isAdmin())->toBeTrue();
});
