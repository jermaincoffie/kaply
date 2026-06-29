<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Terug --}}
    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-neutral-400 hover:text-gray-700 dark:hover:text-neutral-200 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Terug naar zoeken
    </a>

    {{--
        Mobiel:  Hero → Booking → Galerij → Openingstijden → Reviews  (order-*)
        Desktop: Links (Hero, Galerij, Openingstijden, Reviews) | Rechts sticky (Booking)
    --}}
    <div class="flex flex-col gap-6 lg:grid lg:grid-cols-3 lg:gap-8 lg:items-start">

        {{-- ===== HERO — mobiel: 1e, desktop: kolom 1-2 rij 1 ===== --}}
        <div class="order-1 lg:col-span-2 lg:row-start-1">
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-6">
                <div class="flex items-start gap-5">
                    {{-- Avatar --}}
                    @if($kapper->foto)
                    <img src="{{ asset('public/storage/' . $kapper->foto) }}" alt="{{ $kapper->salon_naam }}"
                         class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover flex-shrink-0 border border-gray-100 dark:border-neutral-700">
                    @else
                    @php
                        $avatarKleuren = ['bg-blue-100 text-blue-700','bg-emerald-100 text-emerald-700','bg-violet-100 text-violet-700','bg-rose-100 text-rose-700','bg-amber-100 text-amber-700'];
                        $avatarKleur   = $avatarKleuren[abs(crc32($kapper->salon_naam)) % count($avatarKleuren)];
                    @endphp
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $avatarKleur }}">
                        <span class="text-2xl sm:text-3xl font-bold">{{ mb_strtoupper(mb_substr($kapper->salon_naam, 0, 1)) }}</span>
                    </div>
                    @endif

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <h1 class="text-xl font-bold text-gray-900 dark:text-neutral-100 leading-tight">{{ $kapper->salon_naam }}</h1>
                            @auth
                            @if(auth()->user()->isKlant())
                            <button wire:click="toggleFavoriet"
                                    class="flex-shrink-0 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors group"
                                    title="{{ $isFavoriet ? 'Verwijder uit favorieten' : 'Voeg toe aan favorieten' }}">
                                <svg class="w-5 h-5 transition-colors {{ $isFavoriet ? 'text-red-500' : 'text-gray-300 dark:text-neutral-600 group-hover:text-red-400' }}"
                                     fill="{{ $isFavoriet ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                            @endif
                            @endauth
                        </div>

                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
                            @if($kapper->stad)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(($kapper->adres ? $kapper->adres.', ' : '') . str($kapper->stad)->title()) }}" target="_blank" rel="noopener" class="flex items-center gap-1 text-sm text-gray-500 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors min-w-0">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="truncate">{{ str($kapper->stad)->title() }}{{ $kapper->adres ? ', ' . $kapper->adres : '' }}</span>
                            </a>
                            @endif
                            @if($kapper->telefoon)
                            <a href="tel:{{ $kapper->telefoon }}" class="flex items-center gap-1 text-sm text-gray-500 dark:text-neutral-400 hover:text-blue-600 transition-colors">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $kapper->telefoon }}
                            </a>
                            @endif
                        </div>

                        @if($gemiddeldRating)
                        <div class="flex items-center gap-2 mt-2">
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($gemiddeldRating) ? 'text-amber-400' : 'text-gray-200 dark:text-neutral-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-neutral-300">{{ number_format($gemiddeldRating, 1) }}</span>
                            <a href="#beoordelingen" class="text-xs text-gray-400 dark:text-neutral-500 whitespace-nowrap hover:text-blue-600 dark:hover:text-blue-400 transition-colors">({{ $reviews->count() }} {{ $reviews->count() === 1 ? 'beoordeling' : 'beoordelingen' }})</a>
                        </div>
                        @endif
                    </div>
                </div>

                @if($kapper->bio)
                <p class="mt-4 text-sm text-gray-600 dark:text-neutral-400 leading-relaxed border-t border-gray-100 dark:border-neutral-700 pt-4">{{ $kapper->bio }}</p>
                @endif
            </div>
        </div>

        {{-- ===== BOOKING — mobiel: 2e, desktop: kolom 3 sticky ===== --}}
        <div class="order-2 lg:col-start-3 lg:row-start-1 lg:row-span-5 lg:sticky lg:top-6 space-y-4">

            {{-- Diensten --}}
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-5">
                <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Kies een dienst</p>
                @if($kapper->diensten->isEmpty())
                <p class="text-sm text-gray-400 dark:text-neutral-500">Geen diensten beschikbaar.</p>
                @else
                <div class="space-y-2">
                    @foreach($kapper->diensten as $dienst)
                    <button wire:click="selecteerDienst({{ $dienst->id }})" type="button"
                            class="w-full flex items-center justify-between px-3.5 py-3 rounded-xl border text-sm transition-colors text-left
                                {{ $geselecteerdeDienstId === $dienst->id
                                    ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20'
                                    : 'border-gray-100 dark:border-neutral-700 hover:border-gray-300 dark:hover:border-neutral-600' }}">
                        <div>
                            <p class="font-medium {{ $geselecteerdeDienstId === $dienst->id ? 'text-blue-700 dark:text-blue-400' : 'text-gray-800 dark:text-neutral-200' }}">{{ $dienst->naam }}</p>
                            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $dienst->duur_minuten }} min</p>
                        </div>
                        <span class="font-semibold flex-shrink-0 ml-3 {{ $geselecteerdeDienstId === $dienst->id ? 'text-blue-700 dark:text-blue-400' : 'text-gray-700 dark:text-neutral-300' }}">
                            € {{ $dienst->prijs_in_euros }}
                        </span>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Medewerker --}}
            @if($medewerkers->isNotEmpty())
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-5">
                <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Kies een barber</p>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="selecteerMedewerker(null)" type="button"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg border text-sm font-medium transition-colors
                                {{ $geselecteerdeMedewerkerId === null
                                    ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'
                                    : 'border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:border-gray-300 dark:hover:border-neutral-600' }}">
                        Geen voorkeur
                    </button>
                    @foreach($medewerkers as $mw)
                    <button wire:click="selecteerMedewerker({{ $mw->id }})" type="button"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg border text-sm font-medium transition-colors
                                {{ $geselecteerdeMedewerkerId === $mw->id
                                    ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'
                                    : 'border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:border-gray-300 dark:hover:border-neutral-600' }}">
                        @if($mw->foto)
                        <img src="{{ asset('public/storage/' . $mw->foto) }}" class="w-5 h-5 rounded-full object-cover flex-shrink-0">
                        @else
                        <div class="w-5 h-5 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                            <span class="text-[10px] font-bold text-blue-700 dark:text-blue-400">{{ mb_strtoupper(mb_substr($mw->naam, 0, 1)) }}</span>
                        </div>
                        @endif
                        {{ $mw->naam }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Datum --}}
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-5 overflow-visible">
                <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Kies een datum</p>
                <x-datepicker
                    wire-model="geselecteerdeDatum"
                    :value="$geselecteerdeDatum"
                    :date-min="today()->toDateString()"
                    placeholder="Selecteer een datum"
                />
            </div>

            {{-- Annuleringsbeleid --}}
            @if($kapper->annulering_uren)
            <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 dark:bg-neutral-800/60 border border-gray-200 dark:border-neutral-700 rounded-xl text-xs text-gray-500 dark:text-neutral-400">
                <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Annuleren is niet meer mogelijk binnen
                <span class="font-semibold text-gray-700 dark:text-neutral-300">
                    {{ $kapper->annulering_uren >= 24
                        ? ($kapper->annulering_uren / 24) . ' ' . ($kapper->annulering_uren == 24 ? 'dag' : 'dagen')
                        : $kapper->annulering_uren . ' uur' }}
                </span>
                voor de afspraak.
            </div>
            @endif

            {{-- Tijdsloten --}}
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-5">
                <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Kies een tijdstip</p>

                <div wire:loading.delay wire:target="geselecteerdeDatum,selecteerDienst" class="text-xs text-gray-400 dark:text-neutral-500">Beschikbaarheid laden...</div>

                @if(count($tijdsloten) > 0)
                <div class="grid grid-cols-3 gap-2">
                    @foreach($tijdsloten as $slot)
                    <button wire:click="openBoekModal('{{ $slot }}')" type="button"
                            class="py-2 rounded-lg border border-gray-200 dark:border-neutral-700 text-sm font-medium text-gray-700 dark:text-neutral-300 hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-400 transition-colors text-center">
                        {{ $slot }}
                    </button>
                    @endforeach
                </div>
                @elseif($geselecteerdeDatum && $sluitingsdagReden)
                <div class="py-4 text-center">
                    <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">Salon gesloten</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ ucfirst($sluitingsdagReden) }}</p>
                </div>
                @elseif($geselecteerdeDatum)
                <div class="py-2">
                    @if(!$kapperWerktDag)
                        <p class="text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Kapper werkt niet op deze dag.</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500">Selecteer een andere datum.</p>
                    @elseif($medewerkerWerktNietDezeDag)
                        <p class="text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">{{ $geselecteerdeMedewerker?->naam ?? 'Deze medewerker' }} werkt niet op deze dag.</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500">Kies een andere datum of selecteer een andere medewerker.</p>
                    @else
                    <p class="text-sm text-gray-500 dark:text-neutral-400 mb-3">Geen vrije tijdsloten op deze dag.</p>

                    @if($geselecteerdeDatum <= today()->toDateString())
                        <p class="text-xs text-gray-400 dark:text-neutral-500">Kies een andere dag om een afspraak te plannen.</p>
                    @elseif($wachtlijstVerstuurd)
                        <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 text-sm font-medium">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Je staat op de wachtlijst! We mailen je zodra er een plek vrijkomt.
                        </div>
                    @elseif($toonWachtlijstForm)
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-neutral-400 mb-1">Naam</label>
                                <input wire:model="wachtlijstNaam" type="text" placeholder="Jouw naam"
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                                @error('wachtlijstNaam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-neutral-400 mb-1">E-mailadres</label>
                                <input wire:model="wachtlijstEmail" type="email" placeholder="jouw@email.nl"
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                                @error('wachtlijstEmail') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-neutral-400 mb-1">Telefoonnummer <span class="font-normal text-gray-400">(optioneel)</span></label>
                                <input wire:model="wachtlijstTelefoon" type="tel" placeholder="06 12345678"
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                            </div>
                            @if($wachtlijstFout)
                                <p class="text-xs text-red-500">{{ $wachtlijstFout }}</p>
                            @endif
                            <div class="flex gap-2 pt-1">
                                <button type="button" wire:click="resetWachtlijst"
                                        class="flex-1 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-600 text-gray-500 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                                    Annuleer
                                </button>
                                <button type="button" wire:click="wachtlijstAanmelden"
                                        class="flex-1 py-2 text-sm font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors">
                                    Inschrijven
                                </button>
                            </div>
                        </div>
                    @else
                        <button type="button" wire:click="$set('toonWachtlijstForm', true)"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Zet me op de wachtlijst
                        </button>
                    @endif
                    @endif
                </div>
                @else
                <p class="text-sm text-gray-400 dark:text-neutral-500">Kies eerst een datum hierboven.</p>
                @endif

                @guest
                @if($geselecteerdeTijd)
                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl">
                    <p class="text-xs text-gray-600 dark:text-neutral-300 font-medium mb-1">Geen account nodig</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mb-2">Vul je e-mailadres in en ontvang een eenmalige code — klaar.</p>
                    <a href="{{ route('klant.inloggen') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Doorgaan met e-mail
                    </a>
                </div>
                @endif
                @endguest
            </div>

        </div>{{-- einde booking --}}

        {{-- ===== GALERIJ — mobiel: 3e, desktop: kolom 1-2 rij 2 ===== --}}
        @if($kapper->galerij->isNotEmpty())
        @php $fotoUrls = $kapper->galerij->pluck('pad')->map(fn($p) => str_starts_with($p, 'http') ? $p : asset('public/storage/' . $p))->values(); @endphp
        <div class="order-3 lg:col-span-2 lg:row-start-2"
             x-data="{
                fotos: {{ $fotoUrls->toJson() }},
                lightbox: false,
                huidig: 0,
                open(i) { this.huidig = i; this.lightbox = true; },
                prev() { this.huidig = (this.huidig - 1 + this.fotos.length) % this.fotos.length; },
                next() { this.huidig = (this.huidig + 1) % this.fotos.length; }
             }"
             @keydown.escape.window="lightbox = false"
             @keydown.arrow-left.window="if(lightbox) prev()"
             @keydown.arrow-right.window="if(lightbox) next()">

            {{-- Scroll rij met pijltjes --}}
            <div class="relative" x-data="{ scroll(dir) { $refs.gallerij.scrollBy({ left: dir * 220, behavior: 'smooth' }) } }">
                <button @click="scroll(-1)" type="button"
                        class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-3 z-10 w-8 h-8 rounded-full bg-white dark:bg-neutral-700 shadow-md border border-gray-200 dark:border-neutral-600 flex items-center justify-center text-gray-600 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>

                <div x-ref="gallerij" class="flex gap-2 overflow-x-auto scrollbar-hide pb-1 snap-x snap-mandatory px-1">
                    @foreach($kapper->galerij as $i => $foto)
                    <button @click="open({{ $i }})" type="button"
                            class="flex-shrink-0 w-48 h-48 rounded-xl overflow-hidden group snap-start">
                        <img src="{{ str_starts_with($foto->pad, 'http') ? $foto->pad : asset('public/storage/' . $foto->pad) }}" alt="Galerij"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </button>
                    @endforeach
                </div>

                <button @click="scroll(1)" type="button"
                        class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-3 z-10 w-8 h-8 rounded-full bg-white dark:bg-neutral-700 shadow-md border border-gray-200 dark:border-neutral-600 flex items-center justify-center text-gray-600 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            {{-- Lightbox --}}
            <div x-show="lightbox"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[9999] bg-black/90 flex items-center justify-center p-4"
                 @click.self="lightbox = false"
                 style="display:none">
                <button @click="lightbox = false" type="button"
                        class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <button @click="prev" type="button"
                        class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <img :src="fotos[huidig]" alt="Foto" class="max-h-[85vh] max-w-full rounded-xl object-contain select-none">
                <button @click="next" type="button"
                        class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <span class="absolute bottom-4 left-1/2 -translate-x-1/2 text-xs text-white/60 tabular-nums"
                      x-text="(huidig + 1) + ' / ' + fotos.length"></span>
            </div>
        </div>
        @endif

        {{-- ===== OPENINGSTIJDEN — mobiel: 4e, desktop: kolom 1-2 rij 3 ===== --}}
        @if($openingstijden->isNotEmpty())
        <div class="order-4 lg:col-span-2 lg:row-start-3">
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-5">
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
        </div>
        @endif

        {{-- ===== REVIEWS — mobiel: 5e, desktop: kolom 1-2 rij 4 ===== --}}
        @if($reviews->isNotEmpty())
        @php
            $totalReviews = $reviews->count();
            $breakdown = collect([5,4,3,2,1])->map(fn($s) => [
                'stars' => $s,
                'count' => $reviews->where('rating', $s)->count(),
                'pct'   => $totalReviews > 0 ? round($reviews->where('rating', $s)->count() / $totalReviews * 100) : 0,
            ]);
        @endphp
        <div id="beoordelingen" class="order-5 lg:col-span-2 lg:row-start-4">
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-5">
                <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-4">Beoordelingen</p>

                {{-- Rating samenvatting --}}
                <div class="flex items-center gap-6 mb-5 pb-5 border-b border-gray-100 dark:border-neutral-700">
                    <div class="text-center flex-shrink-0">
                        <p class="text-4xl font-bold text-gray-900 dark:text-neutral-100 leading-none">{{ number_format($gemiddeldRating, 1) }}</p>
                        <div class="flex items-center gap-0.5 justify-center mt-2">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= round($gemiddeldRating) ? 'text-amber-400' : 'text-gray-200 dark:text-neutral-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1.5">{{ $totalReviews }} {{ $totalReviews === 1 ? 'beoordeling' : 'beoordelingen' }}</p>
                    </div>

                    <div class="flex-1 space-y-2">
                        @foreach($breakdown as $row)
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500 dark:text-neutral-400 w-3 text-right flex-shrink-0">{{ $row['stars'] }}</span>
                            <svg class="w-3 h-3 text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <div class="flex-1 h-1.5 bg-gray-100 dark:bg-neutral-700 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: {{ $row['pct'] }}%"></div>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-neutral-500 w-5 flex-shrink-0 tabular-nums">{{ $row['count'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($reviews as $review)
                    <div class="{{ !$loop->last ? 'pb-4 border-b border-gray-50 dark:border-neutral-700' : '' }}">
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-neutral-700 flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-neutral-400">{{ mb_strtoupper(mb_substr($review->klant?->name ?? 'A', 0, 1)) }}</span>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-neutral-300">{{ str($review->klant?->name ?? 'Anoniem')->title() }}</span>
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200 dark:text-neutral-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-neutral-500 flex-shrink-0">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        @if($review->tekst)
                        <p class="text-sm text-gray-600 dark:text-neutral-400 leading-relaxed pl-9">{{ $review->tekst }}</p>
                        @endif
                        @if($review->reactie)
                        <div class="mt-3 ml-9 pl-3 border-l-2 border-blue-200 dark:border-blue-800">
                            <p class="text-xs font-semibold text-blue-700 dark:text-blue-400 mb-1">Reactie van {{ $kapper->salon_naam }}</p>
                            <p class="text-sm text-gray-600 dark:text-neutral-400 leading-relaxed">{{ $review->reactie }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>{{-- einde flex/grid --}}

    {{-- ===== BOEKING MODAL ===== --}}
    @if($toonBoekModal)
    <div x-data
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="absolute inset-0 bg-black/50" wire:click="sluitModal"></div>
        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             class="relative w-full sm:max-w-sm bg-white dark:bg-neutral-800 sm:rounded-2xl rounded-t-2xl shadow-2xl overflow-hidden">
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
                        <span class="font-medium text-gray-800 dark:text-neutral-100">{{ $geselecteerdeMedewerker ? $geselecteerdeMedewerker->naam : 'Geen voorkeur' }}</span>
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
                    <div class="{{ $kapper->stripe_connect_onboarded ? 'grid grid-cols-2' : 'grid grid-cols-1' }} gap-2">
                        <button type="button" wire:click="$set('betaalmethode', 'in_zaak')"
                                class="py-2.5 rounded-lg border text-sm font-medium transition-colors {{ $betaalmethode === 'in_zaak' ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' : 'border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                            In de zaak
                        </button>
                        @if($kapper->stripe_connect_onboarded)
                        <button type="button" wire:click="$set('betaalmethode', 'online')"
                                class="py-2.5 rounded-lg border text-sm font-medium transition-colors {{ $betaalmethode === 'online' ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' : 'border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                            Online
                        </button>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Opmerking <span class="font-normal text-gray-400">(optioneel)</span></label>
                    <textarea wire:model="klantNotitie" rows="2"
                              placeholder="Bijv. kom samen met mijn vrouw die heeft afspraak om 10:00..."
                              class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none"></textarea>
                </div>
                @error('slot')
                <p class="text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-3 py-2 rounded-lg">{{ $message }}</p>
                @enderror
                <div class="flex gap-2">
                    <button wire:click="sluitModal" class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">Annuleer</button>
                    <button wire:click="bevestigBoeking" class="flex-1 py-2.5 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">Bevestigen</button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Kaply badge --}}
    <div class="mt-8 pb-2 text-center">
        <a href="{{ route('voor-kappers') }}" target="_blank"
           class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-blue-600 dark:text-neutral-600 dark:hover:text-blue-400 transition-colors group">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/>
            </svg>
            Geboekt via <span class="font-semibold text-blue-500 group-hover:text-blue-600">Kaply</span>
        </a>
    </div>

</div>
