<?php

namespace App\Livewire\Admin;

use App\Models\Afspraak;
use App\Models\Kapper;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $omzet_totaal = Afspraak::where('status', 'voltooid')
            ->join('diensten', 'afspraken.dienst_id', '=', 'diensten.id')
            ->sum('diensten.prijs');

        $omzet_maand = Afspraak::where('status', 'voltooid')
            ->whereMonth('datum', now()->month)
            ->whereYear('datum', now()->year)
            ->join('diensten', 'afspraken.dienst_id', '=', 'diensten.id')
            ->sum('diensten.prijs');

        $omzet_per_kapper = Kapper::withCount(['afspraken as voltooide_afspraken' => fn($q) => $q->where('status', 'voltooid')])
            ->with(['afspraken' => fn($q) => $q->where('status', 'voltooid')->with('dienst')])
            ->having('voltooide_afspraken', '>', 0)
            ->get()
            ->map(fn($k) => [
                'salon_naam' => $k->salon_naam,
                'stad' => $k->stad,
                'voltooide_afspraken' => $k->voltooide_afspraken,
                'omzet' => $k->afspraken->sum(fn($a) => $a->dienst?->prijs ?? 0),
            ])
            ->sortByDesc('omzet')
            ->values();

        return view('livewire.admin.dashboard', [
            'kappers_actief'     => Kapper::where('actief', true)->count(),
            'kappers_totaal'     => Kapper::count(),
            'afspraken_vandaag'  => Afspraak::whereDate('datum', today())->count(),
            'afspraken_week'     => Afspraak::whereBetween('datum', [today()->startOfWeek(), today()->endOfWeek()])->count(),
            'klanten_totaal'     => User::where('role', 'klant')->count(),
            'omzet_totaal'       => $omzet_totaal,
            'omzet_maand'        => $omzet_maand,
            'omzet_per_kapper'   => $omzet_per_kapper,
            'recente_afspraken'  => Afspraak::with(['kapper', 'dienst', 'klant'])
                ->orderByDesc('datum')->orderByDesc('start_tijd')
                ->limit(8)
                ->get(),
        ])->layout('layouts.admin');
    }
}
