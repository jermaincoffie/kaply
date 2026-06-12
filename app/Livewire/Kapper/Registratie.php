<?php

namespace App\Livewire\Kapper;

use App\Models\Kapper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Registratie extends Component
{
    public int $stap = 1;

    // Stap 1
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Stap 2
    public string $salon_naam = '';
    public string $stad = '';
    public string $adres = '';
    public string $telefoon = '';

    protected function stapEenRules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ];
    }

    protected function stapTweeRules(): array
    {
        return [
            'salon_naam' => 'required|string|max:255',
            'stad'       => 'required|string|max:255',
            'adres'      => 'nullable|string|max:255',
            'telefoon'   => 'nullable|string|max:20',
        ];
    }

    public function volgende(): void
    {
        $this->validate($this->stapEenRules(), [
            'name.required'      => 'Naam is verplicht.',
            'name.max'           => 'Naam mag maximaal 255 tekens zijn.',
            'email.required'     => 'E-mailadres is verplicht.',
            'email.email'        => 'Voer een geldig e-mailadres in.',
            'email.unique'       => 'Dit e-mailadres is al geregistreerd.',
            'password.required'  => 'Wachtwoord is verplicht.',
            'password.min'       => 'Wachtwoord moet minimaal 8 tekens zijn.',
            'password.confirmed' => 'Wachtwoorden komen niet overeen.',
        ]);
        $this->stap = 2;
    }

    public function vorige(): void
    {
        $this->stap = 1;
    }

    public function registreer(): void
    {
        $this->validate($this->stapTweeRules());

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'role'     => 'kapper',
        ]);

        Kapper::create([
            'user_id'           => $user->id,
            'salon_naam'        => $this->salon_naam,
            'slug'              => Kapper::generateSlug($this->salon_naam),
            'stad'              => $this->stad,
            'adres'             => $this->adres ?: null,
            'telefoon'          => $this->telefoon ?: null,
            'abonnement_status' => 'geen',
            'actief'            => false,
        ]);

        Auth::login($user);

        $this->stap = 3;
    }

    public function render()
    {
        return view('livewire.kapper.registratie')->layout('layouts.publiek');
    }
}
