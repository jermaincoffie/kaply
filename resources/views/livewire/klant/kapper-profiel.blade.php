<div class="max-w-2xl mx-auto px-4 py-8">

    {{-- Terug --}}
    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-neutral-400 hover:text-gray-700 dark:hover:text-neutral-200 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Terug naar zoeken
    </a>

    {{-- Hero card --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl overflow-hidden mb-5">
        @if($kapper->foto)
        <img src="{{ asset('storage/' . $kapper->foto) }}" alt="{{ $kapper->salon_naam }}" class="w-full h-40 object-cover">
        @else
        @php
            $tekst = ['text-blue-400','text-emerald-400','text-violet-400','text-rose-400','text-amber-400','text-cyan-400'];
            $idx   = abs(crc32($kapper->salon_naam)) % count($tekst);
        @endphp
        <div class="w-full h-40 bg-blue-900/30 flex flex-col items-center justify-center gap-0.5">
            <span class="text-xs font-medium text-white/60 uppercase tracking-widest">Welkom bij</span>
            <span class="text-2xl font-bold text-white">{{ $kapper->salon_naam }}</span>
        </div>
        @endif

        <div class="px-5 py-4">
            <h1 class="text-lg font-bold text-gray-900 dark:text-neutral-100">{{ $kapper->salon_naam }}</h1>
            <div class="flex flex-wrap items-center gap-3 mt-1">
                @if($kapper->stad)
                <span class="flex items-center gap-1 text-sm text-gray-500 dark:text-neutral-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ str($kapper->stad)->title() }}{{ $kapper->adres ? ', ' . $kapper->adres : '' }}
                </span>
                @endif
                @if($kapper->telefoon)
                <a href="tel:{{ $kapper->telefoon }}" class="flex items-center gap-1 text-sm text-gray-500 dark:text-neutral-400 hover:text-blue-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ $kapper->telefoon }}
                </a>
                @endif
            </div>
            @if($kapper->bio)
            <p class="mt-3 text-sm text-gray-600 dark:text-neutral-400 leading-relaxed">{{ $kapper->bio }}</p>
            @endif
        </div>
    </div>

    {{-- Booking flow --}}
    <div class="space-y-4">

        {{-- Stap 1: Dienst --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">1 — Kies een dienst</p>
            @if($kapper->diensten->isEmpty())
            <p class="text-sm text-gray-400 dark:text-neutral-500">Geen diensten beschikbaar.</p>
            @else
            <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between py-2 pl-3 pr-3 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm shadow-sm hover:border-gray-300 dark:hover:border-neutral-600 transition-colors cursor-pointer"
                        :class="open ? 'border-blue-600 ring-1 ring-blue-600' : ''">
                    <span class="{{ $geselecteerdeDienst ? 'text-gray-800 dark:text-neutral-100' : 'text-gray-400 dark:text-neutral-500' }}">
                        {{ $geselecteerdeDienst ? $geselecteerdeDienst->naam : 'Kies een dienst...' }}
                    </span>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute left-0 right-0 top-full mt-1 z-50 bg-white dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 rounded-xl shadow-xl overflow-hidden"
                     style="display:none">
                    @foreach($kapper->diensten as $dienst)
                    <button type="button"
                            wire:click="selecteerDienst({{ $dienst->id }})"
                            @click="open = false"
                            class="w-full flex items-center gap-2 px-3 py-2.5 text-sm transition-colors
                                {{ $geselecteerdeDienstId === $dienst->id
                                    ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'
                                    : 'text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-800' }}">
                        <svg x-show="{{ $geselecteerdeDienstId === $dienst->id ? 'true' : 'false' }}" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        @if($geselecteerdeDienstId !== $dienst->id)
                        <span class="w-4 flex-shrink-0"></span>
                        @endif
                        <span class="flex-1 text-left font-medium">{{ $dienst->naam }}</span>
                        <span class="text-xs text-gray-400 dark:text-neutral-500 w-14 text-right">{{ $dienst->duur_minuten }} min</span>
                        <span class="text-xs font-semibold w-12 text-right">€ {{ $dienst->prijs_in_euros }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Stap 2: Medewerker (alleen als er medewerkers zijn) --}}
        @if($medewerkers->isNotEmpty())
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">2 — Kies een barber</p>
            <div class="flex flex-wrap gap-2">
                {{-- Maakt niet uit --}}
                <button wire:click="selecteerMedewerker(null)"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg border text-sm font-medium transition-colors
                            {{ $geselecteerdeMedewerkerId === null
                                ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'
                                : 'border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:border-gray-300 dark:hover:border-neutral-600' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Maakt niet uit
                </button>
                {{-- Individuele medewerkers --}}
                @foreach($medewerkers as $mw)
                <button wire:click="selecteerMedewerker({{ $mw->id }})"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg border text-sm font-medium transition-colors
                            {{ $geselecteerdeMedewerkerId === $mw->id
                                ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'
                                : 'border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:border-gray-300 dark:hover:border-neutral-600' }}">
                    @if($mw->foto)
                    <img src="{{ asset('storage/' . $mw->foto) }}" class="w-5 h-5 rounded-full object-cover">
                    @else
                    <div class="w-5 h-5 rounded-full bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                        <span class="text-[10px] font-bold text-blue-400">{{ mb_strtoupper(mb_substr($mw->naam, 0, 1)) }}</span>
                    </div>
                    @endif
                    {{ $mw->naam }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Stap 2/3: Datum --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5 overflow-visible">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">{{ $medewerkers->isNotEmpty() ? '3' : '2' }} — Kies een datum</p>
            <x-datepicker
                wire-model="geselecteerdeDatum"
                :value="$geselecteerdeDatum"
                :date-min="today()->toDateString()"
                placeholder="Selecteer een datum"
            />
        </div>

        {{-- Stap 3/4: Tijdslot --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">{{ $medewerkers->isNotEmpty() ? '4' : '3' }} — Kies een tijdstip</p>

            <div wire:loading.delay wire:target="geselecteerdeDatum,selecteerDienst" class="text-xs text-gray-400 dark:text-neutral-500">Beschikbaarheid laden...</div>

            @if(count($tijdsloten) > 0)
            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                @foreach($tijdsloten as $slot)
                <button wire:click="openBoekModal('{{ $slot }}')"
                        class="py-2 px-1 rounded-lg border border-gray-200 dark:border-neutral-700 text-sm font-medium text-gray-700 dark:text-neutral-300 hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-400 transition-colors text-center">
                    {{ $slot }}
                </button>
                @endforeach
            </div>
            @elseif($geselecteerdeDatum && $sluitingsdagReden)
            <div class="py-6 text-center">
                <div class="w-10 h-10 rounded-full bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">Salon gesloten</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">{{ ucfirst($sluitingsdagReden) }}</p>
            </div>
            @elseif($geselecteerdeDatum)
            <div class="py-6 text-center">
                <svg class="w-8 h-8 text-gray-200 dark:text-neutral-700 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-sm text-gray-400 dark:text-neutral-500">Geen vrije tijdsloten op deze dag</p>
            </div>
            @else
            <p class="text-sm text-gray-400 dark:text-neutral-500">Kies eerst een datum hierboven.</p>
            @endif

            @guest
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-3">
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Inloggen</a> om te boeken
            </p>
            @endguest
        </div>

        {{-- Openingstijden (inklapbaar onderin) --}}
        @if($openingstijden->isNotEmpty())
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Openingstijden</p>
            <div class="grid grid-cols-2 gap-x-6 gap-y-1.5">
                @foreach($openingstijden as $ot)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-neutral-400 uppercase text-xs font-medium">{{ $ot['dag'] }}</span>
                    <span class="text-gray-700 dark:text-neutral-300">{{ $ot['start'] }} – {{ $ot['eind'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Boeking modal --}}
    @if($toonBoekModal)
<div
    x-data
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
>
    <div class="absolute inset-0 bg-black/50" wire:click="sluitModal"></div>
    <div
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        class="relative w-full sm:max-w-sm bg-white dark:bg-neutral-800 sm:rounded-2xl rounded-t-2xl shadow-2xl overflow-hidden"
    >
        <div class="sm:hidden flex justify-center pt-3 pb-1">
            <div class="w-10 h-1 bg-gray-200 dark:bg-neutral-600 rounded-full"></div>
        </div>
        <div class="px-5 pt-4 pb-6">
            @if($geboekt)
            <div class="text-center py-4">
                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-1">Afspraak bevestigd!</h3>
                <p class="text-sm text-gray-500 dark:text-neutral-400 mb-5">{{ \Carbon\Carbon::parse($geselecteerdeDatum)->isoFormat('dddd D MMMM') }} om {{ $geselecteerdeTijd }}</p>
                <div class="flex gap-2">
                    <button wire:click="sluitModal" class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">Sluiten</button>
                    <a href="{{ route('klant.afspraken') }}" class="flex-1 py-2.5 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors text-center">Mijn afspraken</a>
                </div>
            </div>
            @else
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Afspraak bevestigen</h3>
                <button wire:click="sluitModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="bg-gray-50 dark:bg-neutral-700/40 rounded-xl p-4 mb-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-neutral-400">Dienst</span>
                    <span class="font-medium text-gray-800 dark:text-neutral-100">{{ $geselecteerdeDienst?->naam }}</span>
                </div>
                @if($medewerkers->isNotEmpty())
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-neutral-400">Barber</span>
                    <span class="font-medium text-gray-800 dark:text-neutral-100">
                        {{ $geselecteerdeMedewerker ? $geselecteerdeMedewerker->naam : 'Maakt niet uit' }}
                    </span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-neutral-400">Datum</span>
                    <span class="font-medium text-gray-800 dark:text-neutral-100">{{ \Carbon\Carbon::parse($geselecteerdeDatum)->isoFormat('D MMM YYYY') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-neutral-400">Tijd</span>
                    <span class="font-medium text-gray-800 dark:text-neutral-100">{{ $geselecteerdeTijd }}</span>
                </div>
                <div class="flex justify-between border-t border-gray-100 dark:border-neutral-700 pt-2 mt-2">
                    <span class="text-gray-500 dark:text-neutral-400">Prijs</span>
                    <span class="font-semibold text-gray-800 dark:text-neutral-100">€ {{ $geselecteerdeDienst?->prijs_in_euros }}</span>
                </div>
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Betaling</label>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" wire:click="$set('betaalmethode', 'in_zaak')"
                            class="py-2.5 rounded-lg border text-sm font-medium transition-colors {{ $betaalmethode === 'in_zaak' ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' : 'border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                        In de zaak
                    </button>
                    <button type="button" wire:click="$set('betaalmethode', 'online')"
                            class="py-2.5 rounded-lg border text-sm font-medium transition-colors {{ $betaalmethode === 'online' ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' : 'border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                        Online
                    </button>
                </div>
            </div>
            <div class="flex gap-2">
                <button wire:click="sluitModal" class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">Annuleer</button>
                <button wire:click="bevestigBoeking" class="flex-1 py-2.5 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">Bevestigen</button>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
