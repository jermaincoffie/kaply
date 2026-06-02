<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Agenda</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                {{ \Carbon\Carbon::parse($geselecteerdeDatum)->isoFormat('dddd D MMMM YYYY') }}
            </p>
        </div>

        <input
            wire:model.live="geselecteerdeDatum"
            type="date"
            class="py-2 px-3 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 shadow-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 cursor-pointer"
        >
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-2">Omzet {{ now()->isoFormat('MMMM') }}</p>
            <span class="text-2xl font-bold text-gray-900 dark:text-neutral-100">€ {{ number_format($omzet_maand / 100, 0, ',', '.') }}</span>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-2">Afspraken {{ now()->isoFormat('MMMM') }}</p>
            <span class="text-2xl font-bold text-gray-900 dark:text-neutral-100">{{ $afspraken_maand }}</span>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-2">Komende</p>
            <span class="text-2xl font-bold text-gray-900 dark:text-neutral-100">{{ $komende_afspraken }}</span>
        </div>
    </div>

    {{-- Afspraken lijst --}}
    <div class="space-y-3">
        @forelse($afspraken as $afspraak)
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4 flex justify-between items-center">
            <div>
                <p class="font-semibold text-gray-900 dark:text-neutral-100 text-sm">{{ $afspraak->start_tijd }} — {{ $afspraak->eind_tijd }}</p>
                <p class="text-gray-700 dark:text-neutral-300 text-sm mt-0.5">{{ str($afspraak->klant->name)->title() }}</p>
                <p class="text-gray-400 dark:text-neutral-500 text-xs mt-0.5">
                    {{ $afspraak->dienst->naam }}
                    <span class="mx-1">·</span>
                    {{ $afspraak->betaalmethode === 'online' ? 'Online betaald' : 'In zaak betalen' }}
                </p>
            </div>
            <div class="flex gap-2 items-center">
                @if($afspraak->status === 'gepland')
                <button wire:click="voltooid({{ $afspraak->id }})"
                        class="text-xs font-medium px-3 py-1.5 rounded-lg bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-100 transition-colors">
                    Voltooid
                </button>
                <button wire:click="noShow({{ $afspraak->id }})" wire:confirm="No-show markeren?"
                        class="text-xs font-medium px-3 py-1.5 rounded-lg bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 hover:bg-red-100 transition-colors">
                    No-show
                </button>
                @else
                @php
                    $badge = match($afspraak->status) {
                        'voltooid'    => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                        default       => 'bg-gray-100 text-gray-500',
                    };
                @endphp
                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                    {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
                </span>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl px-6 py-12 text-center">
            <p class="text-sm text-gray-400 dark:text-neutral-500">Geen afspraken op deze dag</p>
        </div>
        @endforelse
    </div>
</div>

