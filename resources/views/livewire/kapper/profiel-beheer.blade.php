<div>
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Profiel</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Jouw publieke profielpagina voor klanten</p>
    </div>

    @if(session('message'))
    <div class="flex items-center gap-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl text-sm mb-6">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('message') }}
    </div>
    @endif

    <form wire:submit="opslaan" class="space-y-6" enctype="multipart/form-data">

        {{-- Salon foto --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-4">Salon foto</h2>
            <div class="flex items-start gap-5">
                {{-- Huidige foto --}}
                <div class="flex-shrink-0">
                    @if(auth()->user()->kapper->foto)
                    <img src="{{ asset('storage/' . auth()->user()->kapper->foto) }}"
                         alt="Salon foto"
                         class="w-24 h-24 rounded-xl object-cover border border-gray-200 dark:border-neutral-700">
                    @else
                    <div class="w-24 h-24 rounded-xl bg-gray-100 dark:bg-neutral-700 flex items-center justify-center border border-gray-200 dark:border-neutral-700">
                        <svg class="w-8 h-8 text-gray-300 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1">
                    @if($foto)
                    <img src="{{ $foto->temporaryUrl() }}" class="w-24 h-24 rounded-xl object-cover border border-blue-300 mb-3">
                    @endif
                    <label class="flex items-center gap-2 cursor-pointer inline-flex px-3 py-2 rounded-lg border border-gray-200 dark:border-neutral-700 text-sm text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Foto uploaden
                        <input wire:model="foto" type="file" accept="image/*" class="hidden">
                    </label>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1.5">JPG, PNG — max 2 MB</p>
                    @error('foto') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
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
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Profiel opslaan
        </button>
    </form>
</div>
