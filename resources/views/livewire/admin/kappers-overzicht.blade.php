<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Kappers</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Overzicht van alle geregistreerde kappers</p>
        </div>
        <a href="{{ route('kapper.registreer') }}"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Kapper registreren
        </a>
    </div>

    {{-- Wachtend op goedkeuring --}}
    @if($wachtend->count() > 0)
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-3">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                {{ $wachtend->count() }} wachtend op goedkeuring
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
            @foreach($wachtend as $k)
            <div class="bg-white dark:bg-neutral-800 border border-amber-200 dark:border-amber-800/40 rounded-xl p-4 flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="font-semibold text-sm text-gray-800 dark:text-neutral-100">{{ $k->salon_naam }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $k->user?->email }}</p>
                    </div>
                    <span class="text-xs text-gray-400 dark:text-neutral-500 flex-shrink-0">{{ $k->created_at->diffForHumans() }}</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-neutral-400">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $k->stad }}
                    @if($k->telefoon)
                    <span class="ml-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $k->telefoon }}
                    </span>
                    @endif
                </div>
                <div class="flex items-center gap-2 pt-1 border-t border-gray-100 dark:border-neutral-700">
                    <button wire:click="goedkeuren({{ $k->id }})"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-semibold transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Goedkeuren
                    </button>
                    <button
                            @click="$dispatch('open-confirm', { title: 'Kapper afwijzen', message: '{{ addslashes($k->salon_naam) }} verwijderen? Dit kan niet ongedaan worden gemaakt.', action: () => $wire.afwijzen({{ $k->id }}) })"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg border border-red-200 dark:border-red-800/40 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 text-xs font-medium transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Afwijzen
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Actieve kappers --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">

        {{-- Mobiel: cards --}}
        <div class="sm:hidden divide-y divide-gray-50 dark:divide-neutral-700">
            @forelse($kappers as $kapper)
            <div class="px-4 py-3">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">{{ str($kapper->salon_naam)->title() }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5 truncate">{{ strtolower($kapper->user?->email ?? '—') }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ str($kapper->stad)->title() }} · {{ $kapper->created_at->format('d-m-Y') }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $kapper->actief ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $kapper->actief ? 'bg-green-500' : 'bg-gray-400 dark:bg-neutral-500' }}"></span>
                            {{ $kapper->actief ? 'Actief' : 'Inactief' }}
                        </span>
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $kapper->abonnement_status === 'actief' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                            {{ $kapper->abonnement_status }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    @if($kapper->no_show_rate !== null)
                    <span class="text-xs {{ $kapper->no_show_rate >= 20 ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-400 dark:text-neutral-500' }}">
                        No-show: {{ $kapper->no_show_rate }}% ({{ $kapper->no_show_count }}/{{ $kapper->totaal_afspraken }})
                    </span>
                    @else
                    <span class="text-xs text-gray-300 dark:text-neutral-600">Geen afspraken</span>
                    @endif
                    @if($kapper->actief)
                    <button wire:click="deactiveer({{ $kapper->id }})"
                            class="text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 transition-colors">
                        Deactiveer
                    </button>
                    @else
                    <button wire:click="activeer({{ $kapper->id }})"
                            class="text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 transition-colors">
                        Activeer
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-4 py-12 text-center text-sm text-gray-400 dark:text-neutral-500">Geen kappers geregistreerd</div>
            @endforelse
        </div>

        {{-- Desktop: tabel --}}
        <table class="hidden sm:table w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-neutral-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Salon</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Stad</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Abonnement</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">
                        <span class="flex items-center gap-1">
                            No-show
                            <x-tooltip>Percentage afspraken waarbij de klant niet is komen opdagen. Rood boven 20%.</x-tooltip>
                        </span>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Aangemeld</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-neutral-700">
                @forelse($kappers as $kapper)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-neutral-700/20">
                    <td class="px-6 py-3.5">
                        <p class="font-medium text-gray-800 dark:text-neutral-100">{{ str($kapper->salon_naam)->title() }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ strtolower($kapper->user?->email ?? '—') }}</p>
                    </td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400">{{ str($kapper->stad)->title() }}</td>
                    <td class="px-6 py-3.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $kapper->actief
                                ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $kapper->actief ? 'bg-green-500' : 'bg-gray-400 dark:bg-neutral-500' }}"></span>
                            {{ $kapper->actief ? 'Actief' : 'Inactief' }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $kapper->abonnement_status === 'actief'
                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                            {{ $kapper->abonnement_status }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        @if($kapper->no_show_rate !== null)
                        <span class="text-xs font-semibold {{ $kapper->no_show_rate >= 20 ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-neutral-400' }}">
                            {{ $kapper->no_show_rate }}%
                        </span>
                        <span class="text-xs text-gray-400 dark:text-neutral-600 ml-1">({{ $kapper->no_show_count }}/{{ $kapper->totaal_afspraken }})</span>
                        @else
                        <span class="text-xs text-gray-300 dark:text-neutral-600">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-3.5 text-xs text-gray-400 dark:text-neutral-500">
                        {{ $kapper->created_at->format('d-m-Y') }}
                    </td>
                    <td class="px-6 py-3.5 text-right">
                        @if($kapper->actief)
                        <button wire:click="deactiveer({{ $kapper->id }})"
                                class="text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                            Deactiveer
                        </button>
                        @else
                        <button wire:click="activeer({{ $kapper->id }})"
                                class="text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                            Activeer
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <p class="text-sm text-gray-400 dark:text-neutral-500">Geen kappers geregistreerd</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
