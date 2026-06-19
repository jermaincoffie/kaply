<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * Stripe Connect Demo — Webhook Controller
 *
 * Handles two separate webhook streams:
 *
 * 1. V2 Thin Events — for connected account requirement changes
 *    Endpoint:  POST /stripe-demo/webhook/connect
 *    Secret:    STRIPE_DEMO_CONNECT_WEBHOOK_SECRET
 *
 *    Setup: In Stripe Dashboard → Developers → Webhooks → Add destination
 *      - Source: Connected accounts
 *      - Payload style: Thin
 *      - Events: v2.core.account[requirements].updated
 *                v2.core.account[configuration.merchant].capability_status_updated
 *                v2.core.account[configuration.customer].capability_status_updated
 *
 *    Or start a local listener:
 *      stripe listen \
 *        --thin-events 'v2.core.account[requirements].updated,...' \
 *        --forward-thin-to http://localhost:8000/stripe-demo/webhook/connect
 *
 * 2. V1 Subscription Events — for subscription lifecycle management
 *    Endpoint:  POST /stripe-demo/webhook/subscriptions
 *    Secret:    STRIPE_DEMO_SUBSCRIPTION_WEBHOOK_SECRET
 *
 *    Events: customer.subscription.updated, customer.subscription.deleted,
 *            invoice.payment_succeeded, invoice.payment_failed,
 *            payment_method.attached, payment_method.detached,
 *            customer.updated, customer.tax_id.created, customer.tax_id.deleted,
 *            customer.tax_id.updated
 */
class StripeConnectDemoWebhookController extends Controller
{
    /**
     * Build the Stripe client (same as the main controller).
     */
    private function stripe(): \Stripe\StripeClient
    {
        // ⚠️  PLACEHOLDER — set STRIPE_SECRET in your .env file
        $apiKey = config('cashier.secret');

        if (empty($apiKey)) {
            throw new \RuntimeException('STRIPE_SECRET is not set.');
        }

        return new \Stripe\StripeClient(['api_key' => $apiKey]);
    }

    // ─────────────────────────────────────────────────────────────
    // ENDPOINT 1 — V2 Thin Events (connected account requirements)
    // ─────────────────────────────────────────────────────────────

    /**
     * POST /stripe-demo/webhook/connect
     *
     * Handles V2 thin events for connected account status changes.
     *
     * WHAT IS A THIN EVENT?
     * V2 webhooks send a "thin" payload — a small JSON object containing
     * only the event ID and type. You must then call the API to retrieve
     * the full event data. This avoids stale payload data and keeps
     * webhook payloads small.
     *
     * HOW TO VERIFY:
     * parseThinEvent() both verifies the Stripe-Signature header AND
     * parses the thin payload. It throws SignatureVerificationException
     * if the signature is invalid — always validate before processing.
     */
    public function handleConnect(Request $request): Response
    {
        // ⚠️  PLACEHOLDER — set STRIPE_DEMO_CONNECT_WEBHOOK_SECRET in .env
        //     Find this in Stripe Dashboard → Developers → Webhooks → your endpoint → Signing secret
        $webhookSecret = env('STRIPE_DEMO_CONNECT_WEBHOOK_SECRET');

        if (empty($webhookSecret)) {
            Log::error('[StripeDemo] STRIPE_DEMO_CONNECT_WEBHOOK_SECRET is not set');
            return response('Webhook secret not configured', 500);
        }

        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $stripeClient = $this->stripe();

            // Parse and verify the thin event.
            // This checks the signature and decodes the thin payload.
            // Returns a ThinEvent object with `id` and `type`.
            $thinEvent = $stripeClient->parseThinEvent($payload, $sigHeader, $webhookSecret);

            // Fetch the full event data from the Stripe API.
            // The thin event only has the ID and type — we need to retrieve
            // the full event to access `related_object` and other details.
            $event = $stripeClient->v2->core->events->retrieve($thinEvent->id);

        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature — could be a replay attack or misconfigured secret
            Log::warning('[StripeDemo] Invalid webhook signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        } catch (\Exception $e) {
            Log::error('[StripeDemo] Webhook parsing error: ' . $e->getMessage());
            return response('Webhook error', 400);
        }

        // The `related_object` field on a V2 event tells us which object changed
        $relatedObject = $event->related_object ?? null;
        $accountId     = $relatedObject?->id ?? null; // shape: acct_...

        Log::info("[StripeDemo] V2 event received: {$event->type}", [
            'event_id'   => $event->id,
            'account_id' => $accountId,
        ]);

        // Route to the appropriate handler based on event type
        match ($event->type) {
            'v2.core.account[requirements].updated'
                => $this->handleRequirementsUpdated($event, $accountId),

            'v2.core.account[configuration.merchant].capability_status_updated'
                => $this->handleMerchantCapabilityUpdated($event, $accountId),

            'v2.core.account[configuration.customer].capability_status_updated'
                => $this->handleCustomerCapabilityUpdated($event, $accountId),

            default => Log::info("[StripeDemo] Unhandled V2 event type: {$event->type}"),
        };

        // Always return 200 quickly — Stripe retries if you return non-2xx
        return response('OK', 200);
    }

