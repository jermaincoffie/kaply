<div class="p-4 sm:p-6 max-w-4xl mx-auto space-y-6">

    <div>
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Galerij</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Foto's van je salon en werk · max 12 foto's</p>
    </div>

    {{-- Succesmelding --}}
    @if($succesmelding)
    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl px-4 py-3 flex items-center gap-2">
        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-sm text-green-700 dark:text-green-300">{{ $succesmelding }}</p>
    </div>
    @endif

    {{-- Upload sectie --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-medium text-gray-700 dark:text-neutral-200">Foto's toevoegen</p>
            <span class="text-xs text-gray-400 dark:text-neutral-500">{{ $fotos->count() }}/12 foto's</span>
        </div>

        @error('nieuwefotos') <p class="text-xs text-red-500 mb-3">{{ $message }}</p> @enderror
        @error('nieuwefotos.*') <p class="text-xs text-red-500 mb-3">{{ $message }}</p> @enderror

        <div x-data="{ previews: [] }" @fotos-geupload.window="previews = []">
            <label class="block border-2 border-dashed border-gray-200 dark:border-neutral-600 rounded-xl p-8 text-center cursor-pointer hover:border-blue-400 dark:hover:border-blue-500 transition-colors">
                <input type="file" wire:model="nieuwefotos" multiple accept="image/*" class="hidden"
                       x-on:change="previews = Array.from($event.target.files).map(f => URL.createObjectURL(f))">
                <svg class="w-8 h-8 text-gray-300 dark:text-neutral-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 9.75A6.75 6.75 0 019.75 3h4.5A6.75 6.75 0 0121 9.75v4.5A6.75 6.75 0 0114.25 21H9.75A6.75 6.75 0 013 14.25V9.75z"/>
                </svg>
                <p class="text-sm text-gray-500 dark:text-neutral-400">Klik om foto's te selecteren</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">JPG, PNG, WEBP · max 4MB per foto</p>
            </label>

            <template x-if="previews.length > 0">
                <div class="mt-4 grid grid-cols-3 sm:grid-cols-4 gap-3">
                    <template x-for="(url, i) in previews" :key="i">
                        <div class="relative aspect-square rounded-lg overflow-hidden bg-gray-100 dark:bg-neutral-700">
                            <img :src="url" class="w-full h-full object-cover">
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <div class="mt-4">
            <button wire:click="uploaden" wire:loading.attr="disabled" wire:target="uploaden,nieuwefotos"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-2">
                <svg wire:loading wire:target="uploaden,nieuwefotos" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                <span wire:loading.remove wire:target="nieuwefotos">Uploaden</span>
                <span wire:loading wire:target="nieuwefotos">Foto's worden geladen...</span>
            </button>
        </div>
    </div>

    {{-- Huidige foto's --}}
    @if($fotos->count() > 0)
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
        <p class="text-sm font-medium text-gray-700 dark:text-neutral-200 mb-4">Jouw foto's</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            @foreach($fotos as $foto)
            <div class="relative group aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-neutral-700">
                <img src="{{ asset('storage/' . $foto->pad) }}" alt="Galerij foto" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <button wire:click="verwijderen({{ $foto->id }})"
                            wire:confirm="Deze foto verwijderen?"
                            class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
