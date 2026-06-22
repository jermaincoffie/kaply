<div>
    {{-- Welkomstbanner na onboarding --}}
    @if(session('onboarding_klaar'))
    <div class="flex items-center gap-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-5 py-4 rounded-xl mb-6">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <div>
            <p class="text-sm font-semibold">Jouw salon is live! 🎉</p>
            <p class="text-xs mt-0.5 text-green-600 dark:text-green-500">Klanten kunnen nu afspraken boeken bij {{ auth()->user()->kapper?->salon_naam }}.</p>
        </div>
    </div>
    @endif

    {{-- Wachtlijst kaart (alleen tonen als er wachtenden zijn) --}}
    @if($wachtlijst->isNotEmpty())
    <div x-data="{ open: false }" class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl mb-6 overflow-hidden">
        <button type="button" @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-3 text-left">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">
                    Wachtlijst — {{ $wachtlijst->count() }} {{ $wachtlijst->count() === 1 ? 'persoon' : 'personen' }} wacht{{ $wachtlijst->count() === 1 ? '' : 'en' }} op een plek
                </p>
            </div>
            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 transition-transform duration-200 flex-shrink-0"
                 :class="open ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="px-4 pb-4">
            <div class="space-y-2">
                @foreach($wachtlijst as $wachtende)
                <div class="flex items-center justify-between bg-white dark:bg-neutral-800 rounded-lg px-3 py-2">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">{{ $wachtende->naam }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500">
                            {{ $wachtende->email }}
                            @if($wachtende->gewenste_datum)
                                @if(\Carbon\Carbon::parse($wachtende->gewenste_datum)->isToday())
                                · <span class="font-semibold text-red-600 dark:text-red-400">Vandaag — zelf bellen</span>
                                @else
                                · <span class="text-amber-600 dark:text-amber-400 font-medium">{{ \Carbon\Carbon::parse($wachtende->gewenste_datum)->translatedFormat('d M Y') }}</span>
                                @endif
                            @else
                            · <span class="italic">geen datum opgegeven</span>
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                        @if($wachtende->telefoonnummer)
                        <a href="tel:{{ $wachtende->telefoonnummer }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $wachtende->telefoonnummer }}
                        </a>
                        @endif
                        <button type="button"
                                @click.prevent="$dispatch('open-confirm', { title: 'Van wachtlijst verwijderen', message: 'Weet je zeker dat je {{ addslashes($wachtende->naam) }} van de wachtlijst wilt verwijderen?', action: () => $wire.wachtlijstVerwijderen({{ $wachtende->id }}) })"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            <p class="text-xs text-amber-600 dark:text-amber-500 mt-3">Bij een annulering vandaag worden zij niet automatisch gemaild — bel ze zelf op.</p>
        </div>
    </div>
    @endif

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

    {{-- Stats (tabbed) --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl mb-6"
         x-data="{ tab: 'vandaag' }">
        {{-- Tab headers --}}
        <div class="flex border-b border-gray-100 dark:border-neutral-700">
            <button type="button" @click="tab = 'vandaag'"
                    :class="tab === 'vandaag' ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-400 dark:text-neutral-500 hover:text-gray-600 dark:hover:text-neutral-300'"
                    class="flex-1 px-4 py-2.5 text-sm font-medium transition-colors">
                Vandaag
            </button>
            <button type="button" @click="tab = 'maand'"
                    :class="tab === 'maand' ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-400 dark:text-neutral-500 hover:text-gray-600 dark:hover:text-neutral-300'"
                    class="flex-1 px-4 py-2.5 text-sm font-medium transition-colors capitalize">
                {{ now()->isoFormat('MMMM') }}
            </button>
        </div>

        {{-- Vandaag --}}
        <div x-show="tab === 'vandaag'" class="grid grid-cols-3 divide-x divide-gray-100 dark:divide-neutral-700">
            <div class="p-4">
                <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-1.5">Omzet</p>
                <span class="text-xl font-bold text-gray-900 dark:text-neutral-100">€ {{ number_format($omzet_vandaag / 100, 0, ',', '.') }}</span>
            </div>
            <div class="p-4">
                <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-1.5">Afspraken</p>
                <span class="text-xl font-bold text-gray-900 dark:text-neutral-100">{{ $vandaagAfspraken->count() }}</span>
            </div>
            <div class="p-4">
                <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-1.5">Komende</p>
                <span class="text-xl font-bold text-gray-900 dark:text-neutral-100">{{ $komende_afspraken }}</span>
            </div>
        </div>

        {{-- Maand --}}
        <div x-show="tab === 'maand'" style="display:none" class="grid grid-cols-3 divide-x divide-gray-100 dark:divide-neutral-700">
            <div class="p-4">
                <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-1.5">Omzet</p>
                <span class="text-xl font-bold text-gray-900 dark:text-neutral-100">€ {{ number_format($omzet_maand / 100, 0, ',', '.') }}</span>
            </div>
            <div class="p-4">
                <div class="flex items-center mb-1.5">
                    <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Bezetting</p>
                    <x-tooltip position="below-right">Hoeveel procent van je beschikbare werktijd deze maand al gevuld is met afspraken.</x-tooltip>
                </div>
                @if($bezettingsgraad !== null)
                <span class="text-xl font-bold text-gray-900 dark:text-neutral-100">{{ $bezettingsgraad }}%</span>
                <div class="mt-2 bg-gray-100 dark:bg-neutral-700 rounded-full h-1">
                    <div class="h-1 rounded-full {{ $bezettingsgraad >= 80 ? 'bg-green-500' : ($bezettingsgraad >= 50 ? 'bg-blue-500' : 'bg-gray-400') }}"
                         style="width: {{ $bezettingsgraad }}%"></div>
                </div>
                @else
                <span class="text-xl font-bold text-gray-300 dark:text-neutral-600">—</span>
                @endif
            </div>
            <div class="p-4">
                <div class="flex items-center mb-1.5">
                    <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">No-show</p>
                    <x-tooltip position="below-right">Percentage van afgeronde afspraken waarbij de klant niet is komen opdagen. Boven 20% is een aandachtspunt.</x-tooltip>
                </div>
                @if($no_show_pct !== null)
                <span class="text-xl font-bold {{ $no_show_pct >= 20 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-neutral-100' }}">{{ $no_show_pct }}%</span>
                @else
                <span class="text-xl font-bold text-gray-300 dark:text-neutral-600">—</span>
                @endif
            </div>
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

        {{-- Header: datum nav --}}
        <div class="flex items-center justify-between mb-3">
            <button wire:click="vorigeDag"
                    class="p-2 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <div class="text-center flex-1 px-3">
                <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100">
                    {{ $mobielDate->isoFormat('dddd') }}
                    @if($mobielDate->isToday())
                    <span class="ml-1.5 inline-flex px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-600 text-white">Vandaag</span>
                    @endif
                </p>
                <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $mobielDate->isoFormat('D MMMM YYYY') }}</p>
            </div>

            <button wire:click="volgendeDag"
                    class="p-2 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        {{-- Actieknoppen --}}
        <div class="flex items-center gap-2 mb-3">
            @if(!$mobielDate->isToday())
            <button wire:click="naarVandaagMobiel"
                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                Vandaag
            </button>
            @endif
            <button wire:click="openWalkIn"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-600 text-white hover:bg-green-700 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Walk-in
            </button>
            <button wire:click="openBlokkerenForm('{{ $mobielDatum }}')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                Blokkeer
            </button>
            <button wire:click="openPauzeForm('{{ $mobielDatum }}')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-900/50 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Pauze
            </button>
        </div>

        {{-- Medewerker filter chips (mobiel) --}}
        @if($medewerkers->count() > 0)
        <div class="flex items-center gap-2 overflow-x-auto pb-1 mb-3">
            <button wire:click="filterMedewerker(null)"
                    class="flex-shrink-0 px-3 py-1 rounded-full text-xs font-medium transition-colors
                        {{ $gefilterdeMedewerkerId === null
                            ? 'bg-gray-800 dark:bg-neutral-100 text-white dark:text-neutral-900'
                            : 'bg-gray-100 dark:bg-neutral-700 text-gray-600 dark:text-neutral-300' }}">
                Alle
            </button>
            @foreach($medewerkers as $mw)
            <button wire:click="filterMedewerker({{ $mw->id }})"
                    class="flex-shrink-0 px-3 py-1 rounded-full text-xs font-medium capitalize transition-colors
                        {{ $gefilterdeMedewerkerId === $mw->id
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-100 dark:bg-neutral-700 text-gray-600 dark:text-neutral-300' }}">
                {{ $mw->naam }}
            </button>
            @endforeach
        </div>
        @endif

        {{-- Tijdlijn --}}
        @php
            $mDagStart = 8;
            $mDagEind  = 19;
            $mUren     = $mDagEind - $mDagStart;
            $mPxPerUur = 120;
            $mHoogte   = $mUren * $mPxPerUur;
        @endphp

        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
            <div class="overflow-y-auto" style="max-height: 65vh">
                <div class="flex" style="height: {{ $mHoogte }}px">

                    {{-- Tijdlabels links --}}
                    <div class="w-11 flex-shrink-0 relative border-r border-gray-100 dark:border-neutral-700/50">
                        @for ($u = $mDagStart; $u < $mDagEind; $u++)
                        <div class="absolute flex items-start justify-end pr-1.5 w-full"
                             style="top: {{ ($u - $mDagStart) * $mPxPerUur }}px">
                            <span class="text-[10px] text-gray-500 dark:text-neutral-400 -mt-2 leading-none font-medium">
                                {{ str_pad($u, 2, '0', STR_PAD_LEFT) }}:00
                            </span>
                        </div>
                        <div class="absolute flex items-start justify-end pr-1.5 w-full"
                             style="top: {{ ($u - $mDagStart) * $mPxPerUur + $mPxPerUur / 2 }}px">
                            <span class="text-[9px] text-gray-300 dark:text-neutral-600 -mt-1.5 leading-none">
                                :30
                            </span>
                        </div>
                        @endfor
                    </div>

                    {{-- Dag kolom --}}
                    <div class="flex-1 relative"
                         style="height: {{ $mHoogte }}px"
                         x-data
                         @click="
                            const rect = $el.getBoundingClientRect();
                            const scrollTop = $el.closest('.overflow-y-auto')?.scrollTop ?? 0;
                            const y = $event.clientY - rect.top + scrollTop;
                            const minFromTop = Math.floor(y / {{ $mPxPerUur }} * 60);
                            const roundedMin = Math.round(minFromTop / 15) * 15;
                            const hour = Math.floor(roundedMin / 60) + {{ $mDagStart }};
                            const min = roundedMin % 60;
                            if (hour < {{ $mDagStart }} || hour >= {{ $mDagEind }}) return;
                            const tijd = String(hour).padStart(2,'0') + ':' + String(min).padStart(2,'0');
                            $wire.openNieuwFormulier('{{ $mobielDatum }}', tijd);
                         "
                    >
                        {{-- Uurlijnen + kwartierlijnen --}}
                        @for ($u = 0; $u < $mUren; $u++)
                        <div class="absolute w-full border-t pointer-events-none {{ $u === 0 ? 'border-gray-200 dark:border-neutral-600' : 'border-gray-100 dark:border-neutral-700/50' }}"
                             style="top: {{ $u * $mPxPerUur }}px"></div>
                        <div class="absolute w-full border-t pointer-events-none border-gray-100 dark:border-neutral-700/60"
                             style="top: {{ $u * $mPxPerUur + $mPxPerUur / 2 }}px"></div>
                        <div class="absolute w-full border-t pointer-events-none border-dashed border-gray-100 dark:border-neutral-800"
                             style="top: {{ $u * $mPxPerUur + $mPxPerUur / 4 }}px"></div>
                        <div class="absolute w-full border-t pointer-events-none border-dashed border-gray-100 dark:border-neutral-800"
                             style="top: {{ $u * $mPxPerUur + $mPxPerUur * 3 / 4 }}px"></div>
                        @endfor

                        {{-- Huidig tijdstip --}}
                        @if($mobielDate->isToday())
                        @php
                            $mNow = now();
                            $mNowMin = ($mNow->hour - $mDagStart) * 60 + $mNow->minute;
                            $mNowTop = ($mNowMin / 60) * $mPxPerUur;
                        @endphp
                        @if($mNowMin >= 0 && $mNowTop <= $mHoogte)
                        <div class="absolute w-full z-10 flex items-center pointer-events-none" style="top: {{ $mNowTop }}px">
                            <div class="w-2.5 h-2.5 rounded-full bg-blue-500 -ml-1.5 flex-shrink-0"></div>
                            <div class="flex-1 h-px bg-blue-500"></div>
                        </div>
                        @endif
                        @endif

                        {{-- Blokkeringen --}}
                        @foreach($mobielBlokkeringen as $blokkering)
                        @php
                            [$bsh, $bsm] = explode(':', $blokkering->start_tijd);
                            $bStartMin = ((int)$bsh - $mDagStart) * 60 + (int)$bsm;
                            [$beh, $bem] = explode(':', $blokkering->eind_tijd);
                            $bEindMin  = ((int)$beh - $mDagStart) * 60 + (int)$bem;
                            $bTop    = ($bStartMin / 60) * $mPxPerUur;
                            $bHeight = max(24, (($bEindMin - $bStartMin) / 60) * $mPxPerUur - 2);
                        @endphp
                        @php $isPauzeM = ($blokkering->reden === 'Pauze'); @endphp
                        <button wire:click="selecteerBlokkering({{ $blokkering->id }})" @click.stop
                                class="absolute left-1 right-1 rounded-md px-2 py-1 text-left z-10 {{ $isPauzeM ? 'bg-gray-200 dark:bg-neutral-600 border border-gray-300 dark:border-neutral-500' : 'bg-gray-200 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600' }}"
                                style="top: {{ $bTop }}px; height: {{ $bHeight }}px; {{ $isPauzeM ? '' : 'background-image: repeating-linear-gradient(45deg, transparent, transparent 4px, rgba(0,0,0,0.05) 4px, rgba(0,0,0,0.05) 8px);' }}">
                            <p class="text-xs font-semibold truncate leading-tight {{ $isPauzeM ? 'text-gray-600 dark:text-neutral-300' : 'text-gray-500 dark:text-neutral-400' }}">
                                {{ $isPauzeM ? '☕ Pauze' : ('🚫 ' . ($blokkering->reden ?: 'Geblokkeerd')) }}
                            </p>
                        </button>
                        @endforeach

                        {{-- Afspraken --}}
                        @foreach($mobielAfspraken as $afspraak)
                        @php
                            [$sh, $sm] = explode(':', $afspraak->start_tijd);
                            $mStartMin = ((int)$sh - $mDagStart) * 60 + (int)$sm;
                            $mTop    = ($mStartMin / 60) * $mPxPerUur;
                            $mHeight = max(28, ($afspraak->dienst->duur_minuten / 60) * $mPxPerUur - 2);
                            $isWalkInM  = !empty($afspraak->walk_in_naam);
                            $isToekomstM = \Carbon\Carbon::parse($afspraak->datum->toDateString() . ' ' . $afspraak->start_tijd)->isFuture();
                            $mKleur  = match(true) {
                                $isWalkInM && $isToekomstM         => 'bg-emerald-100 border-emerald-300 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-700 dark:text-emerald-300',
                                $isWalkInM                         => 'bg-emerald-500 border-emerald-600 text-white',
                                $afspraak->status === 'voltooid'    => 'bg-green-500 border-green-600 text-white',
                                $afspraak->status === 'no_show'     => 'bg-red-400 border-red-500 text-white',
                                $afspraak->status === 'geannuleerd' => 'bg-gray-300 border-gray-400 text-gray-600 dark:bg-neutral-600 dark:text-neutral-300',
                                $isToekomstM                       => 'bg-blue-100 border-blue-300 text-blue-800 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-300',
                                default                            => 'bg-blue-500 border-blue-600 text-white',
                            };
                        @endphp
                        @php
                            $mAantalKolommen = $gefilterdeMedewerkerId === null && count($medewerkerKolom) > 1
                                ? count($medewerkerKolom)
                                : 1;
                            $mKolomIndex = ($mAantalKolommen > 1 && $afspraak->medewerker_id && isset($medewerkerKolom[$afspraak->medewerker_id]))
                                ? $medewerkerKolom[$afspraak->medewerker_id]
                                : 0;
                            $mColWidthPct = 100 / $mAantalKolommen;
                            $mColLeftPct  = $mKolomIndex * $mColWidthPct;
                        @endphp
                        <button wire:click="selecteerAfspraak({{ $afspraak->id }})" @click.stop
                                class="absolute rounded-md border-l-2 px-2 py-1 text-left z-10 {{ $mKleur }}"
                                style="top: {{ $mTop }}px; height: {{ $mHeight }}px; left: calc({{ $mColLeftPct }}% + 2px); width: calc({{ $mColWidthPct }}% - 4px);">
                            <p class="text-xs font-semibold truncate leading-tight">{{ $afspraak->klant_naam }}</p>
                            @if($mHeight > 42)
                            <p class="text-[11px] opacity-85 truncate leading-tight">{{ $afspraak->dienst->naam }}</p>
                            @endif
                            @if($mHeight > 58)
                            <p class="text-[10px] opacity-70 leading-tight">{{ $afspraak->start_tijd }} – {{ $afspraak->eind_tijd }}</p>
                            @endif
                        </button>
                        @endforeach

                        {{-- Lege staat hint --}}
                        @if($mobielAfspraken->isEmpty() && $mobielBlokkeringen->isEmpty())
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="text-center">
                                <p class="text-sm text-gray-300 dark:text-neutral-600">Geen afspraken</p>
                                <p class="text-xs text-gray-300 dark:text-neutral-700 mt-1">Tik op een tijdslot om in te plannen</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
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
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Blokkeer
                </button>
                <button wire:click="openPauzeForm('{{ today()->toDateString() }}')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-900/50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pauze
                </button>
            </div>
        </div>

        {{-- Medewerker filter tabs (desktop) --}}
        @if($medewerkers->count() > 0)
        <div class="flex items-center gap-2 px-4 py-2 border-b border-gray-100 dark:border-neutral-700 overflow-x-auto">
            <span class="text-xs text-gray-400 dark:text-neutral-500 flex-shrink-0">Barber:</span>
            <button wire:click="filterMedewerker(null)"
                    class="flex-shrink-0 px-3 py-1 rounded-full text-xs font-medium transition-colors
                        {{ $gefilterdeMedewerkerId === null
                            ? 'bg-gray-800 dark:bg-neutral-100 text-white dark:text-neutral-900'
                            : 'bg-gray-100 dark:bg-neutral-700 text-gray-600 dark:text-neutral-300 hover:bg-gray-200 dark:hover:bg-neutral-600' }}">
                Alle
            </button>
            @foreach($medewerkers as $mw)
            <button wire:click="filterMedewerker({{ $mw->id }})"
                    class="flex-shrink-0 px-3 py-1 rounded-full text-xs font-medium capitalize transition-colors
                        {{ $gefilterdeMedewerkerId === $mw->id
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-100 dark:bg-neutral-700 text-gray-600 dark:text-neutral-300 hover:bg-gray-200 dark:hover:bg-neutral-600' }}">
                {{ $mw->naam }}
            </button>
            @endforeach
        </div>
        @endif

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
            $pxPerUur = 120;
            $hoogte   = $uren * $pxPerUur; // totaal px
        @endphp

        <div class="flex overflow-y-auto" style="max-height: 600px">
            {{-- Tijdlijn --}}
            <div class="w-14 flex-shrink-0 relative" style="height: {{ $hoogte }}px">
                @for ($u = $dagStart; $u < $dagEind; $u++)
                {{-- Volledig uur --}}
                <div class="absolute w-full flex items-start justify-end pr-2"
                     style="top: {{ ($u - $dagStart) * $pxPerUur }}px">
                    <span class="text-xs text-gray-500 dark:text-neutral-400 -mt-2 font-medium">
                        {{ str_pad($u, 2, '0', STR_PAD_LEFT) }}:00
                    </span>
                </div>
                {{-- Half uur --}}
                <div class="absolute w-full flex items-start justify-end pr-2"
                     style="top: {{ ($u - $dagStart) * $pxPerUur + $pxPerUur / 2 }}px">
                    <span class="text-[10px] text-gray-300 dark:text-neutral-600 -mt-1.5">
                        {{ str_pad($u, 2, '0', STR_PAD_LEFT) }}:30
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

                {{-- Kwartierlijnen (15, 30, 45 min) --}}
                @for ($u = 0; $u < $uren; $u++)
                <div class="absolute w-full border-t pointer-events-none border-gray-100 dark:border-neutral-700/60"
                     style="top: {{ $u * $pxPerUur + $pxPerUur / 2 }}px"></div>
                <div class="absolute w-full border-t pointer-events-none border-dashed border-gray-100 dark:border-neutral-800"
                     style="top: {{ $u * $pxPerUur + $pxPerUur / 4 }}px"></div>
                <div class="absolute w-full border-t pointer-events-none border-dashed border-gray-100 dark:border-neutral-800"
                     style="top: {{ $u * $pxPerUur + $pxPerUur * 3 / 4 }}px"></div>
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
                    $bSelected  = $geselecteerdeblokkering?->id === $blokkering->id;
                    $isPauze    = ($blokkering->reden === 'Pauze');
                @endphp
                <button
                    wire:click="selecteerBlokkering({{ $blokkering->id }})"
                    @click.stop
                    class="absolute left-1 right-1 rounded-md px-1.5 py-0.5 text-left transition-all cursor-pointer z-10
                        {{ $isPauze
                            ? 'bg-gray-200 dark:bg-neutral-600 border border-gray-300 dark:border-neutral-500 hover:bg-gray-300 dark:hover:bg-neutral-500'
                            : 'bg-gray-200 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600 hover:bg-gray-300 dark:hover:bg-neutral-600' }}
                        {{ $bSelected ? 'ring-2 ring-offset-1 ring-gray-400 z-20' : '' }}"
                    style="top: {{ $bTop }}px; height: {{ $bHeight }}px; {{ $isPauze ? '' : 'background-image: repeating-linear-gradient(45deg, transparent, transparent 4px, rgba(0,0,0,0.05) 4px, rgba(0,0,0,0.05) 8px);' }}"
                >
                    <p class="text-xs font-semibold truncate leading-tight {{ $isPauze ? 'text-gray-600 dark:text-neutral-300' : 'text-gray-500 dark:text-neutral-400' }}">
                        {{ $isPauze ? '☕ Pauze' : ($blokkering->reden ?: 'Geblokkeerd') }}
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

                    $isWalkInD  = !empty($afspraak->walk_in_naam);
                    $isToekomst = \Carbon\Carbon::parse($afspraak->datum->toDateString() . ' ' . $afspraak->start_tijd)->isFuture();
                    $kleur = match(true) {
                        $isWalkInD && $isToekomst          => 'bg-emerald-100 hover:bg-emerald-200 border-emerald-300 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-700 dark:text-emerald-300',
                        $isWalkInD                         => 'bg-emerald-500 hover:bg-emerald-600 border-emerald-600 text-white',
                        $afspraak->status === 'voltooid'    => 'bg-green-500 hover:bg-green-600 border-green-600 text-white',
                        $afspraak->status === 'no_show'     => 'bg-red-400 hover:bg-red-500 border-red-500 text-white',
                        $afspraak->status === 'geannuleerd' => 'bg-gray-300 hover:bg-gray-400 border-gray-400 text-gray-600 dark:bg-neutral-600 dark:text-neutral-300',
                        $isToekomst                        => 'bg-blue-100 hover:bg-blue-200 border-blue-300 text-blue-800 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-300',
                        default                            => 'bg-blue-500 hover:bg-blue-600 border-blue-600 text-white',
                    };

                    // Sub-kolom berekening: elke medewerker krijgt eigen kolom
                    $aantalKolommen = $gefilterdeMedewerkerId === null && count($medewerkerKolom) > 1
                        ? count($medewerkerKolom)
                        : 1;
                    $kolomIndex = ($aantalKolommen > 1 && $afspraak->medewerker_id && isset($medewerkerKolom[$afspraak->medewerker_id]))
                        ? $medewerkerKolom[$afspraak->medewerker_id]
                        : 0;
                    $colWidthPct = 100 / $aantalKolommen;
                    $colLeftPct  = $kolomIndex * $colWidthPct;
                @endphp
                <button
                    wire:click="selecteerAfspraak({{ $afspraak->id }})"
                    @click.stop
                    class="absolute rounded-md border-l-2 px-1.5 py-0.5 text-left transition-all cursor-pointer {{ $kleur }}
                        {{ $isSelected ? 'ring-2 ring-offset-1 ring-blue-400 z-20' : 'z-10' }}"
                    style="top: {{ $top }}px; height: {{ $height }}px; left: calc({{ $colLeftPct }}% + 2px); width: calc({{ $colWidthPct }}% - 4px);"
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

                    {{-- Medewerker (alleen tonen als salon medewerkers heeft) --}}
                    @if($medewerkers->count() > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Medewerker</label>
                        <x-select
                            wire-target="nieuwMedewerkerId"
                            :current="$nieuwMedewerkerId"
                            :options="$medewerkers->mapWithKeys(fn($m) => [$m->id => $m->naam])->toArray()"
                            placeholder="— Kies medewerker —"
                        />
                    </div>
                    @endif

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

                {{-- Klant contact --}}
                @if(!$a->walk_in_naam && $a->klant)
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-neutral-700/40 mb-4">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-700 dark:text-blue-400 font-bold text-sm">{{ mb_strtoupper(mb_substr($a->klant->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $a->klant->email }}</p>
                        @if($a->klant->telefoon)
                        <a href="tel:{{ $a->klant->telefoon }}" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">{{ $a->klant->telefoon }}</a>
                        @endif
                    </div>
                    @if($a->klant->telefoon)
                    <a href="tel:{{ $a->klant->telefoon }}"
                       class="flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Bel
                    </a>
                    @endif
                </div>
                @endif

                {{-- Opmerking --}}
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-neutral-700" x-data="{ saved: false }">
                    <label class="block text-xs text-gray-400 dark:text-neutral-500 mb-1.5">Opmerking</label>
                    <textarea
                        wire:model="afspraakNotitie"
                        rows="2"
                        placeholder="Geen opmerking..."
                        class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none"
                    ></textarea>
                    <div class="flex items-center justify-between mt-1.5">
                        <span x-show="saved" x-transition:enter="transition ease-out duration-150" x-transition:leave="transition ease-in duration-150" x-cloak
                              class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Opgeslagen
                        </span>
                        <span x-show="!saved"></span>
                        <button type="button"
                                wire:click="notitieOpslaan({{ $a->id }})"
                                x-on:click="saved = true; setTimeout(() => saved = false, 2000)"
                                class="px-3 py-1 text-xs font-medium rounded-lg bg-gray-800 dark:bg-neutral-100 text-white dark:text-neutral-900 hover:bg-gray-700 dark:hover:bg-neutral-200 transition-colors">
                            Opslaan
                        </button>
                    </div>
                </div>

                @if($a->status === 'gepland')
                <div class="flex flex-col gap-2 pt-4 border-t border-gray-100 dark:border-neutral-700 mt-4">
                    <button wire:click="voltooid({{ $a->id }})"
                            class="w-full py-2.5 text-sm font-medium rounded-lg bg-green-600 text-white hover:bg-green-700 transition-colors">
                        Voltooid
                    </button>
                    <div class="flex gap-2">
                        <button @click.prevent="$dispatch('open-confirm', { title: 'Afspraak annuleren', message: 'Afspraak van {{ addslashes($a->klant_naam) }} annuleren?', action: () => $wire.annuleren({{ $a->id }}) })"
                                class="flex-1 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-neutral-600 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                            Annuleer
                        </button>
                        <button @click.prevent="$dispatch('open-confirm', { title: 'No-show markeren', message: 'Klant markeren als no-show? Dit kan niet ongedaan worden gemaakt.', action: () => $wire.noShow({{ $a->id }}) })"
                                class="flex-1 py-2 text-sm font-medium rounded-lg border border-red-300 text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                            No-show
                        </button>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
