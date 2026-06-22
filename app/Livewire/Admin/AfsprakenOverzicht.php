<?php

namespace App\Livewire\Admin;

use App\Models\Afspraak;
use App\Models\Kapper;
use Livewire\Component;

class AfsprakenOverzicht extends Component
{
    public string $filterStatus = '';
    public string $filterKapper = '';
    public string $zoekterm     = '';
    public int    $limite       = 30;

    public function updatingFilterStatus(): void { $this->limite = 30; }
    public function updatingFilterKapper(): void { $this->limite = 30; }
    public function updatingZoekterm(): void     { $this->limite = 30; }

    public function laadMeer(): void { $this->limite += 30; }

    public function render()
    {
        $query = Afspraak::with(['kapper', 'dienst', 'klant'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterKapper, fn($q) => $q->where('kapper_id', $this->filterKapper))
            ->when($this->zoekterm, fn($q) => $q->where(fn($q2) =>
                $q2->whereHas('klant', fn($q3) => $q3->where('name', 'like', "%{$this->zoekterm}%"))
                   ->orWhereHas('kapper', fn($q3) => $q3->where('salon_naam', 'like', "%{$this->zoekterm}%"))
            ))
            ->orderByDesc('datum')
            ->orderByDesc('start_tijd');

        $totaal    = $query->count();
        $afspraken = $query->limit($this->limite)->get();
        $heeftMeer = $totaal > $this->limite;

        $kapperOpties = ['' => 'Alle kappers'] + Kapper::where('actief', true)
            ->orderBy('salon_naam')
            ->get(['id', 'salon_naam'])
            ->mapWithKeys(fn($k) => [(string) $k->id => str($k->salon_naam)->title()->toString()])
            ->toArray();

        return view('livewire.admin.afspraken-overzicht', compact('afspraken', 'heeftMeer', 'totaal', 'kapperOpties'))
            ->layout('layouts.admin');
    }
}
