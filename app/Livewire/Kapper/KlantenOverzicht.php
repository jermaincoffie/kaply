<?php

namespace App\Livewire\Kapper;

use App\Models\Afspraak;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class KlantenOverzicht extends Component
{
    use WithPagination;

    public string $zoekterm = '';

    public function updatingZoekterm(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $kapperId = auth()->user()->kapper->id;

        $klanten = User::whereHas('afspraken', fn($q) => $q->where('kapper_id', $kapperId))
            ->when($this->zoekterm, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->zoekterm}%")
                  ->orWhere('email', 'like', "%{$this->zoekterm}%");
            }))
            ->withCount(['afspraken as totaal_afspraken' => fn($q) => $q->where('kapper_id', $kapperId)])
            ->withCount(['afspraken as voltooide_afspraken' => fn($q) => $q->where('kapper_id', $kapperId)->where('status', 'voltooid')])
            ->with(['afspraken' => fn($q) => $q->where('kapper_id', $kapperId)->orderByDesc('datum')->limit(1)])
            ->orderByDesc('totaal_afspraken')
            ->paginate(20);

        return view('livewire.kapper.klanten-overzicht', compact('klanten'))
            ->layout('layouts.kapper', ['title' => 'Klanten']);
    }
}
