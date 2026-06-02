<?php

namespace App\Livewire\Klant;

use App\Models\Kapper;
use App\Models\Dienst;
use App\Services\BeschikbaarheidsService;
use Carbon\Carbon;
use Livewire\Component;

class KapperProfiel extends Component
{
    public Kapper $kapper;
    public ?int $geselecteerdeDienstId = null;
    public string $geselecteerdeDatum = '';
    public array $tijdsloten = [];

    public function mount(string $slug): void
    {
        $this->kapper = Kapper::where('slug', $slug)
            ->where('actief', true)
            ->where('abonnement_status', 'actief')
            ->with(['diensten', 'beschikbaarheden'])
            ->firstOrFail();

        if ($this->kapper->diensten->isNotEmpty()) {
            $this->geselecteerdeDienstId = $this->kapper->diensten->first()->id;
        }

        $this->geselecteerdeDatum = today()->toDateString();
        $this->laadTijdsloten();
    }

    public function selecteerDienst(int $id): void
    {
        $this->geselecteerdeDienstId = $id;
        $this->laadTijdsloten();
    }

    public function updatedGeselecteerdeDatum(): void
    {
        $this->laadTijdsloten();
    }

    public function laadTijdsloten(): void
    {
        if (!$this->geselecteerdeDienstId || !$this->geselecteerdeDatum) {
            $this->tijdsloten = [];
            return;
        }

        $dienst = Dienst::find($this->geselecteerdeDienstId);
        if (!$dienst) { $this->tijdsloten = []; return; }

        $service = new BeschikbaarheidsService();
        $this->tijdsloten = $service->getVrijeTijdslots($this->kapper, $dienst, $this->geselecteerdeDatum);
    }

    public function render()
    {
        $dagNamen = ['ma', 'di', 'wo', 'do', 'vr', 'za', 'zo'];
        $openingstijden = $this->kapper->beschikbaarheden
            ->sortBy('dag_van_week')
            ->map(fn($b) => [
                'dag'   => $dagNamen[$b->dag_van_week] ?? '',
                'start' => substr($b->start_tijd, 0, 5),
                'eind'  => substr($b->eind_tijd, 0, 5),
            ]);

        return view('livewire.klant.kapper-profiel', [
            'openingstijden'    => $openingstijden,
            'geselecteerdeDienst' => $this->geselecteerdeDienstId
                ? $this->kapper->diensten->firstWhere('id', $this->geselecteerdeDienstId)
                : null,
        ])->layout('layouts.publiek');
    }
}
