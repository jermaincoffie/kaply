<div>
    {{-- Hero --}}
    <div class="bg-white dark:bg-neutral-800 border-b border-gray-200 dark:border-neutral-700">
        <div class="max-w-3xl mx-auto px-4 py-12 text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-2">
                Vind een kapper bij jou in de buurt
            </h1>
            <p class="text-gray-500 dark:text-neutral-400 mb-8 text-sm">
                Bekijk beschikbare tijden en boek direct online
            </p>
            <div class="relative max-w-xl mx-auto">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </div>
                <input wire:model.live="zoekterm" type="text"
                    placeholder="Zoek op stad of naam..."
                    class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-900 text-gray-900 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm shadow-sm">
            </div>
        </div>
    </div>

    {{-- Results --}}
    <div class="max-w-5xl mx-auto px-4 py-8">

        @if($zoekterm)
        <p class="text-sm text-gray-500 dark:text-neutral-400 mb-4">
            Resultaten voor <span class="font-medium text-gray-700 dark:text-neutral-300">"{{ $zoekterm }}"</span>
        </p>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($kappers as $kapper)
            <a href="{{ route('kapper.profiel', $kapper->slug) }}"
               class="group bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5 hover:shadow-md hover:border-blue-300 dark:hover:border-neutral-600 transition-all duration-150">

                {{-- Avatar + naam --}}
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-700 dark:text-blue-400 font-bold text-sm">
                            {{ mb_strtoupper(mb_substr($kapper->salon_naam, 0, 1)) }}
                        </span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-neutral-100 text-sm truncate group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">
                            {{ $kapper->salon_naam }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-neutral-400 truncate">{{ $kapper->stad }}</p>
                    </div>
                </div>

                {{-- Bio --}}
                @if($kapper->bio)
                <p class="text-xs text-gray-500 dark:text-neutral-400 line-clamp-2 mb-3">{{ $kapper->bio }}</p>
                @endif

                {{-- Footer --}}
                <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-neutral-700">
                    <span class="text-xs text-gray-500 dark:text-neutral-400">
                        {{ $kapper->diensten->count() }} {{ $kapper->diensten->count() === 1 ? 'dienst' : 'diensten' }}
                    </span>
                    <span class="text-xs font-medium text-blue-600 dark:text-blue-400 group-hover:underline">
                        Bekijk →
                    </span>
                </div>
            </a>
            @empty
            <div class="col-span-3 py-16 text-center">
                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-neutral-700 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-neutral-400">Geen kappers gevonden</p>
                @if($zoekterm)
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Probeer een andere zoekterm</p>
                @endif
            </div>
            @endforelse
        </div>
    </div>
</div>
