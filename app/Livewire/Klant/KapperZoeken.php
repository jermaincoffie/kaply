<?php

namespace App\Livewire\Klant;

use App\Models\Kapper;
use Livewire\Component;

class KapperZoeken extends Component
{
    public string $zoekterm = '';

    public function render()
    {
        $kappers = Kapper::where('actief', true)
            ->where('abonnement_status', 'actief')
            ->when($this->zoekterm, function ($query) {
                $query->where(function ($q) {
                    $q->where('stad', 'like', "%{$this->zoekterm}%")
                      ->orWhere('salon_naam', 'like', "%{$this->zoekterm}%");
                });
            })
            ->with('diensten')
            ->orderBy('salon_naam')
            ->get();

        return view('livewire.klant.kapper-zoeken', compact('kappers'))
            ->layout('layouts.publiek');
    }
}
