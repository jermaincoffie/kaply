<div>
    <h2 class="text-xl font-bold mb-4">Diensten</h2>
    <form wire:submit="opslaan" class="bg-white p-4 rounded shadow mb-6 space-y-3">
        <h3 class="font-semibold">{{ $bewerkenId ? 'Dienst bewerken' : 'Nieuwe dienst' }}</h3>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-sm font-medium">Naam</label>
                <input wire:model="naam" type="text" class="mt-1 block w-full rounded border-gray-300">
                @error('naam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Duur (minuten)</label>
                <input wire:model="duur_minuten" type="number" min="5" class="mt-1 block w-full rounded border-gray-300">
                @error('duur_minuten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Prijs (€)</label>
                <input wire:model="prijs" type="text" placeholder="15.00" class="mt-1 block w-full rounded border-gray-300">
                @error('prijs') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">No-show bedrag (€)</label>
                <input wire:model="no_show_bedrag" type="text" placeholder="5.00" class="mt-1 block w-full rounded border-gray-300">
                @error('no_show_bedrag') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            {{ $bewerkenId ? 'Bijwerken' : 'Toevoegen' }}
        </button>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Naam</th>
                    <th class="px-4 py-2 text-left">Duur</th>
                    <th class="px-4 py-2 text-left">Prijs</th>
                    <th class="px-4 py-2 text-left">No-show</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($diensten as $dienst)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $dienst->naam }}</td>
                    <td class="px-4 py-2">{{ $dienst->duur_minuten }} min</td>
                    <td class="px-4 py-2">€ {{ $dienst->prijs_in_euros }}</td>
                    <td class="px-4 py-2">€ {{ $dienst->no_show_bedrag_in_euros }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <button wire:click="bewerk({{ $dienst->id }})" class="text-indigo-600 hover:underline">Bewerk</button>
                        <button wire:click="verwijder({{ $dienst->id }})" wire:confirm="Weet je het zeker?" class="text-red-600 hover:underline">Verwijder</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-4 text-gray-500 text-center">Nog geen diensten.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
