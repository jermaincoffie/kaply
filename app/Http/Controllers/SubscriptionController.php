<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();
        $kapper = $user->kapper;

        $session = $user->newSubscription('default', env('STRIPE_PRICE_MONTHLY'))
            ->checkout([
                'success_url' => route('kapper.abonnement') . '?stripe=success',
                'cancel_url'  => route('kapper.abonnement') . '?stripe=cancel',
                'customer_email' => $user->email,
                'metadata' => ['kapper_id' => $kapper?->id],
            ]);

        return redirect($session->url);
    }

    public function portal(Request $request)
    {
        return $request->user()->redirectToBillingPortal(route('kapper.abonnement'));
    }
}
