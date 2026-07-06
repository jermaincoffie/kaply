<?php

namespace App\Livewire\Kapper;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AccountBeheer extends Component
{
    public string $huidigWachtwoord = '';
    public string $nieuwWachtwoord = '';
    public string $nieuwWachtwoordBevestiging = '';

    public string $email = '';
    public string $naam = '';
    public bool $notificatieEmail = true;

    public function mount(): void
    {
        $this->email = Auth::user()->email;
        $this->naam  = Auth::user()->name;
        $this->notificatieEmail = (bool) Auth::user()->kapper?->notificatie_email ?? true;
    }

    public function slaNotificatieEmailOp(): void
    {
        Auth::user()->kapper?->update(['notificatie_email' => $this->notificatieEmail]);
        session()->flash('notificatie_opgeslagen', true);
    }

    public function wijzigWachtwoord(): void
    {
        $this->validate([
            'huidigWachtwoord'            => ['required'],
            'nieuwWachtwoord'             => ['required', 'same:nieuwWachtwoordBevestiging', Password::min(8)],
            'nieuwWachtwoordBevestiging'  => ['required'],
        ], [
            'huidigWachtwoord.required'           => 'Huidig wachtwoord is verplicht.',
            'nieuwWachtwoord.required'             => 'Nieuw wachtwoord is verplicht.',
            'nieuwWachtwoord.confirmed'            => 'Wachtwoorden komen niet overeen.',
            'nieuwWachtwoord.min'                  => 'Wachtwoord moet minimaal 8 tekens zijn.',
            'nieuwWachtwoordBevestiging.required'  => 'Bevestig je nieuwe wachtwoord.',
        ]);

        if (!Hash::check($this->huidigWachtwoord, Auth::user()->password)) {
            $this->addError('huidigWachtwoord', 'Huidig wachtwoord is onjuist.');
            return;
        }

        Auth::user()->update([
            'password' => Hash::make($this->nieuwWachtwoord),
        ]);

        $this->reset(['huidigWachtwoord', 'nieuwWachtwoord', 'nieuwWachtwoordBevestiging']);
        $this->dispatch('wachtwoord-opgeslagen');
    }

    public function verwijderAccount(): void
    {
        $user = Auth::user();

        // Abonnement direct opzeggen bij Stripe
        try {
            $user->subscription('default')?->cancelNow();
        } catch (\Exception $e) {
            report($e);
        }

        Auth::logout();

        DB::transaction(function () use ($user) {
            $user->kapper?->delete();
            $user->notifications()->delete();
            $user->subscriptions()->delete();
            $user->delete();
        });

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('home'), navigate: false);
    }

    public function render()
    {
        return view('livewire.kapper.account-beheer')
            ->layout('layouts.kapper', ['title' => 'Account']);
    }
}
