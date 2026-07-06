<div class="p-4 sm:p-6 max-w-2xl mx-auto space-y-6">

    <div>
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Account</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Beheer je inloggegevens</p>
    </div>

    {{-- Wachtwoord wijzigen --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-4">Wachtwoord wijzigen</p>

        <form wire:submit="wijzigWachtwoord" class="space-y-4">

            <div x-data="{ toon: false }">
                <label class="block text-xs font-medium text-gray-600 dark:text-neutral-400 mb-1">Huidig wachtwoord</label>
                <div class="relative">
                    <input :type="toon ? 'text' : 'password'" wire:model="huidigWachtwoord"
                           class="w-full px-3 py-2 pr-10 text-sm rounded-lg border border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-800 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           autocomplete="current-password">
                    <button type="button" @click="toon = !toon" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300">
                        <svg x-show="!toon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="toon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                @error('huidigWachtwoord')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="{ toon: false }">
                <label class="block text-xs font-medium text-gray-600 dark:text-neutral-400 mb-1">Nieuw wachtwoord</label>
                <div class="relative">
                    <input :type="toon ? 'text' : 'password'" wire:model="nieuwWachtwoord"
                           class="w-full px-3 py-2 pr-10 text-sm rounded-lg border border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-800 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           autocomplete="new-password">
                    <button type="button" @click="toon = !toon" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300">
                        <svg x-show="!toon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="toon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                @error('nieuwWachtwoord')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="{ toon: false }">
                <label class="block text-xs font-medium text-gray-600 dark:text-neutral-400 mb-1">Bevestig nieuw wachtwoord</label>
                <div class="relative">
                    <input :type="toon ? 'text' : 'password'" wire:model="nieuwWachtwoordBevestiging"
                           class="w-full px-3 py-2 pr-10 text-sm rounded-lg border border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-800 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           autocomplete="new-password">
                    <button type="button" @click="toon = !toon" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300">
                        <svg x-show="!toon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="toon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                @error('nieuwWachtwoordBevestiging')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit"
                        x-data="{ saved: false }"
                        @wachtwoord-opgeslagen.window="saved = true; setTimeout(() => saved = false, 3000)"
                        :class="saved ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white text-sm font-medium transition-colors">
                    <svg x-show="!saved" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                    <svg x-show="saved" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    <span x-text="saved ? 'Wachtwoord gewijzigd!' : 'Wachtwoord opslaan'"></span>
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

    {{-- Email notificaties --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-1">Email notificaties</p>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-4">Ontvang een email bij elke nieuwe afspraak.</p>

        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" wire:model.live="notificatieEmail" wire:change="slaNotificatieEmailOp"
                   class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500 cursor-pointer">
            <span class="text-sm text-gray-700 dark:text-neutral-300">Email sturen bij nieuwe afspraak</span>
        </label>

        @if(session('notificatie_opgeslagen'))
            <p class="mt-2 text-xs text-green-600 dark:text-green-400">Opgeslagen.</p>
        @endif
    </div>

    {{-- Account verwijderen --}}
    <div x-data="{ bevestig: false }"
         class="bg-white dark:bg-neutral-800 border border-red-100 dark:border-red-900/30 rounded-xl p-6">
        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-1">Account verwijderen</p>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-4">Alle gegevens, afspraken en je salonprofiel worden permanent verwijderd. Dit kan niet ongedaan worden.</p>

        <button @click="bevestig = true"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
            </svg>
            Account verwijderen
        </button>

        {{-- Bevestigingsmodal --}}
        <div x-show="bevestig"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display:none"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div @click.outside="bevestig = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="w-full max-w-sm bg-white dark:bg-neutral-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-neutral-700 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Account verwijderen</p>
                        <p class="text-xs text-gray-500 dark:text-neutral-400">Dit kan niet ongedaan worden</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-neutral-400 mb-6">Je salonprofiel, alle afspraken en je gegevens worden permanent verwijderd. Weet je het zeker?</p>
                <div class="flex gap-3">
                    <button @click="bevestig = false"
                            class="flex-1 px-4 py-2 rounded-lg border border-gray-200 dark:border-neutral-600 text-sm font-medium text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        Annuleren
                    </button>
                    <button @click="bevestig = false; $wire.verwijderAccount()"
                            class="flex-1 px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition-colors">
                        Ja, verwijderen
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
