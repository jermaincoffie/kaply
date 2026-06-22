<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nieuwe kapper aangemeld</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 36px 32px 28px; text-align: center; }
    .header h1 { color: #fff; margin: 0 0 6px; font-size: 22px; font-weight: 700; }
    .header p { color: rgba(255,255,255,.85); margin: 0; font-size: 14px; }
    .body { padding: 32px 32px 24px; }
    .body p { font-size: 15px; line-height: 1.6; color: #374151; margin: 0 0 16px; }
    .detail-row { display: flex; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
    .detail-label { color: #6b7280; width: 80px; flex-shrink: 0; }
    .detail-value { color: #111827; font-weight: 500; }
    .btn { display: block; background: #f59e0b; color: #fff !important; text-align: center; padding: 14px 24px; border-radius: 8px; font-weight: 600; font-size: 15px; text-decoration: none; margin: 24px 0 0; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Nieuwe kapper aangemeld</h1>
        <p>Wacht op jouw goedkeuring</p>
    </div>
    <div class="body">
        <p>Er heeft zich een nieuwe kapper aangemeld op Kaply.</p>

        <div>
            <div class="detail-row">
                <span class="detail-label">Salon</span>
                <span class="detail-value">{{ $salonNaam }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Naam</span>
                <span class="detail-value">{{ $naam }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Stad</span>
                <span class="detail-value">{{ $stad ?: '—' }}</span>
            </div>
            <div class="detail-row" style="border-bottom: none;">
                <span class="detail-label">E-mail</span>
                <span class="detail-value">{{ $email }}</span>
            </div>
        </div>

        <a href="{{ route('admin.kappers') }}" class="btn">Bekijk en activeer kapper →</a>
    </div>
    <div class="footer">
        Kaply · Admin notificatie
    </div>
</div>
</body>
</html>
