<?php

namespace App\Livewire\Klant;

use App\Models\Dienst;
use App\Models\Kapper;
use Livewire\Component;

class KapperZoeken extends Component
{
    public string $zoekterm    = '';
    public string $stadFilter  = '';
    public string $dienstFilter = '';
    public string $prijsMax    = '';

    public function resetFilters(): void
    {
        $this->dienstFilter = '';
        $this->prijsMax     = '';
        $this->stadFilter   = '';
    }

    public function render()
    {
        $prijsMaxCenten = $this->prijsMax ? (int) substr($this->prijsMax, 1) * 100 : null;

        $kappers = Kapper::where('actief', true)
            ->where('abonnement_status', 'actief')
            ->when($this->zoekterm, fn($q) =>
                $q->where('salon_naam', 'like', "%{$this->zoekterm}%")
            )
            ->when($this->stadFilter, fn($q) =>
                $q->where('stad', $this->stadFilter)
            )
            ->when($this->dienstFilter, fn($q) =>
                $q->whereHas('diensten', fn($q2) =>
                    $q2->where('naam', 'like', "%{$this->dienstFilter}%")
                )
            )
            ->when($prijsMaxCenten, fn($q) =>
                $q->whereHas('diensten', fn($q2) =>
                    $q2->where('prijs', '<=', $prijsMaxCenten)
                )
            )
            ->with('diensten')
            ->withAvg(['reviews as gem_rating' => fn($q) => $q->where('zichtbaar', true)], 'rating')
            ->withCount(['reviews as review_count' => fn($q) => $q->where('zichtbaar', true)])
            ->orderBy('salon_naam')
            ->get();

        $steden = Kapper::where('actief', true)
            ->where('abonnement_status', 'actief')
            ->whereNotNull('stad')
            ->pluck('stad')
            ->map(fn($s) => str($s)->title()->toString())
            ->unique()
            ->sort()
            ->values();

        $diensteNamen = Dienst::whereHas('kapper', fn($q) =>
                $q->where('actief', true)->where('abonnement_status', 'actief')
            )
            ->select('naam')
            ->groupBy('naam')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(20)
            ->pluck('naam');

        $heeftFilters = $this->stadFilter !== '' || $this->dienstFilter !== '' || $this->prijsMax !== '';

        return view('livewire.klant.kapper-zoeken', compact(
            'kappers', 'steden', 'diensteNamen', 'heeftFilters'
        ))
            ->layout('layouts.publiek', [
                'seoTitle'       => 'Kaply - Online kapper afspraak boeken in jouw buurt',
                'seoDescription' => 'Vind en boek een kapper bij jou in de buurt via Kaply. Kies uit tientallen kappers, bekijk reviews en boek direct online zonder account.',
            ]);
    }
}
