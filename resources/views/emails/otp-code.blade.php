<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inlogcode</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 480px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: #3b82f6; padding: 32px; text-align: center; }
    .header h1 { color: #fff; margin: 0; font-size: 22px; font-weight: 700; }
    .body { padding: 32px; text-align: center; }
    .code { font-size: 48px; font-weight: 800; letter-spacing: 12px; color: #1e40af; background: #eff6ff; border-radius: 12px; padding: 20px 32px; display: inline-block; margin: 16px 0; font-family: monospace; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; }
    p { color: #6b7280; font-size: 15px; margin: 0 0 8px; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Kaply</h1>
    </div>
    <div class="body">
        <p>Jouw inlogcode is:</p>
        <div class="code">{{ $code }}</div>
        <p style="margin-top: 16px; font-size: 13px;">Deze code is 15 minuten geldig.<br>Heb je dit niet aangevraagd? Dan kun je deze email negeren.</p>
    </div>
    <div class="footer">
        Kaply — online kapper boeken
    </div>
</div>
</body>
</html>
