<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="bg-white p-8 rounded-lg shadow w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6">Registreer als kapper</h1>
        <form wire:submit="registreer" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Naam</label>
                <input wire:model="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">E-mailadres</label>
                <input wire:model="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Wachtwoord</label>
                <input wire:model="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Wachtwoord bevestigen</label>
                <input wire:model="password_confirmation" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <hr>
            <div>
                <label class="block text-sm font-medium text-gray-700">Saloonnaam</label>
                <input wire:model="salon_naam" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('salon_naam') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Stad</label>
                <input wire:model="stad" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('stad') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Telefoonnummer</label>
                <input wire:model="telefoon" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                Registreren
            </button>
        </form>
    </div>
</div>
