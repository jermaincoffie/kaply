@extends('layouts.publiek')

@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden bg-white">
    {{-- Aurora achtergrond (zelfde als homepage) --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="[--white-gradient:repeating-linear-gradient(100deg,white_0%,white_7%,transparent_10%,transparent_12%,white_16%)] [--aurora:repeating-linear-gradient(100deg,#93c5fd_10%,#a5b4fc_15%,#bfdbfe_20%,#c4b5fd_25%,#60a5fa_30%)] [background-image:var(--white-gradient),var(--aurora)] [background-size:300%,_200%] [background-position:50%_50%,50%_50%] blur-[80px] absolute -inset-[10px] opacity-[0.38] will-change-transform animate-aurora motion-reduce:animate-none"></div>
    </div>
    <div class="absolute inset-0 pointer-events-none bg-gradient-to-b from-transparent from-[40%] to-white"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-20 sm:py-32 text-center">
        <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-100 text-blue-600 text-xs font-semibold px-3 py-1.5 rounded-full mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
            14 dagen gratis · geen creditcard nodig
        </div>

        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-gray-900 mb-6 leading-tight">
            Meer boekingen.<br>
            <span class="text-blue-600">Minder gedoe.</span>
        </h1>

        <p class="text-lg sm:text-xl text-gray-500 max-w-2xl mx-auto mb-10 leading-relaxed">
            Kaply is het online boekingssysteem voor kappers en barbiers. Klanten boeken 24/7 online — jij beheert alles vanuit één slim dashboard.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('kapper.registreer') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-blue-600 text-white text-base font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/25">
                Start gratis proefperiode
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="{{ route('kapper.profiel', 'demo-salon') }}" target="_blank"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-gray-100 text-gray-700 text-base font-medium hover:bg-gray-200 transition-all">
                Bekijk voorbeeldprofiel
            </a>
        </div>

        <p class="text-gray-400 text-xs mt-4">14 dagen gratis · daarna €25/maand excl. BTW · elke maand opzegbaar</p>
    </div>
</section>

{{-- STATISTIEKEN / TRUST BAR --}}
<section class="bg-gray-50 border-y border-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
            @foreach([
                ['24/7', 'Online bereikbaar'],
                ['14 dagen', 'Gratis proberen'],
                ['€0', 'Per boeking commissie'],
                ['15 min', 'Tot je live gaat'],
            ] as [$getal, $label])
            <div>
                <p class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $getal }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- HOE HET WERKT --}}
