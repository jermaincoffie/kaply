<x-stripe-demo-layout title="Storefront">

    {{--
        PRODUCTION NOTE:
        This URL currently uses the raw Stripe account ID (acct_xxx) as the identifier.
        In production, use a human-readable slug, subdomain, or your own internal ID
        and map it to the Stripe account ID on the server side.

        Example:
            /store/acme-shop  →  look up acct_xxx from your DB by slug
            acme-shop.yourplatform.com  →  resolve from subdomain
    --}}

    <div style="text-align:center;margin-bottom:32px;">
        <h1 style="font-size:32px;margin-bottom:8px;">Welcome to the Store</h1>
        <p style="font-size:15px;color:#697386;">Browse and purchase products from this seller</p>
        <div style="margin-top:8px;font-size:12px;color:#aaa;" class="code">{{ $accountId }}</div>
    </div>

    @if(isset($error))
        <div class="alert alert-error">
            Could not load products: {{ $error }}
        </div>
    @elseif(!$products || count($products->data) === 0)
        <div class="alert alert-info" style="text-align:center;">
            This store doesn't have any products yet.
            <a href="{{ route('stripe-demo.manage', $accountId) }}" style="color:#635bff;">Add products →</a>
        </div>
    @else
        <div class="grid-3">
            @foreach($products->data as $product)
                @php
                    $price    = $product->default_price;
                    $amount   = $price ? number_format($price->unit_amount / 100, 2) : null;
                    $curr     = strtoupper($price->currency ?? 'USD');
                    $symbol   = match(strtolower($price->currency ?? 'usd')) {
                        'eur'  => '€',
                        'gbp'  => '£',
                        default => '$',
                    };
                @endphp
                <div class="product-card">
                    {{-- Product image placeholder --}}
                    <div style="height:140px;background:linear-gradient(135deg,#f6f9fc,#e8eafd);border-radius:6px;margin-bottom:12px;display:flex;align-items:center;justify-content:center;">
                        <svg width="40" height="40" fill="none" stroke="#635bff" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                        </svg>
                    </div>

                    <div class="product-name">{{ $product->name }}</div>
                    @if($amount)
                        <div class="product-price">{{ $symbol }}{{ $amount }}</div>
                    @endif
                    @if($product->description)
                        <div class="product-desc">{{ Str::limit($product->description, 100) }}</div>
                    @endif

                    @if($price)
                        {{-- Checkout button — POST to create a Direct Charge session --}}
                        <form method="POST" action="{{ route('stripe-demo.checkout', [$accountId, $price->id]) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="width:100%;">
                                Buy Now — {{ $symbol }}{{ $amount }}
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary" style="width:100%;cursor:not-allowed;" disabled>
                            Not available
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div style="text-align:center;margin-top:40px;padding-top:24px;border-top:1px solid #e6ebf1;">
        <p style="font-size:13px;color:#aaa;">
            Payments processed securely by <strong>Stripe</strong> on behalf of this seller.
        </p>
    </div>

</x-stripe-demo-layout>
