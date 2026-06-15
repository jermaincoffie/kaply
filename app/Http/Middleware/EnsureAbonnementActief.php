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

        // Controleer via DB-status (webhook) of via Cashier direct (bij webhook vertraging)
        $subscription = $user->subscription('default');
        $heeftToegang = $kapper->abonnement_status === 'actief'
            || ($subscription && $subscription->active());

        if (!$heeftToegang) {
            return redirect()->route('kapper.abonnement')
                ->with('abonnement_vereist', true);
        }

        return $next($request);
    }
}
