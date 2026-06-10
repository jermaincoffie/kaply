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
            <img src="{{ asset('images/kaply-logo-light.png') }}" class="block dark:hidden h-16 w-auto" alt="Kaply">
            <img src="{{ asset('images/kaply-logo-dark.png') }}" class="hidden dark:block h-16 w-auto" alt="Kaply">
        </a>

        <div class="flex-1"></div>

        <nav class="hidden sm:flex items-center gap-1">
            <a href="{{ route('home') }}"
               class="px-3 py-2 rounded-lg text-sm {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-900 dark:bg-neutral-700 dark:text-neutral-200 font-medium' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200' }} transition-colors">
                Kappers zoeken
            </a>
            <a href="{{ route('klant.afspraken') }}"
               class="px-3 py-2 rounded-lg text-sm {{ request()->routeIs('klant.afspraken') ? 'bg-blue-50 text-blue-900 dark:bg-neutral-700 dark:text-neutral-200 font-medium' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200' }} transition-colors">
                Mijn afspraken
            </a>
        </nav>

        {{-- Account dropdown --}}
        @php $authUser = auth()->user(); @endphp
        <div class="relative" id="account-dropdown-wrapper">
            <button onclick="toggleAccountDropdown()"
                    class="p-0.5 inline-flex shrink-0 items-center gap-x-2 text-gray-800 dark:text-neutral-200 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors focus:outline-none">
                <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-100 border border-gray-200 dark:border-neutral-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-400 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
            </button>

            <div id="account-dropdown-menu"
                 class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 rounded-xl shadow-xl z-50">

                <div class="py-2 px-3.5">
                    <span class="font-medium text-sm text-gray-800 dark:text-neutral-200">{{ $authUser->name }}</span>
                    <p class="text-xs text-gray-500 dark:text-neutral-400 mt-0.5">{{ $authUser->email }}</p>
                </div>

                <div class="px-3.5 py-2 border-t border-gray-100 dark:border-neutral-800">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-sm text-gray-800 dark:text-neutral-200">Thema</span>
                        <div class="p-0.5 inline-flex bg-gray-100 dark:bg-neutral-700 rounded-full gap-0.5">
                            <button onclick="setTheme('light')" id="theme-light"
                                    class="size-7 flex justify-center items-center rounded-full text-gray-600 dark:text-neutral-300 transition-colors"
                                    title="Licht">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 3v1M12 20v1M3 12h1M20 12h1m-2.636-6.364-.707.707M6.343 17.657l-.707.707m0-11.314.707.707M17.657 17.657l.707.707"/></svg>
                            </button>
                            <button onclick="setTheme('dark')" id="theme-dark"
                                    class="size-7 flex justify-center items-center rounded-full text-gray-600 dark:text-neutral-300 transition-colors"
                                    title="Donker">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-1 border-t border-gray-100 dark:border-neutral-800">
                    {{-- Mobile only --}}
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 dark:text-neutral-200 hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors sm:hidden">
                        <svg class="shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
                        Kappers zoeken
                    </a>
                    <a href="{{ route('klant.afspraken') }}"
                       class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 dark:text-neutral-200 hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors sm:hidden">
                        <svg class="shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Mijn afspraken
                    </a>
                    {{-- Altijd zichtbaar --}}
                    <a href="{{ route('klant.account') }}"
                       class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 dark:text-neutral-200 hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors">
                        <svg class="shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Mijn account
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 dark:text-neutral-200 hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors">
                            <svg class="shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                            Uitloggen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Main content --}}
    <main class="max-w-5xl mx-auto px-4 py-8 pb-24 sm:pb-8">
        {{ $slot }}
    </main>

    {{-- ===== BOTTOM NAV (mobiel only) ===== --}}
    @php
        $klantTab = fn($route) => request()->routeIs($route)
            ? 'text-blue-400'
            : 'text-neutral-500';
    @endphp
    <nav class="sm:hidden fixed bottom-0 inset-x-0 z-40 bg-neutral-900 border-t border-neutral-700"
         style="padding-bottom: env(safe-area-inset-bottom, 0px)">
        <div class="flex h-16">
            <a href="{{ route('home') }}"
               class="flex-1 flex flex-col items-center justify-center gap-0.5 transition-colors {{ $klantTab('home') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <span class="text-[10px] font-medium">Zoeken</span>
            </a>
            <a href="{{ route('klant.afspraken') }}"
               class="flex-1 flex flex-col items-center justify-center gap-0.5 transition-colors {{ $klantTab('klant.afspraken') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-[10px] font-medium">Afspraken</span>
            </a>
            <a href="{{ route('klant.account') }}"
               class="flex-1 flex flex-col items-center justify-center gap-0.5 transition-colors {{ $klantTab('klant.account') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-[10px] font-medium">Account</span>
            </a>
        </div>
    </nav>

    <x-confirm-modal />
    @livewireScripts
    @stack('scripts')

    <script>
        function setTheme(theme) {
            const isDark = theme === 'dark';
            document.documentElement.classList.toggle('dark', isDark);
            localStorage.setItem('darkMode', isDark);
            updateThemeButtons(isDark);
        }

        function updateThemeButtons(isDark) {
            const light = document.getElementById('theme-light');
            const dark  = document.getElementById('theme-dark');
            if (!light || !dark) return;
            const activeClass = 'bg-white dark:bg-neutral-800 shadow-sm';
            light.className = light.className.replace(activeClass, '').trim();
            dark.className  = dark.className.replace(activeClass, '').trim();
            if (isDark) {
                dark.className += ' ' + activeClass;
            } else {
                light.className += ' ' + activeClass;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateThemeButtons(document.documentElement.classList.contains('dark'));
        });

        function toggleAccountDropdown() {
            document.getElementById('account-dropdown-menu').classList.toggle('hidden');
        }

        document.addEventListener('click', function (e) {
            const accountWrapper = document.getElementById('account-dropdown-wrapper');
            if (accountWrapper && !accountWrapper.contains(e.target)) {
                document.getElementById('account-dropdown-menu').classList.add('hidden');
            }
        });
    </script>
</body>
</html>


