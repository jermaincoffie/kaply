<?php

namespace App\Livewire\Klant;

use App\Models\Afspraak;
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

    // Boeking modal
    public bool $toonBoekModal = false;
    public string $geselecteerdeTijd = '';
    public string $betaalmethode = 'in_zaak';
    public bool $geboekt = false;

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

    public function openBoekModal(string $tijd): void
    {
        if (!auth()->check()) {
            return $this->redirect(route('login'));
        }
        $this->geselecteerdeTijd = $tijd;
        $this->betaalmethode     = 'in_zaak';
        $this->geboekt           = false;
        $this->toonBoekModal     = true;
    }

    public function sluitModal(): void
    {
        $this->toonBoekModal = false;
    }

    public function bevestigBoeking(): void
    {
        if (!auth()->check()) return;

        $dienst = Dienst::findOrFail($this->geselecteerdeDienstId);
        $eind   = Carbon::parse($this->geselecteerdeDatum . ' ' . $this->geselecteerdeTijd)
            ->addMinutes($dienst->duur_minuten)
            ->format('H:i');

        Afspraak::create([
            'klant_id'      => auth()->id(),
            'kapper_id'     => $this->kapper->id,
            'dienst_id'     => $dienst->id,
            'datum'         => $this->geselecteerdeDatum,
            'start_tijd'    => $this->geselecteerdeTijd,
            'eind_tijd'     => $eind,
            'status'        => 'gepland',
            'betaalmethode' => $this->betaalmethode,
        ]);

        $this->geboekt = true;
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
