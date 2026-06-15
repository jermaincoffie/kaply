<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hoe gaat het bij {{ $kapper->salon_naam }}?</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%); padding: 36px 32px 28px; text-align: center; }
    .header h1 { color: #fff; margin: 0 0 6px; font-size: 22px; font-weight: 700; }
    .header p { color: rgba(255,255,255,.85); margin: 0; font-size: 14px; }
    .body { padding: 32px 32px 24px; }
    .body p { font-size: 15px; line-height: 1.6; color: #374151; margin: 0 0 16px; }
    .tip { display: flex; gap: 12px; align-items: flex-start; margin-bottom: 14px; padding: 14px; background: #f8faff; border-radius: 8px; border: 1px solid #e0eaff; }
    .tip-icon { width: 32px; height: 32px; border-radius: 8px; background: #2563eb; color: #fff; font-size: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .tip-text { font-size: 14px; color: #374151; line-height: 1.5; }
    .tip-text strong { display: block; color: #111827; margin-bottom: 2px; }
    .btn { display: block; background: #2563eb; color: #fff !important; text-align: center; padding: 14px 24px; border-radius: 8px; font-weight: 600; font-size: 15px; text-decoration: none; margin: 24px 0 0; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Welkom bij Kaply!</h1>
        <p>Je bent al 3 dagen actief — zo haal je er alles uit</p>
    </div>
    <div class="body">
        <p>Hoi {{ $kapper->user->name }},</p>
        <p>Je bent al 3 dagen bezig met {{ $kapper->salon_naam }} op Kaply. Hier zijn 3 dingen die direct meer boekingen opleveren:</p>

        <div class="tip">
            <div class="tip-icon">1</div>
            <div class="tip-text">
                <strong>Deel je boekingslink</strong>
                Stuur je Kaply-link via Instagram, WhatsApp of als linkje in je bio. Elke klik is een potentiële boeking.
            </div>
        </div>
        <div class="tip">
            <div class="tip-icon">2</div>
            <div class="tip-text">
                <strong>Voeg foto's toe aan je galerij</strong>
                Kappers met foto's ontvangen tot 3x meer boekingen. Upload je beste werk in je dashboard.
            </div>
        </div>
        <div class="tip">
            <div class="tip-icon">3</div>
            <div class="tip-text">
                <strong>Stel je beschikbaarheid in</strong>
                Zorg dat klanten direct de juiste tijdsloten zien. Controleer of je openingstijden kloppen.
            </div>
        </div>

        <a href="{{ route('kapper.dashboard') }}" class="btn">Naar mijn dashboard</a>
    </div>
    <div class="footer">
        Kaply · Online boekingsplatform voor kappers<br>
        Je ontvangt deze mail als kapper op Kaply.
    </div>
</div>
</body>
</html>
