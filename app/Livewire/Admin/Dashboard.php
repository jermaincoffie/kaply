<?php

namespace App\Livewire\Admin;

use App\Models\Kapper;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    const ABONNEMENT_PRIJS = 2500; // €25 in centen
    const TRIAL_DAGEN = 14;

    public function toggleReviewZichtbaar(int $id): void
    {
        $review = Review::findOrFail($id);
        $review->update(['zichtbaar' => !$review->zichtbaar]);
    }

    public function render()
    {
        $abonnees_actief = Kapper::where('abonnement_status', 'actief')->count();
        $kappers_totaal  = Kapper::count();
        $mrr             = $abonnees_actief * self::ABONNEMENT_PRIJS;
        $prognose_mrr    = $kappers_totaal * self::ABONNEMENT_PRIJS;

        // MRR trend: nieuwe actieve abonnees deze maand vs vorige maand
        $nieuwe_actief_deze_maand = DB::table('subscriptions')
            ->where('stripe_status', 'active')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $nieuwe_actief_vorige_maand = DB::table('subscriptions')
            ->where('stripe_status', 'active')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $mrr_verschil = ($nieuwe_actief_deze_maand - $nieuwe_actief_vorige_maand) * self::ABONNEMENT_PRIJS;

        // Trial kappers: Stripe subscription met stripe_status = 'trialing'
        $trial_kappers = Kapper::with('user')
            ->whereHas('user', fn($q) => $q->whereHas('subscriptions', fn($q2) => $q2->where('stripe_status', 'trialing')))
            ->orderBy('created_at')
            ->get()
            ->map(function ($k) {
                $trialEnds = $k->user->subscription('default')?->trial_ends_at;
                $k->dagen_resterend = $trialEnds ? max(0, (int) now()->diffInDays($trialEnds)) : 0;
                return $k;
            });

        // Conversieratio: actieve abonnees / alle kappers die onboarding voltooid hebben
        $totaal_onboarded = Kapper::where('onboarding_voltooid', true)->count();
        $conversieratio   = $totaal_onboarded > 0 ? round(($abonnees_actief / $totaal_onboarded) * 100) : 0;

        $wachtende_kappers = Kapper::with('user')
            ->where('actief', false)
            ->where('abonnement_status', 'geen')
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        // Churn: past_due (betaling mislukt) + inactief (opgezegd, laatste 60 dagen)
        $churn_kappers = Kapper::with('user')
            ->where(fn($q) => $q
                ->where('abonnement_status', 'past_due')
                ->orWhere(fn($q2) => $q2
                    ->where('abonnement_status', 'inactief')
                    ->where('updated_at', '>=', now()->subDays(60))
                )
            )
            ->orderByRaw("FIELD(abonnement_status, 'past_due', 'inactief')")
            ->orderByDesc('updated_at')
            ->get();
        $churn_count = $churn_kappers->count();
        $past_due_count = $churn_kappers->where('abonnement_status', 'past_due')->count();

        $nieuw_aangemeld = Kapper::where('actief', false)
            ->where('abonnement_status', 'geen')
            ->count();

        // Recente aanmeldingen: nieuwste kappers die onboarding voltooid hebben
        $recente_aanmeldingen = Kapper::with('user')
            ->where('onboarding_voltooid', true)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('livewire.admin.dashboard', [
            'kappers_totaal'          => $kappers_totaal,
            'nieuw_aangemeld'         => $nieuw_aangemeld,
            'klanten_totaal'          => User::where('role', 'klant')->count(),
            'abonnees_actief'         => $abonnees_actief,
            'mrr'                     => $mrr,
            'prognose_mrr'            => $prognose_mrr,
            'mrr_verschil'            => $mrr_verschil,
            'trial_kappers'           => $trial_kappers,
            'trial_count'             => $trial_kappers->count(),
            'conversieratio'          => $conversieratio,
            'recente_aanmeldingen'    => $recente_aanmeldingen,
            'wachtende_kappers'       => $wachtende_kappers,
            'churn_kappers'           => $churn_kappers,
            'churn_count'             => $churn_count,
            'past_due_count'          => $past_due_count,
            'top_kappers'             => Kapper::withCount([
                'afspraken as boekingen_maand' => fn($q) => $q
                    ->whereMonth('datum', now()->month)
                    ->whereYear('datum', now()->year)
                    ->whereIn('status', ['gepland', 'voltooid']),
            ])
                ->where('actief', true)
                ->orderByDesc('boekingen_maand')
                ->limit(6)
                ->get(),
            'recente_reviews'         => Review::with(['kapper', 'klant'])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(),
        ])->layout('layouts.admin');
    }
}
