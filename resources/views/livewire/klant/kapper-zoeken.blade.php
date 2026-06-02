<div>
    {{-- Hero --}}
    <div class="bg-white dark:bg-neutral-900 border-b border-gray-200 dark:border-neutral-700 py-20 px-4">
        <div class="text-center mb-10">
            <p class="text-sm font-medium text-gray-400 dark:text-neutral-500 mb-2 tracking-widest uppercase">Welkom bij</p>
            <h1 class="text-6xl font-extrabold tracking-tight text-gray-900 dark:text-neutral-100 mb-6">
                {{ config('app.name') }}
            </h1>
            <p class="text-gray-400 dark:text-neutral-500 text-sm">Bekijk beschikbare tijden en boek direct online</p>
        </div>

        {{-- Pill zoekbalk --}}
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-full px-6 py-4 shadow-sm focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all">
                <svg class="w-5 h-5 text-gray-400 dark:text-neutral-500 flex-shrink-0 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input wire:model.live="zoekterm" type="text"
                    placeholder="Zoek op stad of naam..."
                    class="flex-1 bg-transparent border-none outline-none text-gray-900 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 text-sm focus:ring-0">
                @if($zoekterm)
                <button wire:click="$set('zoekterm', '')" class="ml-3 text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Results --}}
    <div class="max-w-5xl mx-auto px-4 py-8">
        @if($zoekterm)
        <p class="text-sm text-gray-500 dark:text-neutral-400 mb-5">
            Resultaten voor <span class="font-semibold text-gray-700 dark:text-neutral-300">"{{ $zoekterm }}"</span>
        </p>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($kappers as $kapper)
            <a href="{{ route('kapper.profiel', $kapper->slug) }}"
               class="group bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5 hover:shadow-md hover:border-blue-200 dark:hover:border-neutral-500 transition-all duration-150">

                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-700 dark:text-blue-400 font-bold text-sm">
                            {{ mb_strtoupper(mb_substr($kapper->salon_naam, 0, 1)) }}
                        </span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-sm text-gray-900 dark:text-neutral-100 truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $kapper->salon_naam }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-neutral-400 truncate">{{ $kapper->stad }}</p>
                    </div>
                </div>

                @if($kapper->bio)
                <p class="text-xs text-gray-500 dark:text-neutral-400 line-clamp-2 mb-3 leading-relaxed">{{ $kapper->bio }}</p>
                @endif

                <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-neutral-700">
                    <span class="text-xs text-gray-400 dark:text-neutral-500">
                        {{ $kapper->diensten->count() }} {{ $kapper->diensten->count() === 1 ? 'dienst' : 'diensten' }}
                    </span>
                    <span class="text-xs font-medium text-blue-600 dark:text-blue-400">
                        Boek afspraak →
                    </span>
                </div>
            </a>
            @empty
            <div class="col-span-3 py-20 text-center">
                <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700 dark:text-neutral-300">Geen kappers gevonden</p>
                @if($zoekterm)
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Probeer een andere zoekterm of stad</p>
                @else
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Er zijn nog geen kappers geregistreerd</p>
                @endif
            </div>
            @endforelse
        </div>
    </div>
</div>
