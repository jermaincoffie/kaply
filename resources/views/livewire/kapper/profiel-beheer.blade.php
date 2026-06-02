<div>
    <h2 class="text-xl font-bold mb-4">Mijn profiel</h2>
    @if(session('message'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('message') }}</div>
    @endif
    <form wire:submit="opslaan" class="bg-white p-6 rounded shadow space-y-4 max-w-lg">
        <div>
            <label class="block text-sm font-medium">Saloonnaam</label>
            <input wire:model="salon_naam" type="text" class="mt-1 block w-full rounded border-gray-300">
            @error('salon_naam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Adres</label>
            <input wire:model="adres" type="text" class="mt-1 block w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Stad</label>
            <input wire:model="stad" type="text" class="mt-1 block w-full rounded border-gray-300">
            @error('stad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Telefoonnummer</label>
            <input wire:model="telefoon" type="text" class="mt-1 block w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Bio</label>
            <textarea wire:model="bio" rows="4" class="mt-1 block w-full rounded border-gray-300"></textarea>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Opslaan</button>
    </form>
</div>
