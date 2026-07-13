<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Je salon staat live op Kaply</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 36px 32px 28px; text-align: center; }
    .header h1 { color: #fff; margin: 0 0 6px; font-size: 24px; font-weight: 700; }
    .header p { color: rgba(255,255,255,.85); margin: 0; font-size: 14px; }
    .body { padding: 32px 32px 24px; }
    .body p { font-size: 15px; line-height: 1.6; color: #374151; margin: 0 0 16px; }
    .highlight { background: #f0fdf4; border-left: 3px solid #10b981; padding: 14px 16px; border-radius: 0 8px 8px 0; margin: 20px 0; font-size: 14px; color: #065f46; }
    .btn { display: block; background: #059669; color: #fff !important; text-align: center; padding: 14px 24px; border-radius: 8px; font-weight: 600; font-size: 15px; text-decoration: none; margin: 24px 0 0; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Je salon staat live! 🎉</h1>
        <p>{{ $salonNaam }} is goedgekeurd</p>
    </div>
    <div class="body">
        <p>Hoi {{ $user->name }},</p>
        <p>Goed nieuws — je salon is goedgekeurd en staat nu live op Kaply. Klanten kunnen vanaf nu bij jou een afspraak boeken.</p>

        <div class="highlight">
            <strong>Wat kun je nu doen?</strong><br>
            Stel je openingstijden in, voeg je diensten toe en pas je profiel aan. Dan ben je klaar om klanten te ontvangen.
        </div>

        <a href="{{ route('kapper.dashboard') }}" class="btn">Ga naar mijn dashboard</a>
    </div>
    <div class="footer">
        Kaply · Online boekingsplatform voor kappers<br>
        Vragen? Mail ons op info@kaply.nl
    </div>
</div>
</body>
</html>
