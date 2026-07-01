<?php

namespace App\Livewire\Kapper;

use Livewire\Component;

class AbonnementBeheer extends Component
{
    public function annuleer(): void
    {
        $subscription = auth()->user()->subscription('default');

        if (!$subscription) {
            session()->flash('abonnement_fout', 'Geen abonnement gevonden.');
            return;
        }

        try {
            $subscription->cancelNow();
            $this->redirect(route('kapper.abonnement'));
        } catch (\Exception $e) {
            report($e);
            session()->flash('abonnement_fout', 'Opzeggen mislukt: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $user         = auth()->user();
        $kapper       = $user->kapper;
        $subscription = $user->subscription('default');

        $inTrial = $subscription?->onTrial() ?? false;
        $trialDagenOver = $inTrial
            ? max(0, (int) now()->diffInDays($subscription->trial_ends_at))
            : null;

        return view('livewire.kapper.abonnement-beheer', [
            'subscription'          => $subscription,
            'actief'                => $subscription?->active() ?? false,
            'gepauzeerd'            => $subscription?->onGracePeriod() ?? false,
            'eindDatum'             => $subscription?->ends_at,
            'inTrial'               => $inTrial,
            'trialDagenOver'        => $trialDagenOver,
            'stripeConnectOnboarded' => $kapper?->stripe_connect_onboarded ?? false,
        ])->layout('layouts.kapper');
    }
}
