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
            ->withAvg(['reviews as gem_rating' => fn($q) => $q->where('zichtbaar', true)], 'rating')
            ->withCount(['reviews as review_count' => fn($q) => $q->where('zichtbaar', true)])
            ->orderBy('salon_naam')
            ->get();

        return view('livewire.klant.kapper-zoeken', compact('kappers'))
            ->layout('layouts.publiek');
    }
}
