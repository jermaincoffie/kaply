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
     * Activeer een platform-abonnement voor de kapper.
     *
     * Gebruikt stripe_balance als betaalmethode: de kosten worden automatisch
     * afgeschreven van het Stripe-saldo dat de kapper ontvangt via klantbetalingen.
     * De kapper hoeft geen creditcard in te voeren — alles loopt via hun Stripe-rekening.
     *
     * Flow:
     *  1. SetupIntent met stripe_balance → autoriseert saldo als betaalmethode
     *  2. Subscription direct aanmaken via de API → geen hosted checkout nodig
     */
    public function subscribe(Request $request)
    {
        $kapper    = $request->user()->kapper;
        $accountId = $kapper?->stripe_connect_id; // acct_xxx van de kapper

        if (!$accountId) {
            return redirect()->route('kapper.abonnement')
                ->with('abonnement_fout', 'Koppel eerst je Stripe account (via Profiel) voordat je een abonnement activeert.');
        }

        $priceId = $this->resolvePriceId();

        if (!$priceId) {
            return redirect()->route('kapper.abonnement')
                ->with('abonnement_fout', 'Abonnement prijs niet gevonden. Neem contact op met de beheerder.');
        }

        try {
            $stripe = $this->stripe();

            // Stap 1: SetupIntent aanmaken om stripe_balance te autoriseren als betaalmethode.
            // `confirm: true` bevestigt de intent direct — geen extra stap nodig.
            // `usage: off_session` zodat toekomstige maanden automatisch worden afgeschreven.
            $setupIntent = $stripe->setupIntents->create([
                'payment_method_types' => ['stripe_balance'],
                'confirm'              => true,
                'customer_account'     => $accountId,
                'usage'                => 'off_session',
                'payment_method_data'  => ['type' => 'stripe_balance'],
            ]);

            // Stap 2: Subscription aanmaken met de stripe_balance betaalmethode.
            // `customer_account` koppelt het abonnement aan het connected account.
            // Bij V2 accounts gebruik je customer_account (acct_xxx), NIET customer (cus_xxx).
            $subscription = $stripe->subscriptions->create([
                'customer_account'       => $accountId,
                'default_payment_method' => $setupIntent->payment_method,
                'items'                  => [
                    ['price' => $priceId, 'quantity' => 1],
                ],
                'payment_settings' => [
                    'payment_method_types' => ['stripe_balance'],
                ],
            ]);

            // Sla het subscription ID op zodat je het later kunt ophalen/annuleren
            $kapper->update(['stripe_subscription_id' => $subscription->id]);

            return redirect()->route('kapper.abonnement')
                ->with('success', 'Abonnement geactiveerd! Kosten worden automatisch van je Stripe-saldo afgeschreven.');

        } catch (\Stripe\Exception\ApiErrorException $e) {
            report($e);
            return redirect()->route('kapper.abonnement')
                ->with('abonnement_fout', 'Fout: ' . $e->getMessage());
        }
    }

    /**
     * Stuur de kapper naar de Stripe Billing Portal om hun abonnement te beheren.
     * Voor V2 accounts: gebruik customer_account (acct_xxx), niet de user's stripe_id.
     */
    public function portal(Request $request)
    {
        $kapper    = $request->user()->kapper;
        $accountId = $kapper?->stripe_connect_id;

        if (!$accountId) {
            return redirect()->route('kapper.abonnement')
                ->with('abonnement_fout', 'Geen gekoppeld Stripe account gevonden.');
        }

        try {
            $portalSession = $this->stripe()->billingPortal->sessions->create([
                'customer_account' => $accountId,
                'return_url'       => route('kapper.abonnement'),
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
