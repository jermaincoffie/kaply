<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hoe was je bezoek?</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: #3b82f6; padding: 32px 32px 24px; text-align: center; }
    .header h1 { color: #fff; margin: 0; font-size: 22px; font-weight: 700; }
    .header p { color: rgba(255,255,255,.85); margin: 6px 0 0; font-size: 14px; }
    .body { padding: 28px 32px; }
    .stars { text-align: center; font-size: 36px; letter-spacing: 4px; margin: 4px 0 20px; }
    .intro { font-size: 15px; color: #374151; line-height: 1.6; margin-bottom: 24px; text-align: center; }
    .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
    .row:last-child { border-bottom: none; }
    .label { color: #6b7280; }
    .value { font-weight: 600; color: #111827; text-align: right; }
    .cta { display: block; background: #3b82f6; color: #fff !important; text-decoration: none; font-size: 15px; font-weight: 700; padding: 14px 32px; border-radius: 10px; text-align: center; margin: 28px 0 0; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Hoe was je bezoek?</h1>
        <p>{{ $afspraak->kapper->salon_naam }}</p>
    </div>
    <div class="body">
        <div class="stars">⭐⭐⭐⭐⭐</div>
        <p class="intro">
            Bedankt voor je bezoek bij <strong>{{ $afspraak->kapper->salon_naam }}</strong>!<br>
            We zijn benieuwd naar je ervaring. Laat een beoordeling achter en help anderen de juiste kapper te vinden.
        </p>
        <div class="row">
            <span class="label">Dienst</span>
            <span class="value">{{ $afspraak->dienst->naam }}</span>
        </div>
        <div class="row">
            <span class="label">Datum</span>
            <span class="value">{{ $afspraak->datum->translatedFormat('l d F Y') }}</span>
        </div>
        <a href="{{ route('kapper.profiel', $afspraak->kapper->slug) }}" class="cta">
            Laat een beoordeling achter →
        </a>
    </div>
    <div class="footer">
        Je ontvangt dit bericht omdat je een afspraak had bij {{ $afspraak->kapper->salon_naam }} via Kaply.
    </div>
</div>
</body>
</html>
