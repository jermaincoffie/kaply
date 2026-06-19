<x-stripe-demo-layout title="Onboarding — {{ $accountId }}">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
        <a href="{{ route('stripe-demo.dashboard') }}" style="color:#635bff;font-size:13px;text-decoration:none;">← Dashboard</a>
    </div>

    <h1>Onboarding Status</h1>
    <p class="subtitle">
        Account ID: <span class="code">{{ $accountId }}</span>
        &nbsp;—&nbsp;Status is fetched live from the Stripe API, never from cache.
    </p>

    @if(request()->query('onboarded'))
    <div class="alert alert-success">
        ✓ You've returned from Stripe onboarding! The status below reflects the latest information.
    </div>
    @endif

    {{-- Capability status --}}
    <div class="card">
        <h2>Payment Capability</h2>

        <div class="status-row">
            <div>
                <div style="font-weight:500;font-size:14px;">Card Payments</div>
                <div style="font-size:13px;color:#697386;">Required to accept card transactions</div>
            </div>
            @if($readyToProcessPayments)
                <span class="badge badge-active">✓ Active</span>
            @else
                <span class="badge badge-pending">Pending</span>
            @endif
        </div>

        <div class="status-row">
            <div>
                <div style="font-weight:500;font-size:14px;">Onboarding Requirements</div>
                <div style="font-size:13px;color:#697386;">KYC, bank account, and Stripe ToS</div>
            </div>
            @if($onboardingComplete)
                <span class="badge badge-active">✓ Complete</span>
            @else
                <span class="badge badge-error">Incomplete</span>
            @endif
        </div>
    </div>

    {{-- Onboarding action --}}
    <div class="card">
        <h2>
            @if($onboardingComplete && $readyToProcessPayments)
                Onboarding Complete
            @else
                Complete Onboarding to Collect Payments
            @endif
        </h2>

        @if($onboardingComplete && $readyToProcessPayments)
            <div class="alert alert-success" style="margin-bottom:16px;">
                ✓ This account is fully set up and ready to accept payments.
            </div>
            <div style="display:flex;gap:12px;">
                <a href="{{ route('stripe-demo.manage', $accountId) }}" class="btn btn-primary">
                    Manage Account & Products →
                </a>
                <a href="{{ route('stripe-demo.store', $accountId) }}" class="btn btn-secondary">
                    View Storefront ↗
                </a>
            </div>
        @else
            <p>
                To collect payments, the connected account must complete Stripe's
                KYC process, add a bank account, and agree to the Stripe Terms of Service.
            </p>
            <p style="font-size:13px;color:#697386;margin-bottom:20px;">
                Clicking the button below creates a fresh Account Link (they expire quickly)
                and redirects the seller to Stripe's hosted onboarding flow.
            </p>

            {{-- Account Link creation — always create a new link, never store the URL --}}
            <form method="POST" action="{{ route('stripe-demo.create-account-link', $accountId) }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    @if($onboardingComplete)
                        Resume Onboarding →
                    @else
                        Onboard to Collect Payments →
                    @endif
                </button>
            </form>
        @endif
    </div>

    {{-- Raw account data for debugging --}}
    <div class="card">
        <h2>Account Details</h2>
        <div style="font-size:13px;color:#697386;margin-bottom:12px;">
            Raw data from <span class="code">v2->core->accounts->retrieve()</span> with
            <span class="code">include: ['configuration.merchant', 'requirements']</span>
        </div>
        <pre style="background:#f6f9fc;border:1px solid #e6ebf1;border-radius:6px;padding:16px;font-size:12px;overflow-x:auto;white-space:pre-wrap;">{{ json_encode($account, JSON_PRETTY_PRINT) }}</pre>
    </div>

</x-stripe-demo-layout>