    /**
     * Handle v2.core.account[requirements].updated
     *
     * Triggered when the requirements on a connected account change.
     * This often happens due to regulatory changes, card network rules,
     * or when Stripe collects new information from the account holder.
     *
     * Action: Notify the seller they need to submit additional information.
     */
    private function handleRequirementsUpdated(object $event, ?string $accountId): void
    {
        if (!$accountId) return;

        Log::info("[StripeDemo] Requirements updated for account: {$accountId}");

        // Retrieve the current account status to check what's needed
        try {
            $stripeClient = $this->stripe();
            $account = $stripeClient->v2->core->accounts->retrieve($accountId, [
                'include' => ['requirements'],
            ]);

            $status = $account->requirements?->summary?->minimum_deadline?->status;

            if (in_array($status, ['currently_due', 'past_due'])) {
                Log::warning("[StripeDemo] Account {$accountId} has {$status} requirements");

                // TODO: Notify the connected account owner they need to take action
                // Examples:
                //   - Send an email: Mail::to($seller->email)->send(new RequirementsUpdateMail($accountId))
                //   - Update a DB column: ConnectedAccount::where('account_id', $accountId)
                //       ->update(['requirements_status' => $status])
                //   - Push a notification via Pusher/Echo
            }

        } catch (\Exception $e) {
            Log::error("[StripeDemo] Error retrieving account {$accountId}: " . $e->getMessage());
        }
    }

    /**
     * Handle v2.core.account[configuration.merchant].capability_status_updated
     *
     * Triggered when a merchant capability (e.g. card_payments) changes status.
     * Statuses: active, inactive, pending, restricted, restricted_soon
     *
     * Action: Enable/disable payment features based on capability status.
     */
    private function handleMerchantCapabilityUpdated(object $event, ?string $accountId): void
    {
        if (!$accountId) return;

        Log::info("[StripeDemo] Merchant capability updated for account: {$accountId}");

        try {
            $stripeClient = $this->stripe();
            $account = $stripeClient->v2->core->accounts->retrieve($accountId, [
                'include' => ['configuration.merchant'],
            ]);

            $cardPaymentsStatus = $account->configuration?->merchant?->capabilities?->card_payments?->status;

            Log::info("[StripeDemo] Account {$accountId} card_payments status: {$cardPaymentsStatus}");

            // TODO: Update your DB to reflect the new capability status
            // ConnectedAccount::where('account_id', $accountId)
            //     ->update(['card_payments_status' => $cardPaymentsStatus]);

            if ($cardPaymentsStatus === 'active') {
                // TODO: Enable payment features for this seller in your system
                // ConnectedAccount::where('account_id', $accountId)->update(['can_accept_payments' => true]);
            } elseif (in_array($cardPaymentsStatus, ['restricted', 'restricted_soon'])) {
                // TODO: Alert the seller and restrict payment features
            }

        } catch (\Exception $e) {
            Log::error("[StripeDemo] Error retrieving account {$accountId}: " . $e->getMessage());
        }
    }

    /**
     * Handle v2.core.account[configuration.customer].capability_status_updated
     *
     * Triggered when a customer-side capability changes status.
     * This affects whether the connected account can BE a customer
     * on your platform (e.g. for platform subscriptions).
     */
    private function handleCustomerCapabilityUpdated(object $event, ?string $accountId): void
    {
        if (!$accountId) return;

        Log::info("[StripeDemo] Customer capability updated for account: {$accountId}");

        // TODO: Check configuration.customer capabilities and update your system
        // ConnectedAccount::where('account_id', $accountId)
        //     ->update(['customer_capability_updated_at' => now()]);
    }

    // ─────────────────────────────────────────────────────────────
    // ENDPOINT 2 — V1 Subscription Events
    // ─────────────────────────────────────────────────────────────

