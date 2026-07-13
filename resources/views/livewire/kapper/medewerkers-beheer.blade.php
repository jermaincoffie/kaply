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
            <span class="hidden sm:inline">Medewerker toevoegen</span>
            <span class="sm:hidden">Toevoegen</span>
        </button>
    </div>

    {{-- Formulier --}}
    @if($toonFormulier)
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5 mb-5">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-4">Nieuwe medewerker</h2>
        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Naam</label>
                    <input wire:model="naam" type="text" placeholder="Voornaam"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('naam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Foto <span class="text-gray-400">(optioneel)</span></label>
                    <div class="flex flex-col items-start gap-3">
                        {{-- Avatar preview --}}
                        @if($foto)
                        <img src="{{ $foto->temporaryUrl() }}"
                             class="w-16 h-16 rounded-full object-cover border-2 border-blue-400 flex-shrink-0">
                        @else
                        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-neutral-700 border border-gray-200 dark:border-neutral-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-gray-400 dark:text-neutral-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                        @endif
                        {{-- Upload knop --}}
                        <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg border border-gray-200 dark:border-neutral-700 text-sm text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                            <svg wire:loading.remove wire:target="foto" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <svg wire:loading wire:target="foto" class="w-4 h-4 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            <span wire:loading.remove wire:target="foto">{{ $foto ? 'Wijzigen' : 'Foto uploaden' }}</span>
                            <span wire:loading wire:target="foto" class="text-blue-500">Uploaden...</span>
                            <input wire:model="foto" type="file" accept="image/*" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="button" wire:click="toevoegen" class="px-4 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-colors">Toevoegen</button>
                <button type="button" wire:click="sluitFormulier" class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">Annuleer</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Flash: rooster opgeslagen --}}
    @if(session('rooster_opgeslagen'))
    <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-sm text-green-700 dark:text-green-400">
        Rooster van {{ session('rooster_opgeslagen') }} opgeslagen.
    </div>
    @endif

    {{-- Lijst --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
        @forelse($medewerkers as $medewerker)
        <div class="{{ !$loop->last || $openRoosterId === $medewerker->id ? 'border-b border-gray-100 dark:border-neutral-700' : '' }}">
            {{-- Rij --}}
            <div class="flex items-center gap-3 px-4 sm:px-5 py-3.5">
                {{-- Avatar --}}
                @if($medewerker->foto)
                <img src="{{ asset('storage/' . $medewerker->foto) }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                @else
                <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-neutral-700 border border-gray-200 dark:border-neutral-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-400 dark:text-neutral-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                @endif

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">{{ $medewerker->naam }}</p>
                        @if($medewerker->beschikbaarheden_count > 0)
                        <span class="hidden sm:inline text-xs px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-medium">Eigen rooster</span>
                        @endif
                    </div>
                    {{-- Mobiel: controls onder naam --}}
                    <div class="flex items-center gap-3 mt-1.5 sm:hidden">
                        <button wire:click="toggleActief({{ $medewerker->id }})"
                                class="text-xs px-2.5 py-0.5 rounded-full font-medium transition-colors
                                    {{ $medewerker->actief ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                            {{ $medewerker->actief ? 'Actief' : 'Inactief' }}
                        </button>
                        <button wire:click="openRooster({{ $medewerker->id }})"
                                class="text-xs font-medium text-blue-600 dark:text-blue-400">
                            {{ $openRoosterId === $medewerker->id ? 'Sluiten' : 'Rooster' }}
                        </button>
                        <button @click.prevent="$dispatch('open-confirm', { title: 'Medewerker verwijderen', message: 'Weet je zeker dat je {{ addslashes($medewerker->naam) }} wilt verwijderen?', action: () => $wire.verwijder({{ $medewerker->id }}) })"
                                class="text-xs font-medium text-red-500 dark:text-red-400">
                            Verwijder
                        </button>
                    </div>
                </div>

                {{-- Desktop: controls rechts --}}
                <div class="hidden sm:flex items-center gap-3">
                    <button wire:click="toggleActief({{ $medewerker->id }})"
                            class="text-xs px-2.5 py-1 rounded-full font-medium transition-colors
                                {{ $medewerker->actief ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-100' : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400 hover:bg-gray-200' }}">
                        {{ $medewerker->actief ? 'Actief' : 'Inactief' }}
                    </button>
                    <button wire:click="openRooster({{ $medewerker->id }})"
                            class="text-xs px-2.5 py-1 rounded-lg font-medium border transition-colors
                                {{ $openRoosterId === $medewerker->id ? 'border-blue-300 text-blue-600 bg-blue-50 dark:border-blue-700 dark:text-blue-400 dark:bg-blue-900/20' : 'border-gray-200 text-gray-600 bg-white dark:border-neutral-700 dark:text-neutral-400 dark:bg-neutral-800 hover:border-blue-300 hover:text-blue-600' }}">
                        {{ $openRoosterId === $medewerker->id ? 'Sluiten' : 'Rooster instellen' }}
                    </button>
                    <button @click.prevent="$dispatch('open-confirm', { title: 'Medewerker verwijderen', message: 'Weet je zeker dat je {{ addslashes($medewerker->naam) }} wilt verwijderen?', action: () => $wire.verwijder({{ $medewerker->id }}) })"
                            class="text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 transition-colors">
                        Verwijder
                    </button>
                </div>
            </div>

            {{-- Inline rooster editor --}}
            @if($openRoosterId === $medewerker->id)
            <div class="px-4 sm:px-5 py-4 bg-gray-50 dark:bg-neutral-900 border-t border-gray-100 dark:border-neutral-700">
                <p class="text-xs font-semibold text-gray-500 dark:text-neutral-400 uppercase tracking-wide mb-3">
                    Eigen werkrooster voor {{ $medewerker->naam }}
                </p>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mb-4">Als geen dag aangevinkt, wordt het salonrooster gebruikt.</p>

                <div class="space-y-2">
                    @foreach($medewerkerRooster as $dag => $data)
                    <div wire:key="rooster-{{ $dag }}" class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-0">

                        {{-- Checkbox + dagnaam --}}
                        <div class="flex items-center gap-3">
                            <input type="checkbox"
                                   wire:model.live="medewerkerRooster.{{ $dag }}.actief"
                                   class="w-4 h-4 flex-shrink-0 rounded border-gray-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            <span class="sm:w-28 sm:flex-shrink-0 text-sm {{ $data['actief'] ? 'text-gray-800 dark:text-neutral-100' : 'text-gray-400 dark:text-neutral-500' }}">
                                {{ $data['naam'] }}
                            </span>
                        </div>

                        {{-- Tijdvelden: desktop altijd, mobiel alleen als actief --}}
                        <div class="{{ $data['actief'] ? 'flex' : 'hidden sm:flex' }} items-center gap-2 ml-7 sm:ml-3">
                            <input type="time"
                                   wire:model.live="medewerkerRooster.{{ $dag }}.start_tijd"
                                   @if(!$data['actief']) disabled @endif
                                   class="py-1 px-1.5 w-[4.5rem] sm:w-24 rounded-lg border text-xs sm:text-sm focus:outline-none focus:border-blue-500
                                       {{ $data['actief']
                                           ? 'border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-800 text-gray-700 dark:text-neutral-200'
                                           : 'border-gray-100 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-900 text-gray-300 dark:text-neutral-600 cursor-not-allowed' }}">
                            <span class="text-xs {{ $data['actief'] ? 'text-gray-400 dark:text-neutral-500' : 'text-gray-200 dark:text-neutral-700' }}">tot</span>
                            <input type="time"
                                   wire:model.live="medewerkerRooster.{{ $dag }}.eind_tijd"
                                   @if(!$data['actief']) disabled @endif
                                   class="py-1 px-1.5 w-[4.5rem] sm:w-24 rounded-lg border text-xs sm:text-sm focus:outline-none focus:border-blue-500
                                       {{ $data['actief']
                                           ? 'border-gray-200 dark:border-neutral-600 bg-white dark:bg-neutral-800 text-gray-700 dark:text-neutral-200'
                                           : 'border-gray-100 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-900 text-gray-300 dark:text-neutral-600 cursor-not-allowed' }}">
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex gap-2 mt-4">
                    <button wire:click="slaRoosterOp"
                            class="px-4 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                        Opslaan
                    </button>
                    <button wire:click="$set('openRoosterId', null)"
                            class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        Annuleer
                    </button>
                </div>
            </div>
            @endif
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
