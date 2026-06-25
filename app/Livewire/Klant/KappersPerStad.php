<?php

namespace App\Livewire\Klant;

use App\Models\Dienst;
use App\Models\Kapper;
use Livewire\Component;

class KappersPerStad extends Component
{
    public string $stad         = '';
    public string $stadLabel    = '';
    public string $dienstFilter = '';
    public string $prijsMax     = '';

    public function mount(string $stad): void
    {
        $this->stad      = $stad;
        $this->stadLabel = str(str_replace('-', ' ', $stad))->title()->toString();
    }

    public function resetFilters(): void
    {
        $this->dienstFilter = '';
        $this->prijsMax     = '';
    }

    public function render()
    {
        $prijsMaxCenten = $this->prijsMax ? (int) substr($this->prijsMax, 1) * 100 : null;
        $stadSlug = strtolower($this->stad);

        $kappers = Kapper::where('actief', true)
            ->where('abonnement_status', 'actief')
            ->whereRaw("REPLACE(LOWER(stad), ' ', '-') = ?", [$stadSlug])
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
            ->orderByDesc('gem_rating')
            ->get();

        $diensteNamen = Dienst::whereHas('kapper', fn($q) =>
                $q->where('actief', true)
                  ->where('abonnement_status', 'actief')
                  ->whereRaw("REPLACE(LOWER(stad), ' ', '-') = ?", [$stadSlug])
            )
            ->select('naam')
            ->groupBy('naam')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(20)
            ->pluck('naam');

        $heeftFilters = $this->dienstFilter !== '' || $this->prijsMax !== '';

        return view('livewire.klant.kappers-per-stad', compact('kappers', 'diensteNamen', 'heeftFilters'))
            ->layout('layouts.publiek', [
                'seoTitle'       => "Kapper boeken in {$this->stadLabel} | Kaply",
                'seoDescription' => "Vind en boek een kapper in {$this->stadLabel} via Kaply. Bekijk reviews, kies een dienst en boek direct online zonder account.",
            ]);
    }
}
