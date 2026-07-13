<div class="min-h-screen bg-white dark:bg-neutral-900">

    {{-- Header --}}
    <div class="border-b border-gray-100 dark:border-neutral-800 bg-white dark:bg-neutral-900 py-8 px-4">
        <div class="max-w-5xl mx-auto">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-xs text-gray-400 dark:text-neutral-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Kappers</a>
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-600 dark:text-neutral-300 font-medium">{{ $this->stadLabel }}</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-neutral-100 tracking-tight">
                        Kappers in {{ $this->stadLabel }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-neutral-400 mt-1">
                        @if($kappers->count() > 0)
                            {{ $kappers->count() }} {{ $kappers->count() === 1 ? 'kapper' : 'kappers' }} gevonden · Boek direct online
                        @else
                            Nog geen kappers geregistreerd in deze stad
                        @endif
                    </p>
                </div>

                {{-- Filters --}}
                @if($diensteNamen->count() > 0)
                <div class="flex items-center gap-2 flex-wrap">
                    <x-select
                        wire-target="dienstFilter"
                        :current="$dienstFilter"
                        :options="collect([''=>'Alle diensten'])->merge($diensteNamen->mapWithKeys(fn($n)=>[$n=>$n]))->toArray()"
                        placeholder="Alle diensten"
                    />
                    <x-select
                        wire-target="prijsMax"
                        :current="$prijsMax"
                        :options="['' => 'Alle prijzen', 'p15' => 'Tot €15', 'p25' => 'Tot €25', 'p40' => 'Tot €40', 'p60' => 'Tot €60', 'p100' => 'Tot €100']"
                        placeholder="Alle prijzen"
                    />
                    @if($heeftFilters)
                    <button wire:click="resetFilters"
                            class="inline-flex items-center gap-1 py-1.5 px-2.5 rounded-lg text-xs text-gray-500 dark:text-neutral-400 hover:text-red-600 dark:hover:text-red-400 border border-gray-200 dark:border-neutral-700 hover:border-red-300 dark:hover:border-red-700 bg-white dark:bg-neutral-800 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Wis
                    </button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Kapper grid --}}
    <div class="max-w-5xl mx-auto px-4 py-8">
        @if($kappers->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($kappers as $kapper)
            <a wire:key="kapper-{{ $kapper->id }}" href="{{ route('kapper.profiel', $kapper->slug) }}"
               class="group flex flex-col bg-gradient-to-b from-indigo-50 to-white dark:from-neutral-700 dark:to-neutral-800 border border-indigo-100 dark:border-neutral-700 rounded-xl overflow-hidden hover:shadow-md hover:border-blue-200 dark:hover:border-neutral-500 transition-all duration-150">

                {{-- Foto --}}
                <div class="h-36 flex items-center justify-center overflow-hidden">
                    @if($kapper->foto)
                    <img src="{{ asset('storage/' . $kapper->foto) }}"
                         alt="{{ $kapper->salon_naam }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                    <span class="text-6xl font-extrabold text-blue-200 dark:text-neutral-500 select-none tracking-tight">
                        {{ mb_strtoupper(mb_substr($kapper->salon_naam, 0, 1)) }}
                    </span>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex flex-col flex-1 p-4 bg-transparent border-t border-gray-200 dark:border-neutral-600">
                    <p class="font-semibold text-sm text-gray-900 dark:text-neutral-100 truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                        {{ $kapper->salon_naam }}
                    </p>

                    @if($kapper->gem_rating)
                    <div class="flex items-center gap-1 mt-1">
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3 h-3 {{ $i <= round($kapper->gem_rating) ? 'text-amber-400' : 'text-gray-200 dark:text-neutral-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <span class="text-xs font-semibold text-gray-700 dark:text-neutral-300">{{ number_format($kapper->gem_rating, 1) }}</span>
                        <span class="text-xs text-gray-400 dark:text-neutral-500">({{ $kapper->review_count }})</span>
                    </div>
                    @endif

                    @if($kapper->adres)
                    <div class="flex items-center gap-1 mt-1.5">
                        <svg class="w-3 h-3 text-gray-400 dark:text-neutral-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-xs text-gray-500 dark:text-neutral-400 truncate">{{ $kapper->adres }}</p>
                    </div>
                    @endif

                    @if($kapper->diensten->count() > 0)
                    @php $zichtbareDiensten = $kapper->diensten->take(3); $extra = $kapper->diensten->count() - 3; @endphp
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($zichtbareDiensten as $dienst)
                        <span wire:key="dienst-{{ $kapper->id }}-{{ $dienst->id }}" class="px-2 py-0.5 rounded-full text-xs bg-gray-100 dark:bg-neutral-700 text-gray-600 dark:text-neutral-300">{{ $dienst->naam }}</span>
                        @endforeach
                        @if($extra > 0)
                        <span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 dark:bg-neutral-700 text-gray-500 dark:text-neutral-400">+{{ $extra }}</span>
                        @endif
                    </div>
                    @endif

                    <div class="flex justify-end mt-auto pt-3">
                        <span class="text-xs font-medium text-blue-600 dark:text-blue-400">Boek afspraak →</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Terug naar alle kappers --}}
        <div class="flex justify-center mt-10">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-200 dark:border-neutral-700 text-sm font-medium text-gray-600 dark:text-neutral-400 bg-white dark:bg-neutral-800 hover:border-blue-300 hover:text-blue-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Alle kappers bekijken
            </a>
        </div>

        @else
        {{-- Geen kappers --}}
        <div class="py-20 text-center">
            <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-gray-700 dark:text-neutral-300">Geen kappers in {{ $this->stadLabel }}</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1 mb-5">Er zijn nog geen kappers geregistreerd in deze stad</p>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors">
                Alle kappers bekijken
            </a>
        </div>
        @endif
    </div>
</div>
