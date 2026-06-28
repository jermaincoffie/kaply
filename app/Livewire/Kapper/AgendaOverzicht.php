<?php

namespace App\Livewire\Kapper;

use App\Models\Afspraak;
use App\Models\Blokkering;
use App\Models\Dienst;
use App\Models\User;
use App\Models\Wachtlijst;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AgendaOverzicht extends Component
{
    public string $weekStart;
    public string $mobielDatum;
    public ?int $geselecteerdeAfspraakId = null;

    // Nieuw formulier
    public bool $toonNieuwFormulier = false;

    // Blokkeren
    public bool $toonBlokkerenForm = false;
    public string $blokkeerDatum = '';
    public string $blokkeerStartTijd = '';
    public string $blokkeerEindTijd = '';
    public string $blokkeerReden = '';
    public ?int $geselecteerdeBlokkeringId = null;
    public string $nieuwDatum = '';
    public string $nieuwTijd = '';
    public ?int $nieuwDienstId = null;
    public string $nieuwBetaalmethode = 'in_zaak';
    public ?int $nieuwMedewerkerId = null;
    public string $klantZoekterm = '';
    public ?int $geselecteerdeKlantId = null;
    public string $geselecteerdeKlantNaam = '';
    public bool $toonKlantDropdown = false;
    public bool $isWalkIn = false;
    public string $walkInNaam = '';
    public ?int $gefilterdeMedewerkerId = null;
    public string $afspraakNotitie = '';

    public function mount(): void
    {
        $this->weekStart   = today()->startOfWeek(Carbon::MONDAY)->toDateString();
        $this->mobielDatum = today()->toDateString();
    }

    public function vorigeDag(): void
    {
        $this->mobielDatum = Carbon::parse($this->mobielDatum)->subDay()->toDateString();
        $this->sluitAlles();
    }

    public function volgendeDag(): void
    {
        $this->mobielDatum = Carbon::parse($this->mobielDatum)->addDay()->toDateString();
        $this->sluitAlles();
    }

    public function naarVandaagMobiel(): void
    {
        $this->mobielDatum = today()->toDateString();
        $this->sluitAlles();
    }

    public function selecteerDagMobiel(string $datum): void
    {
        $this->mobielDatum = $datum;
        $this->sluitAlles();
    }

    public function vorigeWeekMobiel(): void
    {
        $this->mobielDatum = Carbon::parse($this->mobielDatum)->subWeek()->toDateString();
        $this->sluitAlles();
    }

    public function volgendeWeekMobiel(): void
    {
        $this->mobielDatum = Carbon::parse($this->mobielDatum)->addWeek()->toDateString();
        $this->sluitAlles();
    }

    public function vorigeWeek(): void
    {
        $this->weekStart = Carbon::parse($this->weekStart)->subWeek()->toDateString();
        $this->sluitAlles();
    }

    public function volgendeWeek(): void
    {
        $this->weekStart = Carbon::parse($this->weekStart)->addWeek()->toDateString();
        $this->sluitAlles();
    }

    public function naarVandaag(): void
    {
        $this->weekStart = today()->startOfWeek(Carbon::MONDAY)->toDateString();
        $this->sluitAlles();
    }

    public function selecteerAfspraak(?int $id): void
    {
        $this->geselecteerdeAfspraakId = $this->geselecteerdeAfspraakId === $id ? null : $id;
        $this->toonNieuwFormulier = false;

        if ($this->geselecteerdeAfspraakId) {
            $afspraak = Afspraak::find($this->geselecteerdeAfspraakId);
            $this->afspraakNotitie = $afspraak?->notitie ?? '';
        } else {
            $this->afspraakNotitie = '';
        }
    }

    public function notitieOpslaan(int $id): void
    {
        Afspraak::where('id', $id)
            ->where('kapper_id', auth()->user()->kapper->id)
            ->update(['notitie' => trim($this->afspraakNotitie) ?: null]);
    }

    public function openNieuwFormulier(string $datum, string $tijd, bool $walkIn = false): void
    {
        $this->geselecteerdeAfspraakId = null;
        $this->toonNieuwFormulier = true;
        $this->nieuwDatum = $datum;
        $this->nieuwTijd = $tijd;
        $this->nieuwDienstId = auth()->user()->kapper->diensten()->first()?->id;
        $this->nieuwBetaalmethode = 'in_zaak';
        $this->nieuwMedewerkerId = null;
        $this->klantZoekterm = '';
        $this->geselecteerdeKlantId = null;
        $this->geselecteerdeKlantNaam = '';
        $this->toonKlantDropdown = false;
        $this->isWalkIn = $walkIn;
        $this->walkInNaam = '';
    }

    public function openWalkIn(): void
    {
        $nu = now()->format('H:i');
        // Afronden op dichtstbijzijnde half uur
        $minuten = (int) now()->format('i');
        $afgerond = $minuten < 30 ? '00' : '30';
        $tijd = now()->format('H') . ':' . $afgerond;
        $this->openNieuwFormulier(today()->toDateString(), $tijd, true);
    }

    public function selecteerKlant(int $id, string $naam): void
    {
        $this->geselecteerdeKlantId = $id;
        $this->geselecteerdeKlantNaam = $naam;
        $this->klantZoekterm = $naam;
        $this->toonKlantDropdown = false;
    }

    public function updatedKlantZoekterm(): void
    {
        $this->toonKlantDropdown = strlen($this->klantZoekterm) >= 2;
        if ($this->geselecteerdeKlantNaam !== $this->klantZoekterm) {
            $this->geselecteerdeKlantId = null;
            $this->geselecteerdeKlantNaam = '';
        }
    }

    public function afspraakOpslaan(): void
    {
        $this->validate([
            'nieuwDatum'        => 'required|date',
            'nieuwTijd'         => 'required',
            'nieuwDienstId'     => 'required|integer',
            'nieuwBetaalmethode' => 'required|in:in_zaak,online',
        ]);

        $klantId = null;
        $walkInNaam = null;

        if ($this->isWalkIn) {
            $this->validate(['walkInNaam' => 'required|string|min:2']);
            $walkInNaam = trim($this->walkInNaam);
        } elseif ($this->geselecteerdeKlantId) {
            $klantId = $this->geselecteerdeKlantId;
        } else {
            $naam = trim($this->klantZoekterm);
            $this->validate(['klantZoekterm' => 'required|string|min:2']);
            $klant = User::firstOrCreate(
                ['email' => 'walkin-' . now()->timestamp . '@kapperplatform.nl'],
                ['name' => $naam, 'password' => Hash::make(str()->random(16)), 'role' => 'klant']
            );
            $klantId = $klant->id;
        }

        $dienst = Dienst::findOrFail($this->nieuwDienstId);
        $eind = Carbon::parse($this->nieuwDatum . ' ' . $this->nieuwTijd)
            ->addMinutes($dienst->duur_minuten)
            ->format('H:i');

        Afspraak::create([
            'klant_id'      => $klantId,
            'walk_in_naam'  => $walkInNaam,
            'kapper_id'     => auth()->user()->kapper->id,
            'dienst_id'     => $dienst->id,
            'medewerker_id' => $this->nieuwMedewerkerId,
            'datum'         => $this->nieuwDatum,
            'start_tijd'    => $this->nieuwTijd,
            'eind_tijd'     => $eind,
            'status'        => 'gepland',
            'betaalmethode' => $this->nieuwBetaalmethode,
        ]);

        $this->toonNieuwFormulier = false;
    }

    public function openBlokkerenForm(string $datum = '', string $startTijd = ''): void
    {
        $this->geselecteerdeAfspraakId = null;
        $this->geselecteerdeBlokkeringId = null;
        $this->toonNieuwFormulier = false;
        $this->toonBlokkerenForm = true;
        $this->blokkeerDatum = $datum ?: today()->toDateString();
        $this->blokkeerStartTijd = $startTijd ?: now()->format('H:i');
        $this->blokkeerEindTijd = Carbon::parse($this->blokkeerStartTijd)->addHour()->format('H:i');
        $this->blokkeerReden = '';
    }

    public function openPauzeForm(string $datum = ''): void
    {
        $this->openBlokkerenForm($datum);
        $this->blokkeerEindTijd = Carbon::parse($this->blokkeerStartTijd)->addMinutes(30)->format('H:i');
        $this->blokkeerReden = 'Pauze';
    }

    public function blokkeerOpslaan(): void
    {
        $this->validate([
            'blokkeerDatum'     => 'required|date',
            'blokkeerStartTijd' => 'required',
            'blokkeerEindTijd'  => 'required|after:blokkeerStartTijd',
        ]);

        Blokkering::create([
            'kapper_id'  => auth()->user()->kapper->id,
            'datum'      => $this->blokkeerDatum,
            'start_tijd' => $this->blokkeerStartTijd,
            'eind_tijd'  => $this->blokkeerEindTijd,
            'reden'      => $this->blokkeerReden ?: null,
        ]);

        $this->toonBlokkerenForm = false;
    }

    public function selecteerBlokkering(int $id): void
    {
        $this->geselecteerdeBlokkeringId = $this->geselecteerdeBlokkeringId === $id ? null : $id;
        $this->geselecteerdeAfspraakId = null;
        $this->toonNieuwFormulier = false;
        $this->toonBlokkerenForm = false;
    }

    public function verwijderBlokkering(int $id): void
    {
        Blokkering::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->delete();
        $this->geselecteerdeBlokkeringId = null;
    }

    public function filterMedewerker(?int $id): void
    {
        $this->gefilterdeMedewerkerId = $id;
        $this->sluitAlles();
    }

    public function sluitAlles(): void
    {
        $this->geselecteerdeAfspraakId = null;
        $this->geselecteerdeBlokkeringId = null;
        $this->toonNieuwFormulier = false;
        $this->toonBlokkerenForm = false;
    }

    public function noShow(int $id): void
    {
        Afspraak::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->update(['status' => 'no_show']);
        $this->geselecteerdeAfspraakId = null;
    }

    public function annuleren(int $id): void
    {
        Afspraak::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->update(['status' => 'geannuleerd']);
        $this->geselecteerdeAfspraakId = null;
    }

    public function voltooid(int $id): void
    {
        Afspraak::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->update(['status' => 'voltooid']);
        $this->geselecteerdeAfspraakId = null;
    }

    public function verbergUitAgenda(int $id): void
    {
        Afspraak::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->update(['verborgen_in_agenda' => true]);
        $this->geselecteerdeAfspraakId = null;
    }

    private function berekenOverlapKolommen($afspraken): array
    {
        $layout = [];
        if ($afspraken->isEmpty()) return $layout;

        $sorted = $afspraken->sortBy('start_tijd')->values();

        // Wijs kolommen toe op basis van tijdoverlap
        $kolomEind = []; // col => eind_tijd van laatste afspraak in die kolom

        foreach ($sorted as $a) {
            $start = substr($a->start_tijd, 0, 5);
            $eind  = substr($a->eind_tijd, 0, 5);

            $col = 0;
            while (isset($kolomEind[$col]) && $kolomEind[$col] > $start) {
                $col++;
            }
            $kolomEind[$col] = $eind;
            $layout[$a->id] = ['col_index' => $col, 'col_count' => 1];
        }

        // Tweede pass: bepaal col_count = hoeveel kolommen tegelijk bezet zijn
        foreach ($sorted as $a) {
            $aStart = substr($a->start_tijd, 0, 5);
            $aEind  = substr($a->eind_tijd, 0, 5);
            $maxKol = $layout[$a->id]['col_index'];

            foreach ($sorted as $b) {
                if ($b->id === $a->id) continue;
                $bStart = substr($b->start_tijd, 0, 5);
                $bEind  = substr($b->eind_tijd, 0, 5);
                if ($aStart < $bEind && $aEind > $bStart) {
                    $maxKol = max($maxKol, $layout[$b->id]['col_index']);
                }
            }
            $layout[$a->id]['col_count'] = $maxKol + 1;
        }

        return $layout;
    }

    public function wachtlijstVerwijderen(int $id): void
    {
        Wachtlijst::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->delete();
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
            ->where('verborgen_in_agenda', false)
            ->with(['klant', 'dienst', 'medewerker'])
            ->orderBy('start_tijd')
            ->get();

        if ($this->gefilterdeMedewerkerId !== null) {
            $afspraken = $afspraken->filter(fn($a) => $a->medewerker_id === $this->gefilterdeMedewerkerId)->values();
        }

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

        $mobielAfspraken = Afspraak::where('kapper_id', $kapper_id)
            ->whereDate('datum', $this->mobielDatum)
            ->where('verborgen_in_agenda', false)
            ->with(['klant', 'dienst', 'medewerker'])
            ->orderBy('start_tijd')
            ->get();

        if ($this->gefilterdeMedewerkerId !== null) {
            $mobielAfspraken = $mobielAfspraken->filter(fn($a) => $a->medewerker_id === $this->gefilterdeMedewerkerId)->values();
        }

        $vandaagAfspraken = Afspraak::where('kapper_id', $kapper_id)
            ->whereDate('datum', today())
            ->where('verborgen_in_agenda', false)
            ->with(['klant', 'dienst'])
            ->orderBy('start_tijd')
            ->get();

        $volgendeAfspraak = $vandaagAfspraken
            ->where('status', 'gepland')
            ->filter(fn($a) => Carbon::parse(today()->toDateString() . ' ' . $a->start_tijd)->gt(now()))
            ->first();

        $omzet_vandaag = $vandaagAfspraken
            ->where('status', 'voltooid')
            ->sum(fn($a) => $a->dienst->prijs ?? 0);

        // No-show %
        $afgerond_maand = Afspraak::where('kapper_id', $kapper_id)
            ->whereMonth('datum', now()->month)
            ->whereYear('datum', now()->year)
            ->whereIn('status', ['voltooid', 'no_show'])
            ->count();
        $no_shows_maand = Afspraak::where('kapper_id', $kapper_id)
            ->whereMonth('datum', now()->month)
            ->whereYear('datum', now()->year)
            ->where('status', 'no_show')
            ->count();
        $no_show_pct = $afgerond_maand > 0 ? round($no_shows_maand / $afgerond_maand * 100) : null;

        // Bezettingsgraad
        $beschikbaarheden = auth()->user()->kapper->beschikbaarheden()->get();
        $beschikbareMinuten = 0;
        for ($d = 1; $d <= now()->daysInMonth; $d++) {
            $dag = Carbon::create(now()->year, now()->month, $d);
            $dow = ($dag->dayOfWeek + 6) % 7;
            $b = $beschikbaarheden->firstWhere('dag_van_week', $dow);
            if ($b) {
                $beschikbareMinuten += Carbon::parse($b->start_tijd)->diffInMinutes(Carbon::parse($b->eind_tijd));
            }
        }
        $geboekteMinuten = Afspraak::where('afspraken.kapper_id', $kapper_id)
            ->whereMonth('afspraken.datum', now()->month)
            ->whereYear('afspraken.datum', now()->year)
            ->whereIn('afspraken.status', ['gepland', 'voltooid'])
            ->join('diensten', 'afspraken.dienst_id', '=', 'diensten.id')
            ->sum('diensten.duur_minuten');
        $bezettingsgraad = $beschikbareMinuten > 0
            ? min(100, round($geboekteMinuten / $beschikbareMinuten * 100))
            : null;

        // Drukste uur deze maand
        $druksteUur = Afspraak::where('kapper_id', $kapper_id)
            ->whereMonth('datum', now()->month)
            ->whereYear('datum', now()->year)
            ->whereIn('status', ['gepland', 'voltooid'])
            ->get()
            ->groupBy(fn($a) => (int) substr($a->start_tijd, 0, 2))
            ->map->count()
            ->sortDesc()
            ->keys()
            ->first();
        $druksteUurLabel = $druksteUur !== null
            ? str_pad($druksteUur, 2, '0', STR_PAD_LEFT) . ':00 – ' . str_pad($druksteUur + 1, 2, '0', STR_PAD_LEFT) . ':00'
            : null;

        $blokkeringen = Blokkering::where('kapper_id', $kapper_id)
            ->whereBetween('datum', [$weekStartDate->toDateString(), $weekEndDate->toDateString()])
            ->orderBy('start_tijd')
            ->get();

        $blokkeringenPerDag = $blokkeringen->groupBy(fn($b) => $b->datum->toDateString());

        $mobielBlokkeringen = Blokkering::where('kapper_id', $kapper_id)
            ->whereDate('datum', $this->mobielDatum)
            ->orderBy('start_tijd')
            ->get();

        // Week-strip voor mobiel
        $mobielWeekStartDate = Carbon::parse($this->mobielDatum)->startOfWeek(Carbon::MONDAY);
        $mobielWeekDays = collect();
        for ($i = 0; $i < 7; $i++) {
            $mobielWeekDays->push($mobielWeekStartDate->copy()->addDays($i));
        }
        $stripAfspraakCounts = Afspraak::where('kapper_id', $kapper_id)
            ->whereBetween('datum', [
                $mobielWeekStartDate->toDateString(),
                $mobielWeekStartDate->copy()->endOfWeek(Carbon::SUNDAY)->toDateString(),
            ])
            ->where('verborgen_in_agenda', false)
            ->whereNotIn('status', ['geannuleerd'])
            ->get()
            ->groupBy(fn($a) => $a->datum->toDateString())
            ->map->count()
            ->toArray();

        $wachtlijst = Wachtlijst::where('kapper_id', $kapper_id)
            ->where('status', 'wachtend')
            ->where(fn($q) => $q->whereNull('gewenste_datum')->orWhere('gewenste_datum', '>=', today()))
            ->orderByDesc('created_at')
            ->get();

        $geselecteerdeAfspraak = $this->geselecteerdeAfspraakId
            ? $afspraken->firstWhere('id', $this->geselecteerdeAfspraakId)
                ?? $mobielAfspraken->firstWhere('id', $this->geselecteerdeAfspraakId)
            : null;

        $geselecteerdeblokkering = $this->geselecteerdeBlokkeringId
            ? $blokkeringen->firstWhere('id', $this->geselecteerdeBlokkeringId)
                ?? $mobielBlokkeringen->firstWhere('id', $this->geselecteerdeBlokkeringId)
            : null;

        $eigenDiensten = auth()->user()->kapper->diensten()->orderBy('naam')->get();

        $zoekKlanten = $this->toonKlantDropdown && strlen($this->klantZoekterm) >= 2
            ? User::where('role', 'klant')
                ->where('name', 'like', "%{$this->klantZoekterm}%")
                ->limit(6)->get()
            : collect();

        $kapper = auth()->user()->kapper;
        $medewerkers = $kapper->medewerkers()->where('actief', true)->orderBy('id')->get();
        $medewerkerKolom = $medewerkers->pluck('id')->values()->flip()->toArray();

        // Overlap-gebaseerde kolom layout per dag
        $afspraakLayout = [];
        foreach ($afsprakenPerDag as $dagAfspraken) {
            $afspraakLayout += $this->berekenOverlapKolommen($dagAfspraken);
        }
        $mobielLayout = $this->berekenOverlapKolommen($mobielAfspraken);

        return view('livewire.kapper.agenda-overzicht', compact(
            'days', 'afsprakenPerDag', 'omzet_maand', 'afspraken_maand',
            'komende_afspraken', 'geselecteerdeAfspraak', 'weekStartDate',
            'eigenDiensten', 'zoekKlanten', 'mobielAfspraken',
            'vandaagAfspraken', 'volgendeAfspraak', 'omzet_vandaag',
            'blokkeringenPerDag', 'mobielBlokkeringen', 'geselecteerdeblokkering',
            'no_show_pct', 'bezettingsgraad', 'druksteUurLabel',
            'medewerkers', 'medewerkerKolom',
            'afspraakLayout', 'mobielLayout',
            'mobielWeekDays', 'stripAfspraakCounts',
            'wachtlijst'
        ))->layout('layouts.kapper', ['title' => 'Agenda']);
    }
}
