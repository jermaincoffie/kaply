<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Betaling mislukt</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: #dc2626; padding: 36px 32px 28px; text-align: center; }
    .header h1 { color: #fff; margin: 0 0 6px; font-size: 22px; font-weight: 700; }
    .header p { color: rgba(255,255,255,.8); margin: 0; font-size: 14px; }
    .body { padding: 32px 32px 24px; }
    .body p { font-size: 15px; line-height: 1.6; color: #374151; margin: 0 0 16px; }
    .btn { display: inline-block; background: #2563eb; color: #fff !important; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Betaling mislukt</h1>
        <p>{{ $kapper->salon_naam }}</p>
    </div>
    <div class="body">
        <p>Hoi {{ $kapper->user->voornaam ?? $kapper->user->name }},</p>
        <p>De betaling van je Kaply abonnement is helaas niet gelukt. Controleer je betaalmethode zodat je salon actief blijft voor klanten.</p>
        <p style="text-align:center; margin: 24px 0;">
            <a href="{{ route('subscription.portal') }}" class="btn">Betaalmethode bijwerken</a>
        </p>
        <p>Vragen? Mail ons op <a href="mailto:info@kaply.nl">info@kaply.nl</a>.</p>
    </div>
    <div class="footer">
        Kaply · Online boekingsplatform voor kappers
    </div>
</div>
</body>
</html>
