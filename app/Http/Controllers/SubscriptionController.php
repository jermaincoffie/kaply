<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Price;
use Stripe\StripeClient;

class SubscriptionController extends Controller
{
    private function stripe(): StripeClient
    {
        return new StripeClient(['api_key' => config('cashier.secret')]);
    }

    /**
     * Stuur kapper naar Stripe Checkout voor abonnement betaling via iDEAL of bankpas.
     * Geen Stripe Connect account vereist — kapper betaalt als consument aan het platform.
     */
    public function subscribe(Request $request)
    {
        $user   = $request->user();
        $kapper = $user->kapper;

        $priceId = $this->resolvePriceId();
        if (!$priceId) {
            return redirect()->route('kapper.abonnement')
                ->with('abonnement_fout', 'Abonnement prijs niet gevonden. Neem contact op met de beheerder.');
        }

        try {
            // Zorg dat kapper een Stripe customer heeft (Cashier)
            if (!$user->stripe_id) {
                $user->createAsStripeCustomer([
                    'name'  => $user->name,
                    'email' => $user->email,
                ]);
            }

            $session = $this->stripe()->checkout->sessions->create([
                'customer'             => $user->stripe_id,
                'mode'                 => 'subscription',
                'payment_method_types' => ['card', 'ideal', 'sepa_debit'],
                'line_items'           => [
                    ['price' => $priceId, 'quantity' => 1],
                ],
                'subscription_data'    => [
                    'trial_period_days' => 14,
                ],
                'success_url' => route('kapper.subscription.succes') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('kapper.abonnement'),
            ]);

            return redirect($session->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            report($e);
            return redirect()->route('kapper.abonnement')
                ->with('abonnement_fout', 'Fout bij starten betaling: ' . $e->getMessage());
        }
    }

    /**
     * Stuur de kapper naar de Stripe Billing Portal om hun abonnement te beheren.
     * Voor V2 accounts: gebruik customer_account (acct_xxx), niet de user's stripe_id.
     */
    public function portal(Request $request)
    {
        $user = $request->user();

        if (!$user->stripe_id) {
            return redirect()->route('kapper.abonnement')
                ->with('abonnement_fout', 'Geen betaalaccount gevonden.');
        }

        try {
            $portalSession = $this->stripe()->billingPortal->sessions->create([
                'customer'   => $user->stripe_id,
                'return_url' => route('kapper.abonnement'),
            ]);

            return redirect($portalSession->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return redirect()->route('kapper.abonnement')
                ->with('abonnement_fout', 'Kan portal niet openen: ' . $e->getMessage());
        }
    }

    private function resolvePriceId(): ?string
    {
        if ($lookupKey = config('services.stripe.price_lookup_key')) {
            try {
                $stripe  = $this->stripe();
                $prices  = $stripe->prices->search(['query' => "lookup_key:'{$lookupKey}'"]);
                if (!empty($prices->data)) {
                    return $prices->data[0]->id;
                }
            } catch (\Exception $e) {
                // fallback
            }
        }

        return config('services.stripe.price_monthly') ?: env('STRIPE_PRICE_MONTHLY');
    }
}
