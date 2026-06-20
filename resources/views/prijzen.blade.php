@extends('layouts.publiek')

@section('content')
<div class="min-h-screen bg-white dark:bg-neutral-900">

    {{-- Hero met aurora --}}
    <div class="relative overflow-hidden py-16 sm:py-24 px-4 text-center">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="[--white-gradient:repeating-linear-gradient(100deg,white_0%,white_7%,transparent_10%,transparent_12%,white_16%)] [--aurora:repeating-linear-gradient(100deg,#93c5fd_10%,#a5b4fc_15%,#bfdbfe_20%,#c4b5fd_25%,#60a5fa_30%)] [background-image:var(--white-gradient),var(--aurora)] [background-size:300%,_200%] [background-position:50%_50%,50%_50%] blur-[80px] absolute -inset-[10px] opacity-[0.38] will-change-transform animate-aurora motion-reduce:animate-none dark:opacity-10"></div>
        </div>
        <div class="absolute inset-0 pointer-events-none bg-gradient-to-b from-transparent from-[40%] to-white dark:to-neutral-900"></div>
        <div class="relative">
            <p class="text-xs font-semibold text-blue-600 tracking-widest uppercase mb-3">Prijzen</p>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 dark:text-neutral-100 mb-4">
                Eén simpel abonnement
            </h1>
            <p class="text-gray-500 dark:text-neutral-400 text-lg max-w-xl mx-auto">
                Geen verborgen kosten. Geen commissie per boeking. Gewoon een vast bedrag per maand.
            </p>
        </div>
    </div>

    {{-- Pricing card --}}
    <div class="max-w-lg mx-auto px-4 pb-16">
        <div class="bg-gray-950 text-white rounded-2xl p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-blue-600/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

            <div class="flex items-start justify-between mb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Pro</p>
                <span class="bg-blue-600/20 text-blue-400 text-xs font-semibold px-3 py-1 rounded-full">
                    14 dagen gratis
                </span>
            </div>

            <div class="flex items-end gap-2 mb-1">
                <span class="text-5xl font-extrabold">€25</span>
                <span class="text-gray-400 mb-2">/ maand</span>
            </div>
            <p class="text-gray-500 text-xs mb-3">excl. BTW</p>
            <p class="text-blue-400 text-sm font-semibold mb-6">14 dagen gratis proberen</p>

            <ul class="space-y-3 mb-8">
                @foreach([
                    'Online boekingen 24/7',
                    'Eigen profielpagina & boekingslink',
                    'Onbeperkt afspraken',
                    'Meerdere medewerkers',
                    'Agenda & beschikbaarheidsbeheer',
                    'Klantenoverzicht',
                    'Reviews & beoordelingen',
                    'Galerij met foto\'s',
                    'Boekingswidget voor je eigen website',
                    'Geen commissie per boeking',
                    'Elke maand opzegbaar',
                ] as $feature)
                <li class="flex items-center gap-3 text-sm text-gray-300">
                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>

            <a href="{{ route('kapper.registreer') }}"
               class="block w-full text-center bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm py-3.5 rounded-xl transition-colors">
                Start gratis proefperiode
            </a>
            <p class="text-center text-gray-500 text-xs mt-3">Geen creditcard nodig · Setup in 15 minuten</p>
        </div>

        {{-- Vergelijk --}}
        <div class="mt-6 grid grid-cols-3 gap-3 text-center">
            @foreach([
                ['€0', 'per boeking'],
                ['14 dagen', 'gratis proberen'],
                ['∞', 'afspraken'],
            ] as [$val, $label])
            <div class="bg-gray-100 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-4">
                <p class="text-xl font-bold text-gray-900 dark:text-neutral-100">{{ $val }}</p>
                <p class="text-xs text-gray-500 dark:text-neutral-400 mt-0.5">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- FAQ --}}
    <div class="max-w-2xl mx-auto px-4 pb-20" x-data="{ open: null }">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-neutral-100 text-center mb-8">Veelgestelde vragen</h2>
        <div class="space-y-3">
            @foreach([
                ['Moet ik betalen om te starten?', 'Nee. Je start met een gratis proefperiode van 14 dagen. Geen creditcard nodig. Daarna kies je zelf of je doorgaat voor €25/maand (excl. BTW).'],
                ['Betaal ik commissie per boeking?', 'Nee. Je betaalt alleen het vaste maandbedrag van €25 (excl. BTW). Geen commissie, geen extra kosten per klant of boeking.'],
                ['Kan ik op elk moment opzeggen?', 'Ja. Het abonnement loopt per maand en is op elk moment opzegbaar. Je behoudt toegang tot het einde van de betaalperiode.'],
                ['Hoeveel klanten en afspraken kan ik verwerken?', 'Onbeperkt. Er zijn geen limieten op het aantal klanten, afspraken of medewerkers.'],
                ['Hoe kunnen klanten bij mij boeken?', 'Na registratie krijg je een eigen profielpagina op Kaply (bijv. kaply.nl/kapper/jouw-naam) én een boekingswidget die je op je eigen website kunt plaatsen.'],
            ] as $i => [$vraag, $antwoord])
            <div class="border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
                <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                        class="w-full flex items-center justify-between px-5 py-4 text-left text-sm font-medium text-gray-800 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                    {{ $vraag }}
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200"
                         :class="open === {{ $i }} ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === {{ $i }}"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="px-5 pb-4 text-sm text-gray-500 dark:text-neutral-400"
                     style="display: none;">
                    {{ $antwoord }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- CTA --}}
    <div class="bg-gray-50 dark:bg-neutral-800 border-t border-gray-200 dark:border-neutral-700 py-16 px-4 text-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-neutral-100 mb-3">Klaar om te starten?</h2>
        <p class="text-gray-500 dark:text-neutral-400 mb-6">14 dagen gratis. Geen creditcard. Setup in 15 minuten.</p>
        <a href="{{ route('kapper.registreer') }}"
           class="inline-flex items-center gap-2 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-colors">
            Start gratis proefperiode
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>

</div>
@endsection
