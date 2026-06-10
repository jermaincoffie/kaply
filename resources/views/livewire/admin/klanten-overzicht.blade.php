<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Klanten</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Alle geregistreerde klanten op het platform</p>
        </div>
    </div>

    {{-- Zoekbalk --}}
    <div class="relative max-w-xs mb-4">
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
            <div class="px-4 py-3 flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">{{ str($klant->name)->title() }}</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5 truncate">{{ $klant->email }}</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5 truncate">
                        Lid: {{ $klant->created_at->format('d-m-y') }}
                        @if($klant->afspraken->first()) · Bezoek: {{ $klant->afspraken->first()->datum->format('d-m-y') }} @endif
                    </p>
                </div>
                <span class="inline-flex flex-shrink-0 px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $klant->totaal_afspraken > 0 ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                    {{ $klant->totaal_afspraken }} {{ $klant->totaal_afspraken === 1 ? 'afspraak' : 'afspraken' }}
                </span>
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

        @if($klanten->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-neutral-700">
            {{ $klanten->links() }}
        </div>
        @endif
    </div>
</div>
