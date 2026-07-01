<div class="p-4 sm:p-6 max-w-2xl mx-auto space-y-6">

    <div>
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Account</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Beheer je inloggegevens</p>
    </div>

    {{-- Wachtwoord wijzigen --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-4">Wachtwoord wijzigen</p>

        <form wire:submit="wijzigWachtwoord" class="space-y-4">

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-neutral-400 mb-1">Huidig wachtwoord</label>
                <input type="password" wire:model="huidigWachtwoord"
                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-800 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       autocomplete="current-password">
                @error('huidigWachtwoord')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-neutral-400 mb-1">Nieuw wachtwoord</label>
                <input type="password" wire:model="nieuwWachtwoord"
                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-800 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       autocomplete="new-password">
                @error('nieuwWachtwoord')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-neutral-400 mb-1">Bevestig nieuw wachtwoord</label>
                <input type="password" wire:model="nieuwWachtwoordBevestiging"
                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-800 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       autocomplete="new-password">
                @error('nieuwWachtwoordBevestiging')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                    Wachtwoord opslaan
                </button>
            </div>

        </form>
    </div>

    {{-- Accountgegevens (readonly) --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-4">Accountgegevens</p>
        <div class="space-y-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-neutral-500 mb-1">Naam</label>
                <p class="text-sm text-gray-800 dark:text-neutral-200">{{ $naam }}</p>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-neutral-500 mb-1">E-mailadres</label>
                <p class="text-sm text-gray-800 dark:text-neutral-200">{{ $email }}</p>
            </div>
        </div>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-4">Naam of e-mailadres wijzigen? Neem contact op via <a href="mailto:info@kaply.nl" class="underline">info@kaply.nl</a>.</p>
    </div>

</div>
