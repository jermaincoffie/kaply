<?php

namespace App\Livewire\Kapper;

use Livewire\Component;

class AbonnementBeheer extends Component
{
    public function render()
    {
        $user = auth()->user();
        $subscription = $user->subscription('default');

        return view('livewire.kapper.abonnement-beheer', [
            'subscription' => $subscription,
            'actief' => $subscription?->active() ?? false,
            'gepauzeerd' => $subscription?->onGracePeriod() ?? false,
            'eindDatum' => $subscription?->ends_at,
        ])->layout('layouts.kapper');
    }
}
