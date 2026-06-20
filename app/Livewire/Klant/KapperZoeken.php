<?php

namespace App\Livewire\Klant;

use App\Models\Dienst;
use App\Models\Kapper;
use Livewire\Component;

class KapperZoeken extends Component
{
    public string $zoekterm    = '';
    public string $dienstFilter = '';
    public string $prijsMax    = '';

    public function filterStad(string $stad): void
    {
        $this->zoekterm = $stad;
    }

    public function resetFilters(): void
    {
        $this->dienstFilter = '';
        $this->prijsMax     = '';
    }

    public function render()
    {
        // prijsMax keys zijn 'p15', 'p25' etc. om int-casting in PHP/JS te vermijden
        $prijsMaxCenten = $this->prijsMax ? (int) substr($this->prijsMax, 1) * 100 : null;

        $kappers = Kapper::where('actief', true)
            ->where('abonnement_status', 'actief')
            ->when($this->zoekterm, function ($query) {
                $query->where(function ($q) {
                    $q->where('stad', 'like', "%{$this->zoekterm}%")
                      ->orWhere('salon_naam', 'like', "%{$this->zoekterm}%");
                });
            })
            ->when($this->dienstFilter, function ($query) {
                $query->whereHas('diensten', fn($q) =>
                    $q->where('naam', 'like', "%{$this->dienstFilter}%")
                );
            })
            ->when($prijsMaxCenten, function ($query) use ($prijsMaxCenten) {
                $query->whereHas('diensten', fn($q) =>
                    $q->where('prijs', '<=', $prijsMaxCenten)
                );
            })
            ->with('diensten')
            ->withAvg(['reviews as gem_rating' => fn($q) => $q->where('zichtbaar', true)], 'rating')
            ->withCount(['reviews as review_count' => fn($q) => $q->where('zichtbaar', true)])
            ->orderBy('salon_naam')
            ->get();

        $kappers_totaal = Kapper::where('actief', true)->where('abonnement_status', 'actief')->count();

        $steden = Kapper::where('actief', true)
            ->where('abonnement_status', 'actief')
            ->whereNotNull('stad')
            ->pluck('stad')
            ->map(fn($s) => str($s)->title()->toString())
            ->unique()
            ->sort()
            ->values()
            ->take(8);

        $diensteNamen = Dienst::whereHas('kapper', fn($q) =>
                $q->where('actief', true)->where('abonnement_status', 'actief')
            )
            ->select('naam')
            ->groupBy('naam')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(20)
            ->pluck('naam');

        $heeftFilters = $this->dienstFilter !== '' || $this->prijsMax !== '';

        return view('livewire.klant.kapper-zoeken', compact(
            'kappers', 'kappers_totaal', 'steden', 'diensteNamen', 'heeftFilters'
        ))
            ->layout('layouts.publiek', [
                'seoTitle'       => 'Kaply - Online kapper afspraak boeken in jouw buurt',
                'seoDescription' => 'Vind en boek een kapper bij jou in de buurt via Kaply. Kies uit tientallen kappers, bekijk reviews en boek direct online zonder account.',
            ]);
    }
}
