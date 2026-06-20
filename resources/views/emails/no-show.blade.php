<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $checkoutUrl ? 'No-show fee' : 'Je was er niet bij' }}</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: #ef4444; padding: 32px 32px 24px; text-align: center; }
    .header h1 { color: #fff; margin: 0; font-size: 22px; font-weight: 700; }
    .header p { color: rgba(255,255,255,.8); margin: 6px 0 0; font-size: 14px; }
    .body { padding: 28px 32px; }
    .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
    .row:last-child { border-bottom: none; }
    .label { color: #6b7280; }
    .value { font-weight: 600; color: #111827; text-align: right; }
    .notice { border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; font-size: 14px; }
    .notice-warning { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    .notice-fee { background: #fff7ed; border: 1px solid #fed7aa; color: #92400e; }
    .btn { display: block; color: #fff; text-decoration: none; text-align: center; font-size: 15px; font-weight: 700; padding: 14px 24px; border-radius: 10px; margin: 24px 0 0; }
    .btn-pay { background: #dc2626; }
    .btn-book { background: #2563eb; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>{{ $checkoutUrl ? 'No-show fee' : 'Je was er niet bij' }}</h1>
        <p>{{ $afspraak->kapper->salon_naam }}</p>
    </div>
    <div class="body">
        @if($checkoutUrl)
        <div class="notice notice-fee">
            Je hebt je afspraak op {{ $afspraak->datum->translatedFormat('l d F Y') }} om {{ $afspraak->start_tijd }} niet bijgewoond. {{ $afspraak->kapper->salon_naam }} brengt hiervoor een no-show fee in rekening.
        </div>
        @else
        <div class="notice notice-warning">
            Je hebt je afspraak op {{ $afspraak->datum->translatedFormat('l d F Y') }} om {{ $afspraak->start_tijd }} niet bijgewoond. Je afspraak is geregistreerd als no-show.
        </div>
        @endif

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
            <span class="value">{{ $afspraak->start_tijd }}</span>
        </div>
        <div class="row">
            <span class="label">Salon</span>
            <span class="value">{{ $afspraak->kapper->salon_naam }}</span>
        </div>
        @if($afspraak->kapper->telefoon)
        <div class="row">
            <span class="label">Telefoon salon</span>
            <span class="value">{{ $afspraak->kapper->telefoon }}</span>
        </div>
        @endif

        @if($checkoutUrl)
        <a href="{{ $checkoutUrl }}" class="btn btn-pay">No-show fee betalen →</a>
        @else
        <a href="{{ route('kapper.profiel', $afspraak->kapper->slug) }}" class="btn btn-book">Nieuwe afspraak boeken →</a>
        @endif
    </div>
    <div class="footer">
        Vragen? Neem contact op met {{ $afspraak->kapper->salon_naam }}.
    </div>
</div>
</body>
</html>
