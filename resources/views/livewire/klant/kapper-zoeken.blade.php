<div x-data="{ sticky: false }" @scroll.window="sticky = window.scrollY > 220" class="relative bg-white dark:bg-neutral-900">

    {{-- Sticky zoekbalk --}}
    <div x-cloak x-show="sticky"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="-translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="-translate-y-full opacity-0"
         class="fixed top-14 left-0 right-0 z-20 bg-white/95 dark:bg-neutral-900/95 backdrop-blur-sm border-b border-gray-200 dark:border-neutral-700 shadow-sm px-4 py-2.5">
        <div class="max-w-2xl mx-auto flex items-center bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-full px-4 py-2 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all">
            <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500 flex-shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <input wire:model.live="zoekterm" type="text"
                placeholder="Zoek op stad of naam..."
                class="flex-1 bg-transparent border-none outline-none text-gray-900 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 text-sm focus:ring-0">
            @if($zoekterm)
            <button wire:click="$set('zoekterm', '')" class="ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            @endif
        </div>
    </div>

    {{-- Aurora: spans volledige hoogte --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="[--white-gradient:repeating-linear-gradient(100deg,white_0%,white_7%,transparent_10%,transparent_12%,white_16%)] [--aurora:repeating-linear-gradient(100deg,#bfdbfe_10%,#c7d2fe_15%,#dbeafe_20%,#e0e7ff_25%,#93c5fd_30%)] [background-image:var(--white-gradient),var(--aurora)] [background-size:300%,_200%] [background-position:50%_50%,50%_50%] blur-[80px] absolute -inset-[10px] opacity-[0.22] will-change-transform animate-aurora motion-reduce:animate-none"></div>
    </div>
    {{-- Fade-to-white overlay: transparant boven, wit onderaan --}}
    <div class="absolute inset-0 pointer-events-none bg-gradient-to-b from-transparent from-[0%] to-white dark:to-neutral-900"></div>

    {{-- Hero --}}
    <div class="relative z-10 py-6 sm:py-20 px-4">
        <div class="text-center mb-6 sm:mb-10">
            <p class="hero-anim hero-anim-1 text-sm font-medium text-gray-400 dark:text-neutral-500 mb-2 tracking-widest uppercase">Welkom bij</p>
            <h1 class="hero-anim hero-anim-2 text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 dark:text-neutral-100 mb-6">
                {{ config('app.name') }}
            </h1>
            <p class="hero-anim hero-anim-3 text-gray-400 dark:text-neutral-500 text-sm">Bekijk beschikbare tijden en boek direct online</p>
        </div>

        {{-- Pill zoekbalk --}}
        <div class="hero-anim hero-anim-4 max-w-2xl mx-auto">
            <div class="flex items-center bg-white/70 dark:bg-neutral-800 backdrop-blur-sm border border-gray-200 dark:border-neutral-700 rounded-full px-6 py-4 shadow-sm focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all">
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
    <div class="relative z-10 max-w-5xl mx-auto px-4 pb-12">

        {{-- Stats + stad chips (alleen zonder zoekterm) --}}
        @if(!$zoekterm)
            @if($kappers_totaal > 0)
            <div class="flex items-center justify-center gap-2 mb-5 text-xs text-gray-400 dark:text-neutral-500">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>{{ $kappers_totaal }} {{ $kappers_totaal === 1 ? 'kapper' : 'kappers' }} aangesloten</span>
                @if($steden->count() > 0)
                <span>·</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>{{ $steden->count() }} steden</span>
                @endif
            </div>
            @endif

            @if($steden->count() > 0)
            <div class="relative mb-7 -mx-4 sm:mx-0">
                {{-- gradient fade rechts als scroll-hint --}}
                <div class="pointer-events-none absolute right-0 top-0 h-full w-10 bg-gradient-to-l from-white dark:from-neutral-900 to-transparent z-10 sm:hidden"></div>
                <div class="flex gap-2 overflow-x-auto scrollbar-hide px-4 sm:px-0 sm:flex-wrap sm:justify-center">
                    @foreach($steden as $stad)
                    <button wire:click="filterStad('{{ addslashes($stad) }}')"
                            class="flex-shrink-0 px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 bg-white dark:bg-neutral-800 hover:border-blue-300 hover:text-blue-600 dark:hover:border-blue-600 dark:hover:text-blue-400 transition-colors">
                        {{ $stad }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
        @else
        <p class="text-sm text-gray-500 dark:text-neutral-400 mb-5">
            Resultaten voor <span class="font-semibold text-gray-700 dark:text-neutral-300">"{{ $zoekterm }}"</span>
        </p>
        @endif

        {{-- Kappers grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($kappers as $kapper)
            <a href="{{ route('kapper.profiel', $kapper->slug) }}"
               class="group flex flex-col bg-gradient-to-b from-blue-50 to-white dark:from-neutral-700 dark:to-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden hover:shadow-md hover:border-blue-200 dark:hover:border-neutral-500 transition-all duration-150">

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
                        <span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 dark:bg-neutral-700 text-gray-600 dark:text-neutral-300">{{ $dienst->naam }}</span>
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
            @empty
            <div class="col-span-full py-20 text-center">
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
                    <button wire:click="filterStad('{{ addslashes($stad) }}')"
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
            @endforelse
        </div>
    </div>

    {{-- Hoe werkt het (alleen zonder zoekterm) --}}
    @if(!$zoekterm)
    <div class="relative z-10 py-14 px-4 border-t border-gray-100 dark:border-neutral-800">
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
                    <p class="text-sm text-gray-500 dark:text-neutral-400">Log in of maak een gratis account aan en bevestig je boeking. Je ontvangt een bevestiging.</p>
                </div>
            </div>

            @guest
            <div class="text-center mt-10 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('klant.inloggen') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
                    Inloggen of account aanmaken
                </a>
            </div>
            @endguest
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
            <p class="text-sm text-gray-500 dark:text-neutral-400 mb-6">Zet je salon online, ontvang boekingen en beheer je agenda op één plek. Vanaf €20 per maand.</p>
            <a href="{{ route('kapper.registreer') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
                Salon aanmelden
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </div>
</div>
