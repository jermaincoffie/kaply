<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Stripe Connect Demo' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f6f9fc; color: #1a1a2e; min-height: 100vh; }
        .demo-banner { background: #635bff; color: #fff; text-align: center; padding: 8px; font-size: 13px; letter-spacing: 0.01em; }
        .demo-banner a { color: #d7d3ff; text-decoration: none; margin-left: 8px; }
        .nav { background: #fff; border-bottom: 1px solid #e6ebf1; padding: 0 24px; display: flex; align-items: center; height: 56px; gap: 24px; }
        .nav-logo { font-weight: 700; font-size: 18px; color: #635bff; text-decoration: none; }
        .nav a { color: #425466; text-decoration: none; font-size: 14px; }
        .nav a:hover { color: #635bff; }
        .container { max-width: 900px; margin: 0 auto; padding: 32px 24px; }
        h1 { font-size: 24px; font-weight: 700; color: #1a1a2e; margin-bottom: 8px; }
        h2 { font-size: 18px; font-weight: 600; color: #1a1a2e; margin-bottom: 16px; }
        h3 { font-size: 15px; font-weight: 600; color: #1a1a2e; margin-bottom: 12px; }
        p { color: #425466; font-size: 14px; line-height: 1.6; margin-bottom: 16px; }
        .subtitle { color: #697386; font-size: 15px; margin-bottom: 32px; }
        .card { background: #fff; border: 1px solid #e6ebf1; border-radius: 8px; padding: 24px; margin-bottom: 24px; }
        .card-sm { background: #fff; border: 1px solid #e6ebf1; border-radius: 8px; padding: 16px; }
        label { display: block; font-size: 13px; font-weight: 500; color: #425466; margin-bottom: 4px; }
        input, textarea, select { width: 100%; padding: 9px 12px; border: 1px solid #d8dde6; border-radius: 6px; font-size: 14px; color: #1a1a2e; background: #fff; outline: none; transition: border-color 0.15s; }
        input:focus, textarea:focus, select:focus { border-color: #635bff; box-shadow: 0 0 0 3px rgba(99,91,255,0.12); }
        .form-group { margin-bottom: 16px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; border: none; text-decoration: none; transition: all 0.15s; }
        .btn-primary { background: #635bff; color: #fff; }
        .btn-primary:hover { background: #5147e6; }
        .btn-secondary { background: #fff; color: #425466; border: 1px solid #d8dde6; }
        .btn-secondary:hover { background: #f6f9fc; }
        .btn-success { background: #00c853; color: #fff; }
        .btn-danger { background: #e53935; color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 13px; }
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 100px; font-size: 12px; font-weight: 500; }
        .badge-active { background: #d7f7e6; color: #1a7a4a; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-error { background: #fde8e8; color: #b71c1c; }
        .badge-info { background: #e8eafd; color: #3730a3; }
        .alert { padding: 12px 16px; border-radius: 6px; font-size: 14px; margin-bottom: 16px; }
        .alert-success { background: #d7f7e6; color: #1a7a4a; border: 1px solid #a3e4b9; }
        .alert-error { background: #fde8e8; color: #b71c1c; border: 1px solid #f5c6c6; }
        .alert-info { background: #e8eafd; color: #3730a3; border: 1px solid #c7d2fe; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .product-card { border: 1px solid #e6ebf1; border-radius: 8px; padding: 16px; background: #fff; }
        .product-name { font-weight: 600; font-size: 15px; margin-bottom: 4px; }
        .product-price { color: #635bff; font-size: 18px; font-weight: 700; margin-bottom: 8px; }
        .product-desc { color: #697386; font-size: 13px; margin-bottom: 12px; }
        .divider { border: none; border-top: 1px solid #e6ebf1; margin: 24px 0; }
        .status-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
        .status-row:last-child { border-bottom: none; }
        .code { background: #f6f9fc; border: 1px solid #e6ebf1; border-radius: 4px; padding: 2px 6px; font-family: monospace; font-size: 13px; color: #635bff; }
        @media (max-width: 600px) { .form-row, .grid-2, .grid-3 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<div class="demo-banner">
    ⚡ Stripe Connect Sample Integration — Test Mode
    <a href="https://stripe.com/docs/connect" target="_blank">Docs ↗</a>
</div>

<nav class="nav">
    <a href="{{ route('stripe-demo.dashboard') }}" class="nav-logo">Connect Demo</a>
    <a href="{{ route('stripe-demo.dashboard') }}">Dashboard</a>
    @if(session('stripe_demo_accounts'))
        @php $firstAccount = session('stripe_demo_accounts')[0] @endphp
        <a href="{{ route('stripe-demo.store', $firstAccount['id']) }}">Storefront</a>
        <a href="{{ route('stripe-demo.manage', $firstAccount['id']) }}">Manage</a>
    @endif
</nav>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{ $slot }}
</div>

</body>
</html>
