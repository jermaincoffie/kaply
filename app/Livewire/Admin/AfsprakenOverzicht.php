<?php

namespace App\Livewire\Admin;

use App\Models\Afspraak;
use Livewire\Component;
use Livewire\WithPagination;

class AfsprakenOverzicht extends Component
{
    use WithPagination;

    public string $filterStatus = '';
    public string $zoekterm = '';

    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingZoekterm(): void { $this->resetPage(); }

    public function render()
    {
        $afspraken = Afspraak::with(['kapper', 'dienst', 'klant'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->zoekterm, function ($q) {
                $q->whereHas('klant', fn($q2) => $q2->where('name', 'like', "%{$this->zoekterm}%"))
                  ->orWhereHas('kapper', fn($q2) => $q2->where('salon_naam', 'like', "%{$this->zoekterm}%"));
            })
            ->orderByDesc('datum')
            ->orderByDesc('start_tijd')
            ->paginate(20);

        return view('livewire.admin.afspraken-overzicht', compact('afspraken'))
            ->layout('layouts.admin');
    }
}
