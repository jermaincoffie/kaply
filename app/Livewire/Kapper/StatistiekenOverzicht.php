<?php

namespace App\Livewire\Kapper;

use App\Models\Afspraak;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StatistiekenOverzicht extends Component
{
    public string $periode = 'week';

    public function render()
    {
        $kapper_id = auth()->user()->kapper->id;
        [$start, $eind, $vorigeStart, $vorigeEind] = $this->periodeGrenzen();

        $omzetHuidig = $this->omzetQuery($kapper_id, $start, $eind);
        $omzetVorig  = $this->omzetQuery($kapper_id, $vorigeStart, $vorigeEind);

        $afsprakenHuidig = Afspraak::where('kapper_id', $kapper_id)
            ->whereBetween('datum', [$start->toDateString(), $eind->toDateString()])
            ->whereIn('status', ['gepland', 'voltooid'])
            ->count();
        $afsprakenVorig = Afspraak::where('kapper_id', $kapper_id)
            ->whereBetween('datum', [$vorigeStart->toDateString(), $vorigeEind->toDateString()])
            ->whereIn('status', ['gepland', 'voltooid'])
            ->count();

        $nieuweKlantenHuidig = $this->nieuweKlanten($kapper_id, $start, $eind);
        $nieuweKlantenVorig  = $this->nieuweKlanten($kapper_id, $vorigeStart, $vorigeEind);

        $voltooideHuidig = Afspraak::where('kapper_id', $kapper_id)
            ->whereBetween('datum', [$start->toDateString(), $eind->toDateString()])
            ->where('status', 'voltooid')->count();
        $gemBesteding = $voltooideHuidig > 0 ? $omzetHuidig / $voltooideHuidig : 0;

        $voltooideVorig = Afspraak::where('kapper_id', $kapper_id)
            ->whereBetween('datum', [$vorigeStart->toDateString(), $vorigeEind->toDateString()])
            ->where('status', 'voltooid')->count();
        $gemBestedingVorig = $voltooideVorig > 0 ? $omzetVorig / $voltooideVorig : 0;

        $grafiekData = $this->grafiekData($kapper_id, $start, $eind);

        $omzetPerDienst = Afspraak::where('afspraken.kapper_id', $kapper_id)
            ->whereBetween('afspraken.datum', [$start->toDateString(), $eind->toDateString()])
            ->where('afspraken.status', 'voltooid')
            ->join('diensten', 'afspraken.dienst_id', '=', 'diensten.id')
            ->selectRaw('diensten.naam, SUM(diensten.prijs) as totaal, COUNT(*) as aantal')
            ->groupBy('diensten.id', 'diensten.naam')
            ->orderByDesc('totaal')
            ->get();

        $totaalOmzetDienst = $omzetPerDienst->sum('totaal') ?: 1;

        $topKlanten = Afspraak::where('afspraken.kapper_id', $kapper_id)
            ->whereBetween('afspraken.datum', [$start->toDateString(), $eind->toDateString()])
            ->where('afspraken.status', 'voltooid')
            ->whereNotNull('afspraken.klant_id')
            ->join('diensten', 'afspraken.dienst_id', '=', 'diensten.id')
            ->join('users', 'afspraken.klant_id', '=', 'users.id')
            ->selectRaw('users.id, users.name, SUM(diensten.prijs) as totaal, COUNT(*) as aantal_afspraken')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('totaal')
            ->limit(5)
            ->get();

        $periodeLabel = $start->isoFormat('D MMM') . ' – ' . $eind->isoFormat('D MMM YYYY');

        return view('livewire.kapper.statistieken-overzicht', compact(
            'omzetHuidig', 'omzetVorig',
            'afsprakenHuidig', 'afsprakenVorig',
            'nieuweKlantenHuidig', 'nieuweKlantenVorig',
            'gemBesteding', 'gemBestedingVorig',
            'grafiekData',
            'omzetPerDienst', 'totaalOmzetDienst',
            'topKlanten',
            'periodeLabel'
        ))->layout('layouts.kapper', ['title' => 'Statistieken']);
    }

    private function omzetQuery(int $kapper_id, Carbon $start, Carbon $eind): float
    {
        return (float) Afspraak::where('afspraken.kapper_id', $kapper_id)
            ->whereBetween('afspraken.datum', [$start->toDateString(), $eind->toDateString()])
            ->where('afspraken.status', 'voltooid')
            ->join('diensten', 'afspraken.dienst_id', '=', 'diensten.id')
            ->sum('diensten.prijs');
    }

    private function nieuweKlanten(int $kapper_id, Carbon $start, Carbon $eind): int
    {
        return DB::table(
            Afspraak::where('kapper_id', $kapper_id)
                ->whereNotNull('klant_id')
                ->selectRaw('klant_id, MIN(datum) as eerste_datum')
                ->groupBy('klant_id'),
            'sub'
        )->whereBetween('eerste_datum', [$start->toDateString(), $eind->toDateString()])->count();
    }

    private function periodeGrenzen(): array
    {
        return match($this->periode) {
            'maand' => [
                today()->startOfMonth(),
                today()->endOfMonth(),
                today()->subMonth()->startOfMonth(),
                today()->subMonth()->endOfMonth(),
            ],
            'jaar' => [
                today()->startOfYear(),
                today()->endOfYear(),
                today()->subYear()->startOfYear(),
                today()->subYear()->endOfYear(),
            ],
            default => [
                today()->startOfWeek(Carbon::MONDAY),
                today()->endOfWeek(Carbon::SUNDAY),
                today()->subWeek()->startOfWeek(Carbon::MONDAY),
                today()->subWeek()->endOfWeek(Carbon::SUNDAY),
            ],
        };
    }

    private function grafiekData(int $kapper_id, Carbon $start, Carbon $eind): array
    {
        if ($this->periode === 'jaar') {
            $rows = Afspraak::where('afspraken.kapper_id', $kapper_id)
                ->whereYear('afspraken.datum', $start->year)
                ->where('afspraken.status', 'voltooid')
                ->join('diensten', 'afspraken.dienst_id', '=', 'diensten.id')
                ->selectRaw('MONTH(afspraken.datum) as maand, SUM(diensten.prijs) as totaal')
                ->groupBy('maand')
                ->get()
                ->keyBy('maand');

            $labels = ['Jan','Feb','Mrt','Apr','Mei','Jun','Jul','Aug','Sep','Okt','Nov','Dec'];
            $data   = array_map(fn($m) => round((float)($rows[$m]?->totaal ?? 0) / 100, 2), range(1, 12));
        } else {
            $dagTotalen = Afspraak::where('afspraken.kapper_id', $kapper_id)
                ->whereBetween('afspraken.datum', [$start->toDateString(), $eind->toDateString()])
                ->where('afspraken.status', 'voltooid')
                ->join('diensten', 'afspraken.dienst_id', '=', 'diensten.id')
                ->selectRaw('afspraken.datum, SUM(diensten.prijs) as totaal')
                ->groupBy('afspraken.datum')
                ->get()
                ->keyBy('datum');

            $dagNamen = $this->periode === 'week'
                ? ['Ma','Di','Wo','Do','Vr','Za','Zo']
                : null;

            $labels = [];
            $data   = [];
            $dag    = $start->copy();

            while ($dag->lte($eind)) {
                $labels[] = $dagNamen ? $dagNamen[$dag->dayOfWeekIso - 1] : $dag->format('j');
                $data[]   = round((float)($dagTotalen[$dag->toDateString()]?->totaal ?? 0) / 100, 2);
                $dag->addDay();
            }
        }

        return compact('labels', 'data');
    }
}