    /**
     * POST /stripe-demo/webhook/subscriptions
     *
     * Handles V1 subscription lifecycle events.
     *
     * These use the standard (non-thin) event format — the full event data
     * is included in the webhook payload. Verification uses constructEvent().
     *
     * NOTE: These do NOT use thin events because they are V1 API events.
     * Only V2 events use the thin event format.
     */
    public function handleSubscriptions(Request $request): Response
    {
        // ⚠️  PLACEHOLDER — set STRIPE_DEMO_SUBSCRIPTION_WEBHOOK_SECRET in .env
        //     This is a different secret from the Connect webhook
        $webhookSecret = env('STRIPE_DEMO_SUBSCRIPTION_WEBHOOK_SECRET');

        if (empty($webhookSecret)) {
            Log::error('[StripeDemo] STRIPE_DEMO_SUBSCRIPTION_WEBHOOK_SECRET is not set');
            return response('Webhook secret not configured', 500);
        }

        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            // constructEvent() verifies the signature AND decodes the full event.
            // For V1 events, the payload IS the event (unlike V2 thin events).
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);

        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('[StripeDemo] Invalid subscription webhook signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        } catch (\Exception $e) {
            Log::error('[StripeDemo] Subscription webhook error: ' . $e->getMessage());
            return response('Webhook error', 400);
        }

        Log::info("[StripeDemo] Subscription event: {$event->type}", ['event_id' => $event->id]);

        // Route to the appropriate handler
        match ($event->type) {
            'customer.subscription.updated'  => $this->handleSubscriptionUpdated($event),
            'customer.subscription.deleted'  => $this->handleSubscriptionDeleted($event),
            'invoice.payment_succeeded'      => $this->handleInvoicePaid($event),
            'invoice.payment_failed'         => $this->handleInvoiceFailed($event),
            'payment_method.attached'        => $this->handlePaymentMethodAttached($event),
            'payment_method.detached'        => $this->handlePaymentMethodDetached($event),
            'customer.updated'               => $this->handleCustomerUpdated($event),
            'customer.tax_id.created'        => $this->handleTaxIdCreated($event),
            'customer.tax_id.deleted'        => $this->handleTaxIdDeleted($event),
            'customer.tax_id.updated'        => $this->handleTaxIdUpdated($event),
            'billing_portal.configuration.created',
            'billing_portal.configuration.updated',
            'billing_portal.session.created' => $this->handleBillingPortalEvent($event),
            default => Log::info("[StripeDemo] Unhandled subscription event: {$event->type}"),
        };

