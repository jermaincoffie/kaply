<?php

namespace App\Livewire\Kapper;

use App\Models\Afspraak;
use App\Models\Blokkering;
use App\Models\Dienst;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class KapperDashboard extends Component
{
    public ?int $geselecteerdeAfspraakId = null;

    // Nieuw afspraak formulier
    public bool $toonNieuwFormulier = false;
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

    public function openNieuwFormulier(): void
    {
        $this->geselecteerdeAfspraakId = null;
        $this->toonNieuwFormulier = true;
        $this->nieuwDatum = today()->toDateString();
        $minuten = (int) now()->format('i');
        $afgerond = $minuten < 30 ? '00' : '30';
        $this->nieuwTijd = now()->format('H') . ':' . $afgerond;
        $this->nieuwDienstId = auth()->user()->kapper->diensten()->first()?->id;
        $this->nieuwBetaalmethode = 'in_zaak';
        $this->nieuwMedewerkerId = null;
        $this->klantZoekterm = '';
        $this->geselecteerdeKlantId = null;
        $this->geselecteerdeKlantNaam = '';
        $this->toonKlantDropdown = false;
        $this->isWalkIn = false;
        $this->walkInNaam = '';
    }

    public function sluitFormulier(): void
    {
        $this->toonNieuwFormulier = false;
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
            'nieuwDatum'         => 'required|date',
            'nieuwTijd'          => 'required',
            'nieuwDienstId'      => 'required|integer',
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

    public function selecteerAfspraak(?int $id): void
    {
        $this->geselecteerdeAfspraakId = $this->geselecteerdeAfspraakId === $id ? null : $id;
        $this->toonNieuwFormulier = false;
    }

    public function voltooid(int $id): void
    {
        Afspraak::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->update(['status' => 'voltooid']);
        $this->geselecteerdeAfspraakId = null;
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

    public function verwijderAfspraak(int $id): void
    {
        Afspraak::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->delete();
        $this->geselecteerdeAfspraakId = null;
    }

    public function render()
    {
        $kapper    = auth()->user()->kapper;
        $kapper_id = $kapper->id;

        $vandaagAfspraken = Afspraak::where('kapper_id', $kapper_id)
            ->whereDate('datum', today())
            ->where('verborgen_in_agenda', false)
            ->with(['klant', 'dienst', 'medewerker'])
            ->orderBy('start_tijd')
            ->get();

        $vandaagBlokkeringen = Blokkering::where('kapper_id', $kapper_id)
            ->whereDate('datum', today())
            ->orderBy('start_tijd')
            ->get();

        $omzet_vandaag = $vandaagAfspraken
            ->where('status', 'voltooid')
            ->sum(fn($a) => $a->dienst->prijs ?? 0);

        $volgendeAfspraak = $vandaagAfspraken
            ->where('status', 'gepland')
            ->filter(fn($a) => Carbon::parse(today()->toDateString() . ' ' . $a->start_tijd)->gt(now()))
            ->first();

        // Week stats
        $weekBegin   = now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $weekEind    = now()->endOfWeek(Carbon::SUNDAY)->toDateString();
        $vorigeBegin = now()->subWeek()->startOfWeek(Carbon::MONDAY)->toDateString();
        $vorigeEind  = now()->subWeek()->endOfWeek(Carbon::SUNDAY)->toDateString();

        $omzet_week = Afspraak::where('afspraken.kapper_id', $kapper_id)
            ->where('afspraken.status', 'voltooid')
            ->whereBetween('afspraken.datum', [$weekBegin, $weekEind])
            ->join('diensten', 'afspraken.dienst_id', '=', 'diensten.id')
            ->sum('diensten.prijs');

        $omzet_vorige_week = Afspraak::where('afspraken.kapper_id', $kapper_id)
            ->where('afspraken.status', 'voltooid')
            ->whereBetween('afspraken.datum', [$vorigeBegin, $vorigeEind])
            ->join('diensten', 'afspraken.dienst_id', '=', 'diensten.id')
            ->sum('diensten.prijs');

        $afspraken_week = Afspraak::where('kapper_id', $kapper_id)
            ->whereBetween('datum', [$weekBegin, $weekEind])
            ->whereIn('status', ['gepland', 'voltooid'])
            ->count();

        $afspraken_vorige_week = Afspraak::where('kapper_id', $kapper_id)
            ->whereBetween('datum', [$vorigeBegin, $vorigeEind])
            ->whereIn('status', ['gepland', 'voltooid'])
            ->count();

        $klanten_week = Afspraak::where('kapper_id', $kapper_id)
            ->whereBetween('datum', [$weekBegin, $weekEind])
            ->whereIn('status', ['gepland', 'voltooid'])
            ->whereNotNull('klant_id')
            ->distinct('klant_id')
            ->count('klant_id');

        $klanten_vorige_week = Afspraak::where('kapper_id', $kapper_id)
            ->whereBetween('datum', [$vorigeBegin, $vorigeEind])
            ->whereIn('status', ['gepland', 'voltooid'])
            ->whereNotNull('klant_id')
            ->distinct('klant_id')
            ->count('klant_id');

        $omzet_week_pct     = $omzet_vorige_week > 0     ? round(($omzet_week - $omzet_vorige_week) / $omzet_vorige_week * 100)         : null;
        $afspraken_week_pct = $afspraken_vorige_week > 0 ? round(($afspraken_week - $afspraken_vorige_week) / $afspraken_vorige_week * 100) : null;
        $klanten_week_pct   = $klanten_vorige_week > 0   ? round(($klanten_week - $klanten_vorige_week) / $klanten_vorige_week * 100)     : null;

        $top_dienst_data = Afspraak::where('afspraken.kapper_id', $kapper_id)
            ->whereMonth('afspraken.datum', now()->month)
            ->whereYear('afspraken.datum', now()->year)
            ->whereIn('afspraken.status', ['gepland', 'voltooid'])
            ->join('diensten', 'afspraken.dienst_id', '=', 'diensten.id')
            ->selectRaw('diensten.naam, COUNT(*) as aantal, SUM(diensten.prijs) as omzet')
            ->groupBy('diensten.naam', 'diensten.id')
            ->orderByDesc('omzet')
            ->first();

        $onboarding = [
            'beschikbaarheid' => $kapper->beschikbaarheden()->exists(),
            'diensten'        => $kapper->diensten()->exists(),
            'medewerkers'     => $kapper->medewerkers()->where('actief', true)->exists(),
        ];
        $toonOnboarding = !$onboarding['beschikbaarheid'] || !$onboarding['diensten'];

        $medewerkers = $kapper->medewerkers()->where('actief', true)->orderBy('id')->get();

        $eigenDiensten = $kapper->diensten()->orderBy('naam')->get();

        $zoekKlanten = $this->toonKlantDropdown && strlen($this->klantZoekterm) >= 2
            ? User::where('role', 'klant')
                ->where('name', 'like', "%{$this->klantZoekterm}%")
                ->limit(6)->get()
            : collect();

        $geselecteerdeAfspraak = $this->geselecteerdeAfspraakId
            ? $vandaagAfspraken->firstWhere('id', $this->geselecteerdeAfspraakId)
            : null;

        // Combineer vandaag afspraken + blokkeringen gesorteerd
        $alleVandaag = collect();
        foreach ($vandaagAfspraken as $a) {
            $alleVandaag->push(['type' => 'afspraak', 'start' => $a->start_tijd, 'data' => $a]);
        }
        foreach ($vandaagBlokkeringen as $b) {
            $alleVandaag->push(['type' => 'blokkering', 'start' => $b->start_tijd, 'data' => $b]);
        }
        $alleVandaag = $alleVandaag->sortBy('start')->values();

        return view('livewire.kapper.kapper-dashboard', compact(
            'vandaagAfspraken', 'vandaagBlokkeringen', 'alleVandaag',
            'omzet_vandaag', 'volgendeAfspraak',
            'omzet_week', 'omzet_week_pct',
            'afspraken_week', 'afspraken_week_pct',
            'klanten_week', 'klanten_week_pct',
            'top_dienst_data',
            'onboarding', 'toonOnboarding',
            'medewerkers', 'geselecteerdeAfspraak',
            'eigenDiensten', 'zoekKlanten'
        ))->layout('layouts.kapper', ['title' => 'Dashboard']);
    }
}
