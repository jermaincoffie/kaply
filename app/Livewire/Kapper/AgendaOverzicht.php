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
        $kapper_id = auth()->user()->kapper->id;

        $omzet_maand = Afspraak::where('afspraken.kapper_id', $kapper_id)
            ->where('afspraken.status', 'voltooid')
            ->whereMonth('afspraken.datum', now()->month)
            ->whereYear('afspraken.datum', now()->year)
            ->join('diensten', 'afspraken.dienst_id', '=', 'diensten.id')
            ->sum('diensten.prijs');

        $afspraken_maand = Afspraak::where('kapper_id', $kapper_id)
            ->whereMonth('datum', now()->month)
            ->whereYear('datum', now()->year)
            ->whereIn('status', ['gepland', 'voltooid'])
            ->count();

        $komende_afspraken = Afspraak::where('kapper_id', $kapper_id)
            ->where('datum', '>=', today())
            ->where('status', 'gepland')
            ->count();

        return view('livewire.kapper.agenda-overzicht', [
            'afspraken'        => Afspraak::where('kapper_id', $kapper_id)
                ->whereDate('datum', $this->geselecteerdeDatum)
                ->with(['klant', 'dienst'])
                ->orderBy('start_tijd')
                ->get(),
            'omzet_maand'      => $omzet_maand,
            'afspraken_maand'  => $afspraken_maand,
            'komende_afspraken' => $komende_afspraken,
        ])->layout('layouts.kapper');
    }
}
