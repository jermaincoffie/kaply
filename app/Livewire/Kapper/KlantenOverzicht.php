<?php

namespace App\Livewire\Kapper;

use App\Models\Afspraak;
use App\Models\KlantNotitie;
use App\Models\User;
use Livewire\Component;

class KlantenOverzicht extends Component
{
    public string $zoekterm = '';
    public int $limite = 30;
    public ?int $notitieKlantId = null;
    public string $notitieText = '';
    public ?int $geselecteerdeKlantId = null;

    public function selecteerKlant(int $id): void
    {
        $this->geselecteerdeKlantId = $this->geselecteerdeKlantId === $id ? null : $id;
    }

    public function openNotitie(int $klantId): void
    {
        $this->notitieKlantId = $klantId;
        $kapperId = auth()->user()->kapper->id;
        $this->notitieText = KlantNotitie::where('kapper_id', $kapperId)
            ->where('klant_id', $klantId)
            ->value('notities') ?? '';
    }

    public function slaNotitieOp(): void
    {
        $kapperId = auth()->user()->kapper->id;
        KlantNotitie::updateOrCreate(
            ['kapper_id' => $kapperId, 'klant_id' => $this->notitieKlantId],
            ['notities' => $this->notitieText ?: null]
        );
        $this->notitieKlantId = null;
        $this->dispatch('notitie-opgeslagen');
    }

    public function updatingZoekterm(): void { $this->limite = 30; }

    public function laadMeer(): void { $this->limite += 30; }

    public function render()
    {
        $kapperId = auth()->user()->kapper->id;

        $query = User::whereHas('afspraken', fn($q) => $q->where('kapper_id', $kapperId))
            ->when($this->zoekterm, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->zoekterm}%")
                  ->orWhere('email', 'like', "%{$this->zoekterm}%");
            }))
            ->withCount(['afspraken as totaal_afspraken' => fn($q) => $q->where('kapper_id', $kapperId)])
            ->withCount(['afspraken as voltooide_afspraken' => fn($q) => $q->where('kapper_id', $kapperId)->where('status', 'voltooid')])
            ->with(['afspraken' => fn($q) => $q->where('kapper_id', $kapperId)->orderByDesc('datum')->limit(1)])
            ->with(['klantNotitie' => fn($q) => $q->where('kapper_id', $kapperId)])
            ->orderByDesc('totaal_afspraken');

        $totaal    = $query->count();
        $klanten   = $query->limit($this->limite)->get();
        $heeftMeer = $totaal > $this->limite;

        $geselecteerdeKlant = null;
        if ($this->geselecteerdeKlantId) {
            $geselecteerdeKlant = User::where('id', $this->geselecteerdeKlantId)
                ->with(['afspraken' => fn($q) => $q
                    ->where('kapper_id', $kapperId)
                    ->with('dienst')
                    ->orderByDesc('datum')
                    ->limit(5)
                ])
                ->with(['klantNotitie' => fn($q) => $q->where('kapper_id', $kapperId)])
                ->first();
        }

        return view('livewire.kapper.klanten-overzicht', compact('klanten', 'geselecteerdeKlant', 'heeftMeer'))
            ->layout('layouts.kapper', ['title' => 'Klanten']);
    }
}
