<div>
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Profiel</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Jouw publieke profielpagina voor klanten</p>
    </div>


    <form wire:submit="opslaan" class="space-y-6" enctype="multipart/form-data">

        {{-- Salon foto --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-4">Salon foto</h2>
            <div class="flex flex-col items-start gap-3">
                {{-- Preview: nieuw > huidig > placeholder --}}
                @if($foto)
                <img src="{{ $foto->temporaryUrl() }}"
                     class="w-32 h-32 rounded-xl object-cover border-2 border-blue-400">
                @elseif(auth()->user()->kapper->foto)
                <img src="{{ asset('storage/' . auth()->user()->kapper->foto) }}"
                     alt="Salon foto"
                     class="w-32 h-32 rounded-xl object-cover border border-gray-200 dark:border-neutral-700">
                @else
                @php
                    $woorden  = explode(' ', trim(auth()->user()->kapper->salon_naam));
                    $init     = mb_strtoupper(mb_substr($woorden[0], 0, 1) . (isset($woorden[1]) ? mb_substr($woorden[1], 0, 1) : ''));
                    $kleuren  = ['bg-blue-400','bg-violet-400','bg-emerald-400','bg-rose-400','bg-amber-400','bg-cyan-400'];
                    $kleur    = $kleuren[abs(crc32(auth()->user()->kapper->salon_naam)) % count($kleuren)];
                @endphp
                <div class="w-32 h-32 rounded-xl bg-neutral-800 flex items-center justify-center">
                    <span class="text-3xl font-bold {{ str_replace('bg-', 'text-', $kleur) }}">{{ $init }}</span>
                </div>
                @endif

                <div class="flex items-center gap-2">
                    <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg border border-gray-200 dark:border-neutral-700 text-sm text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Foto uploaden
                        <input wire:model="foto" type="file" accept="image/*" class="hidden">
                    </label>
                    @if(auth()->user()->kapper->foto && !$foto)
                    <button type="button" @click.prevent="$dispatch('open-confirm', { title: 'Foto verwijderen', message: 'Weet je zeker dat je de salon foto wilt verwijderen?', action: () => $wire.fotoVerwijderen() })"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-lg border border-red-200 dark:border-red-900 text-sm text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Verwijder
                    </button>
                    @endif
                </div>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1.5">JPG, PNG — max 2 MB</p>
                @error('foto') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Salon info --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-4">Salon informatie</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Saloonnaam</label>
                    <input wire:model="salon_naam" type="text"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('salon_naam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Stad</label>
                    <input wire:model="stad" type="text"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('stad') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Telefoon</label>
                    <input wire:model="telefoon" type="text"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Adres</label>
                    <input wire:model="adres" type="text"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Bio</label>
                    <textarea wire:model="bio" rows="4" placeholder="Beschrijf je salon..."
                              class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 resize-none"></textarea>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Max 1000 tekens</p>
                </div>
            </div>
        </div>

        <button type="submit"
                x-data="{ saved: false }"
                @profiel-opgeslagen.window="saved = true; setTimeout(() => saved = false, 3000)"
                :class="saved ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span x-text="saved ? 'Profiel opgeslagen!' : 'Profiel opslaan'"></span>
        </button>
    </form>
</div>
