<?php

namespace App\Livewire\Admin;

use App\Models\Afspraak;
use App\Models\Dienst;
use App\Models\Kapper;
use App\Models\Review;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    const ABONNEMENT_PRIJS = 2500; // €25 in centen

    public function toggleReviewZichtbaar(int $id): void
    {
        $review = Review::findOrFail($id);
        $review->update(['zichtbaar' => !$review->zichtbaar]);
    }

    public function render()
    {
        $abonnees_actief    = Kapper::where('abonnement_status', 'actief')->count();
        $abonnees_gepauzeerd = Kapper::where('abonnement_status', 'gepauzeerd')->count();
        $kappers_totaal     = Kapper::count();
        $mrr                = $abonnees_actief * self::ABONNEMENT_PRIJS;
        $prognose_mrr       = $kappers_totaal * self::ABONNEMENT_PRIJS;

        $abonnement_status = Kapper::select('abonnement_status', \DB::raw('count(*) as aantal'))
            ->groupBy('abonnement_status')
            ->get()
            ->keyBy('abonnement_status');

        $nieuw_aangemeld = Kapper::where('actief', false)
            ->where('abonnement_status', 'geen')
            ->count();

        return view('livewire.admin.dashboard', [
            'kappers_actief'      => Kapper::where('actief', true)->count(),
            'kappers_totaal'      => $kappers_totaal,
            'nieuw_aangemeld'     => $nieuw_aangemeld,
            'afspraken_vandaag'   => Afspraak::whereDate('datum', today())->count(),
            'afspraken_week'      => Afspraak::whereBetween('datum', [today()->startOfWeek(), today()->endOfWeek()])->count(),
            'klanten_totaal'      => User::where('role', 'klant')->count(),
            'abonnees_actief'     => $abonnees_actief,
            'abonnees_gepauzeerd' => $abonnees_gepauzeerd,
            'mrr'                 => $mrr,
            'prognose_mrr'        => $prognose_mrr,
            'abonnement_status'   => $abonnement_status,
            'recente_afspraken'   => Afspraak::with(['kapper', 'dienst', 'klant'])
                ->orderByDesc('datum')->orderByDesc('start_tijd')
                ->limit(8)
                ->get(),
            'top_kappers'         => Kapper::withCount([
                'afspraken as boekingen_maand' => fn($q) => $q
                    ->whereMonth('datum', now()->month)
                    ->whereYear('datum', now()->year)
                    ->whereIn('status', ['gepland', 'voltooid']),
            ])
                ->where('actief', true)
                ->orderByDesc('boekingen_maand')
                ->limit(6)
                ->get(),
            'recente_reviews'     => Review::with(['kapper', 'klant'])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(),
        ])->layout('layouts.admin');
    }
}
