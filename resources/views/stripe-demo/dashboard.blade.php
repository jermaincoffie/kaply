<x-stripe-demo-layout title="Connect Demo — Dashboard">

    <h1>Stripe Connect Demo</h1>
    <p class="subtitle">
        Create connected accounts, onboard sellers, build storefronts, and process payments.
        This is a sample integration — all data is in test mode.
    </p>

    {{-- Step 1: Create a connected account --}}
    <div class="card">
        <h2>Step 1 — Create a Connected Account</h2>
        <p>
            Connected accounts represent sellers on your platform. Using the V2 API, we create
            accounts that can accept card payments and be subscribed to platform plans.
        </p>

        <form method="POST" action="{{ route('stripe-demo.create-account') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Business / Display Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', 'Acme Shop') }}" required placeholder="e.g. Acme Shop">
                </div>
                <div class="form-group">
                    <label for="email">Contact Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', 'seller@example.com') }}" required placeholder="seller@example.com">
                </div>
            </div>
            <p style="font-size:13px;color:#697386;margin-bottom:16px;">
                In production, pre-fill these from your user's profile and map
                the resulting <span class="code">acct_xxx</span> to your user in the database.
            </p>
            <button type="submit" class="btn btn-primary">
                ＋ Create Connected Account
            </button>
        </form>
    </div>

    {{-- Step 2: Existing accounts --}}
    @if(count($accountIds) > 0)
    <div class="card">
        <h2>Your Demo Accounts</h2>
        <p style="font-size:13px;color:#697386;margin-bottom:16px;">
            Stored in session for this demo. In production, load from your database.
        </p>
        @foreach($accountIds as $account)
        <div class="status-row">
            <div>
                <div style="font-weight:600;font-size:14px;">{{ $account['name'] }}</div>
                <div class="code" style="margin-top:4px;">{{ $account['id'] }}</div>
            </div>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('stripe-demo.onboard', $account['id']) }}" class="btn btn-secondary btn-sm">
                    Onboarding Status
                </a>
                <a href="{{ route('stripe-demo.manage', $account['id']) }}" class="btn btn-secondary btn-sm">
                    Manage
                </a>
                <a href="{{ route('stripe-demo.store', $account['id']) }}" class="btn btn-primary btn-sm">
                    Storefront ↗
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Webhook setup instructions --}}
    <div class="card">
        <h2>Webhook Setup</h2>
        <p>Two webhook endpoints are included in this demo:</p>

        <div style="margin-bottom:20px;">
            <h3>1. V2 Connected Account Events (thin events)</h3>
            <p>Endpoint: <span class="code">POST /stripe-demo/webhook/connect</span></p>
            <p>Use the Stripe CLI to forward thin events locally:</p>
            <div style="background:#1a1a2e;color:#a8f3c8;padding:16px;border-radius:6px;font-family:monospace;font-size:13px;overflow-x:auto;margin-bottom:8px;">
                stripe listen \<br>
                &nbsp;&nbsp;--thin-events 'v2.core.account[requirements].updated,v2.core.account[configuration.merchant].capability_status_updated,v2.core.account[configuration.customer].capability_status_updated' \<br>
                &nbsp;&nbsp;--forward-thin-to http://localhost:8000/stripe-demo/webhook/connect
            </div>
            <p style="font-size:13px;color:#697386;">Set the signing secret as <span class="code">STRIPE_DEMO_CONNECT_WEBHOOK_SECRET</span> in your .env</p>
        </div>

        <div>
            <h3>2. V1 Subscription Events (standard webhooks)</h3>
            <p>Endpoint: <span class="code">POST /stripe-demo/webhook/subscriptions</span></p>
            <div style="background:#1a1a2e;color:#a8f3c8;padding:16px;border-radius:6px;font-family:monospace;font-size:13px;overflow-x:auto;margin-bottom:8px;">
                stripe listen \<br>
                &nbsp;&nbsp;--events 'customer.subscription.updated,customer.subscription.deleted,invoice.payment_succeeded,invoice.payment_failed' \<br>
                &nbsp;&nbsp;--forward-to http://localhost:8000/stripe-demo/webhook/subscriptions
            </div>
            <p style="font-size:13px;color:#697386;">Set the signing secret as <span class="code">STRIPE_DEMO_SUBSCRIPTION_WEBHOOK_SECRET</span> in your .env</p>
        </div>
    </div>

</x-stripe-demo-layout>
