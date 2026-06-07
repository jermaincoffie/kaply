<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Afspraken</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Alle afspraken van alle kappers</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex gap-3 mb-4">
        <div class="relative flex-1 max-w-xs">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
            </div>
            <input wire:model.live="zoekterm" type="text" placeholder="Zoek klant of kapper..."
                class="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <x-select
            wire-target="filterStatus"
            :current="$filterStatus"
            :options="['' => 'Alle statussen', 'gepland' => 'Gepland', 'voltooid' => 'Voltooid', 'geannuleerd' => 'Geannuleerd', 'no_show' => 'No-show']"
            placeholder="Alle statussen"
        />
    </div>

    {{-- Tabel --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-neutral-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Klant</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Kapper</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Dienst</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Datum & tijd</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Betaling</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-neutral-700">
                @forelse($afspraken as $afspraak)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-neutral-700/20">
                    <td class="px-6 py-3.5 font-medium text-gray-800 dark:text-neutral-100">
                        {{ str($afspraak->klant?->name ?? '—')->title() }}
                    </td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400">
                        {{ str($afspraak->kapper?->salon_naam ?? '—')->title() }}
                    </td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400">
                        {{ $afspraak->dienst?->naam ?? '—' }}
                    </td>
                    <td class="px-6 py-3.5 text-gray-400 dark:text-neutral-500 text-xs">
                        {{ $afspraak->datum->format('d-m-Y') }}<br>
                        {{ $afspraak->start_tijd }} – {{ $afspraak->eind_tijd }}
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $afspraak->betaalmethode === 'online'
                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                            {{ $afspraak->betaalmethode === 'online' ? 'Online' : 'In zaak' }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        @php
                            $badge = match($afspraak->status) {
                                'gepland'     => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'voltooid'    => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                                'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                default       => 'bg-gray-100 text-gray-500',
                            };
                        @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                            {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-neutral-500">
                        Geen afspraken gevonden
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <div class="mt-6">
        {{ $afspraken->links() }}
    </div>
</div>
