<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Plek vrijgekomen</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: #10b981; padding: 32px 32px 24px; text-align: center; }
    .header h1 { color: #fff; margin: 0; font-size: 22px; font-weight: 700; }
    .header p { color: rgba(255,255,255,.8); margin: 6px 0 0; font-size: 14px; }
    .body { padding: 28px 32px; }
    .btn { display: block; background: #10b981; color: #fff; text-decoration: none; text-align: center; font-size: 15px; font-weight: 700; padding: 14px 24px; border-radius: 10px; margin: 24px 0 0; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
    .uitschrijf { display: block; margin-top: 8px; font-size: 12px; color: #9ca3af; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Er is een plek vrijgekomen! 🎉</h1>
        <p>{{ $kapper->salon_naam }}</p>
    </div>
    <div class="body">
        <p style="font-size:15px; color:#374151; margin:0 0 8px;">Goed nieuws!</p>
        <p style="font-size:14px; color:#6b7280; margin:0;">Er is zojuist een afspraak vrijgekomen bij <strong>{{ $kapper->salon_naam }}</strong>. Wees er snel bij — op=op.</p>
        <a href="{{ url('/kapper/' . $kapper->slug) }}" class="btn">Boek nu je afspraak →</a>
    </div>
    <div class="footer">
        Je ontvangt deze mail omdat je op de wachtlijst staat bij {{ $kapper->salon_naam }}.
    </div>
</div>
</body>
</html>
