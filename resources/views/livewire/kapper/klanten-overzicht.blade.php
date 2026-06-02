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
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-neutral-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Klant</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Afspraken</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden md:table-cell">Laatste bezoek</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden md:table-cell">Klant sinds</th>
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
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
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
</div>
