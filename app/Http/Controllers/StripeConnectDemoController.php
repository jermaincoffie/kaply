<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Stripe Connect Sample Integration — Controller
 *
 * Covers:
 *  1. Create V2 connected accounts
 *  2. Onboard accounts via V2 AccountLinks
 *  3. Create products on a connected account
 *  4. Display a storefront for a connected account
 *  5. Direct-charge checkout (customer pays connected account)
 *  6. Platform-level subscription via customer_account
 *  7. Billing portal for subscription management
 *
 * Required .env values:
 *   STRIPE_SECRET                   — your platform secret key (sk_test_... or sk_live_...)
 *   STRIPE_DEMO_PRICE_ID            — price ID for the platform subscription (price_...)
 *   STRIPE_DEMO_CONNECT_WEBHOOK_SECRET     — webhook secret for V2 thin-event endpoint
 *   STRIPE_DEMO_SUBSCRIPTION_WEBHOOK_SECRET — webhook secret for V1 subscription endpoint
 */
class StripeConnectDemoController extends Controller
{
    /**
     * Build a configured Stripe client using the platform's secret key.
     *
     * IMPORTANT: All Stripe requests go through this client, never via global
     * \Stripe\Stripe::setApiKey(). This lets you use different keys per request
     * if needed (e.g. test vs live) without polluting global state.
     *
     * Requires stripe-php v16+ (bundled with laravel/cashier ^16).
     */
    private function stripe(): \Stripe\StripeClient
    {
        // ⚠️  PLACEHOLDER — set STRIPE_SECRET in your .env file
        $apiKey = config('cashier.secret');

        if (empty($apiKey)) {
            throw new \RuntimeException(
                'STRIPE_SECRET is not set. Add it to your .env file: STRIPE_SECRET=sk_test_...'
            );
        }

        return new \Stripe\StripeClient(['api_key' => $apiKey]);
    }

    // ─────────────────────────────────────────────────────────────
    // DASHBOARD — create a connected account
    // ─────────────────────────────────────────────────────────────

    /**
     * Show the demo dashboard.
     * Displays a form to create a connected account and lists any
     * account IDs stored in the session (demo-only; use a DB in production).
     */
    public function dashboard(Request $request)
    {
        // In production, load connected account IDs from your database:
        //   $accounts = ConnectedAccount::where('user_id', auth()->id())->get();
        $accountIds = $request->session()->get('stripe_demo_accounts', []);

        return view('stripe-demo.dashboard', compact('accountIds'));
    }

