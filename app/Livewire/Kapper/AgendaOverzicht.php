<?php

namespace App\Livewire\Kapper;

use App\Models\Afspraak;
use Livewire\Component;

class AgendaOverzicht extends Component
{
    public string $geselecteerdeDatum;

    public function mount(): void
    {
        $this->geselecteerdeDatum = today()->toDateString();
    }

    public function noShow(int $id): void
    {
        Afspraak::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->update(['status' => 'no_show']);
    }

    public function voltooid(int $id): void
    {
        Afspraak::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->update(['status' => 'voltooid']);
    }

    public function render()
    {
        return view('livewire.kapper.agenda-overzicht', [
            'afspraken' => Afspraak::where('kapper_id', auth()->user()->kapper->id)
                ->whereDate('datum', $this->geselecteerdeDatum)
                ->with(['klant', 'dienst'])
                ->orderBy('start_tijd')
                ->get(),
        ])->layout('layouts.kapper');
    }
}
