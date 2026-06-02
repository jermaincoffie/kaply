<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Medewerkers</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Barbers die klanten kunnen selecteren bij het boeken</p>
        </div>
        <button wire:click="openFormulier"
                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Medewerker toevoegen
        </button>
    </div>

    {{-- Formulier --}}
    @if($toonFormulier)
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5 mb-5">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-4">Nieuwe medewerker</h2>
        <form wire:submit="toevoegen" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Naam</label>
                    <input wire:model="naam" type="text" placeholder="Voornaam"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('naam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Foto <span class="text-gray-400">(optioneel)</span></label>
                    @if($foto)
                    <img src="{{ $foto->temporaryUrl() }}" class="w-10 h-10 rounded-full object-cover mb-2">
                    @endif
                    <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg border border-gray-200 dark:border-neutral-700 text-sm text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors w-fit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Uploaden
                        <input wire:model="foto" type="file" accept="image/*" class="hidden">
                    </label>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-colors">Toevoegen</button>
                <button type="button" wire:click="sluitFormulier" class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">Annuleer</button>
            </div>
        </form>
    </div>
    @endif

    {{-- Lijst --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
        @forelse($medewerkers as $medewerker)
        <div class="flex items-center gap-4 px-5 py-3.5 {{ !$loop->last ? 'border-b border-gray-100 dark:border-neutral-700' : '' }}">
            {{-- Avatar --}}
            @if($medewerker->foto)
            <img src="{{ asset('storage/' . $medewerker->foto) }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
            @else
            <div class="w-9 h-9 rounded-full bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-bold text-blue-400">{{ mb_strtoupper(mb_substr($medewerker->naam, 0, 1)) }}</span>
            </div>
            @endif

            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800 dark:text-neutral-100">{{ $medewerker->naam }}</p>
            </div>

            {{-- Actief toggle --}}
            <button wire:click="toggleActief({{ $medewerker->id }})"
                    class="text-xs px-2.5 py-1 rounded-full font-medium transition-colors
                        {{ $medewerker->actief ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-100' : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400 hover:bg-gray-200' }}">
                {{ $medewerker->actief ? 'Actief' : 'Inactief' }}
            </button>

            <button @click.prevent="$dispatch('open-confirm', { title: 'Medewerker verwijderen', message: 'Weet je zeker dat je {{ addslashes($medewerker->naam) }} wilt verwijderen?', action: () => $wire.verwijder({{ $medewerker->id }}) })"
                    class="text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 transition-colors">
                Verwijder
            </button>
        </div>
        @empty
        <div class="px-5 py-12 text-center">
            <svg class="w-10 h-10 text-gray-200 dark:text-neutral-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-sm text-gray-400 dark:text-neutral-500">Nog geen medewerkers toegevoegd</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Klanten kunnen dan de kapper zelf selecteren</p>
        </div>
        @endforelse
    </div>
</div>
