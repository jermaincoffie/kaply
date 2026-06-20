Herinnering: je afspraak is over {{ $timing }}

Dienst:  {{ $afspraak->dienst->naam }}
Datum:   {{ $afspraak->datum->translatedFormat('l d F Y') }}
Tijd:    {{ $afspraak->start_tijd }} - {{ $afspraak->eind_tijd }}
Salon:   {{ $afspraak->kapper->salon_naam }}
@if($afspraak->kapper->adres || $afspraak->kapper->stad)
Adres:   {{ implode(', ', array_filter([$afspraak->kapper->adres, $afspraak->kapper->stad])) }}
@endif

Vergeet je afspraak niet!
Annuleren kan via Mijn afspraken op kaply.nl

---
Kaply - online kapper boeken
kaply.nl
