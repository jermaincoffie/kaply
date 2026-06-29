<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Klanten</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Klanten die bij jou hebben geboekt</p>
        </div>
    </div>

    {{-- Stat kaartjes --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-900 dark:text-neutral-100 leading-none">{{ $totaalKlanten }}</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Totaal klanten</p>
            </div>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-900 dark:text-neutral-100 leading-none">{{ $klantenDezeWeek }}</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Deze week</p>
            </div>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-amber-500 dark:text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-900 dark:text-neutral-100 leading-none">{{ $gemBeoordeling ? number_format($gemBeoordeling, 1) : '—' }}</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Gem. beoordeling</p>
            </div>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l5-5 4 4 5-6 4 3"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-900 dark:text-neutral-100 leading-none">€ {{ number_format($totaleOmzet / 100, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Totale omzet</p>
            </div>
        </div>
    </div>

    {{-- Zoekbalk --}}
    <div class="relative mb-4 max-w-xs">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
        </div>
        <input wire:model.live="zoekterm" type="text" placeholder="Zoek op naam of e-mail..."
               class="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    {{-- Tabel --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">

        {{-- Mobiel: cards --}}
        <div class="sm:hidden divide-y divide-gray-50 dark:divide-neutral-700">
            @forelse($klanten as $klant)
            <button wire:click="selecteerKlant({{ $klant->id }})" type="button"
                    class="w-full px-4 py-3 text-left {{ $geselecteerdeKlantId === $klant->id ? 'bg-blue-50 dark:bg-blue-900/10' : 'hover:bg-gray-50 dark:hover:bg-neutral-700/20' }} transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-700 dark:text-blue-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">{{ str($klant->name)->title() }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 truncate">{{ $klant->email }}</p>
                        @if($klant->telefoon)
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-0.5">{{ $klant->telefoon }}</p>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-gray-300 dark:text-neutral-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </button>
            @empty
            <div class="px-4 py-12 text-center text-sm text-gray-400 dark:text-neutral-500">
                {{ $zoekterm ? 'Geen klanten gevonden' : 'Nog geen klanten' }}
            </div>
            @endforelse
        </div>

        {{-- Desktop: tabel --}}
        <table class="hidden sm:table w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-neutral-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Klant</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Afspraken</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden md:table-cell">Laatste bezoek</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden md:table-cell">Klant sinds</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-neutral-700">
                @forelse($klanten as $klant)
                <tr wire:click="selecteerKlant({{ $klant->id }})" class="cursor-pointer transition-colors {{ $geselecteerdeKlantId === $klant->id ? 'bg-blue-50 dark:bg-blue-900/10' : 'hover:bg-gray-50/50 dark:hover:bg-neutral-700/20' }}">
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-700 dark:text-blue-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-neutral-100">{{ str($klant->name)->title() }}</p>
                                <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $klant->email }}</p>
                                @if($klant->telefoon)
                                <p class="text-xs text-blue-600 dark:text-blue-400">{{ $klant->telefoon }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="text-sm font-semibold text-gray-800 dark:text-neutral-100">{{ $klant->voltooide_afspraken }}</span>
                        <span class="text-xs text-gray-400 dark:text-neutral-500 ml-1">voltooid</span>
                        @if($klant->totaal_afspraken > $klant->voltooide_afspraken)
                        <span class="text-xs text-gray-400 dark:text-neutral-500 ml-1">({{ $klant->totaal_afspraken }} totaal)</span>
                        @endif
                    </td>
                    <td class="px-6 py-3.5 text-xs text-gray-400 dark:text-neutral-500 hidden md:table-cell">
                        {{ $klant->afspraken->first()?->datum?->format('d-m-Y') ?? '—' }}
                    </td>
                    <td class="px-6 py-3.5 text-xs text-gray-400 dark:text-neutral-500 hidden md:table-cell">
                        {{ $klant->created_at->format('d-m-Y') }}
                    </td>
                    <td class="px-6 py-3.5 text-right">
                        <svg class="w-4 h-4 text-gray-300 dark:text-neutral-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <p class="text-sm text-gray-400 dark:text-neutral-500">
                            {{ $zoekterm ? 'Geen klanten gevonden' : 'Nog geen klanten' }}
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($heeftMeer)
        <div class="px-6 py-4 border-t border-gray-100 dark:border-neutral-700">
            <button wire:click="laadMeer" wire:loading.attr="disabled" wire:target="laadMeer"
                    class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline disabled:opacity-50 transition-colors">
                <span wire:loading.remove wire:target="laadMeer">Laad meer klanten</span>
                <span wire:loading wire:target="laadMeer">Laden...</span>
            </button>
        </div>
        @endif
    </div>

    {{-- Klant detail modal --}}
    @if($geselecteerdeKlant)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="absolute inset-0 bg-black/50" wire:click="$set('geselecteerdeKlantId', null)"></div>
        <div class="relative w-full sm:max-w-md bg-white dark:bg-neutral-800 sm:rounded-2xl rounded-t-2xl shadow-2xl overflow-hidden">
            <div class="sm:hidden flex justify-center pt-3 pb-1">
                <div class="w-10 h-1 bg-gray-200 dark:bg-neutral-600 rounded-full"></div>
            </div>
            <div class="px-5 pt-4 pb-6">
                {{-- Header --}}
                <div class="flex items-start justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-700 dark:text-blue-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">{{ str($geselecteerdeKlant->name)->title() }}</h3>
                            <p class="text-xs text-gray-400 dark:text-neutral-500">Klant sinds {{ $geselecteerdeKlant->created_at->format('d-m-Y') }}</p>
                        </div>
                    </div>
                    <button wire:click="$set('geselecteerdeKlantId', null)" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Contact --}}
                <div class="space-y-2 mb-5">
                    <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-gray-50 dark:bg-neutral-700/40">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm text-gray-700 dark:text-neutral-300">{{ $geselecteerdeKlant->email }}</span>
                    </div>
                    @if($geselecteerdeKlant->telefoon)
                    <a href="tel:{{ $geselecteerdeKlant->telefoon }}"
                       class="flex items-center gap-2.5 p-2.5 rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $geselecteerdeKlant->telefoon }}</span>
                    </a>
                    @else
                    <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-gray-50 dark:bg-neutral-700/40">
                        <svg class="w-4 h-4 text-gray-300 dark:text-neutral-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-sm text-gray-400 dark:text-neutral-600 italic">Geen telefoonnummer opgegeven</span>
                    </div>
                    @endif
                </div>

                {{-- Recente afspraken --}}
                @if($geselecteerdeKlant->afspraken->isNotEmpty())
                <div class="mb-5">
                    <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-2">Recente afspraken</p>
                    <div class="space-y-1.5">
                        @foreach($geselecteerdeKlant->afspraken as $ap)
                        @php
                            $apBadge = match($ap->status) {
                                'voltooid'    => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                                'no_show'     => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                                default       => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                            };
                        @endphp
                        <div class="flex items-center justify-between gap-2 py-1.5">
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-gray-700 dark:text-neutral-300">{{ $ap->dienst->naam }}</p>
                                <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $ap->datum->format('d-m-Y') }} · {{ $ap->start_tijd }}</p>
                            </div>
                            <span class="flex-shrink-0 inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $apBadge }}">
                                {{ ucfirst(str_replace('_', ' ', $ap->status)) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Notitie knop --}}
                <button wire:click="openNotitie({{ $geselecteerdeKlant->id }})"
                        class="w-full flex items-center justify-center gap-2 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                    @php $heeftNotitie = $geselecteerdeKlant->klantNotitie?->notities; @endphp
                    <svg class="w-4 h-4" fill="{{ $heeftNotitie ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ $heeftNotitie ? 'Notitie bewerken' : 'Notitie toevoegen' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Notitie modal --}}
    @if($notitieKlantId)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="absolute inset-0 bg-black/50" wire:click="$set('notitieKlantId', null)"></div>
        <div class="relative w-full sm:max-w-md bg-white dark:bg-neutral-800 sm:rounded-2xl rounded-t-2xl shadow-2xl overflow-hidden">
            <div class="sm:hidden flex justify-center pt-3 pb-1">
                <div class="w-10 h-1 bg-gray-200 dark:bg-neutral-600 rounded-full"></div>
            </div>
            <div class="px-5 pt-4 pb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Klantnotitie</h3>
                        <x-tooltip>Notities zijn alleen zichtbaar voor jouw kapperszaak. Andere salons zien dit niet.</x-tooltip>
                    </div>
                    <button wire:click="$set('notitieKlantId', null)" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mb-3">Haartype, kleurrecept, voorkeuren — alleen zichtbaar voor jou.</p>
                <textarea wire:model="notitieText" rows="5" placeholder="bijv. Kort aan de zijkanten, blond highlight T18, voorkeur voor droog knippen..."
                          class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 resize-none"></textarea>
                <div class="flex gap-2 mt-4">
                    <button wire:click="$set('notitieKlantId', null)" class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">Annuleer</button>
                    <button wire:click="slaNotitieOp"
                            x-data="{ saved: false }"
                            @notitie-opgeslagen.window="saved = true; setTimeout(() => saved = false, 2000)"
                            :class="saved ? 'bg-green-600' : 'bg-blue-600 hover:bg-blue-700'"
                            class="flex-1 py-2.5 text-sm font-semibold rounded-lg text-white transition-colors">
                        <span x-text="saved ? 'Opgeslagen!' : 'Opslaan'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
