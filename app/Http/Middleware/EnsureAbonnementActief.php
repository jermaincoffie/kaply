<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAbonnementActief
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $kapper = $user?->kapper;

        if ($user?->role === 'admin') {
            return $next($request);
        }

        if (!$kapper) {
            return redirect()->route('kapper.abonnement');
        }

        $subscription = $user->subscription('default');

        if ($kapper->abonnement_status === 'actief' || ($subscription && $subscription->active())) {
            return $next($request);
        }

        // Past_due: iDEAL = geen grace, card/sepa = 7 dagen grace
        if ($kapper->abonnement_status === 'past_due') {
            $betaalmethode = $kapper->abonnement_betaalmethode;
            $isIdeal = $betaalmethode === 'ideal';

            if (!$isIdeal && $kapper->abonnement_past_due_since) {
                $dagenPastDue = now()->diffInDays($kapper->abonnement_past_due_since);
                if ($dagenPastDue < 7) {
                    // Nog binnen grace period — geef toegang maar banner wordt getoond
                    $request->session()->flash('abonnement_past_due', 7 - $dagenPastDue);
                    return $next($request);
                }
            }
        }

        return redirect()->route('kapper.abonnement')
            ->with('abonnement_vereist', true);

        return $next($request);
    }
}
