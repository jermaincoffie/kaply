<?php

namespace App\Livewire\Klant;

use App\Models\OtpCode;
use App\Models\Review;
use App\Models\Wachtlijst;
use Illuminate\Support\Facades\Auth;
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

    public function verwijderAccount(): void
    {
        $user = auth()->user();

        // Verwijder klantgebonden data (afspraken worden genullified via DB foreign key)
        Review::where('klant_id', $user->id)->delete();
        Wachtlijst::where('klant_id', $user->id)->delete();
        OtpCode::where('email', $user->email)->delete();
        $user->favorieteKappers()->detach();

        Auth::logout();

        $user->delete();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: false);
    }

    public function render()
    {
        return view('livewire.klant.account-beheer', [
            'favorieteKappers' => auth()->user()->favorieteKappers()->get(),
        ])->layout('layouts.klant', ['title' => 'Account']);
    }
}
