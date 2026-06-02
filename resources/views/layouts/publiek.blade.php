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
        <a href="{{ route('home') }}" class="font-bold text-base tracking-tight whitespace-nowrap hover:opacity-80 transition-opacity">
            {{ config('app.name') }}
        </a>

        <div class="flex-1"></div>

        <nav class="flex items-center gap-1">
            <a href="{{ route('home') }}"
               class="px-3 py-2 rounded-lg text-sm {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-900 dark:bg-neutral-700 dark:text-neutral-200 font-medium' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200' }} transition-colors">
                Kappers zoeken
            </a>

            @auth
                @if(auth()->user()->isKapper())
                <a href="{{ route('kapper.dashboard') }}"
                   class="px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors">
                    Mijn salon
                </a>
                @elseif(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                   class="px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors">
                    Admin
                </a>
                @else
                <a href="{{ route('klant.afspraken') }}"
                   class="px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors">
                    Mijn afspraken
                </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors">
                        Uitloggen
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors">
                    Inloggen
                </a>
                <a href="{{ route('kapper.registreer') }}"
                   class="px-3 py-2 rounded-lg text-sm bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                    Kapper worden
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

    @livewireScripts
    @stack('scripts')

</body>
</html>