        return response('OK', 200);
    }

    /**
     * customer.subscription.updated
     *
     * Fires when a subscription is upgraded, downgraded, quantity-changed,
     * paused, or when `cancel_at_period_end` toggles.
     *
     * For V2 accounts: use `customer_account` (acct_xxx), NOT `customer` (cus_xxx)
     */
    private function handleSubscriptionUpdated(\Stripe\Event $event): void
    {
        $subscription = $event->data->object;

        // For V2 connected accounts, the identifier is customer_account (acct_xxx)
        // NOT subscription->customer (which would be cus_xxx for V1 customers)
        // ⚠️  If you see null here, ensure the subscription was created with customer_account
        $accountId = $subscription->customer_account ?? null;

        // Fallback to customer for V1-style subscriptions
        $customerId = $subscription->customer ?? null;

        $priceId  = $subscription->items->data[0]->price->id ?? null;
        $quantity = $subscription->items->data[0]->quantity ?? 1;
        $status   = $subscription->status;

        Log::info("[StripeDemo] Subscription updated", [
            'subscription_id' => $subscription->id,
            'account_id'      => $accountId ?? $customerId,
            'price_id'        => $priceId,
            'quantity'        => $quantity,
            'status'          => $status,
        ]);

        // Check if subscription was cancelled at period end
        if ($subscription->cancel_at_period_end) {
            Log::info("[StripeDemo] Subscription will cancel at period end");
            // TODO: Flag in DB that access continues until period end
            // ConnectedAccount::where('account_id', $accountId)
            //     ->update(['subscription_cancels_at' => $subscription->cancel_at]);
        }

        // Check if subscription was reactivated (cancel_at_period_end went false)
        if (!$subscription->cancel_at_period_end && $subscription->status === 'active') {
            // TODO: Remove cancellation flag from DB
        }

        // Check if collection is paused
        if (!empty($subscription->pause_collection)) {
            // `behavior` is always 'void' when paused via customer portal
            $resumesAt = $subscription->pause_collection->resumes_at ?? null;
            Log::info("[StripeDemo] Subscription paused, resumes at: {$resumesAt}");
            // TODO: Restrict access until resumes_at
        }

        // TODO: Update DB with new price/plan, grant/revoke access accordingly
        // ConnectedAccount::where('account_id', $accountId)->update([
        //     'subscription_id'     => $subscription->id,
        //     'subscription_status' => $status,
        //     'price_id'            => $priceId,
        //     'quantity'            => $quantity,
        // ]);
    }

    /**
     * customer.subscription.deleted
     *
     * Fires when a subscription is fully cancelled (not just scheduled to cancel).
     * Immediately revoke the customer's access to paid features.
     */
    private function handleSubscriptionDeleted(\Stripe\Event $event): void
    {
        $subscription = $event->data->object;
        $accountId    = $subscription->customer_account ?? $subscription->customer;

        Log::info("[StripeDemo] Subscription cancelled", [
            'subscription_id' => $subscription->id,
            'account_id'      => $accountId,
        ]);

        // TODO: Revoke access immediately
        // ConnectedAccount::where('account_id', $accountId)->update([
        //     'subscription_status'  => 'cancelled',
        //     'subscription_ends_at' => now(),
        // ]);

        // TODO: Optionally send a cancellation confirmation email
    }

    /**
     * invoice.payment_succeeded
     *
     * Fires when a subscription invoice is paid successfully.
     * Use this to extend access and store billing records.
     */
    private function handleInvoicePaid(\Stripe\Event $event): void
    {
        $invoice      = $event->data->object;
        $accountId    = $invoice->customer_account ?? $invoice->customer;
        $amountPaid   = $invoice->amount_paid; // in cents
        $subscriptionId = $invoice->subscription;

        Log::info("[StripeDemo] Invoice paid", [
            'invoice_id'      => $invoice->id,
            'account_id'      => $accountId,
            'amount_paid'     => $amountPaid,
            'subscription_id' => $subscriptionId,
        ]);

        // TODO: Store invoice record and ensure access is active
        // Invoice::create([
        //     'account_id'      => $accountId,
        //     'stripe_invoice'  => $invoice->id,
        //     'amount'          => $amountPaid,
        //     'paid_at'         => now(),
        // ]);
    }

    /**
     * invoice.payment_failed
     *
     * Fires when a subscription payment fails.
     * Stripe will retry based on your Smart Retries settings.
     */
    private function handleInvoiceFailed(\Stripe\Event $event): void
    {
        $invoice   = $event->data->object;
        $accountId = $invoice->customer_account ?? $invoice->customer;

        Log::warning("[StripeDemo] Invoice payment failed", [
            'invoice_id' => $invoice->id,
            'account_id' => $accountId,
        ]);

        // TODO: Notify the seller to update their payment method
        // TODO: Optionally restrict access after N failed attempts
    }

    /**
     * payment_method.attached / payment_method.detached
     *
     * Fires when a customer adds or removes a payment method.
     */
    private function handlePaymentMethodAttached(\Stripe\Event $event): void
    {
        $pm = $event->data->object;
        Log::info("[StripeDemo] Payment method attached: {$pm->id}");
        // TODO: Update DB with new payment method info if needed
    }

    private function handlePaymentMethodDetached(\Stripe\Event $event): void
    {
        $pm = $event->data->object;
        Log::info("[StripeDemo] Payment method detached: {$pm->id}");
        // TODO: Update DB to reflect removed payment method
    }

    /**
     * customer.updated
     *
     * Fires when customer billing information changes.
     * ⚠️  Do NOT use the billing email as a login credential —
     *     it's billing-only and can be changed by anyone with portal access.
     */
    private function handleCustomerUpdated(\Stripe\Event $event): void
    {
        $customer = $event->data->object;
        Log::info("[StripeDemo] Customer updated: {$customer->id}");

        // Check if default payment method changed
        $defaultPm = $customer->invoice_settings->default_payment_method ?? null;
        if ($defaultPm) {
            // TODO: Store the new default payment method reference
        }
    }

    /**
     * customer.tax_id.created / updated / deleted
     *
     * Fires when a customer manages their tax IDs via the billing portal.
     * Stripe validates some tax ID types (e.g. EU VAT numbers).
     */
    private function handleTaxIdCreated(\Stripe\Event $event): void
    {
        Log::info('[StripeDemo] Tax ID created: ' . $event->data->object->id);
        // TODO: Store tax ID for invoice generation
    }

    private function handleTaxIdDeleted(\Stripe\Event $event): void
    {
        Log::info('[StripeDemo] Tax ID deleted: ' . $event->data->object->id);
    }

    private function handleTaxIdUpdated(\Stripe\Event $event): void
    {
        $taxId = $event->data->object;
        Log::info("[StripeDemo] Tax ID updated: {$taxId->id}, validation: {$taxId->verification->status}");
        // TODO: Update tax ID validation status in DB
    }

    /**
     * billing_portal.* events
     *
     * Informational — fired when portal configurations/sessions are created/updated.
     */
    private function handleBillingPortalEvent(\Stripe\Event $event): void
    {
        Log::info("[StripeDemo] Billing portal event: {$event->type}");
    }
}
