<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Klanten</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Klanten die bij jou hebben geboekt</p>
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
            <div class="px-4 py-3">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                            <span class="text-blue-700 dark:text-blue-400 font-bold text-xs">{{ mb_strtoupper(mb_substr($klant->name, 0, 1)) }}</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">{{ str($klant->name)->title() }}</p>
                            <p class="text-xs text-gray-400 dark:text-neutral-500 truncate">{{ $klant->email }}</p>
                            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                                {{ $klant->voltooide_afspraken }} voltooid
                                @if($klant->totaal_afspraken > $klant->voltooide_afspraken)· {{ $klant->totaal_afspraken }} totaal @endif
                                · lid {{ $klant->created_at->format('d-m-Y') }}
                            </p>
                        </div>
                    </div>
                    @php $heeftNotitie = $klant->klantNotitie?->notities; @endphp
                    <button wire:click="openNotitie({{ $klant->id }})"
                            class="flex-shrink-0 inline-flex items-center gap-1 text-xs font-medium transition-colors {{ $heeftNotitie ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400 dark:text-neutral-500' }}">
                        <svg class="w-4 h-4" fill="{{ $heeftNotitie ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                </div>
            </div>
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
                <tr class="hover:bg-gray-50/50 dark:hover:bg-neutral-700/20">
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-700 dark:text-blue-400 font-bold text-xs">
                                    {{ mb_strtoupper(mb_substr($klant->name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-neutral-100">{{ str($klant->name)->title() }}</p>
                                <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $klant->email }}</p>
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
                        @php $heeftNotitie = $klant->klantNotitie?->notities; @endphp
                        <button wire:click="openNotitie({{ $klant->id }})"
                                class="inline-flex items-center gap-1 text-xs font-medium transition-colors {{ $heeftNotitie ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400 dark:text-neutral-500 hover:text-gray-600 dark:hover:text-neutral-300' }}">
                            <svg class="w-3.5 h-3.5" fill="{{ $heeftNotitie ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Notitie
                        </button>
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

        @if($klanten->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-neutral-700">
            {{ $klanten->links() }}
        </div>
        @endif
    </div>

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
