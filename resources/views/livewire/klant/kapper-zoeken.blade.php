<div>
    {{-- Hero --}}
    <div class="relative overflow-hidden bg-white dark:bg-neutral-900 border-b border-gray-200 dark:border-neutral-700 py-20 px-4">
        {{-- Aurora achtergrond --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="[--white-gradient:repeating-linear-gradient(100deg,white_0%,white_7%,transparent_10%,transparent_12%,white_16%)] [--aurora:repeating-linear-gradient(100deg,var(--blue-500)_10%,var(--indigo-300)_15%,var(--blue-300)_20%,var(--violet-200)_25%,var(--blue-400)_30%)] [background-image:var(--white-gradient),var(--aurora)] [background-size:300%,_200%] [background-position:50%_50%,50%_50%] blur-[80px] absolute -inset-[10px] opacity-50 will-change-transform animate-aurora"></div>
        </div>
        <div class="relative z-10 text-center mb-10">
            <p class="hero-anim hero-anim-1 text-sm font-medium text-gray-400 dark:text-neutral-500 mb-2 tracking-widest uppercase">Welkom bij</p>
            <h1 class="hero-anim hero-anim-2 text-6xl font-extrabold tracking-tight text-gray-900 dark:text-neutral-100 mb-6">
                {{ config('app.name') }}
            </h1>
            <p class="hero-anim hero-anim-3 text-gray-400 dark:text-neutral-500 text-sm">Bekijk beschikbare tijden en boek direct online</p>
        </div>

        {{-- Pill zoekbalk --}}
        <div class="relative z-10 hero-anim hero-anim-4 max-w-2xl mx-auto">
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

    {{-- Hoe werkt het --}}
    <div class="relative overflow-hidden bg-gray-50 dark:bg-neutral-800/50 border-b border-gray-200 dark:border-neutral-700 py-14 px-4">
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
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
                    Gratis account aanmaken
                </a>
                <a href="{{ route('kapper.registreer') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 text-sm font-medium hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                    Kapper? Meld je aan
                </a>
            </div>
            @endguest
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
