<?php

namespace App\Livewire\Kapper;

use Livewire\Component;

class AbonnementCancel extends Component
{
    public function render()
    {
        return view('livewire.kapper.abonnement-cancel')
            ->layout('layouts.kapper', ['title' => 'Betaling geannuleerd']);
    }
}
