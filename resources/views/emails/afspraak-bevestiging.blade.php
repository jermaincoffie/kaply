<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Afspraakbevestiging</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 32px 16px; color: #111827;">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td align="center">
<table width="520" cellpadding="0" cellspacing="0" border="0" style="max-width:520px; width:100%; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,.08);">

    {{-- Header --}}
    <tr>
        <td bgcolor="#3b82f6" style="padding:32px 32px 24px; text-align:center;">
            <p style="margin:0; color:#fff; font-size:22px; font-weight:700;">Afspraak bevestigd</p>
            <p style="margin:6px 0 0; color:rgba(255,255,255,.8); font-size:14px;">{{ $afspraak->kapper->salon_naam }}</p>
        </td>
    </tr>

    {{-- Badge --}}
    <tr>
        <td style="padding:24px 32px 0; text-align:center;">
            <span style="display:inline-block; background:#ecfdf5; color:#059669; font-size:13px; font-weight:600; padding:4px 12px; border-radius:999px;">✓ Bevestigd</span>
        </td>
    </tr>

    {{-- Details table --}}
    <tr>
        <td style="padding:20px 32px 28px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">

                <tr>
                    <td style="padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:14px; color:#6b7280; width:40%;">Dienst</td>
                    <td style="padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:14px; font-weight:600; color:#111827; text-align:right;">{{ $afspraak->dienst->naam }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:14px; color:#6b7280;">Datum</td>
                    <td style="padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:14px; font-weight:600; color:#111827; text-align:right;">{{ $afspraak->datum->translatedFormat('l d F Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:14px; color:#6b7280;">Tijd</td>
                    <td style="padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:14px; font-weight:600; color:#111827; text-align:right;">{{ $afspraak->start_tijd }} – {{ $afspraak->eind_tijd }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:14px; color:#6b7280;">Salon</td>
                    <td style="padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:14px; font-weight:600; color:#111827; text-align:right;">{{ $afspraak->kapper->salon_naam }}</td>
                </tr>
                @if($afspraak->kapper->adres || $afspraak->kapper->stad)
                <tr>
                    <td style="padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:14px; color:#6b7280;">Adres</td>
                    <td style="padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:14px; font-weight:600; color:#111827; text-align:right;">{{ implode(', ', array_filter([$afspraak->kapper->adres, $afspraak->kapper->stad])) }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:10px 0; font-size:14px; color:#6b7280;">Betaalmethode</td>
                    <td style="padding:10px 0; font-size:14px; font-weight:600; color:#111827; text-align:right;">{{ $afspraak->betaalmethode === 'online' ? 'Online betaald' : 'Betalen in de zaak' }}</td>
                </tr>

            </table>
        </td>
    </tr>

    {{-- Footer --}}
    <tr>
        <td bgcolor="#f9fafb" style="padding:16px 32px; text-align:center; font-size:12px; color:#9ca3af; border-top:1px solid #f3f4f6;">
            Je ontvangt een herinnering 24 uur en 1 uur voor je afspraak.<br>
            Annuleren kan via Mijn afspraken op het platform.
        </td>
    </tr>

</table>
</td></tr>
</table>

</body>
</html>
