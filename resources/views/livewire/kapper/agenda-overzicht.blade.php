<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold">Agenda</h2>
        <input wire:model.live="geselecteerdeDatum" type="date" class="rounded border-gray-300 text-sm">
    </div>
    <div class="space-y-3">
        @forelse($afspraken as $afspraak)
        <div class="bg-white rounded shadow p-4 flex justify-between items-center">
            <div>
                <p class="font-semibold">{{ $afspraak->start_tijd }} — {{ $afspraak->eind_tijd }}</p>
                <p class="text-gray-700">{{ $afspraak->klant->name }}</p>
                <p class="text-gray-500 text-sm">{{ $afspraak->dienst->naam }} · {{ $afspraak->betaalmethode === 'online' ? 'Online betaald' : 'In zaak betalen' }}</p>
            </div>
            <div class="flex gap-2">
                @if($afspraak->status === 'gepland')
                <button wire:click="voltooid({{ $afspraak->id }})" class="text-sm bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Voltooid</button>
                <button wire:click="noShow({{ $afspraak->id }})" wire:confirm="No-show markeren?" class="text-sm bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">No-show</button>
                @else
                <span class="text-sm px-3 py-1 rounded
                    {{ $afspraak->status === 'voltooid' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $afspraak->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $afspraak->status === 'geannuleerd' ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
                </span>
                @endif
            </div>
        </div>
        @empty
        <p class="text-gray-500 text-center py-8 bg-white rounded shadow">Geen afspraken op deze dag.</p>
        @endforelse
    </div>
</div>
