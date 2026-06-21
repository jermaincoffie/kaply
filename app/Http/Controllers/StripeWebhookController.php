<?php

namespace App\Http\Controllers;

use App\Models\Kapper;
use App\Models\User;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class StripeWebhookController extends CashierWebhookController
{
    public function handleCustomerSubscriptionCreated(array $payload): void
    {
        parent::handleCustomerSubscriptionCreated($payload);
        $this->syncAbonnementStatus($payload);
    }

    public function handleCustomerSubscriptionUpdated(array $payload): void
    {
        parent::handleCustomerSubscriptionUpdated($payload);
        $this->syncAbonnementStatus($payload);
    }

    public function handleCustomerSubscriptionDeleted(array $payload): void
    {
        parent::handleCustomerSubscriptionDeleted($payload);
        $this->syncAbonnementStatus($payload);
    }

    public function handleCheckoutSessionCompleted(array $payload): void
    {
        $object = $payload['data']['object'] ?? [];
        if (($object['mode'] ?? '') !== 'subscription') return;

        $customerId     = $object['customer'] ?? null;
        $subscriptionId = $object['subscription'] ?? null;
        if (!$customerId || !$subscriptionId) return;

        $user = User::where('stripe_id', $customerId)->first();
        if (!$user?->kapper) return;

        // Bepaal gebruikte betaalmethode (ideal, card, sepa_debit, etc.)
        $betaalmethode = null;
        $paymentMethodId = $object['payment_method'] ?? null;
        if ($paymentMethodId) {
            try {
                $pm = (new \Stripe\StripeClient(['api_key' => config('cashier.secret')]))->paymentMethods->retrieve($paymentMethodId);
                $betaalmethode = $pm->type;
            } catch (\Exception $e) {}
        }

        $user->kapper->update([
            'stripe_subscription_id'  => $subscriptionId,
            'abonnement_status'       => 'actief',
            'abonnement_betaalmethode' => $betaalmethode,
            'abonnement_past_due_since' => null,
        ]);
    }

    private function syncAbonnementStatus(array $payload): void
    {
        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;
        if (!$stripeCustomerId) return;

        $user = User::where('stripe_id', $stripeCustomerId)->first();
        if (!$user?->kapper) return;

        $stripeStatus = $payload['data']['object']['status'] ?? 'canceled';

        $updates = match ($stripeStatus) {
            'active', 'trialing' => [
                'abonnement_status'        => 'actief',
                'abonnement_past_due_since' => null,
            ],
            'past_due' => [
                'abonnement_status'        => 'past_due',
                'abonnement_past_due_since' => $user->kapper->abonnement_past_due_since ?? now(),
            ],
            default => [
                'abonnement_status'        => 'inactief',
                'abonnement_past_due_since' => null,
            ],
        };

        $user->kapper->update($updates);
    }
}
