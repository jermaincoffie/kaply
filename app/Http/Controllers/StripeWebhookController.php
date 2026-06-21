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
        if ($user?->kapper) {
            $user->kapper->update([
                'stripe_subscription_id' => $subscriptionId,
                'abonnement_status'      => 'actief',
            ]);
        }
    }

    private function syncAbonnementStatus(array $payload): void
    {
        $stripeCustomerId = $payload['data']['object']['customer'] ?? null;
        if (!$stripeCustomerId) return;

        $user = User::where('stripe_id', $stripeCustomerId)->first();
        if (!$user?->kapper) return;

        $stripeStatus = $payload['data']['object']['status'] ?? 'canceled';
        $abonnementStatus = match ($stripeStatus) {
            'active', 'trialing' => 'actief',
            default => 'inactief',
        };

        $user->kapper->update(['abonnement_status' => $abonnementStatus]);
    }
}
