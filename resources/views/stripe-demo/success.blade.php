<x-stripe-demo-layout title="Payment Successful">

    <div style="max-width:520px;margin:0 auto;text-align:center;padding:40px 0;">

        {{-- Success icon --}}
        <div style="width:72px;height:72px;background:#d7f7e6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
            <svg width="36" height="36" fill="none" stroke="#1a7a4a" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
        </div>

        <h1 style="font-size:28px;margin-bottom:8px;">Payment Successful!</h1>
        <p style="color:#697386;margin-bottom:32px;">
            Thank you for your purchase. Your payment was processed directly
            by the seller via Stripe (Direct Charge).
        </p>

        @if($session)
            <div class="card" style="text-align:left;margin-bottom:24px;">
                <h3>Order Summary</h3>
                @foreach($session->line_items->data as $item)
                    <div class="status-row">
                        <div>
                            <div style="font-weight:500;">{{ $item->description }}</div>
                            <div style="font-size:13px;color:#697386;">Qty: {{ $item->quantity }}</div>
                        </div>
                        <div style="font-weight:600;color:#635bff;">
                            {{ strtoupper($item->currency) }} {{ number_format($item->amount_total / 100, 2) }}
                        </div>
                    </div>
                @endforeach
                <div style="margin-top:12px;padding-top:12px;border-top:1px solid #e6ebf1;display:flex;justify-content:space-between;">
                    <strong>Total Paid</strong>
                    <strong style="color:#635bff;">
                        {{ strtoupper($session->currency) }} {{ number_format($session->amount_total / 100, 2) }}
                    </strong>
                </div>
            </div>
        @endif

        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            @if($accountId)
                <a href="{{ route('stripe-demo.store', $accountId) }}" class="btn btn-primary">
                    ← Continue Shopping
                </a>
            @endif
            <a href="{{ route('stripe-demo.dashboard') }}" class="btn btn-secondary">
                Dashboard
            </a>
        </div>

        <div style="margin-top:32px;padding-top:24px;border-top:1px solid #e6ebf1;">
            <p style="font-size:12px;color:#aaa;">
                This was a test payment. No real money was charged.
                Stripe Account: <span class="code">{{ $accountId }}</span>
            </p>
        </div>
    </div>

</x-stripe-demo-layout>
