Afspraak bevestigd bij {{ $afspraak->kapper->salon_naam }}

Dienst:  {{ $afspraak->dienst->naam }}
Datum:   {{ $afspraak->datum->translatedFormat('l d F Y') }}
Tijd:    {{ $afspraak->start_tijd }} - {{ $afspraak->eind_tijd }}
Salon:   {{ $afspraak->kapper->salon_naam }}
@if($afspraak->kapper->adres || $afspraak->kapper->stad)
Adres:   {{ implode(', ', array_filter([$afspraak->kapper->adres, $afspraak->kapper->stad])) }}
@endif

Je ontvangt een herinnering 24 uur en 1 uur voor je afspraak.
Annuleren kan via Mijn afspraken op kaply.nl

---
Kaply - online kapper boeken
kaply.nl
