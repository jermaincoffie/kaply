<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Klanten</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $totaal }} klanten totaal</p>
        </div>
    </div>

    {{-- Zoekbalk --}}
    <div class="relative mb-4">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-neutral-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
        </svg>
        <input wire:model.live.debounce.300ms="zoekterm" type="text" placeholder="Zoek op naam of e-mail..."
            class="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    {{-- Lijst --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">

        {{-- Mobiel: expandable cards --}}
        <div class="sm:hidden divide-y divide-gray-50 dark:divide-neutral-700">
            @forelse($klanten as $klant)
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full px-4 py-3 flex items-center justify-between gap-3 text-left">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">{{ str($klant->name)->title() }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $klant->email }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $klant->totaal_afspraken > 0 ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                            {{ $klant->totaal_afspraken }} {{ $klant->totaal_afspraken === 1 ? 'afspraak' : 'afspraken' }}
                        </span>
                        <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                <div x-show="open" x-collapse class="px-4 pb-3 space-y-1.5 border-t border-gray-50 dark:border-neutral-700 pt-2.5">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400 dark:text-neutral-500">Lid sinds</span>
                        <span class="text-gray-700 dark:text-neutral-300">{{ $klant->created_at->format('d-m-Y') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400 dark:text-neutral-500">Laatste bezoek</span>
                        <span class="text-gray-700 dark:text-neutral-300">
                            {{ $klant->afspraken->first() ? $klant->afspraken->first()->datum->format('d-m-Y') : '—' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-4 py-12 text-center text-sm text-gray-400 dark:text-neutral-500">Geen klanten gevonden</div>
            @endforelse
        </div>

        {{-- Desktop: tabel --}}
        <table class="hidden sm:table w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-neutral-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Klant</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Geregistreerd</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Afspraken</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Laatste bezoek</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-neutral-700">
                @forelse($klanten as $klant)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-neutral-700/20">
                    <td class="px-6 py-3.5">
                        <p class="font-medium text-gray-800 dark:text-neutral-100">{{ str($klant->name)->title() }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $klant->email }}</p>
                    </td>
                    <td class="px-6 py-3.5 text-gray-400 dark:text-neutral-500 text-xs">
                        {{ $klant->created_at->format('d-m-Y') }}
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $klant->totaal_afspraken > 0
                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                            {{ $klant->totaal_afspraken }} {{ $klant->totaal_afspraken === 1 ? 'afspraak' : 'afspraken' }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5 text-gray-400 dark:text-neutral-500 text-xs">
                        @if($klant->afspraken->first())
                            {{ $klant->afspraken->first()->datum->format('d-m-Y') }}
                        @else
                            <span class="text-gray-300 dark:text-neutral-600">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-neutral-500">
                        Geen klanten gevonden
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Laad meer --}}
        @if($heeftMeer)
        <div class="px-6 py-4 border-t border-gray-100 dark:border-neutral-700 text-center">
            <button wire:click="laadMeer" wire:loading.attr="disabled" wire:target="laadMeer"
                    class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline disabled:opacity-50">
                <span wire:loading.remove wire:target="laadMeer">Laad meer klanten</span>
                <span wire:loading wire:target="laadMeer">Laden...</span>
            </button>
        </div>
        @endif

    </div>
</div>
