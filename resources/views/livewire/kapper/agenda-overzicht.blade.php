<div>
    {{-- Onboarding checklist --}}
    @if($toonOnboarding)
    <div class="bg-white dark:bg-neutral-800 border border-blue-200 dark:border-blue-800 rounded-xl p-5 mb-6">
        <div class="flex items-start gap-3 mb-4">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-neutral-100">Stel je account in zodat klanten kunnen boeken</p>
                <p class="text-xs text-gray-500 dark:text-neutral-400 mt-0.5">Voltooi de onderstaande stappen om live te gaan.</p>
            </div>
        </div>

        <div class="space-y-2">
            {{-- Stap 1: Beschikbaarheid --}}
            <a href="{{ route('kapper.beschikbaarheid') }}"
               class="flex items-center gap-3 p-3 rounded-lg border {{ $onboarding['beschikbaarheid'] ? 'border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/10' : 'border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700/50' }} transition-colors group">
                <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 {{ $onboarding['beschikbaarheid'] ? 'bg-green-500' : 'bg-gray-200 dark:bg-neutral-700 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30' }}">
                    @if($onboarding['beschikbaarheid'])
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    @else
                    <span class="text-xs font-bold text-gray-500 dark:text-neutral-400">1</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium {{ $onboarding['beschikbaarheid'] ? 'text-green-700 dark:text-green-400 line-through' : 'text-gray-800 dark:text-neutral-200' }}">Beschikbaarheid instellen</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">Geef aan op welke dagen en tijden je werkt</p>
                </div>
                @if(!$onboarding['beschikbaarheid'])
                <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500 group-hover:text-blue-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                @endif
            </a>

            {{-- Stap 2: Diensten --}}
            <a href="{{ route('kapper.diensten') }}"
               class="flex items-center gap-3 p-3 rounded-lg border {{ $onboarding['diensten'] ? 'border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/10' : 'border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700/50' }} transition-colors group">
                <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 {{ $onboarding['diensten'] ? 'bg-green-500' : 'bg-gray-200 dark:bg-neutral-700 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30' }}">
                    @if($onboarding['diensten'])
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    @else
                    <span class="text-xs font-bold text-gray-500 dark:text-neutral-400">2</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium {{ $onboarding['diensten'] ? 'text-green-700 dark:text-green-400 line-through' : 'text-gray-800 dark:text-neutral-200' }}">Diensten toevoegen</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">Voeg je knippen, kleuren en andere diensten toe</p>
                </div>
                @if(!$onboarding['diensten'])
                <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500 group-hover:text-blue-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                @endif
            </a>

            {{-- Stap 3: Medewerkers (optioneel) --}}
            <a href="{{ route('kapper.medewerkers') }}"
               class="flex items-center gap-3 p-3 rounded-lg border {{ $onboarding['medewerkers'] ? 'border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/10' : 'border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700/50' }} transition-colors group">
                <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 {{ $onboarding['medewerkers'] ? 'bg-green-500' : 'bg-gray-200 dark:bg-neutral-700 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30' }}">
                    @if($onboarding['medewerkers'])
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    @else
                    <span class="text-xs font-bold text-gray-500 dark:text-neutral-400">3</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-medium {{ $onboarding['medewerkers'] ? 'text-green-700 dark:text-green-400 line-through' : 'text-gray-800 dark:text-neutral-200' }}">Medewerkers toevoegen</p>
                        <span class="text-xs font-medium px-1.5 py-0.5 rounded bg-gray-100 dark:bg-neutral-700 text-gray-500 dark:text-neutral-400">Optioneel</span>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">Voeg medewerkers toe zodat klanten bij hen kunnen boeken</p>
                </div>
                @if(!$onboarding['medewerkers'])
                <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500 group-hover:text-blue-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                @endif
            </a>
        </div>
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-2">Omzet {{ now()->isoFormat('MMMM') }}</p>
            <span class="text-2xl font-bold text-gray-900 dark:text-neutral-100">€ {{ number_format($omzet_maand / 100, 0, ',', '.') }}</span>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-2">Afspraken {{ now()->isoFormat('MMMM') }}</p>
            <span class="text-2xl font-bold text-gray-900 dark:text-neutral-100">{{ $afspraken_maand }}</span>
        </div>
        <div class="col-span-2 lg:col-span-1 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-2">Komende</p>
            <span class="text-2xl font-bold text-gray-900 dark:text-neutral-100">{{ $komende_afspraken }}</span>
        </div>
    </div>

    {{-- Boeklink banner --}}
    @php $boeklink = url('/kapper/' . auth()->user()->kapper->slug); @endphp
    <div class="flex items-center justify-between bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl px-4 py-3 mb-4"
         x-data="{ copied: false }">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-gray-600 dark:text-neutral-300">Jouw boeklink</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500 font-mono truncate">{{ $boeklink }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0 ml-4">
            <a href="{{ route('kapper.profiel', auth()->user()->kapper->slug) }}" target="_blank"
               class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Bekijk
            </a>
            <button
                @click="navigator.clipboard.writeText('{{ $boeklink }}'); copied = true; setTimeout(() => copied = false, 2500)"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 active:bg-gray-100 dark:active:bg-neutral-600 transition-colors">
                <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <svg x-show="copied" class="w-3.5 h-3.5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span x-show="!copied">Kopieer</span>
                <span x-show="copied" class="text-green-600 dark:text-green-400">Gekopieerd</span>
            </button>
        </div>
    </div>

    {{-- Vandaag sectie (desktop) --}}
    <div class="hidden lg:block bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl mb-4 overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-neutral-700">
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Vandaag</span>
                <span class="text-xs text-gray-400 dark:text-neutral-500">{{ now()->isoFormat('dddd D MMMM') }}</span>
            </div>
            <div class="flex items-center gap-4">
                @if($omzet_vandaag > 0)
                <span class="text-xs text-gray-500 dark:text-neutral-400">
                    Omzet: <span class="font-semibold text-gray-700 dark:text-neutral-200">€ {{ number_format($omzet_vandaag / 100, 0, ',', '.') }}</span>
                </span>
                @endif
                <span class="text-xs text-gray-400 dark:text-neutral-500">
                    {{ $vandaagAfspraken->count() }} {{ $vandaagAfspraken->count() === 1 ? 'afspraak' : 'afspraken' }}
                </span>
            </div>
        </div>

        @if($vandaagAfspraken->isEmpty())
        <div class="px-4 py-6 text-center">
            <p class="text-sm text-gray-400 dark:text-neutral-500">Geen afspraken vandaag</p>
            <button wire:click="openNieuwFormulier('{{ today()->toDateString() }}', '09:00')"
                    class="mt-2 inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Afspraak inplannen
            </button>
        </div>
        @else
        <div class="flex items-center gap-2 px-4 py-3 overflow-x-auto">
            @foreach($vandaagAfspraken as $va)
            @php
                $isPast = \Carbon\Carbon::parse(today()->toDateString() . ' ' . $va->eind_tijd)->isPast();
                $isActive = \Carbon\Carbon::parse(today()->toDateString() . ' ' . $va->start_tijd)->isPast()
                         && \Carbon\Carbon::parse(today()->toDateString() . ' ' . $va->eind_tijd)->isFuture();

                $chipKleur = match(true) {
                    $va->status === 'voltooid'    => 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800/40',
                    $va->status === 'no_show'     => 'bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800/40',
                    $va->status === 'geannuleerd' => 'bg-gray-50 border-gray-200 dark:bg-neutral-700 dark:border-neutral-600',
                    $isActive                     => 'bg-blue-50 border-blue-300 dark:bg-blue-900/20 dark:border-blue-700 ring-1 ring-blue-300 dark:ring-blue-700',
                    $isPast                       => 'bg-gray-50 border-gray-200 dark:bg-neutral-700/50 dark:border-neutral-600',
                    default                       => 'bg-white border-gray-200 dark:bg-neutral-800 dark:border-neutral-700',
                };
                $dotKleur = match($va->status) {
                    'voltooid'    => 'bg-green-500',
                    'no_show'     => 'bg-red-500',
                    'geannuleerd' => 'bg-gray-400',
                    default       => ($isActive ? 'bg-blue-500 animate-pulse' : ($isPast ? 'bg-gray-400' : 'bg-blue-400')),
                };
            @endphp
            <button wire:click="selecteerAfspraak({{ $va->id }})"
                    class="flex-shrink-0 flex items-center gap-2.5 px-3 py-2 rounded-xl border text-left transition-colors hover:shadow-sm {{ $chipKleur }}">
                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $dotKleur }}"></span>
                <div>
                    <p class="text-xs font-semibold text-gray-800 dark:text-neutral-100 whitespace-nowrap">
                        {{ $va->start_tijd }} {{ $va->klant_naam }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 whitespace-nowrap">{{ $va->dienst->naam }}</p>
                </div>
            </button>
            @if(!$loop->last)
            <div class="w-4 h-px bg-gray-200 dark:bg-neutral-700 flex-shrink-0"></div>
            @endif
            @endforeach

            <button wire:click="openNieuwFormulier('{{ today()->toDateString() }}', '09:00')"
                    class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2 rounded-xl border border-dashed border-gray-300 dark:border-neutral-600 text-xs text-gray-400 dark:text-neutral-500 hover:border-blue-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors ml-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Toevoegen
            </button>
        </div>

        @if($volgendeAfspraak)
        <div class="px-4 py-2.5 border-t border-gray-50 dark:border-neutral-700/50 flex items-center gap-2">
            <svg class="w-3.5 h-3.5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs text-gray-500 dark:text-neutral-400">
                Volgende: <span class="font-semibold text-gray-700 dark:text-neutral-200">{{ $volgendeAfspraak->klant_naam }}</span>
                om <span class="font-semibold text-gray-700 dark:text-neutral-200">{{ $volgendeAfspraak->start_tijd }}</span>
                <span class="text-gray-400 dark:text-neutral-500 ml-1">
                    (over {{ \Carbon\Carbon::parse(today()->toDateString() . ' ' . $volgendeAfspraak->start_tijd)->diffForHumans(['parts' => 1]) }})
                </span>
            </span>
        </div>
        @endif
        @endif
    </div>

    {{-- ===== MOBIELE DAG VIEW (alleen zichtbaar op < lg) ===== --}}
    @php $mobielDate = \Carbon\Carbon::parse($mobielDatum); @endphp
    <div class="lg:hidden mb-6">
        {{-- Datum navigatie --}}
        <div class="flex items-center justify-between mb-4">
            <button wire:click="vorigeDag"
                    class="p-2 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <div class="text-center flex-1 px-3">
                <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100">
                    {{ $mobielDate->isoFormat('dddd') }}
                </p>
                <p class="text-xs text-gray-400 dark:text-neutral-500">
                    {{ $mobielDate->isoFormat('D MMMM YYYY') }}
                    @if($mobielDate->isToday())
                    <span class="ml-1 inline-flex px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-600 text-white">Vandaag</span>
                    @endif
                </p>
            </div>

            <button wire:click="volgendeDag"
                    class="p-2 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        {{-- Terug naar vandaag (alleen als niet vandaag) --}}
        @if(!$mobielDate->isToday())
        <div class="flex justify-center mb-4">
            <button wire:click="naarVandaagMobiel"
                    class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                ← Terug naar vandaag
            </button>
        </div>
        @endif

        {{-- Afspraken lijst --}}
        <div class="space-y-2">
            @foreach($mobielBlokkeringen as $blokkering)
            <button wire:click="selecteerBlokkering({{ $blokkering->id }})"
                    class="w-full text-left flex items-center gap-3 p-3 rounded-xl border-l-4 border-gray-400 bg-gray-100 dark:bg-neutral-700/50 border border-gray-200 dark:border-neutral-700 transition-colors hover:shadow-sm"
                    style="border-left-color: #9ca3af;">
                <div class="text-center flex-shrink-0 w-12">
                    <p class="text-sm font-bold text-gray-600 dark:text-neutral-400">{{ $blokkering->start_tijd }}</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $blokkering->eind_tijd }}</p>
                </div>
                <div class="w-px self-stretch bg-gray-200 dark:bg-neutral-600 flex-shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-600 dark:text-neutral-400 truncate">
                        🚫 {{ $blokkering->reden ?: 'Geblokkeerd' }}
                    </p>
                </div>
            </button>
            @endforeach

            @forelse($mobielAfspraken as $afspraak)
            @php
                $kleur = match($afspraak->status) {
                    'voltooid'    => 'border-green-400 bg-green-50 dark:bg-green-900/20',
                    'no_show'     => 'border-red-400 bg-red-50 dark:bg-red-900/20',
                    'geannuleerd' => 'border-gray-300 bg-gray-50 dark:bg-neutral-800',
                    default       => 'border-blue-400 bg-blue-50 dark:bg-blue-900/20',
                };
            @endphp
            <button wire:click="selecteerAfspraak({{ $afspraak->id }})"
                    class="w-full text-left flex items-center gap-3 p-3 rounded-xl border-l-4 {{ $kleur }} border border-gray-200 dark:border-neutral-700 transition-colors hover:shadow-sm">
                <div class="text-center flex-shrink-0 w-12">
                    <p class="text-sm font-bold text-gray-800 dark:text-neutral-100">{{ $afspraak->start_tijd }}</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $afspraak->eind_tijd }}</p>
                </div>
                <div class="w-px self-stretch bg-gray-200 dark:bg-neutral-700 flex-shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100 truncate">
                        {{ $afspraak->klant_naam }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-neutral-400 truncate">
                        {{ $afspraak->dienst->naam }} · € {{ $afspraak->dienst->prijs_in_euros }}
                    </p>
                </div>
                <div class="flex-shrink-0">
                    @php
                        $dot = match($afspraak->status) {
                            'voltooid'    => 'bg-green-500',
                            'no_show'     => 'bg-red-500',
                            'geannuleerd' => 'bg-gray-400',
                            default       => 'bg-blue-500',
                        };
                    @endphp
                    <span class="w-2 h-2 rounded-full inline-block {{ $dot }}"></span>
                </div>
            </button>
            @empty
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl px-4 py-10 text-center">
                <p class="text-sm text-gray-400 dark:text-neutral-500 mb-3">Geen afspraken</p>
                <button wire:click="openNieuwFormulier('{{ $mobielDatum }}', '09:00')"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Afspraak inplannen
                </button>
            </div>
            @endforelse

            {{-- + knop onderaan lijst --}}
            @if($mobielAfspraken->isNotEmpty())
            <button wire:click="openNieuwFormulier('{{ $mobielDatum }}', '09:00')"
                    class="w-full flex items-center justify-center gap-2 py-3 rounded-xl border border-dashed border-gray-300 dark:border-neutral-600 text-sm text-gray-500 dark:text-neutral-400 hover:border-blue-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Afspraak toevoegen
            </button>
            @endif
        </div>
    </div>

    {{-- Week navigatie (alleen desktop) --}}
    <div class="hidden lg:block bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">

        {{-- Header toolbar --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-neutral-700">
            <div class="flex items-center gap-2">
                <button wire:click="vorigeWeek"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button wire:click="volgendeWeek"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <span class="text-sm font-semibold text-gray-700 dark:text-neutral-200">
                    {{ $weekStartDate->isoFormat('D MMMM') }} – {{ $weekStartDate->copy()->endOfWeek()->isoFormat('D MMMM YYYY') }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="naarVandaag"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                    Vandaag
                </button>
                <button wire:click="openWalkIn"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-600 text-white hover:bg-green-700 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Walk-in
                </button>
                <button wire:click="openBlokkerenForm('{{ today()->toDateString() }}')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-neutral-200 hover:bg-gray-300 dark:hover:bg-neutral-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Blokkeer
                </button>
            </div>
        </div>

        {{-- Dag headers --}}
        <div class="flex border-b border-gray-100 dark:border-neutral-700">
            <div class="w-14 flex-shrink-0"></div>
            @foreach($days as $day)
            <div class="flex-1 px-2 py-2 text-center border-l border-gray-100 dark:border-neutral-700
                {{ $day->isToday() ? 'bg-blue-50 dark:bg-blue-900/10' : '' }}">
                <p class="text-xs text-gray-400 dark:text-neutral-500 uppercase tracking-wide">{{ $day->isoFormat('ddd') }}</p>
                <p class="text-sm font-semibold mt-0.5
                    {{ $day->isToday() ? 'text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-neutral-300' }}">
                    {{ $day->format('d') }}
                </p>
            </div>
            @endforeach
        </div>

        {{-- Kalender grid --}}
        @php
            $dagStart = 8;   // 08:00
            $dagEind  = 19;  // 19:00
            $uren     = $dagEind - $dagStart;
            $pxPerUur = 64;
            $hoogte   = $uren * $pxPerUur; // totaal px
        @endphp

        <div class="flex overflow-y-auto" style="max-height: 600px">
            {{-- Tijdlijn --}}
            <div class="w-14 flex-shrink-0 relative" style="height: {{ $hoogte }}px">
                @for ($u = $dagStart; $u < $dagEind; $u++)
                <div class="absolute w-full flex items-start justify-end pr-2"
                     style="top: {{ ($u - $dagStart) * $pxPerUur }}px; height: {{ $pxPerUur }}px">
                    <span class="text-xs text-gray-400 dark:text-neutral-600 -mt-2">
                        {{ str_pad($u, 2, '0', STR_PAD_LEFT) }}:00
                    </span>
                </div>
                @endfor
            </div>

            {{-- Dag kolommen --}}
            @foreach($days as $day)
            @php $dagKey = $day->toDateString(); @endphp
            <div class="flex-1 relative border-l border-gray-100 dark:border-neutral-700
                {{ $day->isToday() ? 'bg-blue-50/30 dark:bg-blue-900/5' : '' }}"
                 style="height: {{ $hoogte }}px"
                 x-data
                 @click="
                    const rect = $el.getBoundingClientRect();
                    const scrollTop = $el.closest('.overflow-y-auto')?.scrollTop ?? 0;
                    const y = $event.clientY - rect.top + scrollTop;
                    const minFromTop = Math.floor(y / {{ $pxPerUur }} * 60);
                    const roundedMin = Math.round(minFromTop / 15) * 15;
                    const hour = Math.floor(roundedMin / 60) + {{ $dagStart }};
                    const min = roundedMin % 60;
                    if (hour < {{ $dagStart }} || hour >= {{ $dagEind }}) return;
                    const tijd = String(hour).padStart(2,'0') + ':' + String(min).padStart(2,'0');
                    $wire.openNieuwFormulier('{{ $dagKey }}', tijd);
                 "
            >

                {{-- Uurlijnen (pointer-events-none zodat klikken doorgaan naar parent) --}}
                @for ($u = 0; $u < $uren; $u++)
                <div class="absolute w-full border-t pointer-events-none {{ $u === 0 ? 'border-gray-200 dark:border-neutral-600' : 'border-gray-100 dark:border-neutral-700/50' }}"
                     style="top: {{ $u * $pxPerUur }}px"></div>
                @endfor

                {{-- Halvuurslijnen --}}
                @for ($u = 0; $u < $uren; $u++)
                <div class="absolute w-full border-t pointer-events-none border-gray-50 dark:border-neutral-800"
                     style="top: {{ $u * $pxPerUur + $pxPerUur / 2 }}px"></div>
                @endfor

                {{-- Huidig tijdstip lijn --}}
                @if($day->isToday())
                @php
                    $now = now();
                    $nowMin = ($now->hour - $dagStart) * 60 + $now->minute;
                    $nowTop = ($nowMin / 60) * $pxPerUur;
                @endphp
                @if($nowMin >= 0 && $nowTop <= $hoogte)
                <div class="absolute w-full z-10 flex items-center pointer-events-none" style="top: {{ $nowTop }}px">
                    <div class="w-2 h-2 rounded-full bg-blue-500 -ml-1 flex-shrink-0"></div>
                    <div class="flex-1 h-px bg-blue-500"></div>
                </div>
                @endif
                @endif

                {{-- Blokkeringen --}}
                @foreach($blokkeringenPerDag[$dagKey] ?? [] as $blokkering)
                @php
                    [$bsh, $bsm] = explode(':', $blokkering->start_tijd);
                    $bStartMin = ((int)$bsh - $dagStart) * 60 + (int)$bsm;
                    [$beh, $bem] = explode(':', $blokkering->eind_tijd);
                    $bEindMin  = ((int)$beh - $dagStart) * 60 + (int)$bem;
                    $bTop    = ($bStartMin / 60) * $pxPerUur;
                    $bHeight = max(20, (($bEindMin - $bStartMin) / 60) * $pxPerUur - 2);
                    $bSelected = $geselecteerdeblokkering?->id === $blokkering->id;
                @endphp
                <button
                    wire:click="selecteerBlokkering({{ $blokkering->id }})"
                    @click.stop
                    class="absolute left-1 right-1 rounded-md px-1.5 py-0.5 text-left transition-all cursor-pointer z-10
                        bg-gray-200 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600
                        hover:bg-gray-300 dark:hover:bg-neutral-600
                        {{ $bSelected ? 'ring-2 ring-offset-1 ring-gray-400 z-20' : '' }}"
                    style="top: {{ $bTop }}px; height: {{ $bHeight }}px; background-image: repeating-linear-gradient(45deg, transparent, transparent 4px, rgba(0,0,0,0.05) 4px, rgba(0,0,0,0.05) 8px);"
                >
                    <p class="text-xs font-semibold text-gray-500 dark:text-neutral-400 truncate leading-tight">
                        {{ $blokkering->reden ?: 'Geblokkeerd' }}
                    </p>
                </button>
                @endforeach

                {{-- Afspraken --}}
                @foreach($afsprakenPerDag[$dagKey] ?? [] as $afspraak)
                @php
                    [$sh, $sm] = explode(':', $afspraak->start_tijd);
                    $startMinFromTop = ((int)$sh - $dagStart) * 60 + (int)$sm;
                    $top    = ($startMinFromTop / 60) * $pxPerUur;
                    $height = max(24, ($afspraak->dienst->duur_minuten / 60) * $pxPerUur - 2);
                    $isSelected = $geselecteerdeAfspraak?->id === $afspraak->id;

                    $kleur = match($afspraak->status) {
                        'gepland'     => 'bg-blue-500 hover:bg-blue-600 border-blue-600 text-white',
                        'voltooid'    => 'bg-green-500 hover:bg-green-600 border-green-600 text-white',
                        'no_show'     => 'bg-red-400 hover:bg-red-500 border-red-500 text-white',
                        'geannuleerd' => 'bg-gray-300 hover:bg-gray-400 border-gray-400 text-gray-600 dark:bg-neutral-600 dark:text-neutral-300',
                        default       => 'bg-blue-500 text-white',
                    };
                @endphp
                <button
                    wire:click="selecteerAfspraak({{ $afspraak->id }})"
                    @click.stop
                    class="absolute left-1 right-1 rounded-md border-l-2 px-1.5 py-0.5 text-left transition-all cursor-pointer {{ $kleur }}
                        {{ $isSelected ? 'ring-2 ring-offset-1 ring-blue-400 z-20' : 'z-10' }}"
                    style="top: {{ $top }}px; height: {{ $height }}px"
                >
                    <p class="text-xs font-semibold truncate leading-tight">{{ $afspraak->klant_naam }}</p>
                    @if($height > 35)
                    <p class="text-xs opacity-80 truncate leading-tight">{{ $afspraak->dienst->naam }}</p>
                    @endif
                </button>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

    {{-- ===== MODAL OVERLAY ===== --}}
    @if($toonNieuwFormulier || $geselecteerdeAfspraak || $toonBlokkerenForm || $geselecteerdeblokkering)
    <div
        x-data
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50" wire:click="sluitAlles"></div>

        {{-- Modal card — bottom sheet op mobiel, centered card op desktop --}}
        <div
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
            class="relative w-full sm:max-w-md bg-white dark:bg-neutral-800 sm:rounded-2xl rounded-t-2xl shadow-2xl overflow-hidden"
        >
            {{-- Drag handle (mobiel) --}}
            <div class="sm:hidden flex justify-center pt-3 pb-1">
                <div class="w-10 h-1 bg-gray-200 dark:bg-neutral-600 rounded-full"></div>
            </div>

            @if($toonNieuwFormulier)
            {{-- ===== NIEUW FORMULIER ===== --}}
            <div class="px-5 pt-4 pb-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Afspraak inplannen</h3>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                            {{ \Carbon\Carbon::parse($nieuwDatum)->isoFormat('dddd D MMMM') }} · {{ $nieuwTijd }}
                        </p>
                    </div>
                    <button wire:click="sluitAlles" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="afspraakOpslaan" class="space-y-4">

                    {{-- Walk-in toggle --}}
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-neutral-700/40">
                        <button type="button" wire:click="$set('isWalkIn', false)"
                                class="flex-1 py-1.5 rounded-md text-xs font-semibold transition-colors {{ !$isWalkIn ? 'bg-white dark:bg-neutral-800 text-gray-800 dark:text-neutral-100 shadow-sm' : 'text-gray-500 dark:text-neutral-400' }}">
                            Bestaande klant
                        </button>
                        <button type="button" wire:click="$set('isWalkIn', true)"
                                class="flex-1 py-1.5 rounded-md text-xs font-semibold transition-colors {{ $isWalkIn ? 'bg-green-600 text-white shadow-sm' : 'text-gray-500 dark:text-neutral-400' }}">
                            Walk-in
                        </button>
                    </div>

                    {{-- Klant veld --}}
                    @if($isWalkIn)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Naam klant</label>
                        <input wire:model="walkInNaam" type="text" placeholder="Voornaam..."
                               class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('walkInNaam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    @else
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Klant</label>
                        <input wire:model.live="klantZoekterm" type="text" placeholder="Zoek op naam of email..."
                               autocomplete="off"
                               class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('klantZoekterm') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

                        @if($toonKlantDropdown && $zoekKlanten->count())
                        <div class="absolute left-0 right-0 top-full mt-1 z-50 bg-white dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 rounded-xl shadow-xl overflow-hidden">
                            @foreach($zoekKlanten as $klant)
                            <button type="button"
                                    wire:click="selecteerKlant({{ $klant->id }}, '{{ addslashes($klant->name) }}')"
                                    class="w-full text-left flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                                <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                    <span class="text-blue-700 dark:text-blue-400 font-bold text-xs">{{ mb_strtoupper(mb_substr($klant->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-100">{{ $klant->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $klant->email }}</p>
                                </div>
                            </button>
                            @endforeach
                        </div>
                        @endif
                        @if($geselecteerdeKlantId)
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Bestaande klant geselecteerd
                        </p>
                        @endif
                    </div>
                    @endif

                    {{-- Dienst --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Dienst</label>
                        <x-select
                            wire-target="nieuwDienstId"
                            :current="$nieuwDienstId"
                            :options="$eigenDiensten->mapWithKeys(fn($d) => [$d->id => $d->naam . ' — ' . $d->duur_minuten . ' min · €' . $d->prijs_in_euros])->toArray()"
                            placeholder="Kies dienst"
                        />
                        @error('nieuwDienstId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tijd + betaling --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Begintijd</label>
                            <input wire:model="nieuwTijd" type="time"
                                   class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Betaling</label>
                            <x-select
                                wire-target="nieuwBetaalmethode"
                                :current="$nieuwBetaalmethode"
                                :options="['in_zaak' => 'In de zaak', 'online' => 'Online']"
                                placeholder="Betaalmethode"
                            />
                        </div>
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button type="button" wire:click="sluitAlles"
                                class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                            Annuleer
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                            Inplannen
                        </button>
                    </div>
                </form>
            </div>

            @elseif($toonBlokkerenForm)
            {{-- ===== BLOKKEER FORMULIER ===== --}}
            <div class="px-5 pt-4 pb-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Tijdslot blokkeren</h3>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Klanten kunnen dit slot niet boeken</p>
                    </div>
                    <button wire:click="sluitAlles" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form wire:submit="blokkeerOpslaan" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Datum</label>
                        <input wire:model="blokkeerDatum" type="date"
                               class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('blokkeerDatum') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Van</label>
                            <input wire:model="blokkeerStartTijd" type="time"
                                   class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            @error('blokkeerStartTijd') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Tot</label>
                            <input wire:model="blokkeerEindTijd" type="time"
                                   class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            @error('blokkeerEindTijd') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Reden <span class="text-gray-400 font-normal">(optioneel)</span></label>
                        <input wire:model="blokkeerReden" type="text" placeholder="bijv. Lunch, Vergadering, Vakantie..."
                               class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="button" wire:click="sluitAlles"
                                class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                            Annuleer
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 text-sm font-semibold rounded-lg bg-gray-700 dark:bg-neutral-600 text-white hover:bg-gray-800 dark:hover:bg-neutral-500 transition-colors">
                            Blokkeer slot
                        </button>
                    </div>
                </form>
            </div>

            @elseif($geselecteerdeblokkering)
            {{-- ===== BLOKKERING DETAIL ===== --}}
            @php $b = $geselecteerdeblokkering; @endphp
            <div class="px-5 pt-4 pb-6">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Geblokkeerd slot</h3>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                            {{ $b->datum->isoFormat('dddd D MMMM') }} · {{ $b->start_tijd }} – {{ $b->eind_tijd }}
                        </p>
                    </div>
                    <button wire:click="sluitAlles" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @if($b->reden)
                <div class="mb-5 p-3 rounded-lg bg-gray-50 dark:bg-neutral-700/40">
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mb-0.5">Reden</p>
                    <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">{{ $b->reden }}</p>
                </div>
                @endif
                <div class="flex gap-2 pt-4 border-t border-gray-100 dark:border-neutral-700">
                    <button wire:click="sluitAlles"
                            class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        Sluiten
                    </button>
                    <button @click.prevent="$dispatch('open-confirm', { title: 'Blokkering verwijderen', message: 'Tijdslot vrijgeven? Klanten kunnen daarna weer boeken.', action: () => $wire.verwijderBlokkering({{ $b->id }}) })"
                            class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-red-300 text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                        Verwijder blokkering
                    </button>
                </div>
            </div>

            @elseif($geselecteerdeAfspraak)
            {{-- ===== DETAIL PANEL ===== --}}
            @php $a = $geselecteerdeAfspraak; @endphp
            <div class="px-5 pt-4 pb-6">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">{{ $a->klant_naam }}</h3>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                            {{ $a->datum->isoFormat('dddd D MMMM') }} · {{ $a->start_tijd }} – {{ $a->eind_tijd }}
                        </p>
                    </div>
                    <button wire:click="sluitAlles" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-0.5">Dienst</p>
                        <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">{{ $a->dienst->naam }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-0.5">Prijs</p>
                        <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">€ {{ $a->dienst->prijs_in_euros }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-0.5">Betaling</p>
                        <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">
                            {{ $a->betaalmethode === 'online' ? 'Online betaald' : 'In de zaak' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-0.5">Status</p>
                        @php
                            $badge = match($a->status) {
                                'gepland'     => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'voltooid'    => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                                'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                default       => 'bg-gray-100 text-gray-500',
                            };
                        @endphp
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                            {{ ucfirst(str_replace('_', ' ', $a->status)) }}
                        </span>
                    </div>
                </div>

                @if($a->status === 'gepland')
                <div class="flex gap-2 pt-4 border-t border-gray-100 dark:border-neutral-700">
                    <button wire:click="voltooid({{ $a->id }})"
                            class="flex-1 py-2.5 text-sm font-medium rounded-lg bg-green-600 text-white hover:bg-green-700 transition-colors">
                        Voltooid
                    </button>
                    <button @click.prevent="$dispatch('open-confirm', { title: 'No-show markeren', message: 'Klant markeren als no-show? Dit kan niet ongedaan worden gemaakt.', action: () => $wire.noShow({{ $a->id }}) })"
                            class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-red-300 text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                        No-show
                    </button>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
