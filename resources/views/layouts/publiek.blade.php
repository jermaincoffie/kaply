<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
<body class="font-sans antialiased bg-gray-100 dark:bg-neutral-900 text-gray-800 dark:text-neutral-200 min-h-screen">

    {{-- Header --}}
    <header class="sticky top-0 z-30 bg-white dark:bg-neutral-800 border-b border-gray-200 dark:border-neutral-700 h-14 flex items-center px-4 sm:px-6 gap-4">
        <a href="{{ route('home') }}" class="hover:opacity-80 transition-opacity flex-shrink-0">
            <img src="{{ asset('images/Kaply logo light.png') }}" class="block dark:hidden h-16 w-auto" alt="Kaply">
            <img src="{{ asset('images/kaply dark logo.png') }}" class="hidden dark:block h-16 w-auto" alt="Kaply">
        </a>

        <div class="flex-1"></div>

        <nav class="flex items-center gap-2">
            <a href="{{ route('home') }}"
               class="px-3 py-2 rounded-lg text-sm {{ request()->routeIs('home') ? 'text-gray-900 dark:text-neutral-100 font-medium' : 'text-gray-500 hover:text-gray-900 dark:text-neutral-400 dark:hover:text-neutral-100' }} transition-colors">
                Kappers zoeken
            </a>

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
                {{-- Knipklok-stijl: outlined registreren knop + tekst login link --}}
                <a href="{{ route('kapper.registreer') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-300 hover:border-gray-400 dark:hover:border-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                    Kapperszaak registreren
                </a>
                <a href="{{ route('login') }}"
                   class="px-3 py-2 text-sm font-medium text-gray-600 dark:text-neutral-400 hover:text-gray-900 dark:hover:text-neutral-100 transition-colors inline-flex items-center gap-1">
                    Inloggen voor kappers
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            @endauth
        </nav>
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
    @livewireScripts
    @stack('scripts')

</body>
</html>

