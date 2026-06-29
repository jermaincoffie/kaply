<?php

namespace App\Livewire\Klant;

use App\Mail\AfspraakBevestigingMail;
use App\Models\Activiteit;
use App\Models\Afspraak;
use App\Models\Kapper;
use App\Models\Dienst;
use App\Models\Review;
use App\Models\Wachtlijst;
use App\Services\BeschikbaarheidsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
    public bool $isFavoriet = false;
    public bool $toonBoekModal = false;
    public string $geselecteerdeTijd = '';
    public string $betaalmethode = 'in_zaak';
    public string $klantNotitie = '';
    public bool $geboekt = false;

    // Beschikbaarheid
    public bool $kapperWerktDag            = false;
    public bool $medewerkerWerktNietDezeDag = false;

    // Wachtlijst
    public bool   $toonWachtlijstForm  = false;
    public string $wachtlijstNaam      = '';
    public string $wachtlijstEmail     = '';
    public string $wachtlijstTelefoon  = '';
    public bool   $wachtlijstVerstuurd = false;
    public string $wachtlijstFout      = '';

    public function mount(string $slug): void
    {
        $this->kapper = Kapper::where('slug', $slug)
            ->where('actief', true)
            ->where('abonnement_status', 'actief')
            ->with(['diensten', 'beschikbaarheden', 'galerij'])
            ->firstOrFail();

        if ($this->kapper->diensten->isNotEmpty()) {
            $herboekDienstId = (int) request('dienst_id');
            $heeftDienst = $herboekDienstId && $this->kapper->diensten->contains('id', $herboekDienstId);
            $this->geselecteerdeDienstId = $heeftDienst
                ? $herboekDienstId
                : $this->kapper->diensten->first()->id;
        }

        $this->geselecteerdeDatum = today()->toDateString();
        $this->laadTijdsloten();

        if (auth()->check()) {
            $this->wachtlijstNaam  = auth()->user()->name;
            $this->wachtlijstEmail = auth()->user()->email;

            if (auth()->user()->isKlant()) {
                $this->isFavoriet = auth()->user()->favorieteKappers()
                    ->where('kapper_id', $this->kapper->id)
                    ->exists();
            }
        }

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
        $this->resetWachtlijst();
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
        $this->klantNotitie      = '';
        $this->geboekt           = false;
        $this->toonBoekModal     = true;
    }

    public function sluitModal(): void
    {
        $this->toonBoekModal = false;
        $this->klantNotitie  = '';
    }

    public function bevestigBoeking(): void
    {
        if (!auth()->check()) return;

        $dienst = Dienst::findOrFail($this->geselecteerdeDienstId);
        $eind   = Carbon::parse($this->geselecteerdeDatum . ' ' . $this->geselecteerdeTijd)
            ->addMinutes($dienst->duur_minuten)
            ->format('H:i');

        $slotBezet     = false;
        $redirectUrl   = null;
        $afspraak      = null;

        DB::transaction(function () use ($dienst, $eind, &$slotBezet, &$redirectUrl, &$afspraak) {
            // Conflict check met lock — voorkomt dubbele boeking bij gelijktijdige requests
            $conflict = Afspraak::where('kapper_id', $this->kapper->id)
                ->where('datum', $this->geselecteerdeDatum)
                ->whereIn('status', ['gepland', 'wacht_op_betaling'])
                ->where('start_tijd', '<', $eind)
                ->where('eind_tijd', '>', $this->geselecteerdeTijd)
                ->when(
                    $this->geselecteerdeMedewerkerId,
                    fn($q) => $q->where('medewerker_id', $this->geselecteerdeMedewerkerId),
                    fn($q) => $q->whereNull('medewerker_id')
                )
                ->lockForUpdate()
                ->exists();

            if ($conflict) {
                $slotBezet = true;
                return;
            }

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
                    'notitie'       => trim($this->klantNotitie) ?: null,
                ]);
                Activiteit::create([
                    'kapper_id'   => $this->kapper->id,
                    'afspraak_id' => $afspraak->id,
                    'datum'       => $this->geselecteerdeDatum,
                    'type'        => 'geboekt',
                    'tekst'       => auth()->user()->name . " heeft een afspraak gemaakt voor {$dienst->naam} om {$this->geselecteerdeTijd}",
                ]);
                $redirectUrl = route('afspraak.betaling.checkout', ['afspraak_id' => $afspraak->id]);
                return;
            }

            $afspraak = Afspraak::create([
                'klant_id'      => auth()->id(),
                'kapper_id'     => $this->kapper->id,
                'dienst_id'     => $dienst->id,
                'medewerker_id' => $this->geselecteerdeMedewerkerId,
                'datum'         => $this->geselecteerdeDatum,
                'start_tijd'    => $this->geselecteerdeTijd,
                'eind_tijd'     => $eind,
                'status'        => 'gepland',
                'betaalmethode' => $this->betaalmethode,
                'notitie'       => trim($this->klantNotitie) ?: null,
            ]);
        });

        if ($slotBezet) {
            $this->addError('slot', 'Dit tijdslot is zojuist geboekt. Kies een ander tijdstip.');
            $this->laadTijdsloten();
            return;
        }

        if ($redirectUrl) {
            $this->redirect($redirectUrl);
            return;
        }

        Activiteit::create([
            'kapper_id'   => $this->kapper->id,
            'afspraak_id' => $afspraak->id,
            'datum'       => $this->geselecteerdeDatum,
            'type'        => 'geboekt',
            'tekst'       => auth()->user()->name . " heeft een afspraak gemaakt voor {$dienst->naam} om {$this->geselecteerdeTijd}",
        ]);

        Mail::to(auth()->user()->email)->send(new AfspraakBevestigingMail($afspraak));

        // Klant heeft geboekt — verwijder van wachtlijst voor deze kapper
        if (auth()->id()) {
            Wachtlijst::where('kapper_id', $this->kapper->id)
                ->where('klant_id', auth()->id())
                ->delete();
        }

        $this->geboekt = true;
        $this->laadTijdsloten();
    }

    public function wachtlijstAanmelden(): void
    {
        $this->wachtlijstFout = '';
        $this->validate([
            'wachtlijstNaam'     => 'required|string|min:2',
            'wachtlijstEmail'    => 'required|email',
            'wachtlijstTelefoon' => 'nullable|string|max:20',
        ]);

        if (Wachtlijst::where('kapper_id', $this->kapper->id)->where('email', $this->wachtlijstEmail)->exists()) {
            $this->wachtlijstFout = 'Dit e-mailadres staat al op de wachtlijst.';
            return;
        }

        Wachtlijst::create([
            'kapper_id'      => $this->kapper->id,
            'klant_id'       => auth()->id(),
            'naam'           => $this->wachtlijstNaam,
            'email'          => $this->wachtlijstEmail,
            'telefoonnummer' => $this->wachtlijstTelefoon ?: null,
            'gewenste_datum' => $this->geselecteerdeDatum ?: null,
            'status'         => 'wachtend',
        ]);

        $this->wachtlijstVerstuurd = true;
        $this->toonWachtlijstForm  = false;
    }

    public function toggleFavoriet(): void
    {
        if (!auth()->check() || !auth()->user()->isKlant()) {
            $this->redirect(route('klant.inloggen'));
            return;
        }

        $user = auth()->user();
        if ($this->isFavoriet) {
            $user->favorieteKappers()->detach($this->kapper->id);
            $this->isFavoriet = false;
        } else {
            $user->favorieteKappers()->attach($this->kapper->id);
            $this->isFavoriet = true;
        }
    }

    public function resetWachtlijst(): void
    {
        $this->toonWachtlijstForm  = false;
        $this->wachtlijstVerstuurd = false;
        $this->wachtlijstFout      = '';
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

        $this->kapperWerktDag             = $service->heeftBeschikbaarheid($this->kapper, $this->geselecteerdeDatum);
        $this->medewerkerWerktNietDezeDag = false;

        // Detecteer: medewerker heeft eigen rooster maar werkt niet op deze dag
        if ($this->geselecteerdeMedewerkerId && $this->kapperWerktDag) {
            $dagVanWeek = \Carbon\Carbon::parse($this->geselecteerdeDatum)->dayOfWeekIso - 1;
            $heeftEigenRooster = \App\Models\MedewerkerBeschikbaarheid::where('medewerker_id', $this->geselecteerdeMedewerkerId)->exists();
            if ($heeftEigenRooster) {
                $werktDezeDag = \App\Models\MedewerkerBeschikbaarheid::where('medewerker_id', $this->geselecteerdeMedewerkerId)
                    ->where('dag_van_week', $dagVanWeek)
                    ->exists();
                $this->medewerkerWerktNietDezeDag = !$werktDezeDag;
            }
        }

        $sluitingsdag = $service->getSluitingsdag($this->kapper, $this->geselecteerdeDatum);
        $this->sluitingsdagReden = $sluitingsdag
            ? ($sluitingsdag->reden ?: 'gesloten')
            : null;
        $this->tijdsloten = ($sluitingsdag || !$this->kapperWerktDag)
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

        $stad = $this->kapper->stad ? str($this->kapper->stad)->title() : null;
        $seoTitle = $this->kapper->salon_naam
            . ($stad ? ' - Kapper in ' . $stad : ' - Kapper')
            . ' | Kaply';

        $dienstenNamen = $this->kapper->diensten->pluck('naam')->take(3)->implode(', ');
        $seoDescription = $this->kapper->bio
            ? str($this->kapper->bio)->limit(155)->toString()
            : 'Boek een afspraak bij ' . $this->kapper->salon_naam
              . ($stad ? ' in ' . $stad : '')
              . ($dienstenNamen ? '. Diensten: ' . $dienstenNamen . '.' : '.');

        $seoImage = $this->kapper->foto
            ? asset('public/storage/' . $this->kapper->foto)
            : null;

        $seoCanonical = route('kapper.profiel', $this->kapper->slug);

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
        ])->layout($layout, [
            'seoTitle'       => $seoTitle,
            'seoDescription' => $seoDescription,
            'seoImage'       => $seoImage,
            'seoCanonical'   => $seoCanonical,
        ]);
    }
}