<section id="hoe-werkt-het" class="py-20 px-4 sm:px-6">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-14">
            <p class="text-xs font-semibold text-blue-600 tracking-widest uppercase mb-2">Zo simpel</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">In 3 stappen online</h2>
        </div>
        <div class="grid sm:grid-cols-3 gap-8">
            @foreach([
                ['1', 'Registreer gratis', 'Maak je account aan en vul je salonprofiel in. Voeg je diensten, prijzen en beschikbaarheid toe.', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['2', 'Stel je profiel in', 'Voeg een profielfoto, beschrijving en fotos van je werk toe. Klanten zien direct wie jij bent.', 'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z M15 13a3 3 0 11-6 0 3 3 0 016 0z'],
                ['3', 'Deel je link & ontvang boekingen', 'Deel jouw Kaply-link via Instagram, WhatsApp of je eigen site. Klanten boeken direct online.', 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z'],
            ] as [$nr, $titel, $tekst, $icon])
            <div class="relative text-center">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm font-bold mb-4 mx-auto" style="box-shadow: 0 0 24px rgba(59,130,246,0.55), 0 4px 12px rgba(59,130,246,0.3)">{{ $nr }}</div>
                <h3 class="text-base font-bold text-gray-900 mb-2">{{ $titel }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $tekst }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section class="py-20 px-4 sm:px-6 bg-gray-50">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-14">
            <p class="text-xs font-semibold text-blue-600 tracking-widest uppercase mb-2">Alles inbegrepen</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Alles wat je nodig hebt</h2>
            <p class="text-gray-500 mt-3 max-w-lg mx-auto">Geen losse apps meer. Kaply bundelt boekingen, agenda, klanten en marketing in één dashboard.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach([
                ['Online boekingen 24/7', 'Klanten boeken dag en nacht — ook als jij de schaar vasthebt.', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['Agenda & rooster', 'Dag-, week- en maandweergave. Stel beschikbaarheid en pauzes in.', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['Automatische mails', 'Bevestigings- en herinneringsmail naar klant bij elke boeking.', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['Klantenoverzicht', 'Zie wie je klanten zijn, hoeveel bezoeken ze hebben en voeg notities toe.', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['Reviews & beoordelingen', 'Klanten laten reviews achter op je profiel. Bouw vertrouwen op.', 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                ['Google & Apple Calendar', 'Synchroniseer je afspraken automatisch met je telefoonkalender.', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Medewerkers', 'Meerdere medewerkers in één salon, met eigen agenda.', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['Kortingscodes', 'Maak kortingscodes aan voor acties en vaste klanten.', 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ['Eigen profielpagina', 'Jouw salon op kaply.nl met foto\'s, diensten, reviews en directe boekknop.', 'M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z'],
            ] as [$titel, $tekst, $icon])
            <div class="bg-white border border-gray-100 rounded-xl p-5 hover:border-blue-100 hover:shadow-sm transition-all">
                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $titel }}</h3>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $tekst }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PRICING --}}
<section class="relative overflow-hidden bg-white py-20 px-4 sm:px-6">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="[--white-gradient:repeating-linear-gradient(100deg,white_0%,white_7%,transparent_10%,transparent_12%,white_16%)] [--aurora:repeating-linear-gradient(100deg,#93c5fd_10%,#a5b4fc_15%,#bfdbfe_20%,#c4b5fd_25%,#60a5fa_30%)] [background-image:var(--white-gradient),var(--aurora)] [background-size:300%,_200%] [background-position:50%_50%,50%_50%] blur-[80px] absolute -inset-[10px] opacity-[0.38] will-change-transform animate-aurora motion-reduce:animate-none"></div>
    </div>
    <div class="absolute inset-0 pointer-events-none bg-gradient-to-b from-transparent from-[20%] to-white"></div>
    <div class="max-w-lg mx-auto relative">
        <div class="text-center mb-10">
            <p class="text-xs font-semibold text-blue-600 tracking-widest uppercase mb-2">Prijzen</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Eén eenvoudig tarief</h2>
            <p class="text-gray-500 mt-3">Geen verborgen kosten, geen commissie per boeking.</p>
        </div>

        <div class="bg-gray-950 text-white rounded-2xl p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-blue-600/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

            <div class="flex items-end gap-2 mb-1">
                <span class="text-5xl font-extrabold">€25</span>
                <span class="text-gray-400 mb-2">/maand</span>
            </div>
            <p class="text-gray-500 text-xs mb-3">excl. BTW</p>
            <p class="text-blue-400 text-sm font-semibold mb-6">14 dagen gratis proberen</p>

            <ul class="space-y-3 mb-8">
                @foreach([
                    'Alle functies inbegrepen',
                    'Onbeperkte boekingen',
                    'Onbeperkte klanten',
                    'Geen commissie per boeking',
                    'Elke maand opzegbaar',
                    'Setup in 15 minuten',
                ] as $feature)
                <li class="flex items-center gap-3 text-sm text-gray-300">
                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>

            <a href="{{ route('kapper.registreer') }}"
               class="block w-full text-center px-6 py-3.5 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-500 transition-colors">
                Start gratis proefperiode
            </a>
            <p class="text-center text-xs text-gray-500 mt-3">Geen creditcard nodig voor de proefperiode</p>
        </div>
    </div>
</section>


{{-- FAQ --}}
<section class="py-20 px-4 sm:px-6 bg-gray-50" x-data="{ open: null }">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-12">
            <p class="text-xs font-semibold text-blue-600 tracking-widest uppercase mb-2">Veelgestelde vragen</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Vragen?</h2>
        </div>

        <div class="space-y-2">
            @foreach([
                ['Moet ik betalen om te starten?', 'Nee. Je start met een gratis proefperiode van 14 dagen. Geen creditcard nodig. Daarna kies je zelf of je doorgaat voor €25/maand (excl. BTW).'],
                ['Hoe lang duurt het instellen?', 'Gemiddeld 15 minuten. Je vult je profiel in, voegt je diensten toe en stelt je beschikbaarheid in. Daarna deel je je link en ontvang je boekingen.'],
                ['Moet mijn klant een account aanmaken?', 'Klanten loggen in via een eenmalige code die ze per e-mail ontvangen. Geen wachtwoord, geen gedoe — gewoon code invullen en boeken.'],
                ['Kan ik opzeggen wanneer ik wil?', 'Ja. Je abonnement is maandelijks opzegbaar. Je hebt altijd toegang tot het einde van je betaalperiode.'],
                ['Werkt het ook met meerdere medewerkers?', 'Ja. Je kunt medewerkers toevoegen aan je salon, elk met hun eigen agenda en beschikbaarheid.'],
                ['Betalen klanten online of in de zaak?', 'Beide. Jij bepaalt per dienst of klanten online vooruitbetalen of in de zaak afrekenen.'],
            ] as $i => [$vraag, $antwoord])
            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
                <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                        class="w-full flex items-center justify-between px-5 py-4 text-left text-sm font-semibold text-gray-900 hover:bg-gray-50 transition-colors">
                    {{ $vraag }}
                    <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0 ml-4"
                         :class="open === {{ $i }} ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === {{ $i }}"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>
                    <p class="px-5 pb-4 text-sm text-gray-500 leading-relaxed border-t border-gray-50 pt-3">{{ $antwoord }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FINAL CTA --}}
<section class="py-20 px-4 sm:px-6 bg-gray-950 text-white text-center">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-3xl sm:text-5xl font-extrabold mb-4 tracking-tight">
            Klaar om te beginnen?
        </h2>
        <p class="text-gray-400 mb-8 text-lg">14 dagen gratis. Geen creditcard. Setup in 15 minuten.</p>
        <a href="{{ route('kapper.registreer') }}"
           class="inline-flex items-center gap-2 px-10 py-4 rounded-xl bg-blue-600 text-white font-bold text-base hover:bg-blue-500 transition-all shadow-xl shadow-blue-600/25">
            Start vandaag gratis
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-gray-950 border-t border-gray-800 py-8 px-4 sm:px-6">
    <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
        <img src="{{ asset('images/dark modus kaply bg removed.PNG') }}" class="h-16 w-auto opacity-70" alt="Kaply">
        <div class="flex items-center gap-6 text-xs text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-gray-300 transition-colors">Kappers zoeken</a>
            <a href="{{ route('privacy') }}" class="hover:text-gray-300 transition-colors">Privacy</a>
            <a href="{{ route('voorwaarden') }}" class="hover:text-gray-300 transition-colors">Voorwaarden</a>
            <a href="{{ route('kapper.registreer') }}" class="hover:text-gray-300 transition-colors">Registreren</a>
        </div>
        <p class="text-xs text-gray-600">© {{ date('Y') }} Kaply</p>
    </div>
</footer>

@endsection
