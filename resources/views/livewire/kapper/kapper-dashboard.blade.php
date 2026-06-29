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
            <a href="{{ route('kapper.beschikbaarheid') }}"
               class="flex items-center gap-3 p-3 rounded-lg border {{ $onboarding['beschikbaarheid'] ? 'border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/10' : 'border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700/50' }} transition-colors group">
                <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 {{ $onboarding['beschikbaarheid'] ? 'bg-green-500' : 'bg-gray-200 dark:bg-neutral-700' }}">
                    @if($onboarding['beschikbaarheid'])
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                    <span class="text-xs font-bold text-gray-500 dark:text-neutral-400">1</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium {{ $onboarding['beschikbaarheid'] ? 'text-green-700 dark:text-green-400 line-through' : 'text-gray-800 dark:text-neutral-200' }}">Beschikbaarheid instellen</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">Geef aan op welke dagen en tijden je werkt</p>
                </div>
                @if(!$onboarding['beschikbaarheid'])
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                @endif
            </a>
            <a href="{{ route('kapper.diensten') }}"
               class="flex items-center gap-3 p-3 rounded-lg border {{ $onboarding['diensten'] ? 'border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/10' : 'border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700/50' }} transition-colors group">
                <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 {{ $onboarding['diensten'] ? 'bg-green-500' : 'bg-gray-200 dark:bg-neutral-700' }}">
                    @if($onboarding['diensten'])
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                    <span class="text-xs font-bold text-gray-500 dark:text-neutral-400">2</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium {{ $onboarding['diensten'] ? 'text-green-700 dark:text-green-400 line-through' : 'text-gray-800 dark:text-neutral-200' }}">Diensten toevoegen</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">Voeg je knippen, kleuren en andere diensten toe</p>
                </div>
                @if(!$onboarding['diensten'])
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                @endif
            </a>
        </div>
    </div>
    @endif

    @php $voornaam = explode(' ', trim(auth()->user()->name))[0]; @endphp

    {{-- Welcome header --}}
    <div class="mb-5">
        <h1 class="text-xl font-bold text-gray-900 dark:text-neutral-100">Welkom terug, {{ $voornaam }} 👋</h1>
        <p class="text-sm text-gray-500 dark:text-neutral-400 mt-1">Dit is je overzicht van vandaag.</p>
    </div>

    {{-- Afspraken kaart --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl overflow-hidden mb-4">

        {{-- Kaart header --}}
        <div class="flex items-center justify-between px-4 py-3.5 border-b border-gray-100 dark:border-neutral-700">
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-gray-900 dark:text-neutral-100">Vandaag</span>
                <span class="text-xs text-gray-400 dark:text-neutral-500">{{ now()->isoFormat('D MMMM') }}</span>
                @if($vandaagAfspraken->count() > 0)
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold">{{ $vandaagAfspraken->count() }}</span>
                @endif
            </div>
            @if($omzet_vandaag > 0)
            <span class="text-xs text-gray-500 dark:text-neutral-400">
                Omzet: <span class="font-semibold text-gray-700 dark:text-neutral-200">€ {{ number_format($omzet_vandaag / 100, 0, ',', '.') }}</span>
            </span>
            @endif
        </div>

        {{-- Afsprakenlijst --}}
        @forelse($alleVandaag as $item)

            @if($item['type'] === 'blokkering')
            @php $bl = $item['data']; $isPauze = ($bl->reden === 'Pauze'); @endphp
            <div class="flex items-center gap-3 px-4 py-3.5 border-b border-gray-50 dark:border-neutral-700/50 last:border-b-0">
                <div class="flex-shrink-0 w-12">
                    <p class="text-sm font-bold text-gray-400 dark:text-neutral-500">{{ substr($bl->start_tijd, 0, 5) }}</p>
                    <p class="text-[10px] text-gray-300 dark:text-neutral-600">{{ substr($bl->eind_tijd, 0, 5) }}</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center {{ $isPauze ? 'bg-gray-100 dark:bg-neutral-700' : 'bg-red-100 dark:bg-red-900/30' }}">
                    <svg class="w-4 h-4 {{ $isPauze ? 'text-gray-400 dark:text-neutral-500' : 'text-red-500 dark:text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($isPauze)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        @endif
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-600 dark:text-neutral-300 truncate">{{ $isPauze ? 'Pauze' : ($bl->reden ?: 'Geblokkeerd') }}</p>
                </div>
                <span class="flex-shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $isPauze ? 'bg-gray-100 dark:bg-neutral-700 text-gray-500 dark:text-neutral-400' : 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' }}">
                    {{ $isPauze ? 'Pauze' : 'Geblokkeerd' }}
                </span>
            </div>

            @else
            @php
                $af = $item['data'];
                $isWalkIn = !empty($af->walk_in_naam);
                $isActief = \Carbon\Carbon::parse($af->datum->toDateString() . ' ' . $af->start_tijd)->isPast()
                          && \Carbon\Carbon::parse($af->datum->toDateString() . ' ' . $af->eind_tijd)->isFuture();
                $isGeselecteerd = $geselecteerdeAfspraak?->id === $af->id;

                [$statusLabel, $statusKleur] = match(true) {
                    $af->status === 'voltooid'    => ['Voltooid',    'bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400'],
                    $af->status === 'no_show'     => ['No-show',     'bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400'],
                    $af->status === 'geannuleerd' => ['Geannuleerd', 'bg-gray-100 dark:bg-neutral-700 text-gray-500 dark:text-neutral-400'],
                    $isActief                     => ['Bezig',       'bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'],
                    $isWalkIn                     => ['Walk-in',     'bg-emerald-100 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400'],
                    default                       => ['Bevestigd',   'bg-blue-50 dark:bg-blue-900/10 text-blue-600 dark:text-blue-400'],
                };
                $avatarKleur = match(true) {
                    $af->status === 'voltooid'    => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                    $af->status === 'no_show'     => 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400',
                    $af->status === 'geannuleerd' => 'bg-gray-100 dark:bg-neutral-700 text-gray-400 dark:text-neutral-500',
                    $isActief                     => 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
                    $isWalkIn                     => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
                    default                       => 'bg-gray-100 dark:bg-neutral-700 text-gray-600 dark:text-neutral-400',
                };
            @endphp

            <button wire:click="selecteerAfspraak({{ $af->id }})"
                    class="w-full flex items-center gap-3 px-4 py-3.5 border-b border-gray-50 dark:border-neutral-700/50 last:border-b-0 text-left hover:bg-gray-50 dark:hover:bg-neutral-700/30 transition-colors {{ $isGeselecteerd ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                <div class="flex-shrink-0 w-12">
                    <p class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ substr($af->start_tijd, 0, 5) }}</p>
                    <p class="text-[10px] text-gray-300 dark:text-neutral-600">{{ substr($af->eind_tijd, 0, 5) }}</p>
                </div>
                <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center {{ $avatarKleur }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100 truncate">{{ $af->klant_naam }}</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 truncate">
                        {{ $af->dienst->naam }}
                        @if($medewerkers->count() > 0 && $af->medewerker)
                        · {{ $af->medewerker->naam }}
                        @endif
                    </p>
                </div>
                <span class="flex-shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusKleur }}">{{ $statusLabel }}</span>
                <svg class="w-4 h-4 flex-shrink-0 transition-transform {{ $isGeselecteerd ? 'rotate-90 text-blue-400' : 'text-gray-300 dark:text-neutral-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            {{-- Uitklapbare detail + acties --}}
            @if($isGeselecteerd)
            <div class="px-4 py-4 bg-gray-50 dark:bg-neutral-700/30 border-b border-gray-100 dark:border-neutral-700">
                <div class="flex items-start gap-3 mb-4">
                    @if($af->klant?->telefoonnummer)
                    <a href="tel:{{ $af->klant->telefoonnummer }}"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 text-sm font-medium text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $af->klant->telefoonnummer }}
                    </a>
                    @endif
                    <a href="{{ route('kapper.agenda') }}"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 text-sm font-medium text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        In agenda
                    </a>
                </div>
                @if(in_array($af->status, ['gepland', 'no_show']))
                <div class="flex items-center gap-2 flex-wrap">
                    <button wire:click="voltooid({{ $af->id }})"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Voltooid
                    </button>
                    @if($af->status !== 'no_show')
                    <button wire:click="noShow({{ $af->id }})"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-sm font-semibold hover:bg-amber-200 dark:hover:bg-amber-900/50 transition-colors">
                        No-show
                    </button>
                    <button
                        @click.prevent="$dispatch('open-confirm', { title: 'Afspraak annuleren', message: 'Weet je zeker dat je deze afspraak wilt annuleren?', action: () => $wire.annuleren({{ $af->id }}) })"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-semibold hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                        Annuleer
                    </button>
                    @endif
                </div>
                @elseif(in_array($af->status, ['geannuleerd', 'voltooid', 'no_show']))
                <div class="flex items-center gap-2">
                    <button
                        @click.prevent="$dispatch('open-confirm', { title: 'Afspraak verwijderen', message: 'Afspraak permanent verwijderen? Dit kan niet ongedaan worden gemaakt.', action: () => $wire.verwijderAfspraak({{ $af->id }}) })"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-semibold hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Verwijder
                    </button>
                </div>
                @endif
            </div>
            @endif

            @endif

        @empty
        <div class="px-4 py-10 text-center">
            <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-neutral-700 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">Geen afspraken vandaag</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Ga naar de agenda om een afspraak in te plannen</p>
        </div>
        @endforelse
    </div>

    {{-- + Nieuwe afspraak --}}
    <button wire:click="openNieuwFormulier"
            class="w-full flex items-center justify-center gap-2 px-4 py-3.5 rounded-2xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 active:bg-blue-800 transition-colors mb-5 shadow-sm shadow-blue-200 dark:shadow-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Nieuwe afspraak
    </button>

    {{-- Stats 2×2 --}}
    <div class="grid grid-cols-2 gap-3">

        {{-- Afspraken --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-4">
            <div class="flex items-center gap-1.5 mb-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-xs font-medium text-gray-500 dark:text-neutral-400">Afspraken</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-neutral-100">{{ $afspraken_week }}</p>
            @if($afspraken_week_pct !== null)
            <p class="text-xs mt-0.5 {{ $afspraken_week_pct >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                {{ $afspraken_week_pct >= 0 ? '↗' : '↘' }} {{ abs($afspraken_week_pct) }}%
            </p>
            @else
            <div class="h-4 mt-0.5"></div>
            @endif
            <p class="text-[11px] text-gray-400 dark:text-neutral-500 mt-0.5">Deze week</p>
        </div>

        {{-- Klanten --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-4">
            <div class="flex items-center gap-1.5 mb-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-xs font-medium text-gray-500 dark:text-neutral-400">Klanten</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-neutral-100">{{ $klanten_week }}</p>
            @if($klanten_week_pct !== null)
            <p class="text-xs mt-0.5 {{ $klanten_week_pct >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                {{ $klanten_week_pct >= 0 ? '↗' : '↘' }} {{ abs($klanten_week_pct) }}%
            </p>
            @else
            <div class="h-4 mt-0.5"></div>
            @endif
            <p class="text-[11px] text-gray-400 dark:text-neutral-500 mt-0.5">Deze week</p>
        </div>

        {{-- Omzet --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-4">
            <div class="flex items-center gap-1.5 mb-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-xs font-medium text-gray-500 dark:text-neutral-400">Omzet</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-neutral-100">€ {{ number_format($omzet_week / 100, 0, ',', '.') }}</p>
            @if($omzet_week_pct !== null)
            <p class="text-xs mt-0.5 {{ $omzet_week_pct >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                {{ $omzet_week_pct >= 0 ? '↗' : '↘' }} {{ abs($omzet_week_pct) }}%
            </p>
            @else
            <div class="h-4 mt-0.5"></div>
            @endif
            <p class="text-[11px] text-gray-400 dark:text-neutral-500 mt-0.5">Deze week</p>
        </div>

        {{-- Top dienst --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-4">
            <div class="flex items-center gap-1.5 mb-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-xs font-medium text-gray-500 dark:text-neutral-400">Top dienst</span>
            </div>
            @if($top_dienst_data)
            <p class="text-base font-bold text-gray-900 dark:text-neutral-100 truncate leading-tight">{{ $top_dienst_data->naam }}</p>
            <p class="text-xs text-green-600 dark:text-green-400 mt-0.5">€ {{ number_format($top_dienst_data->omzet / 100, 0, ',', '.') }}</p>
            @else
            <p class="text-2xl font-bold text-gray-300 dark:text-neutral-600">—</p>
            <div class="h-4 mt-0.5"></div>
            @endif
            <p class="text-[11px] text-gray-400 dark:text-neutral-500 mt-0.5">Deze maand</p>
        </div>
    </div>

    {{-- ===== NIEUW AFSPRAAK MODAL ===== --}}
    @if($toonNieuwFormulier)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50" wire:click="sluitFormulier"></div>

        {{-- Modal --}}
        <div class="relative w-full sm:max-w-md bg-white dark:bg-neutral-900 rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[92dvh] overflow-y-auto">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b border-gray-100 dark:border-neutral-800 sticky top-0 bg-white dark:bg-neutral-900 z-10">
                <h3 class="text-base font-semibold text-gray-900 dark:text-neutral-100">Afspraak inplannen</h3>
                <button wire:click="sluitFormulier"
                        class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-neutral-800 dark:hover:text-neutral-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <form wire:submit="afspraakOpslaan" class="px-5 py-5 space-y-4">

                {{-- Walk-in toggle --}}
                <div class="flex items-center gap-2 p-1.5 rounded-xl bg-gray-100 dark:bg-neutral-800">
                    <button type="button" wire:click="$set('isWalkIn', false)"
                            class="flex-1 py-2 rounded-lg text-sm font-semibold transition-colors {{ !$isWalkIn ? 'bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm' : 'text-gray-500 dark:text-neutral-400' }}">
                        Bestaande klant
                    </button>
                    <button type="button" wire:click="$set('isWalkIn', true)"
                            class="flex-1 py-2 rounded-lg text-sm font-semibold transition-colors {{ $isWalkIn ? 'bg-green-600 text-white shadow-sm' : 'text-gray-500 dark:text-neutral-400' }}">
                        Walk-in
                    </button>
                </div>

                {{-- Klant --}}
                @if($isWalkIn)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1.5">Naam klant</label>
                    <input wire:model="walkInNaam" type="text" placeholder="Voornaam..."
                           class="w-full py-2.5 px-3.5 rounded-xl border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('walkInNaam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                @else
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1.5">Klant</label>
                    <input wire:model.live="klantZoekterm" type="text" placeholder="Zoek op naam of email..."
                           autocomplete="off"
                           class="w-full py-2.5 px-3.5 rounded-xl border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('klantZoekterm') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    @if($toonKlantDropdown && $zoekKlanten->count())
                    <div class="absolute left-0 right-0 top-full mt-1 z-50 bg-white dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 rounded-xl shadow-xl overflow-hidden">
                        @foreach($zoekKlanten as $klant)
                        <button type="button"
                                wire:click="selecteerKlant({{ $klant->id }}, '{{ addslashes($klant->name) }}')"
                                class="w-full text-left flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                            <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-blue-700 dark:text-blue-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1.5">Dienst</label>
                    <x-select
                        wire-target="nieuwDienstId"
                        :current="$nieuwDienstId"
                        :options="$eigenDiensten->mapWithKeys(fn($d) => [$d->id => $d->naam . ' — ' . $d->duur_minuten . ' min · €' . $d->prijs_in_euros])->toArray()"
                        placeholder="Kies dienst"
                    />
                    @error('nieuwDienstId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Medewerker --}}
                @if($medewerkers->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1.5">Medewerker</label>
                    <x-select
                        wire-target="nieuwMedewerkerId"
                        :current="$nieuwMedewerkerId"
                        :options="array_merge(['' => 'Geen voorkeur'], $medewerkers->mapWithKeys(fn($m) => [$m->id => $m->naam])->toArray())"
                        placeholder="Geen voorkeur"
                    />
                </div>
                @endif

                {{-- Datum + Tijd --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1.5">Datum</label>
                        <x-datepicker wire-model="nieuwDatum" :value="$nieuwDatum" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1.5">Begintijd</label>
                        <input wire:model="nieuwTijd" type="time"
                               class="w-full py-2.5 px-3.5 rounded-xl border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    </div>
                </div>

                {{-- Betaling --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1.5">Betaling</label>
                    <x-select
                        wire-target="nieuwBetaalmethode"
                        :current="$nieuwBetaalmethode"
                        :options="['in_zaak' => 'In de zaak', 'online' => 'Online']"
                        placeholder="Betaalmethode"
                    />
                </div>

                {{-- Knoppen --}}
                <div class="flex gap-3 pt-2 pb-safe">
                    <button type="button" wire:click="sluitFormulier"
                            class="flex-1 py-3 text-sm font-medium rounded-xl border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                        Annuleer
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 text-sm font-semibold rounded-xl bg-blue-600 text-white hover:bg-blue-700 active:bg-blue-800 transition-colors">
                        Inplannen
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
