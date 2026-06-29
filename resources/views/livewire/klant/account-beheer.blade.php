<div>
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Account</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Beheer je persoonlijke gegevens</p>
    </div>

    <div class="space-y-5">

        {{-- Favoriete kappers --}}
        @if($favorieteKappers->isNotEmpty())
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-4">Favoriete kappers</h2>
            <div class="space-y-2">
                @foreach($favorieteKappers as $favKapper)
                <div class="flex items-center justify-between gap-3 py-2 border-b border-gray-50 dark:border-neutral-700 last:border-0">
                    <a href="{{ route('kapper.profiel', $favKapper->slug) }}"
                       class="flex items-center gap-3 group">
                        @if($favKapper->foto)
                        <img src="{{ asset('public/storage/' . $favKapper->foto) }}" alt="{{ $favKapper->salon_naam }}"
                             class="w-9 h-9 rounded-lg object-cover flex-shrink-0">
                        @else
                        @php
                            $kleuren = ['bg-blue-100 text-blue-700','bg-emerald-100 text-emerald-700','bg-violet-100 text-violet-700','bg-rose-100 text-rose-700','bg-amber-100 text-amber-700'];
                            $kleur   = $kleuren[abs(crc32($favKapper->salon_naam)) % count($kleuren)];
                        @endphp
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ $kleur }}">
                            <span class="text-sm font-bold">{{ mb_strtoupper(mb_substr($favKapper->salon_naam, 0, 1)) }}</span>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $favKapper->salon_naam }}</p>
                            @if($favKapper->stad)
                            <p class="text-xs text-gray-400 dark:text-neutral-500">{{ str($favKapper->stad)->title() }}</p>
                            @endif
                        </div>
                    </a>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ route('kapper.profiel', $favKapper->slug) }}"
                           class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                            Boek
                        </a>
                        <button wire:click="verwijderFavoriet({{ $favKapper->id }})"
                                class="p-1 rounded hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors text-gray-400 hover:text-red-500 dark:hover:text-red-400"
                                title="Verwijder uit favorieten">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Gegevens --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-4">Persoonlijke gegevens</h2>
            <form wire:submit="opslaanGegevens" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Voornaam <span class="text-red-500">*</span></label>
                        <input wire:model="voornaam" type="text" placeholder="Jan"
                               class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('voornaam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Achternaam <span class="text-red-500">*</span></label>
                        <input wire:model="achternaam" type="text" placeholder="Jansen"
                               class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('achternaam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Telefoonnummer</label>
                    <input wire:model="telefoon" type="text" placeholder="0612345678"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">E-mailadres <span class="text-red-500">*</span></label>
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

    </div>
</div>
