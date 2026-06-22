<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Aanvraag niet goedgekeurd</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: #6b7280; padding: 36px 32px 28px; text-align: center; }
    .header h1 { color: #fff; margin: 0 0 6px; font-size: 22px; font-weight: 700; }
    .header p { color: rgba(255,255,255,.8); margin: 0; font-size: 14px; }
    .body { padding: 32px 32px 24px; }
    .body p { font-size: 15px; line-height: 1.6; color: #374151; margin: 0 0 16px; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
    a { color: #2563eb; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Aanvraag niet goedgekeurd</h1>
        <p>{{ $salonNaam }}</p>
    </div>
    <div class="body">
        <p>Hoi {{ $naam }},</p>
        <p>Bedankt voor je aanmelding bij Kaply. Na beoordeling hebben we besloten je aanvraag op dit moment niet goed te keuren.</p>
        <p>Heb je vragen of wil je weten wat de reden is? Neem dan contact op via <a href="mailto:info@kaply.nl">info@kaply.nl</a> — we helpen je graag verder.</p>
    </div>
    <div class="footer">
        Kaply · Online boekingsplatform voor kappers
    </div>
</div>
</body>
</html>
