<?php

namespace App\Livewire\Klant;

use App\Models\Afspraak;
use App\Models\Kapper;
use App\Models\Dienst;
use App\Models\Review;
use App\Services\BeschikbaarheidsService;
use Carbon\Carbon;
use Livewire\Component;

class KapperProfiel extends Component
{
    public Kapper $kapper;
    public ?int $geselecteerdeDienstId = null;
    public string $geselecteerdeDatum = '';
    public array $tijdsloten = [];
    public ?int $geselecteerdeMedewerkerId = null;
    public ?string $sluitingsdagReden = null;

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
            ->with(['diensten', 'beschikbaarheden', 'galerij'])
            ->firstOrFail();

        if ($this->kapper->diensten->isNotEmpty()) {
            $this->geselecteerdeDienstId = $this->kapper->diensten->first()->id;
        }

        $this->geselecteerdeDatum = today()->toDateString();
        $this->laadTijdsloten();

        // Herstel pending boeking na OTP login
        $pending = session()->pull('pending_boeking');
        if ($pending && auth()->check() && $pending['kapper_slug'] === $slug) {
            $this->geselecteerdeDienstId   = $pending['dienst_id'];
            $this->geselecteerdeDatum      = $pending['datum'];
            $this->geselecteerdeMedewerkerId = $pending['medewerker_id'] ?? null;
            $this->laadTijdsloten();
            $this->geselecteerdeTijd = $pending['tijd'];
            $this->betaalmethode     = 'in_zaak';
            $this->geboekt           = false;
            $this->toonBoekModal     = true;
        }
    }

    public function selecteerDienst(int $id): void
    {
        $this->geselecteerdeDienstId = $id;
        $this->laadTijdsloten();
    }

    public function updatedGeselecteerdeDienstId(): void
    {
        $this->geselecteerdeDienstId = (int) $this->geselecteerdeDienstId;
        $this->laadTijdsloten();
    }

    public function updatedGeselecteerdeDatum(): void
    {
        $this->laadTijdsloten();
    }

    public function selecteerMedewerker(?int $id): void
    {
        $this->geselecteerdeMedewerkerId = $id;
        $this->laadTijdsloten();
    }

    public function openBoekModal(string $tijd): void
    {
        if (!auth()->check()) {
            session([
                'pending_boeking' => [
                    'kapper_slug'  => $this->kapper->slug,
                    'dienst_id'    => $this->geselecteerdeDienstId,
                    'datum'        => $this->geselecteerdeDatum,
                    'tijd'         => $tijd,
                    'medewerker_id' => $this->geselecteerdeMedewerkerId,
                ],
                'url.intended' => route('kapper.profiel', $this->kapper->slug),
            ]);
            $this->redirect(route('klant.inloggen'));
            return;
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

        if ($this->betaalmethode === 'online') {
            $afspraak = Afspraak::create([
                'klant_id'      => auth()->id(),
                'kapper_id'     => $this->kapper->id,
                'dienst_id'     => $dienst->id,
                'medewerker_id' => $this->geselecteerdeMedewerkerId,
                'datum'         => $this->geselecteerdeDatum,
                'start_tijd'    => $this->geselecteerdeTijd,
                'eind_tijd'     => $eind,
                'status'        => 'wacht_op_betaling',
                'betaalmethode' => 'online',
            ]);
            $this->redirect(route('afspraak.betaling.checkout', ['afspraak_id' => $afspraak->id]));
            return;
        }

        Afspraak::create([
            'klant_id'      => auth()->id(),
            'kapper_id'     => $this->kapper->id,
            'dienst_id'     => $dienst->id,
            'medewerker_id' => $this->geselecteerdeMedewerkerId,
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
        $sluitingsdag = $service->getSluitingsdag($this->kapper, $this->geselecteerdeDatum);
        $this->sluitingsdagReden = $sluitingsdag
            ? ($sluitingsdag->reden ?: 'gesloten')
            : null;
        $this->tijdsloten = $sluitingsdag
            ? []
            : $service->getVrijeTijdslots($this->kapper, $dienst, $this->geselecteerdeDatum, $this->geselecteerdeMedewerkerId);
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

        $medewerkers = $this->kapper->medewerkers()->where('actief', true)->get();

        $reviews = Review::where('kapper_id', $this->kapper->id)
            ->where('zichtbaar', true)
            ->with('klant')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $gemiddeldRating = $reviews->avg('rating');

        $layout = request()->boolean('embed') ? 'layouts.widget' : 'layouts.publiek';

        return view('livewire.klant.kapper-profiel', [
            'openingstijden'         => $openingstijden,
            'medewerkers'            => $medewerkers,
            'geselecteerdeDienst'    => $this->geselecteerdeDienstId
                ? $this->kapper->diensten->firstWhere('id', $this->geselecteerdeDienstId)
                : null,
            'geselecteerdeMedewerker' => $this->geselecteerdeMedewerkerId
                ? $medewerkers->firstWhere('id', $this->geselecteerdeMedewerkerId)
                : null,
            'reviews'          => $reviews,
            'gemiddeldRating'  => $gemiddeldRating,
        ])->layout($layout);
    }
}
