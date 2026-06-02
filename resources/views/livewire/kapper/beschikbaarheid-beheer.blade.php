<div>
    <h2 class="text-xl font-bold mb-4">Beschikbaarheid</h2>

    @if(session('message'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('message') }}</div>
    @endif

    <form wire:submit="opslaan" class="bg-white p-4 rounded shadow mb-6">
        <h3 class="font-semibold mb-3">Weekrooster</h3>
        @foreach($rooster as $dag => $data)
        <div class="flex items-center gap-4 py-2 border-b last:border-b-0">
            <div class="w-24">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model.live="rooster.{{ $dag }}.actief" type="checkbox" class="rounded">
                    <span class="text-sm font-medium">{{ $data['naam'] }}</span>
                </label>
            </div>
            @if($data['actief'])
            <input wire:model="rooster.{{ $dag }}.start_tijd" type="time" class="rounded border-gray-300 text-sm">
            <span class="text-gray-500">tot</span>
            <input wire:model="rooster.{{ $dag }}.eind_tijd" type="time" class="rounded border-gray-300 text-sm">
            @endif
        </div>
        @endforeach
        <button type="submit" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Opslaan</button>
    </form>

    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-3">Sluitingsdagen / Vakantie</h3>
        <form wire:submit="sluitingsdagToevoegen" class="flex gap-3 mb-4">
            <input wire:model="sluitingsDatum" type="date" class="rounded border-gray-300">
            <input wire:model="sluitingsReden" type="text" placeholder="Reden (optioneel)" class="rounded border-gray-300 flex-1">
            @error('sluitingsDatum') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            <button type="submit" class="bg-gray-800 text-white px-3 py-1 rounded">Toevoegen</button>
        </form>
        <ul class="space-y-1">
            @forelse($sluitingsdagen as $dag)
            <li class="flex justify-between text-sm py-1 border-b">
                <span>{{ $dag->datum->format('d-m-Y') }} {{ $dag->reden ? '— '.$dag->reden : '' }}</span>
                <button wire:click="sluitingsdagVerwijderen({{ $dag->id }})" class="text-red-600 hover:underline">Verwijder</button>
            </li>
            @empty
            <li class="text-gray-500 text-sm">Geen sluitingsdagen gepland.</li>
            @endforelse
        </ul>
    </div>
</div>
