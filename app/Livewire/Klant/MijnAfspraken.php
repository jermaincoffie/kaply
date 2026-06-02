<?php

namespace App\Livewire\Klant;

use App\Models\Afspraak;
use Livewire\Component;

class MijnAfspraken extends Component
{
    public function annuleer(int $id): void
    {
        Afspraak::where('id', $id)
            ->where('klant_id', auth()->id())
            ->where('status', 'gepland')
            ->update(['status' => 'geannuleerd']);
    }

    public function render()
    {
        return view('livewire.klant.mijn-afspraken', [
            'aankomend' => Afspraak::where('klant_id', auth()->id())
                ->where('datum', '>=', today())
                ->where('status', 'gepland')
                ->with(['kapper', 'dienst'])
                ->orderBy('datum')->orderBy('start_tijd')
                ->get(),
            'geschiedenis' => Afspraak::where('klant_id', auth()->id())
                ->where(fn($q) => $q->where('datum', '<', today())->orWhereNotIn('status', ['gepland']))
                ->with(['kapper', 'dienst'])
                ->orderByDesc('datum')
                ->limit(20)
                ->get(),
        ])->layout('layouts.klant');
    }
}
