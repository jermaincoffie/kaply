<?php

namespace App\Livewire\Klant;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountBeheer extends Component
{
    public string $name = '';
    public string $email = '';
    public string $huidig_wachtwoord = '';
    public string $nieuw_wachtwoord = '';
    public string $nieuw_wachtwoord_confirmation = '';

    public function mount(): void
    {
        $this->name  = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function opslaanGegevens(): void
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore(auth()->id())],
        ]);

        auth()->user()->update([
            'name'  => $this->name,
            'email' => $this->email,
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
