<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Afspraken</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $totaal }} afspraken totaal</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-2 mb-4">
        <div class="relative flex-1 min-w-40">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-neutral-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="zoekterm" type="text" placeholder="Zoek klant of kapper..."
                class="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <select wire:model.live="filterStatus"
            class="px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-700 dark:text-neutral-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Alle statussen</option>
            <option value="gepland">Gepland</option>
            <option value="voltooid">Voltooid</option>
            <option value="geannuleerd">Geannuleerd</option>
            <option value="no_show">No-show</option>
        </select>

        <select wire:model.live="filterKapper"
            class="px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-700 dark:text-neutral-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Alle kappers</option>
            @foreach($kappers as $kapper)
            <option value="{{ $kapper->id }}">{{ str($kapper->salon_naam)->title() }}</option>
            @endforeach
        </select>
    </div>

    {{-- Lijst --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">

        {{-- Mobiel: compacte cards met uitklap --}}
        <div class="sm:hidden divide-y divide-gray-50 dark:divide-neutral-700">
            @forelse($afspraken as $afspraak)
            @php
                $badgeCls = match($afspraak->status) {
                    'gepland'     => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                    'voltooid'    => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                    'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                    'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                    default       => 'bg-gray-100 text-gray-500',
                };
            @endphp
            <div x-data="{ open: false }">
                {{-- Compacte rij --}}
                <button @click="open = !open" class="w-full px-4 py-3 flex items-center justify-between gap-3 text-left">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">
                            {{ str($afspraak->klant?->name ?? '—')->title() }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                            {{ $afspraak->datum->format('d M Y') }} · {{ $afspraak->start_tijd }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeCls }}">
                            {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
                        </span>
                        <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                {{-- Uitklapbare details --}}
                <div x-show="open" x-collapse class="px-4 pb-3 space-y-1.5 border-t border-gray-50 dark:border-neutral-700 pt-2.5">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400 dark:text-neutral-500">Kapper</span>
                        <span class="text-gray-700 dark:text-neutral-300 font-medium">{{ str($afspraak->kapper?->salon_naam ?? '—')->title() }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400 dark:text-neutral-500">Dienst</span>
                        <span class="text-gray-700 dark:text-neutral-300">{{ $afspraak->dienst?->naam ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400 dark:text-neutral-500">Tijd</span>
                        <span class="text-gray-700 dark:text-neutral-300">{{ $afspraak->start_tijd }} – {{ $afspraak->eind_tijd }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400 dark:text-neutral-500">Betaling</span>
                        <span class="text-gray-700 dark:text-neutral-300">{{ $afspraak->betaalmethode === 'online' ? 'Online' : 'In zaak' }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-4 py-12 text-center text-sm text-gray-400 dark:text-neutral-500">Geen afspraken gevonden</div>
            @endforelse
        </div>

        {{-- Desktop: tabel --}}
        <table class="hidden sm:table w-full text-sm">
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
                    <td class="px-6 py-3.5 font-medium text-gray-800 dark:text-neutral-100">{{ str($afspraak->klant?->name ?? '—')->title() }}</td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400">{{ str($afspraak->kapper?->salon_naam ?? '—')->title() }}</td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400">{{ $afspraak->dienst?->naam ?? '—' }}</td>
                    <td class="px-6 py-3.5 text-gray-400 dark:text-neutral-500 text-xs">
                        {{ $afspraak->datum->format('d-m-Y') }}<br>{{ $afspraak->start_tijd }} – {{ $afspraak->eind_tijd }}
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
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
                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-neutral-500">Geen afspraken gevonden</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Laad meer --}}
        @if($heeftMeer)
        <div class="px-6 py-4 border-t border-gray-100 dark:border-neutral-700 text-center">
            <button wire:click="laadMeer" wire:loading.attr="disabled" wire:target="laadMeer"
                    class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline disabled:opacity-50">
                <span wire:loading.remove wire:target="laadMeer">Laad meer afspraken</span>
                <span wire:loading wire:target="laadMeer">Laden...</span>
            </button>
        </div>
        @endif

    </div>
</div>