    /**
     * POST /stripe-demo/account
     *
     * Creates a V2 connected account on behalf of a seller ("connected account").
     *
     * V2 accounts unify Express, Standard, and Custom under one API.
     * Key differences from V1:
     *   - Use v2->core->accounts, NOT accounts->create()
     *   - Never pass top-level `type` (Express/Standard/Custom are gone)
     *   - `fees_collector: 'stripe'` means Stripe collects/manages fees
     *   - `dashboard: 'full'` gives the connected account a Stripe dashboard
     */
    public function createAccount(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $stripeClient = $this->stripe();

        try {
            // Create a V2 connected account
            // See: https://docs.stripe.com/api/v2/core/accounts/object
            $account = $stripeClient->v2->core->accounts->create([
                // Publicly visible display name for this seller's account
                'display_name'  => $request->input('name'),
                // Contact email Stripe uses for compliance/notifications
                'contact_email' => $request->input('email'),

                'identity' => [
                    // Country where the connected account operates
                    // ⚠️  PLACEHOLDER — change to your supported countries
                    'country' => 'us',
                ],

                // 'full' gives the seller a Stripe Express-style dashboard
                // Use 'none' if you want an embedded dashboard instead
                'dashboard' => 'full',

                'defaults' => [
                    'responsibilities' => [
                        // 'stripe' means Stripe handles fee collection (not your platform)
                        'fees_collector'   => 'stripe',
                        // 'stripe' means Stripe absorbs fraud losses (not your platform)
                        'losses_collector' => 'stripe',
                    ],
                ],

                'configuration' => [
                    // 'customer' config enables this account to BE a customer
                    // on your platform (needed for customer_account subscriptions)
                    'customer' => [],

                    // 'merchant' config enables this account to accept payments
                    'merchant' => [
                        'capabilities' => [
                            // Request card_payments capability so this account
                            // can process card transactions
                            'card_payments' => ['requested' => true],
                        ],
                    ],
                ],
            ]);

            $accountId = $account->id; // shape: acct_...

            // ── Database storage (production) ──────────────────────────────
            // In production, map this account to your user:
            //   ConnectedAccount::create([
            //       'user_id'    => auth()->id(),
            //       'account_id' => $accountId,
            //       'name'       => $request->input('name'),
            //       'email'      => $request->input('email'),
            //   ]);
            // ──────────────────────────────────────────────────────────────

            // Demo-only: store in session so we can list it on the dashboard
            $accounts   = $request->session()->get('stripe_demo_accounts', []);
            $accounts[] = ['id' => $accountId, 'name' => $request->input('name')];
            $request->session()->put('stripe_demo_accounts', $accounts);

            return redirect()->route('stripe-demo.onboard', $accountId)
                ->with('success', "Account {$accountId} created! Complete onboarding to accept payments.");

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return back()->withErrors(['stripe' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // ONBOARDING — collect KYC/bank info via AccountLinks
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /stripe-demo/onboard/{accountId}
     *
     * Retrieves the current onboarding/requirements status directly from the
     * Stripe API (not from your DB) and shows a UI to start/resume onboarding.
     *
     * Status checks:
     *   - readyToProcessPayments: card_payments capability is active
     *   - onboardingComplete:     no currently_due or past_due requirements
     */
    public function onboard(Request $request, string $accountId)
    {
        $stripeClient = $this->stripe();

        try {
            // Retrieve the V2 account with expanded fields.
            // We always fetch from the API (never cache) so status is accurate.
            // `include` lets us expand nested objects without separate API calls.
            $account = $stripeClient->v2->core->accounts->retrieve($accountId, [
                'include' => ['configuration.merchant', 'requirements'],
            ]);

            // Check if card payments are fully active for this account
            $readyToProcessPayments =
                ($account->configuration->merchant->capabilities->card_payments->status ?? null) === 'active';

            // Check requirements deadline status
            // 'currently_due' or 'past_due' means the account still needs to submit info
            $requirementsStatus = $account->requirements->summary->minimum_deadline->status ?? null;
            $onboardingComplete  = !in_array($requirementsStatus, ['currently_due', 'past_due']);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return back()->withErrors(['stripe' => $e->getMessage()]);
        }

        return view('stripe-demo.onboard', compact(
            'accountId', 'account', 'readyToProcessPayments', 'onboardingComplete'
        ));
    }

    /**
     * POST /stripe-demo/onboard/{accountId}/link
     *
     * Creates a V2 AccountLink so the connected account can complete KYC,
     * add a bank account, and agree to Stripe's terms of service.
     *
     * Account links expire after a short time — always create a fresh one
     * when the user clicks "Continue Onboarding". Never store the URL.
     */
    public function createAccountLink(Request $request, string $accountId)
    {
        $stripeClient = $this->stripe();

        try {
            $returnUrl  = route('stripe-demo.onboard', $accountId);
            $refreshUrl = route('stripe-demo.onboard', $accountId); // called if link expires

            // Create a V2 account link for onboarding
            $accountLink = $stripeClient->v2->core->accountLinks->create([
                'account'  => $accountId,
                'use_case' => [
                    'type' => 'account_onboarding',
                    'account_onboarding' => [
                        // Collect info for both merchant (payments) and customer (subscriptions)
                        'configurations' => ['merchant', 'customer'],
                        // If the link expires, send them back here to get a new one
                        'refresh_url'    => $refreshUrl,
                        // After finishing onboarding, Stripe redirects here
                        'return_url'     => $returnUrl . '?onboarded=1',
                    ],
                ],
            ]);

            // Redirect the seller to Stripe's hosted onboarding UI
            return redirect($accountLink->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return back()->withErrors(['stripe' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // MANAGE — create products on a connected account
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /stripe-demo/manage/{accountId}
     *
     * Shows a management page for the connected account:
     *   - Form to create new products
     *   - List of existing products
     *   - Option to subscribe the account to a platform plan
     *
     * Note: The `stripeAccount` option in the second argument adds the
     * `Stripe-Account: acct_xxx` header, scoping the request to that account.
     */
    public function manage(Request $request, string $accountId)
    {
        $stripeClient = $this->stripe();

        try {
            // List products that belong to the connected account.
            // We pass `stripeAccount` to scope this request to their account
            // rather than your platform's product catalog.
            $products = $stripeClient->products->all(
                [
                    'limit'   => 20,
                    'active'  => true,
                    'expand'  => ['data.default_price'], // include price in one call
                ],
                ['stripe_account' => $accountId] // Stripe-Account header
            );

        } catch (\Stripe\Exception\ApiErrorException $e) {
            $products = null;
        }

        // ⚠️  PLACEHOLDER — set STRIPE_DEMO_PRICE_ID in .env to a recurring price ID
        //     e.g. price_1ABC... for your platform's subscription plan
        $subscriptionPriceId = env('STRIPE_DEMO_PRICE_ID');

        return view('stripe-demo.manage', compact('accountId', 'products', 'subscriptionPriceId'));
    }

    /**
     * POST /stripe-demo/manage/{accountId}/product
     *
     * Creates a product (with a default price) on the connected account.
     *
     * Using `default_price_data` creates the price in one API call instead
     * of creating the product and price separately.
     */
    public function createProduct(Request $request, string $accountId)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|numeric|min:0.50', // minimum 50 cents
            'currency'    => 'required|string|size:3',
        ]);

        $stripeClient = $this->stripe();

        try {
            // Convert dollars/euros to cents (Stripe amounts are in smallest currency unit)
            $priceInCents = (int) round($request->input('price') * 100);

            // Create the product on the connected account's catalog
            $stripeClient->products->create(
                [
                    'name'        => $request->input('name'),
                    'description' => $request->input('description'),

                    // `default_price_data` creates a one-time price for this product
                    // in the same API call — no separate price creation needed
                    'default_price_data' => [
                        'unit_amount' => $priceInCents,
                        'currency'    => strtolower($request->input('currency')),
                    ],
                ],
                // All requests to the connected account require this header
                ['stripe_account' => $accountId]
            );

            return redirect()->route('stripe-demo.manage', $accountId)
                ->with('success', 'Product created successfully!');

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return back()->withErrors(['stripe' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // STOREFRONT — customer-facing product listing
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /stripe-demo/store/{accountId}
     *
     * Public storefront that shows a connected account's products.
     *
     * ⚠️  PRODUCTION NOTE: Do not expose raw `acct_xxx` IDs in public URLs.
     * Use a slug, subdomain, or your own internal identifier and map it
     * to the Stripe account ID server-side. The comment is here as a reminder.
     *
     * Example:
     *   /store/my-shop-name   →  look up acct_xxx from your DB by slug
     */
    public function store(Request $request, string $accountId)
    {
        $stripeClient = $this->stripe();

        try {
            // Retrieve products from the connected account's catalog
            $products = $stripeClient->products->all(
                [
                    'limit'  => 20,
                    'active' => true,
                    'expand' => ['data.default_price'], // include price data
                ],
                ['stripe_account' => $accountId]
            );

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return view('stripe-demo.store', [
                'accountId' => $accountId,
                'products'  => null,
                'error'     => $e->getMessage(),
            ]);
        }

        return view('stripe-demo.store', compact('accountId', 'products'));
    }

    // ─────────────────────────────────────────────────────────────
    // DIRECT CHARGE CHECKOUT — customer pays the connected account
    // ─────────────────────────────────────────────────────────────

    /**
     * POST /stripe-demo/checkout/{accountId}/{priceId}
     *
     * Creates a Hosted Checkout Session using a Direct Charge.
     *
     * In a Direct Charge:
     *   - The customer pays the connected account directly (not your platform)
     *   - The connected account sees the payment in their Stripe dashboard
     *   - Stripe's processing fee is charged to the connected account
     *   - You can add an `application_fee_amount` to take a platform cut
     *
     * This differs from a Destination Charge where the platform receives
     * the funds and transfers them to the connected account.
     */
    public function checkout(Request $request, string $accountId, string $priceId)
    {
        $stripeClient = $this->stripe();

        try {
            // Build the Checkout Session on the connected account.
            // Passing `stripe_account` in the second argument adds the
            // `Stripe-Account` header so Stripe scopes this session to
            // the connected account — it's a Direct Charge.
            $session = $stripeClient->checkout->sessions->create(
                [
                    'line_items' => [
                        [
                            // Use the existing price ID from the product's default_price
                            'price'    => $priceId,
                            'quantity' => 1,
                        ],
                    ],
                    'mode' => 'payment',

                    // {CHECKOUT_SESSION_ID} is replaced by Stripe with the actual session ID
                    'success_url' => route('stripe-demo.success') . '?session_id={CHECKOUT_SESSION_ID}&account=' . $accountId,
                    'cancel_url'  => route('stripe-demo.store', $accountId),

                    // Optional: take a platform application fee
                    // 'payment_intent_data' => [
                    //     'application_fee_amount' => 100, // 100 cents = $1.00 platform fee
                    // ],
                ],
                // This header scopes the session to the connected account (Direct Charge)
                ['stripe_account' => $accountId]
            );

            // Redirect the customer to Stripe's hosted payment page
            return redirect($session->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return back()->withErrors(['stripe' => $e->getMessage()]);
        }
    }

    /**
     * GET /stripe-demo/success
     *
     * Landing page after a successful payment.
     * Optionally retrieves the session to show purchase details.
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        $accountId = $request->query('account');
        $session   = null;

        if ($sessionId && $accountId) {
            try {
                $stripeClient = $this->stripe();

                // Retrieve the completed session to show order details.
                // We must pass the connected account header because the session
                // lives on that account, not on your platform.
                $session = $stripeClient->checkout->sessions->retrieve(
                    $sessionId,
                    ['expand' => ['line_items']],
                    ['stripe_account' => $accountId]
                );
            } catch (\Stripe\Exception\ApiErrorException $e) {
                Log::warning('Could not retrieve checkout session: ' . $e->getMessage());
            }
        }

        return view('stripe-demo.success', compact('session', 'accountId'));
    }

    // ─────────────────────────────────────────────────────────────
    // PLATFORM SUBSCRIPTION — subscribe the connected account itself
    // ─────────────────────────────────────────────────────────────

    /**
     * POST /stripe-demo/manage/{accountId}/subscribe
     *
     * Creates a Hosted Checkout Session for a platform subscription.
     *
     * With V2 accounts, you use `customer_account` instead of a separate
     * customer ID. The connected account's acct_xxx ID serves as both the
     * account AND the customer on your platform.
     *
     * This charges the connected account owner (the seller) a recurring fee
     * to use your platform — e.g. a SaaS subscription for using your marketplace.
     */
    public function subscribe(Request $request, string $accountId)
    {
        // ⚠️  PLACEHOLDER — set STRIPE_DEMO_PRICE_ID in .env
        //     This must be a recurring price (billing_scheme: per_unit, type: recurring)
        //     created on YOUR PLATFORM (not the connected account)
        $priceId = env('STRIPE_DEMO_PRICE_ID');

        if (empty($priceId)) {
            return back()->withErrors([
                'stripe' => 'STRIPE_DEMO_PRICE_ID is not set. Create a recurring price in your Stripe dashboard and add it to .env',
            ]);
        }

        $stripeClient = $this->stripe();

        try {
            // NOTE: No `stripe_account` header here — this session runs on
            // YOUR PLATFORM, not the connected account. The connected account
            // is the *customer* via `customer_account`.
            $session = $stripeClient->checkout->sessions->create([
                // `customer_account` links this subscription to the connected account.
                // With V2 accounts, acct_xxx doubles as a customer ID on your platform.
                // ⚠️  Do NOT use `.customer` for V2 accounts — use `.customer_account`
                'customer_account' => $accountId,

                'mode' => 'subscription',

                'line_items' => [
                    [
                        'price'    => $priceId,
                        'quantity' => 1,
                    ],
                ],

                'success_url' => route('stripe-demo.manage', $accountId) . '?subscribed=1',
                'cancel_url'  => route('stripe-demo.manage', $accountId),
            ]);

            return redirect($session->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return back()->withErrors(['stripe' => $e->getMessage()]);
        }
    }

    /**
     * GET /stripe-demo/manage/{accountId}/portal
     *
     * Redirects the connected account owner to the Stripe Billing Portal
     * so they can manage/cancel their platform subscription.
     *
     * Uses `customer_account` (the acct_xxx ID) instead of a customer ID,
     * because V2 accounts act as their own customers on the platform.
     */
    public function billingPortal(Request $request, string $accountId)
    {
        $stripeClient = $this->stripe();

        try {
            $portalSession = $stripeClient->billingPortal->sessions->create([
                // V2: use customer_account, not customer
                'customer_account' => $accountId,
                // Where to send the user after they leave the portal
                'return_url'       => route('stripe-demo.manage', $accountId),
            ]);

            return redirect($portalSession->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return back()->withErrors(['stripe' => $e->getMessage()]);
        }
    }
}
