<x-mail::message>
# Nieuwe afspraak 📅

Hoi {{ $kapper->name }},

Er is een nieuwe afspraak ingepland bij **{{ $kapper->kapper->salon_naam }}**.

<x-mail::panel>
**Klant:** {{ $afspraak->klant?->name ?? $afspraak->walk_in_naam ?? 'Walk-in' }}
**Dienst:** {{ $afspraak->dienst?->naam ?? '—' }}
**Datum:** {{ $afspraak->datum->format('d-m-Y') }}
**Tijd:** {{ $afspraak->start_tijd }} – {{ $afspraak->eind_tijd }}
</x-mail::panel>

<x-mail::button :url="config('app.url') . '/kapper/agenda'" color="green">
Bekijk agenda
</x-mail::button>

---

<small>Je ontvangt deze email omdat je afspraaknotificaties hebt ingeschakeld. [Uitschrijven]({{ $uitschrijfUrl }})</small>

Met vriendelijke groet,
**Kaply**
</x-mail::message>
