<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Price;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    public function checkout(Request $request)
    {
        $user   = $request->user();
        $kapper = $user->kapper;

        $priceId = $this->resolvePriceId();

        if (!$priceId) {
            return redirect()->route('kapper.abonnement')
                ->with('abonnement_fout', 'Abonnement prijs niet gevonden. Neem contact op met de beheerder.');
        }

        try {
            $session = $user->newSubscription('default', $priceId)
                ->trialDays(14)
                ->checkout([
                    'success_url' => route('kapper.abonnement') . '?stripe=success',
                    'cancel_url'  => route('kapper.abonnement') . '?stripe=cancel',
                    'metadata'    => ['kapper_id' => $kapper?->id],
                    'allow_promotion_codes' => true,
                ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            report($e);
            return redirect()->route('kapper.abonnement')
                ->with('abonnement_fout', 'Er is iets misgegaan. Probeer het later opnieuw.');
        }
    }

    public function portal(Request $request)
    {
        return $request->user()->redirectToBillingPortal(route('kapper.abonnement'));
    }

    private function resolvePriceId(): ?string
    {
        // Probeer eerst via lookup_key (schoner, werkt in test + live mode)
        if ($lookupKey = config('services.stripe.price_lookup_key')) {
            try {
                Stripe::setApiKey(config('cashier.secret'));
                $prices = Price::search(['query' => "lookup_key:'$lookupKey'"]);
                if (!empty($prices->data)) {
                    return $prices->data[0]->id;
                }
            } catch (\Exception $e) {
                // Fallback naar hardcoded price ID
            }
        }

        return config('services.stripe.price_monthly') ?: env('STRIPE_PRICE_MONTHLY');
    }
}
