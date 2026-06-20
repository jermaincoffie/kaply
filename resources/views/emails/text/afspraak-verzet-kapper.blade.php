Een klant heeft een afspraak verzet bij {{ $afspraak->kapper->salon_naam }}.

Klant:   {{ $afspraak->klant->name }}
Dienst:  {{ $afspraak->dienst->naam }}
Was:     {{ \Carbon\Carbon::parse($oudeDatum)->translatedFormat('l d F') }} om {{ $oudeTijd }}
Nieuw:   {{ $afspraak->datum->translatedFormat('l d F Y') }} om {{ $afspraak->start_tijd }}

Bekijk je agenda via het Kaply dashboard op kaply.nl

---
Kaply - online kapper boeken
kaply.nl
