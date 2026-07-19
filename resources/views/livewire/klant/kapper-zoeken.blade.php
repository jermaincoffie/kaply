<div x-data class="relative">

    {{-- Hero --}}
    <div class="relative z-30 min-h-[45vh] sm:min-h-0 flex flex-col justify-center pt-10 pb-4 sm:pt-28 sm:pb-8 px-4">
        <div class="text-center mb-6 sm:mb-14">
            <p class="hero-anim hero-anim-1 text-sm font-medium text-gray-500 dark:text-white mb-3 tracking-widest uppercase">Welkom bij</p>
            <h1 class="hero-anim hero-anim-2 text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 dark:text-neutral-100 mb-0">
                {{ config('app.name') }}
            </h1>
        </div>

        {{-- Pill zoekbalk --}}
        <div class="hero-anim hero-anim-4 max-w-5xl mx-auto w-full">
            <div class="flex items-center bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-full px-6 py-4 shadow-sm focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all">
                @if($steden->count() > 0)
                @php $stadOpties = collect([''=>'Alle steden'])->merge($steden->mapWithKeys(fn($s)=>[$s=>$s]))->toArray(); @endphp
                <div x-data="{
                        open: false,
                        current: '{{ $stadFilter }}',
                        options: {{ Js::from($stadOpties) }},
                        get label() { return this.options[this.current] ?? 'Alle steden'; },
                        choose(val) { this.current = val; this.open = false; this.$wire.set('stadFilter', val); }
                    }"
                    @click.outside="open = false"
                    class="relative flex-shrink-0">
                    <button type="button" @click="open = !open"
                            class="flex items-center gap-1.5 text-sm text-gray-700 dark:text-neutral-300 focus:outline-none whitespace-nowrap">
                        <span x-text="label" class="max-w-[120px] truncate"></span>
                        <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500 flex-shrink-0 transition-transform duration-150"
                             :class="open ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute left-0 top-full mt-3 z-50 w-52 bg-white dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 rounded-xl shadow-xl overflow-hidden"
                         style="display:none">
                        <template x-for="(label, val) in options" :key="val">
                            <button type="button" @click="choose(val)"
                                    class="w-full text-left flex items-center gap-2 px-3 py-2 text-sm transition-colors"
                                    :class="current === val ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-medium' : 'text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-800'">
                                <svg x-show="current === val" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span x-show="current !== val" class="w-4 inline-block flex-shrink-0"></span>
                                <span x-text="label"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="w-px h-5 bg-gray-200 dark:bg-neutral-600 mx-4 flex-shrink-0"></div>
                @endif
                <svg class="w-5 h-5 text-gray-400 dark:text-neutral-500 flex-shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input wire:model.live.debounce.400ms="zoekterm"
                    id="zoekterm-input"
                    wire:key="zoekterm-input"
                    type="text"
                    @keydown.enter.prevent
                    placeholder="Zoek op naam..."
                    autocomplete="off"
                    class="flex-1 bg-transparent border-none outline-none text-gray-900 placeholder-gray-400 text-sm focus:ring-0">
                @if($zoekterm)
                <button wire:click="$set('zoekterm', '')" onclick="document.getElementById('zoekterm-input').value=''" class="ml-3 text-gray-400 hover:text-gray-600 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Results --}}
    <div class="relative z-20 max-w-5xl mx-auto px-4 pb-6">

        {{-- Zoekterm feedback --}}
        @if($zoekterm)
        <p class="text-sm text-gray-500 dark:text-neutral-400 mb-3">
            Resultaten voor <span class="font-semibold text-gray-700 dark:text-neutral-300">"{{ $zoekterm }}"</span>
        </p>
        @endif

        {{-- Filter rij --}}
        @if($diensteNamen->count() > 0)
        <div class="flex items-center gap-2 mb-5">
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

    </div>{{-- einde max-w-5xl --}}

    {{-- Carousel full-width --}}
    <div class="relative z-10 pb-10">
        <div x-data="{
            updateArrows() {
                const c = this.$refs.carousel;
                this.canLeft = c.scrollLeft > 5;
                this.canRight = c.scrollLeft < c.scrollWidth - c.clientWidth - 5;
            },
            canLeft: false,
            canRight: true
        }" class="relative">

            {{-- Pijl links --}}
            <button x-show="canLeft" @click="$refs.carousel.scrollBy({ left: -300, behavior: 'smooth' })"
                    class="hidden sm:flex absolute left-2 top-1/2 -translate-y-1/2 z-10 w-9 h-9 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-full shadow-sm items-center justify-center text-gray-500 hover:text-gray-800 dark:hover:text-neutral-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>

            {{-- Pijl rechts --}}
            <button x-show="canRight" @click="$refs.carousel.scrollBy({ left: 300, behavior: 'smooth' })"
                    class="hidden sm:flex absolute right-2 top-1/2 -translate-y-1/2 z-10 w-9 h-9 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-full shadow-sm items-center justify-center text-gray-500 hover:text-gray-800 dark:hover:text-neutral-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Scrollbare rij --}}
            <div x-ref="carousel" @scroll="updateArrows()" x-init="$nextTick(() => updateArrows())"
                 class="overflow-x-auto scrollbar-hide">
                @if($kappers->isNotEmpty())
                <div class="flex gap-4 px-4 sm:px-8 pb-2 w-max mx-auto">
                @foreach($kappers as $kapper)
                <a wire:key="kapper-{{ $kapper->id }}" href="{{ route('kapper.profiel', $kapper->slug) }}"
                   class="group flex-shrink-0 w-[260px] sm:w-[280px] flex flex-col bg-gradient-to-b from-indigo-50 to-white dark:from-neutral-700 dark:to-neutral-800 border border-indigo-100 dark:border-neutral-700 rounded-xl overflow-hidden hover:shadow-md hover:border-blue-200 dark:hover:border-neutral-500 transition-all duration-150">

                    {{-- Logo / foto --}}
                    <div class="h-36 flex items-center justify-center overflow-hidden @if($kapper->foto) bg-transparent @endif">
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

                        <div class="flex items-center gap-1 mt-1.5">
                            <svg class="w-3 h-3 text-gray-400 dark:text-neutral-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-xs text-gray-500 dark:text-neutral-400 truncate">{{ $kapper->stad }}</p>
                        </div>

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
                </div>{{-- einde w-max --}}
                @else
                <div class="w-full py-20 text-center px-4">
                <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700 dark:text-neutral-300">Geen kappers gevonden</p>
                @if($zoekterm)
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1 mb-4">Geen resultaten voor "{{ $zoekterm }}"</p>
                @if($steden->count() > 0)
                <p class="text-xs text-gray-400 dark:text-neutral-500 mb-2">Probeer een van deze steden:</p>
                <div class="flex flex-wrap justify-center gap-2">
                    @foreach($steden as $stad)
                    <button wire:key="filter-{{ Str::slug($stad) }}" wire:click="$set('stadFilter', '{{ addslashes($stad) }}')"
                            class="px-3 py-1 rounded-full text-xs font-medium border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 bg-white dark:bg-neutral-800 hover:border-blue-300 hover:text-blue-600 dark:hover:border-blue-600 dark:hover:text-blue-400 transition-colors">
                        {{ $stad }}
                    </button>
                    @endforeach
                </div>
                @endif
                @else
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Er zijn nog geen kappers geregistreerd</p>
                @endif
            </div>
            @endif
            </div>{{-- einde scrollbare rij --}}
        </div>{{-- einde carousel wrapper --}}

        {{-- Bekijk alle kappers knop --}}
        @if($kappers->count() > 0)
        <div class="flex items-center justify-center mt-6">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-200 dark:border-neutral-700 text-sm font-medium text-gray-600 dark:text-neutral-400 bg-white dark:bg-neutral-800 hover:border-blue-300 hover:text-blue-600 dark:hover:border-blue-600 dark:hover:text-blue-400 transition-colors">
                Bekijk alle kappers ({{ $kappers->count() }})
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
        @endif
    </div>{{-- einde full-width carousel sectie --}}

    {{-- Populaire steden --}}
    @if(!$zoekterm && $steden->count() > 1)
    <div class="relative z-10 py-10 px-4 border-t border-gray-100 dark:border-neutral-800">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-sm font-semibold text-gray-500 dark:text-neutral-400 uppercase tracking-widest mb-4">Kappers per stad</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($steden as $stad)
                <a wire:key="stad-{{ Str::slug($stad) }}" href="{{ route('stad.kappers', Str::slug($stad)) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium border border-gray-200 dark:border-neutral-700 text-gray-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 hover:border-blue-300 hover:text-blue-600 dark:hover:border-blue-600 dark:hover:text-blue-400 transition-colors">
                    <svg class="w-3.5 h-3.5 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $stad }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Hoe werkt het (alleen zonder zoekterm) --}}
    @if(!$zoekterm)
    <div id="hoe-werkt-het" class="relative z-10 py-14 px-4 border-t border-gray-100 dark:border-neutral-800">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-xl font-bold text-gray-900 dark:text-neutral-100">Zo werkt het</h2>
                <p class="text-sm text-gray-400 dark:text-neutral-500 mt-1">In drie stappen een afspraak bij jouw kapper</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Stap 1 --}}
                <div class="text-center">
                    <div class="relative inline-flex mb-5">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-blue-900/40">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 0 1 15 0z"/>
                            </svg>
                        </div>
                        <span class="absolute -top-2 -right-2 w-6 h-6 bg-white dark:bg-neutral-700 border-2 border-blue-500 rounded-full text-xs font-bold text-blue-600 dark:text-blue-400 flex items-center justify-center leading-none">1</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-2">Zoek een kapper</h3>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">Zoek op stad of naam en bekijk alle beschikbare kappers bij jou in de buurt.</p>
                </div>
                {{-- Stap 2 --}}
                <div class="text-center">
                    <div class="relative inline-flex mb-5">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-200 dark:shadow-violet-900/40">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75h.008v.008H12v-.008zm0-3h.008v.008H12v-.008zm-3 3h.008v.008H9v-.008zm6 0h.008v.008H15v-.008z"/>
                            </svg>
                        </div>
                        <span class="absolute -top-2 -right-2 w-6 h-6 bg-white dark:bg-neutral-700 border-2 border-violet-500 rounded-full text-xs font-bold text-violet-600 dark:text-violet-400 flex items-center justify-center leading-none">2</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-2">Kies een dienst en datum</h3>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">Selecteer de gewenste dienst, kies een datum en klik op een vrij tijdstip. Je ziet direct de beschikbaarheid.</p>
                </div>
                {{-- Stap 3 --}}
                <div class="text-center">
                    <div class="relative inline-flex mb-5">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-200 dark:shadow-emerald-900/40">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12z"/>
                            </svg>
                        </div>
                        <span class="absolute -top-2 -right-2 w-6 h-6 bg-white dark:bg-neutral-700 border-2 border-emerald-500 rounded-full text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center justify-center leading-none">3</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-2">Bevestig je afspraak</h3>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">Vul je e-mailadres in en ontvang een eenmalige code. Geen account nodig — je afspraak is direct bevestigd.</p>
                </div>
            </div>

        </div>
    </div>
    @endif

    {{-- Kapper CTA --}}
    <div class="relative z-10 py-12 px-4 bg-gray-50 dark:bg-neutral-800/50 border-t border-gray-100 dark:border-neutral-800">
        <div class="max-w-xl mx-auto text-center">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-200 dark:shadow-blue-900/40">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-neutral-100 mb-2">Ben jij kapper?</h3>
            <p class="text-sm text-gray-500 dark:text-neutral-400 mb-6">Zet je salon online, ontvang boekingen en beheer je agenda op één plek. Vanaf €25/maand excl. BTW.</p>
            <a href="{{ route('kapper.registreer') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
                Salon aanmelden
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 dark:border-neutral-800 bg-white dark:bg-neutral-900 py-8 px-4">
        <div class="max-w-4xl mx-auto flex flex-col items-center gap-5">

            {{-- Logo --}}
            <img src="{{ asset('images/kaply-logo.png') }}" alt="Kaply" class="h-7 dark:hidden">
            <img src="{{ asset('images/dark modus kaply bg removed.PNG') }}" alt="Kaply" class="h-7 hidden dark:block" >

            {{-- Contact links --}}
            <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-gray-500 dark:text-neutral-400">
                <a href="mailto:info@kaply.nl"
                   class="flex items-center gap-1.5 hover:text-gray-800 dark:hover:text-neutral-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                    info@kaply.nl
                </a>
                <a href="https://www.instagram.com/kaply.nl" target="_blank" rel="noopener"
                   class="flex items-center gap-1.5 hover:text-gray-800 dark:hover:text-neutral-200 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                    @kaply.nl
                </a>
            </div>

            {{-- Legal links + KVK --}}
            <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-xs text-gray-400 dark:text-neutral-500">
                <a href="{{ route('privacy') }}" class="hover:text-gray-600 dark:hover:text-neutral-300 transition-colors">Privacybeleid</a>
                <span>·</span>
                <a href="{{ route('voorwaarden') }}" class="hover:text-gray-600 dark:hover:text-neutral-300 transition-colors">Algemene voorwaarden</a>
                <span>·</span>
                <span>KVK 42089812</span>
                <span>·</span>
                <span>BTW NL220924260B02</span>
            </div>

            <p class="text-xs text-gray-300 dark:text-neutral-600">© {{ date('Y') }} Kaply · Coffie Digital</p>
        </div>
    </footer>

    <script>
    (function(){
        var inp = document.getElementById('zoekterm-input');
        if(!inp) return;
        var block = false;
        document.addEventListener('mousedown', function(e){
            if(e.target !== inp){ block = true; setTimeout(function(){ block = false; }, 300); }
        }, true);
        document.addEventListener('keydown', function(e){
            if(e.key === 'Tab' || e.key === 'Escape'){ block = true; setTimeout(function(){ block = false; }, 300); }
        }, true);
        inp.addEventListener('blur', function(){
            if(!block) setTimeout(function(){ inp.focus(); }, 0);
        });
    })();
    </script>
</div>
