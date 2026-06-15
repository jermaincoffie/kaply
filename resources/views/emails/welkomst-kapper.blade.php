<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welkom bij Kaply</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%); padding: 36px 32px 28px; text-align: center; }
    .header h1 { color: #fff; margin: 0 0 6px; font-size: 24px; font-weight: 700; }
    .header p { color: rgba(255,255,255,.85); margin: 0; font-size: 14px; }
    .body { padding: 32px 32px 24px; }
    .body p { font-size: 15px; line-height: 1.6; color: #374151; margin: 0 0 16px; }
    .steps { margin: 24px 0; }
    .step { display: flex; gap: 12px; align-items: flex-start; margin-bottom: 14px; font-size: 14px; }
    .step-num { width: 24px; height: 24px; border-radius: 50%; background: #2563eb; color: #fff; font-weight: 700; font-size: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
    .step-text { color: #374151; line-height: 1.5; }
    .btn { display: block; background: #2563eb; color: #fff !important; text-align: center; padding: 14px 24px; border-radius: 8px; font-weight: 600; font-size: 15px; text-decoration: none; margin: 24px 0 0; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Welkom bij Kaply!</h1>
        <p>{{ $salonNaam }} is aangemeld</p>
    </div>
    <div class="body">
        <p>Hoi {{ $user->name }},</p>
        <p>Je account is aangemaakt en je <strong>gratis proefperiode van 14 dagen</strong> staat klaar. Om je salon zichtbaar te maken op Kaply, zijn er twee stappen:</p>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text"><strong>Start je gratis proefperiode</strong> – 14 dagen gratis, daarna €20 per maand. Geen creditcard nodig om te starten.</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text"><strong>Goedkeuring admin</strong> – je profiel gaat live zodra een admin je account heeft goedgekeurd.</div>
            </div>
        </div>

        <a href="{{ route('kapper.subscription.checkout') }}" class="btn">Start gratis proefperiode</a>
    </div>
    <div class="footer">
        Kaply · Online boekingsplatform voor kappers<br>
        Je ontvangt deze mail omdat je zojuist een account hebt aangemaakt.
    </div>
</div>
</body>
</html>
