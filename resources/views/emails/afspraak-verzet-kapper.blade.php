<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Afspraak verzet</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: #f59e0b; padding: 32px 32px 24px; text-align: center; }
    .header h1 { color: #fff; margin: 0; font-size: 22px; font-weight: 700; }
    .header p { color: rgba(255,255,255,.8); margin: 6px 0 0; font-size: 14px; }
    .body { padding: 28px 32px; }
    .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
    .row:last-child { border-bottom: none; }
    .label { color: #6b7280; }
    .value { font-weight: 600; color: #111827; text-align: right; }
    .value.oud { text-decoration: line-through; color: #9ca3af; font-weight: 400; }
    .value.nieuw { color: #059669; }
    .badge { display: inline-block; background: #fffbeb; color: #d97706; font-size: 13px; font-weight: 600; padding: 4px 12px; border-radius: 999px; margin-bottom: 20px; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
    .arrow { color: #d97706; margin: 0 6px; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Afspraak verzet</h1>
        <p>{{ $afspraak->kapper->salon_naam }}</p>
    </div>
    <div class="body">
        <div style="text-align:center; margin-bottom: 20px;">
            <span class="badge">↕ Verzet door klant</span>
        </div>
        <div class="row">
            <span class="label">Klant</span>
            <span class="value">{{ $afspraak->klant->name }}</span>
        </div>
        <div class="row">
            <span class="label">Dienst</span>
            <span class="value">{{ $afspraak->dienst->naam }}</span>
        </div>
        <div class="row">
            <span class="label">Was</span>
            <span class="value oud">{{ \Carbon\Carbon::parse($oudeDatum)->translatedFormat('l d F') }} om {{ $oudeTijd }}</span>
        </div>
        <div class="row">
            <span class="label">Nieuw</span>
            <span class="value nieuw">{{ $afspraak->datum->translatedFormat('l d F Y') }} om {{ $afspraak->start_tijd }}</span>
        </div>
    </div>
    <div class="footer">
        Bekijk je agenda via het Kaply dashboard.
    </div>
</div>
</body>
</html>
