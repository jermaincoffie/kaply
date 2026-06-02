<div>
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Account</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Beheer je persoonlijke gegevens</p>
    </div>

    <div class="space-y-5">

        {{-- Gegevens --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-4">Persoonlijke gegevens</h2>
            <form wire:submit="opslaanGegevens" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Naam</label>
                    <input wire:model="name" type="text"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">E-mailadres</label>
                    <input wire:model="email" type="email"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit"
                        x-data="{ saved: false }"
                        @gegevens-opgeslagen.window="saved = true; setTimeout(() => saved = false, 3000)"
                        :class="saved ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="saved ? 'Opgeslagen!' : 'Opslaan'"></span>
                </button>
            </form>
        </div>

        {{-- Wachtwoord --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-4">Wachtwoord wijzigen</h2>
            <form wire:submit="opslaanWachtwoord" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Huidig wachtwoord</label>
                    <input wire:model="huidig_wachtwoord" type="password"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('huidig_wachtwoord') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Nieuw wachtwoord</label>
                    <input wire:model="nieuw_wachtwoord" type="password" placeholder="Minimaal 8 tekens"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('nieuw_wachtwoord') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Bevestig nieuw wachtwoord</label>
                    <input wire:model="nieuw_wachtwoord_confirmation" type="password"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>
                <button type="submit"
                        x-data="{ saved: false }"
                        @wachtwoord-opgeslagen.window="saved = true; setTimeout(() => saved = false, 3000)"
                        :class="saved ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="saved ? 'Wachtwoord gewijzigd!' : 'Wachtwoord wijzigen'"></span>
                </button>
            </form>
        </div>

    </div>
</div>
