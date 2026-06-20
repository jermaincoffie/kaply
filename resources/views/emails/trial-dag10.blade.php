<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nog 4 dagen resterend</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827; }
    .card { background: #fff; border-radius: 12px; max-width: 520px; margin: 0 auto; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .header { background: #111827; padding: 36px 32px 28px; text-align: center; }
    .header h1 { color: #fff; margin: 0 0 6px; font-size: 22px; font-weight: 700; }
    .header p { color: rgba(255,255,255,.7); margin: 0; font-size: 14px; }
    .countdown { background: #fef3c7; border: 1px solid #fcd34d; border-radius: 10px; padding: 16px 20px; margin: 0 32px 24px; text-align: center; }
    .countdown strong { display: block; font-size: 28px; font-weight: 800; color: #92400e; }
    .countdown span { font-size: 13px; color: #b45309; }
    .body { padding: 0 32px 24px; }
    .body p { font-size: 15px; line-height: 1.6; color: #374151; margin: 0 0 16px; }
    .feature { display: flex; align-items: center; gap-x: 10px; padding: 8px 0; font-size: 14px; color: #374151; border-bottom: 1px solid #f3f4f6; }
    .check { color: #2563eb; font-weight: 700; margin-right: 10px; }
    .price-box { background: #f8faff; border: 1px solid #e0eaff; border-radius: 10px; padding: 20px; margin: 20px 0; text-align: center; }
    .price-box .price { font-size: 32px; font-weight: 800; color: #111827; }
    .price-box .period { font-size: 14px; color: #6b7280; }
    .price-box .note { font-size: 12px; color: #9ca3af; margin-top: 6px; }
    .btn { display: block; background: #2563eb; color: #fff !important; text-align: center; padding: 14px 24px; border-radius: 8px; font-weight: 600; font-size: 15px; text-decoration: none; margin: 24px 0 0; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Nog 4 dagen resterend</h1>
        <p>{{ $kapper->salon_naam }} · gratis proefperiode</p>
    </div>

    <div style="padding-top: 24px">
        <div class="countdown">
            <strong>4 dagen</strong>
            <span>resterend in je gratis proefperiode</span>
        </div>
    </div>

    <div class="body">
        <p>Hoi {{ $kapper->user->name }},</p>
        <p>Je gratis proefperiode loopt over 4 dagen af. Activeer nu je abonnement om {{ $kapper->salon_naam }} online te houden en nieuwe klanten te blijven ontvangen.</p>

        <div class="price-box">
            <div class="price">€25<span style="font-size:16px;font-weight:400;color:#6b7280">/maand excl. BTW</span></div>
            <div class="note">Elke maand opzegbaar · geen commissie per boeking</div>
        </div>

        <p style="font-size:13px;color:#6b7280">Inbegrepen: onbeperkte boekingen, klantenbeheer, agenda, automatische mails, reviews, galerij en meer.</p>

        <a href="{{ route('kapper.abonnement') }}" class="btn">Activeer mijn abonnement</a>
        <p style="text-align:center;font-size:12px;color:#9ca3af;margin-top:12px">Betaal veilig via iDEAL, creditcard of SEPA</p>
    </div>
    <div class="footer">
        Kaply · Online boekingsplatform voor kappers<br>
        Je ontvangt deze mail omdat je proefperiode bijna afloopt.
    </div>
</div>
</body>
</html>
