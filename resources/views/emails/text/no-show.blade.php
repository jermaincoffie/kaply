{{ $checkoutUrl ? 'No-show fee' : 'Je was er niet bij' }} - {{ $afspraak->kapper->salon_naam }}

@if($checkoutUrl)
Je hebt je afspraak op {{ $afspraak->datum->translatedFormat('l d F Y') }} om {{ $afspraak->start_tijd }} niet bijgewoond. {{ $afspraak->kapper->salon_naam }} brengt hiervoor een no-show fee in rekening.
@else
Je hebt je afspraak op {{ $afspraak->datum->translatedFormat('l d F Y') }} om {{ $afspraak->start_tijd }} niet bijgewoond. Je afspraak is geregistreerd als no-show.
@endif

AFSPRAAKDETAILS
---------------
Dienst: {{ $afspraak->dienst->naam }}
Datum: {{ $afspraak->datum->translatedFormat('l d F Y') }}
Tijd: {{ $afspraak->start_tijd }}
Salon: {{ $afspraak->kapper->salon_naam }}
@if($afspraak->kapper->telefoon)
Telefoon: {{ $afspraak->kapper->telefoon }}
@endif

@if($checkoutUrl)
No-show fee betalen: {{ $checkoutUrl }}
@else
Nieuwe afspraak boeken: {{ route('kapper.profiel', $afspraak->kapper->slug) }}
@endif

Vragen? Neem contact op met {{ $afspraak->kapper->salon_naam }}.
