<?php

namespace App\Livewire\Klant;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountBeheer extends Component
{
    public string $voornaam = '';
    public string $achternaam = '';
    public string $telefoon = '';
    public string $email = '';
    public string $huidig_wachtwoord = '';
    public string $nieuw_wachtwoord = '';
    public string $nieuw_wachtwoord_confirmation = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->voornaam   = $user->voornaam ?? '';
        $this->achternaam = $user->achternaam ?? '';
        $this->telefoon   = $user->telefoon ?? '';
        $this->email      = $user->email;
    }

    public function opslaanGegevens(): void
    {
        $this->validate([
            'voornaam'   => 'required|string|max:100',
            'achternaam' => 'required|string|max:100',
            'telefoon'   => 'nullable|string|max:20',
            'email'      => ['required', 'email', Rule::unique('users', 'email')->ignore(auth()->id())],
        ]);

        auth()->user()->update([
            'voornaam'   => $this->voornaam,
            'achternaam' => $this->achternaam,
            'name'       => $this->voornaam . ' ' . $this->achternaam,
            'telefoon'   => $this->telefoon ?: null,
            'email'      => $this->email,
        ]);

        $this->dispatch('gegevens-opgeslagen');
    }

    public function opslaanWachtwoord(): void
    {
        $this->validate([
            'huidig_wachtwoord' => 'required',
            'nieuw_wachtwoord'  => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($this->huidig_wachtwoord, auth()->user()->password)) {
            $this->addError('huidig_wachtwoord', 'Huidig wachtwoord klopt niet.');
            return;
        }

        auth()->user()->update(['password' => Hash::make($this->nieuw_wachtwoord)]);

        $this->huidig_wachtwoord = '';
        $this->nieuw_wachtwoord = '';
        $this->nieuw_wachtwoord_confirmation = '';
        $this->dispatch('wachtwoord-opgeslagen');
    }

    public function render()
    {
        return view('livewire.klant.account-beheer')
            ->layout('layouts.klant', ['title' => 'Account']);
    }
}
