<?php

namespace App\Livewire\Kapper;

use App\Models\Afspraak;
use Carbon\Carbon;
use Livewire\Component;

class AgendaOverzicht extends Component
{
    public string $weekStart;
    public ?int $geselecteerdeAfspraakId = null;

    public function mount(): void
    {
        $this->weekStart = today()->startOfWeek(Carbon::MONDAY)->toDateString();
    }

    public function vorigeWeek(): void
    {
        $this->weekStart = Carbon::parse($this->weekStart)->subWeek()->toDateString();
        $this->geselecteerdeAfspraakId = null;
    }

    public function volgendeWeek(): void
    {
        $this->weekStart = Carbon::parse($this->weekStart)->addWeek()->toDateString();
        $this->geselecteerdeAfspraakId = null;
    }

    public function naarVandaag(): void
    {
        $this->weekStart = today()->startOfWeek(Carbon::MONDAY)->toDateString();
        $this->geselecteerdeAfspraakId = null;
    }

    public function selecteerAfspraak(?int $id): void
    {
        $this->geselecteerdeAfspraakId = $this->geselecteerdeAfspraakId === $id ? null : $id;
    }

    public function noShow(int $id): void
    {
        Afspraak::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->update(['status' => 'no_show']);
        $this->geselecteerdeAfspraakId = null;
    }

    public function voltooid(int $id): void
    {
        Afspraak::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->update(['status' => 'voltooid']);
        $this->geselecteerdeAfspraakId = null;
    }

    public function render()
    {
        $kapper_id = auth()->user()->kapper->id;
        $weekStartDate = Carbon::parse($this->weekStart);
        $weekEndDate   = $weekStartDate->copy()->endOfWeek(Carbon::SUNDAY);

        $days = collect();
        for ($i = 0; $i < 7; $i++) {
            $days->push($weekStartDate->copy()->addDays($i));
        }

        $afspraken = Afspraak::where('kapper_id', $kapper_id)
            ->whereBetween('datum', [$weekStartDate->toDateString(), $weekEndDate->toDateString()])
            ->with(['klant', 'dienst'])
            ->orderBy('start_tijd')
            ->get();

        $afsprakenPerDag = $afspraken->groupBy(fn($a) => $a->datum->toDateString());

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

        $geselecteerdeAfspraak = $this->geselecteerdeAfspraakId
            ? $afspraken->firstWhere('id', $this->geselecteerdeAfspraakId)
            : null;

        return view('livewire.kapper.agenda-overzicht', compact(
            'days', 'afsprakenPerDag', 'omzet_maand', 'afspraken_maand',
            'komende_afspraken', 'geselecteerdeAfspraak', 'weekStartDate'
        ))->layout('layouts.kapper', ['title' => 'Agenda']);
    }
}
