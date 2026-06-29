<?php

namespace App\Livewire\Klant;

use Livewire\Component;
use Illuminate\Validation\Rule;

class AccountBeheer extends Component
{
    public string $voornaam = '';
    public string $achternaam = '';
    public string $telefoon = '';
    public string $email = '';

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

    public function verwijderFavoriet(int $kapperId): void
    {
        auth()->user()->favorieteKappers()->detach($kapperId);
    }

    public function render()
    {
        return view('livewire.klant.account-beheer', [
            'favorieteKappers' => auth()->user()->favorieteKappers()->get(),
        ])->layout('layouts.klant', ['title' => 'Account']);
    }
}
