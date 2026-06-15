<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'kapper') {
            $kapper = $user->kapper;
            if ($kapper && $kapper->actief && !$kapper->onboarding_voltooid) {
                return redirect()->route('kapper.onboarding');
            }
        }

        return $next($request);
    }
}
