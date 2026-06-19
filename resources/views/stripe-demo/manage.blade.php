<x-stripe-demo-layout title="Manage — {{ $accountId }}">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
        <a href="{{ route('stripe-demo.onboard', $accountId) }}" style="color:#635bff;font-size:13px;text-decoration:none;">← Onboarding</a>
    </div>

    <h1>Manage Account</h1>
    <p class="subtitle">
        Account: <span class="code">{{ $accountId }}</span>
    </p>

    @if(request()->query('subscribed'))
    <div class="alert alert-success">✓ Subscription activated! The account is now subscribed to your platform plan.</div>
    @endif

    {{-- ──────────────────────────────────────────── --}}
    {{-- SECTION 1: Create a product --}}
    {{-- ──────────────────────────────────────────── --}}

    <div class="card">
        <h2>Create a Product</h2>
        <p>
            Products are created on the <strong>connected account</strong> (not your platform).
            The <span class="code">Stripe-Account</span> header scopes this request to their catalog.
        </p>

        <form method="POST" action="{{ route('stripe-demo.create-product', $accountId) }}">
            @csrf
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', 'Hand-crafted Mug') }}" required placeholder="e.g. Handmade Ceramic Mug">
            </div>
            <div class="form-group">
                <label for="description">Description (optional)</label>
                <textarea id="description" name="description" rows="2" placeholder="Short product description…">{{ old('description') }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" value="{{ old('price', '29.99') }}" min="0.50" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="currency">Currency</label>
                    <select id="currency" name="currency">
                        <option value="usd" {{ old('currency') === 'usd' ? 'selected' : '' }}>USD ($)</option>
                        <option value="eur" {{ old('currency', 'eur') === 'eur' ? 'selected' : '' }}>EUR (€)</option>
                        <option value="gbp" {{ old('currency') === 'gbp' ? 'selected' : '' }}>GBP (£)</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                ＋ Create Product on Connected Account
            </button>
        </form>
    </div>

    {{-- ──────────────────────────────────────────── --}}
    {{-- SECTION 2: Existing products --}}
    {{-- ──────────────────────────────────────────── --}}

    <div class="card">
        <h2>Products on this Account</h2>

        @if(!$products || count($products->data) === 0)
            <div class="alert alert-info">No products yet. Create one above to see it here.</div>
        @else
            <div class="grid-3">
                @foreach($products->data as $product)
                    @php
                        $price  = $product->default_price;
                        $amount = $price ? number_format($price->unit_amount / 100, 2) : null;
                        $curr   = strtoupper($price->currency ?? 'USD');
                    @endphp
                    <div class="product-card">
                        <div class="product-name">{{ $product->name }}</div>
                        @if($amount)
                            <div class="product-price">{{ $curr }} {{ $amount }}</div>
                        @endif
                        @if($product->description)
                            <div class="product-desc">{{ Str::limit($product->description, 80) }}</div>
                        @endif
                        <div class="code" style="font-size:11px;margin-bottom:10px;">{{ $product->id }}</div>
                        @if($price)
                            {{-- Direct to store so customers can buy it --}}
                            <a href="{{ route('stripe-demo.store', $accountId) }}" class="btn btn-secondary btn-sm">
                                View in Store ↗
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <hr class="divider">

    {{-- ──────────────────────────────────────────── --}}
    {{-- SECTION 3: Platform subscription --}}
    {{-- ──────────────────────────────────────────── --}}

    <div class="card">
        <h2>Platform Subscription</h2>
        <p>
            Subscribe this connected account to your platform plan.
            Unlike product purchases, this charge runs on <strong>your platform</strong>
            using the connected account as the customer via <span class="code">customer_account</span>.
        </p>
        <p>
            The V2 <span class="code">customer_account: "acct_..."</span> field replaces the
            V1 <span class="code">customer: "cus_..."</span> — the account's own ID serves as
            both the account and the customer on your platform.
        </p>

        @if(!$subscriptionPriceId)
            <div class="alert alert-info" style="margin-bottom:16px;">
                ⚠️  <strong>STRIPE_DEMO_PRICE_ID</strong> is not set in your .env file.
                Create a recurring price in your Stripe Dashboard and add it:
                <span class="code">STRIPE_DEMO_PRICE_ID=price_xxx</span>
            </div>
        @endif

        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <form method="POST" action="{{ route('stripe-demo.subscribe', $accountId) }}">
                @csrf
                <button type="submit" class="btn btn-primary" {{ !$subscriptionPriceId ? 'disabled' : '' }}>
                    Subscribe to Platform Plan →
                </button>
            </form>

            <a href="{{ route('stripe-demo.billing-portal', $accountId) }}" class="btn btn-secondary">
                Manage Subscription (Portal)
            </a>
        </div>
    </div>

    <hr class="divider">

    {{-- ──────────────────────────────────────────── --}}
    {{-- SECTION 4: View storefront --}}
    {{-- ──────────────────────────────────────────── --}}

    <div class="card">
        <h2>Customer-facing Storefront</h2>
        <p>
            Share this link with customers so they can browse products and make purchases.
            Payments go directly to the connected account (Direct Charge).
        </p>
        {{-- ⚠️  PRODUCTION NOTE: Replace the acct_xxx in the URL with a human-readable slug --}}
        <a href="{{ route('stripe-demo.store', $accountId) }}" class="btn btn-primary">
            Open Storefront →
        </a>
        <div style="margin-top:12px;font-size:13px;color:#697386;">
            URL: <span class="code">{{ route('stripe-demo.store', $accountId) }}</span><br>
            ⚠️  In production, use a slug or subdomain instead of exposing the raw <span class="code">acct_xxx</span> ID in the URL.
        </div>
    </div>

</x-stripe-demo-layout>
