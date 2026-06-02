<?php

namespace App\Livewire\Admin;

use App\Models\Kapper;
use Livewire\Component;

class KappersOverzicht extends Component
{
    public function activeer(int $id): void
    {
        Kapper::find($id)->update(['actief' => true, 'abonnement_status' => 'actief']);
    }

    public function deactiveer(int $id): void
    {
        Kapper::find($id)->update(['actief' => false, 'abonnement_status' => 'gepauzeerd']);
    }

    public function render()
    {
        return view('livewire.admin.kappers-overzicht', [
            'kappers' => Kapper::with('user')->orderByDesc('created_at')->get(),
        ])->layout('layouts.admin');
    }
}
