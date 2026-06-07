<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Herinnering afspraak</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 32px 32px 24px; text-align: center; }
    .header h1 { color: #fff; margin: 0; font-size: 22px; font-weight: 700; }
    .header p { color: rgba(255,255,255,.85); margin: 6px 0 0; font-size: 14px; }
    .body { padding: 28px 32px; }
    .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
    .row:last-child { border-bottom: none; }
    .label { color: #6b7280; }
    .value { font-weight: 600; color: #111827; text-align: right; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
    .badge { display: inline-block; background: #fffbeb; color: #d97706; font-size: 13px; font-weight: 600; padding: 4px 12px; border-radius: 999px; margin-bottom: 20px; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Herinnering: over {{ $timing }}</h1>
        <p>{{ $afspraak->kapper->salon_naam }}</p>
    </div>
    <div class="body">
        <div style="text-align:center; margin-bottom: 20px;">
            <span class="badge">⏰ Herinnering</span>
        </div>
        <div class="row">
            <span class="label">Dienst</span>
            <span class="value">{{ $afspraak->dienst->naam }}</span>
        </div>
        <div class="row">
            <span class="label">Datum</span>
            <span class="value">{{ $afspraak->datum->translatedFormat('l d F Y') }}</span>
        </div>
        <div class="row">
            <span class="label">Tijd</span>
            <span class="value">{{ $afspraak->start_tijd }} – {{ $afspraak->eind_tijd }}</span>
        </div>
        <div class="row">
            <span class="label">Salon</span>
            <span class="value">{{ $afspraak->kapper->salon_naam }}</span>
        </div>
        @if($afspraak->kapper->adres || $afspraak->kapper->stad)
        <div class="row">
            <span class="label">Adres</span>
            <span class="value">{{ implode(', ', array_filter([$afspraak->kapper->adres, $afspraak->kapper->stad])) }}</span>
        </div>
        @endif
    </div>
    <div class="footer">
        Vergeet je afspraak niet! Annuleren kan via Mijn afspraken op het platform.
    </div>
</div>
</body>
</html>
