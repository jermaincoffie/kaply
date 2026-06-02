<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Terug --}}
    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-neutral-400 hover:text-gray-700 dark:hover:text-neutral-200 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Terug naar zoeken
    </a>

    {{-- Hero card --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl overflow-hidden mb-5">
        {{-- Cover / foto --}}
        @if($kapper->foto)
        <img src="{{ asset('storage/' . $kapper->foto) }}"
             alt="{{ $kapper->salon_naam }}"
             class="w-full h-48 object-cover">
        @else
        <div class="w-full h-32 bg-blue-900/30 flex flex-col items-center justify-center gap-0.5">
            <span class="text-xs font-medium text-white/60 uppercase tracking-widest">Welkom bij</span>
            <span class="text-2xl font-bold text-white">{{ str($kapper->salon_naam)->title() }}</span>
        </div>
        @endif

        <div class="px-6 py-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-neutral-100">
                        {{ str($kapper->salon_naam)->title() }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-3 mt-1.5">
                        @if($kapper->stad)
                        <span class="flex items-center gap-1 text-sm text-gray-500 dark:text-neutral-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ str($kapper->stad)->title() }}{{ $kapper->adres ? ', ' . $kapper->adres : '' }}
                        </span>
                        @endif
                        @if($kapper->telefoon)
                        <a href="tel:{{ $kapper->telefoon }}" class="flex items-center gap-1 text-sm text-gray-500 dark:text-neutral-400 hover:text-blue-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $kapper->telefoon }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            @if($kapper->bio)
            <p class="mt-4 text-sm text-gray-600 dark:text-neutral-400 leading-relaxed">{{ $kapper->bio }}</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- Links: diensten + openingstijden --}}
        <div class="md:col-span-1 space-y-4">

            {{-- Openingstijden --}}
            @if($openingstijden->isNotEmpty())
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-3">Openingstijden</h2>
                <div class="space-y-1.5">
                    @foreach($openingstijden as $ot)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-neutral-400 uppercase text-xs font-medium w-8">{{ $ot['dag'] }}</span>
                        <span class="text-gray-700 dark:text-neutral-300">{{ $ot['start'] }} – {{ $ot['eind'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Diensten --}}
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-3">Diensten</h2>
                @if($kapper->diensten->isEmpty())
                <p class="text-sm text-gray-400 dark:text-neutral-500">Geen diensten beschikbaar.</p>
                @else
                <div class="space-y-2">
                    @foreach($kapper->diensten as $dienst)
                    <button wire:click="selecteerDienst({{ $dienst->id }})"
                            class="w-full text-left px-3 py-2.5 rounded-lg border transition-colors text-sm
                                {{ $geselecteerdeDienstId === $dienst->id
                                    ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'
                                    : 'border-gray-200 dark:border-neutral-700 text-gray-700 dark:text-neutral-300 hover:border-gray-300 dark:hover:border-neutral-600 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ $dienst->naam }}</span>
                            <span class="font-semibold">€ {{ $dienst->prijs_in_euros }}</span>
                        </div>
                        <span class="text-xs opacity-70">{{ $dienst->duur_minuten }} min</span>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Rechts: datum + tijdsloten --}}
        <div class="md:col-span-2">
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5 overflow-visible">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-4">Kies een datum</h2>

                <div class="mb-4 overflow-visible">
                    <x-datepicker
                        wire-model="geselecteerdeDatum"
                        :value="$geselecteerdeDatum"
                        :date-min="today()->toDateString()"
                        placeholder="Kies een datum"
                    />
                </div>

                <div wire:loading class="text-xs text-gray-400 dark:text-neutral-500 mb-3">Beschikbaarheid laden...</div>

                @if($geselecteerdeDatum)
                @if(count($tijdsloten) > 0)
                <div>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mb-3">
                        {{ count($tijdsloten) }} tijdslot{{ count($tijdsloten) !== 1 ? 'en' : '' }} beschikbaar
                        @if($geselecteerdeDienst)
                        voor <span class="font-medium text-gray-600 dark:text-neutral-300">{{ $geselecteerdeDienst->naam }}</span>
                        @endif
                    </p>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2">
                        @foreach($tijdsloten as $slot)
                        <button wire:click="openBoekModal('{{ $slot }}')"
                                class="flex items-center justify-center py-2 px-1 rounded-lg border border-gray-200 dark:border-neutral-700 text-sm font-medium text-gray-700 dark:text-neutral-300 hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                            {{ $slot }}
                        </button>
                        @endforeach
                    </div>
                    @guest
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-3">
                        <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Inloggen</a>
                        om een afspraak te boeken
                    </p>
                    @endguest
                </div>
                @else
                <div class="py-8 text-center">
                    <svg class="w-10 h-10 text-gray-200 dark:text-neutral-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-400 dark:text-neutral-500">Geen vrije tijdsloten op deze dag</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Probeer een andere datum</p>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
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
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-1">Afspraak bevestigd!</h3>
                <p class="text-sm text-gray-500 dark:text-neutral-400 mb-5">
                    {{ \Carbon\Carbon::parse($geselecteerdeDatum)->isoFormat('dddd D MMMM') }} om {{ $geselecteerdeTijd }}
                </p>
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
                    <span class="text-gray-500 dark:text-neutral-400">Salon</span>
                    <span class="font-medium text-gray-800 dark:text-neutral-100">{{ str($kapper->salon_naam)->title() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-neutral-400">Dienst</span>
                    <span class="font-medium text-gray-800 dark:text-neutral-100">{{ $geselecteerdeDienst?->naam }}</span>
                </div>
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
</div>
@endif
