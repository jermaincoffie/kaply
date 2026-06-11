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
