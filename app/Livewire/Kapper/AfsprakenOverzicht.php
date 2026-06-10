<?php

namespace App\Livewire\Kapper;

use App\Models\Afspraak;
use Livewire\Component;
use Livewire\WithPagination;

class AfsprakenOverzicht extends Component
{
    use WithPagination;

    public string $periode = 'aankomend'; // aankomend|verleden|alles
    public string $filterStatus = '';

    public function updatingPeriode(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function render()
    {
        $kapperId = auth()->user()->kapper->id;

        $heeftAfspraken = Afspraak::where('kapper_id', $kapperId)->exists();

        $afspraken = Afspraak::where('kapper_id', $kapperId)
            ->when($this->periode === 'aankomend', fn($q) => $q->where('datum', '>=', today())->where('status', 'gepland'))
            ->when($this->periode === 'verleden',  fn($q) => $q->where(fn($q) => $q->where('datum', '<', today())->orWhereIn('status', ['voltooid','geannuleerd','no_show'])))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->with(['klant', 'dienst'])
            ->orderBy('datum', $this->periode === 'verleden' ? 'desc' : 'asc')
            ->orderBy('start_tijd')
            ->paginate(15);

        return view('livewire.kapper.afspraken-overzicht', compact('afspraken', 'heeftAfspraken'))
            ->layout('layouts.kapper', ['title' => 'Afspraken']);
    }
}
