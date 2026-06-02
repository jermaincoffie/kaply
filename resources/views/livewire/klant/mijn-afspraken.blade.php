<div>
    @if(session('boeking_bevestigd'))
    <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 font-medium">
        Afspraak bevestigd!
    </div>
    @endif

    <h2 class="text-xl font-bold mb-4">Aankomende afspraken</h2>
    <div class="space-y-3 mb-8">
        @forelse($aankomend as $afspraak)
        <div class="bg-white rounded shadow p-4 flex justify-between items-center">
            <div>
                <p class="font-semibold">{{ $afspraak->datum->format('d-m-Y') }} om {{ $afspraak->start_tijd }}</p>
                <p class="text-gray-700">{{ $afspraak->kapper->salon_naam }}</p>
                <p class="text-gray-500 text-sm">{{ $afspraak->dienst->naam }}</p>
            </div>
            <button wire:click="annuleer({{ $afspraak->id }})" wire:confirm="Afspraak annuleren?"
                class="text-sm text-red-600 border border-red-300 px-3 py-1 rounded hover:bg-red-50">
                Annuleer
            </button>
        </div>
        @empty
        <p class="text-gray-500">Geen aankomende afspraken.</p>
        @endforelse
    </div>

    <h2 class="text-xl font-bold mb-4">Geschiedenis</h2>
    <div class="space-y-2">
        @forelse($geschiedenis as $afspraak)
        <div class="bg-white rounded shadow p-3 flex justify-between items-center text-sm">
            <span class="font-medium">{{ $afspraak->datum->format('d-m-Y') }}</span>
            <span class="text-gray-500">{{ $afspraak->kapper->salon_naam }}</span>
            <span class="text-gray-500">{{ $afspraak->dienst->naam }}</span>
            <span class="px-2 py-0.5 rounded text-xs
                {{ $afspraak->status === 'voltooid' ? 'bg-green-100 text-green-800' : '' }}
                {{ $afspraak->status === 'geannuleerd' ? 'bg-gray-100 text-gray-600' : '' }}
                {{ $afspraak->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}">
                {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
            </span>
        </div>
        @empty
        <p class="text-gray-500">Geen eerdere afspraken.</p>
        @endforelse
    </div>
</div>
