<?php

namespace App\Livewire\Kapper;

use App\Models\Afspraak;
use App\Models\Blokkering;
use App\Models\Wachtlijst;
use Carbon\Carbon;
use Livewire\Component;

class KapperDashboard extends Component
{
    public ?int $geselecteerdeAfspraakId = null;

    public function selecteerAfspraak(?int $id): void
    {
        $this->geselecteerdeAfspraakId = $this->geselecteerdeAfspraakId === $id ? null : $id;
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

    public function wachtlijstVerwijderen(int $id): void
    {
        Wachtlijst::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->delete();
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

        $wachtlijst = Wachtlijst::where('kapper_id', $kapper_id)
            ->where('status', 'wachtend')
            ->where(fn($q) => $q->whereNull('gewenste_datum')->orWhere('gewenste_datum', '>=', today()))
            ->orderByDesc('created_at')
            ->get();

        $medewerkers = $kapper->medewerkers()->where('actief', true)->orderBy('id')->get();

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
            'onboarding', 'toonOnboarding', 'wachtlijst',
            'medewerkers', 'geselecteerdeAfspraak'
        ))->layout('layouts.kapper', ['title' => 'Dashboard']);
    }
}
