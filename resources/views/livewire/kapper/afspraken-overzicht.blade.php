<div>
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Afspraken</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Overzicht van al je afspraken</p>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-4">
        {{-- Periode tabs --}}
        <div class="flex rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden bg-white dark:bg-neutral-800">
            @foreach(['aankomend' => 'Aankomend', 'verleden' => 'Verleden', 'alles' => 'Alles'] as $val => $label)
            <button wire:click="$set('periode', '{{ $val }}')"
                    class="px-3 py-1.5 text-sm font-medium transition-colors
                        {{ $periode === $val
                            ? 'bg-blue-600 text-white'
                            : 'text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- Status filter --}}
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
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Datum & tijd</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Klant</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden md:table-cell">Dienst</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden md:table-cell">Betaling</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-neutral-700">
                @forelse($afspraken as $afspraak)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-neutral-700/20">
                    <td class="px-6 py-3.5">
                        <p class="font-medium text-gray-800 dark:text-neutral-100">{{ $afspraak->datum->format('d-m-Y') }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $afspraak->start_tijd }} – {{ $afspraak->eind_tijd }}</p>
                    </td>
                    <td class="px-6 py-3.5 text-gray-700 dark:text-neutral-300">{{ str($afspraak->klant->name)->title() }}</td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400 hidden md:table-cell">
                        {{ $afspraak->dienst->naam }}
                        <span class="text-xs text-gray-400 dark:text-neutral-500 ml-1">€ {{ $afspraak->dienst->prijs_in_euros }}</span>
                    </td>
                    <td class="px-6 py-3.5 hidden md:table-cell">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $afspraak->betaalmethode === 'online' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
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
                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-neutral-500">
                        Geen afspraken gevonden
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($afspraken->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-neutral-700">
            {{ $afspraken->links() }}
        </div>
        @endif
    </div>
</div>
