<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seoTitle ?? config('app.name') }}</title>
    <meta name="description" content="{{ $seoDescription ?? 'Kaply – online kapper afspraken boeken in jouw buurt. Kies een kapper, selecteer een dienst en boek direct.' }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Kaply">
    <meta property="og:title" content="{{ $seoTitle ?? config('app.name') }}">
    <meta property="og:description" content="{{ $seoDescription ?? 'Kaply – online kapper afspraken boeken in jouw buurt.' }}">
    @if(!empty($seoImage))
    <meta property="og:image" content="{{ $seoImage }}">
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ $seoCanonical ?? url()->current() }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e1e22">
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="apple-touch-icon" href="/images/PWA-icon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Kaply">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-white dark:bg-neutral-900 text-gray-800 dark:text-neutral-200 min-h-screen">

    {{-- Header --}}
    <header x-data="{ mobileOpen: false }" class="sticky top-0 z-30 bg-white/80 dark:bg-neutral-900/80 backdrop-blur-sm border-b border-gray-200 dark:border-neutral-800">
        <div class="h-14 flex items-center px-4 sm:px-6 gap-4">
            <a href="{{ route('home') }}" class="hover:opacity-80 transition-opacity flex-shrink-0">
                <img src="{{ asset('images/kaply-logo-light.png') }}" class="block dark:hidden h-11 w-auto" alt="Kaply">
                <img src="{{ asset('images/dark modus kaply bg removed.PNG') }}" class="hidden dark:block h-11 w-auto" alt="Kaply">
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-1 ml-2">
                <a href="{{ route('home') }}#hoe-werkt-het"
                   class="px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-gray-900 dark:text-neutral-400 dark:hover:text-neutral-100 transition-colors">
                    Hoe werkt het
                </a>
                <a href="{{ route('voor-kappers') }}"
                   class="px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-gray-900 dark:text-neutral-400 dark:hover:text-neutral-100 transition-colors {{ request()->routeIs('voor-kappers') ? 'text-gray-900 dark:text-neutral-100 font-medium' : '' }}">
                    Voor kappers
                </a>
            </nav>

            <div class="flex-1"></div>

            {{-- Desktop auth --}}
            <nav class="hidden md:flex items-center gap-1 sm:gap-2">
                @auth
                    @if(auth()->user()->isKapper())
                        <a href="{{ route('kapper.dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-gray-900 dark:text-neutral-400 dark:hover:text-neutral-100 transition-colors">
                            Mijn salon
                        </a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-gray-900 dark:text-neutral-400 dark:hover:text-neutral-100 transition-colors">
                            Admin
                        </a>
                    @else
                        <a href="{{ route('klant.afspraken') }}"
                           class="px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-gray-900 dark:text-neutral-400 dark:hover:text-neutral-100 transition-colors">
                            Mijn afspraken
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-gray-900 dark:text-neutral-400 dark:hover:text-neutral-100 transition-colors">
                            Uitloggen
                        </button>
                    </form>
                @else
                    <a href="{{ route('kapper.registreer') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-300 hover:border-gray-400 dark:hover:border-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        Kapperszaak registreren
                    </a>
                    <a href="{{ route('klant.inloggen') }}"
                       class="px-3 py-2 text-sm font-medium text-gray-600 dark:text-neutral-400 hover:text-gray-900 dark:hover:text-neutral-100 transition-colors inline-flex items-center gap-1">
                        Inloggen
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endauth
            </nav>

            {{-- Mobiel: hamburger --}}
            <button @click="mobileOpen = !mobileOpen"
                    class="md:hidden flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 dark:text-neutral-400 hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobiel menu --}}
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden border-t border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-4 py-3 space-y-1"
             style="display: none;">
            <a href="{{ route('home') }}#hoe-werkt-het" @click="mobileOpen = false"
               class="block px-3 py-2.5 rounded-lg text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                Hoe werkt het
            </a>
            <a href="{{ route('voor-kappers') }}" @click="mobileOpen = false"
               class="block px-3 py-2.5 rounded-lg text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                Voor kappers
            </a>
            <div class="border-t border-gray-100 dark:border-neutral-700 pt-3 mt-2 space-y-1">
                @auth
                    @if(auth()->user()->isKapper())
                        <a href="{{ route('kapper.dashboard') }}" @click="mobileOpen = false"
                           class="block px-3 py-2.5 rounded-lg text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                            Mijn salon
                        </a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" @click="mobileOpen = false"
                           class="block px-3 py-2.5 rounded-lg text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                            Admin
                        </a>
                    @else
                        <a href="{{ route('klant.afspraken') }}" @click="mobileOpen = false"
                           class="block px-3 py-2.5 rounded-lg text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                            Mijn afspraken
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-3 py-2.5 rounded-lg text-sm text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                            Uitloggen
                        </button>
                    </form>
                @else
                    <a href="{{ route('kapper.registreer') }}" @click="mobileOpen = false"
                       class="block px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        Kapperszaak registreren
                    </a>
                    <a href="{{ route('klant.inloggen') }}" @click="mobileOpen = false"
                       class="block px-3 py-2.5 rounded-lg text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                        Inloggen
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Main content --}}
    <main>
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot }}
        @endif
    </main>

    <x-confirm-modal />
    <x-cookie-banner />
    <x-pwa-install-banner />
    @livewireScripts
    @stack('scripts')

</body>
</html>



