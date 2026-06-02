<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} – {{ $title ?? 'Dashboard' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
    <script>
        // Zet dark mode vóór render om flicker te voorkomen
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-neutral-900 text-gray-800 dark:text-neutral-200">

@php
    $linkClass = fn($route) => request()->routeIs($route)
        ? 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium bg-blue-50 text-blue-900 dark:bg-neutral-700 dark:text-neutral-200'
        : 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors';
@endphp

{{-- ===== SIDEBAR ===== --}}
<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-neutral-800 border-r border-gray-200 dark:border-neutral-700 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300">

    {{-- Logo --}}
    <a href="{{ route('kapper.dashboard') }}"
       class="flex items-center gap-2 px-4 h-14 border-b border-gray-100 dark:border-neutral-700 flex-shrink-0 hover:opacity-80 transition-opacity">
        <div class="min-w-0">
            <div class="font-bold text-base tracking-tight whitespace-nowrap">
                {{ config('app.name') }}
            </div>
            @if(auth()->user()->kapper?->salon_naam)
                <div class="text-xs text-gray-500 dark:text-neutral-400 truncate leading-tight">
                    {{ auth()->user()->kapper->salon_naam }}
                </div>
            @endif
        </div>
    </a>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

        {{-- Agenda --}}
        <a href="{{ route('kapper.dashboard') }}" class="{{ $linkClass('kapper.dashboard') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Agenda
        </a>

        {{-- Afspraken --}}
        <a href="{{ route('kapper.afspraken') }}" class="{{ $linkClass('kapper.afspraken') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Afspraken
        </a>

        {{-- Klanten --}}
        <a href="{{ route('kapper.klanten') }}" class="{{ $linkClass('kapper.klanten') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Klanten
        </a>

        {{-- Diensten --}}
        <a href="{{ route('kapper.diensten') }}" class="{{ $linkClass('kapper.diensten') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Diensten
        </a>

        {{-- Beschikbaarheid --}}
        <a href="{{ route('kapper.beschikbaarheid') }}" class="{{ $linkClass('kapper.beschikbaarheid') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
            </svg>
            Beschikbaarheid
        </a>

        {{-- Profiel --}}
        <a href="{{ route('kapper.profiel-beheer') }}" class="{{ $linkClass('kapper.profiel-beheer') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profiel
        </a>

        {{-- Mijn pagina --}}
        @if(auth()->user()->kapper?->slug)
        <a href="{{ route('kapper.profiel', auth()->user()->kapper->slug) }}" target="_blank"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Mijn pagina
        </a>
        @endif

    </nav>

    {{-- Onderin: Uitloggen --}}
    <div class="px-3 pb-4 pt-2 border-t border-gray-100 dark:border-neutral-700 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                </svg>
                Uitloggen
            </button>
        </form>
    </div>
</aside>

{{-- Overlay (mobile) --}}
<div id="sidebar-overlay" onclick="closeSidebar()"
     class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

{{-- ===== HEADER ===== --}}
<header class="sticky top-0 z-30 lg:ml-64 bg-white dark:bg-neutral-800 border-b border-gray-200 dark:border-neutral-700 h-14 flex items-center px-4 sm:px-6 gap-4">

    <button onclick="openSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <h1 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">{{ $title ?? 'Dashboard' }}</h1>

    <div class="flex-1"></div>

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

        {{-- Dropdown menu --}}
        <div id="account-dropdown-menu"
             class="hidden absolute right-0 mt-2 w-60 bg-white dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 rounded-xl shadow-xl z-50">

            {{-- Naam + email --}}
            <div class="py-2 px-3.5">
                <span class="font-medium text-sm text-gray-800 dark:text-neutral-200">
                    {{ $authUser->name }}
                </span>
                <p class="text-xs text-gray-500 dark:text-neutral-400 mt-0.5">{{ $authUser->email }}</p>
            </div>

            {{-- Dark mode toggle --}}
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

            {{-- Links --}}
            <div class="p-1 border-t border-gray-100 dark:border-neutral-800">
                <a href="{{ route('kapper.profiel-beheer') }}"
                   class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 dark:text-neutral-200 hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors">
                    <svg class="shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Mijn profiel
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

{{-- ===== MAIN CONTENT ===== --}}
<main class="lg:ml-64 min-h-screen">
    <div class="p-4 sm:p-6">
        {{ $slot }}
    </div>
</main>

<x-confirm-modal />
@livewireScripts
@stack('scripts')

<script>
    // ===== DARK MODE =====
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

    // ===== ACCOUNT DROPDOWN =====
    function toggleAccountDropdown() {
        document.getElementById('account-dropdown-menu').classList.toggle('hidden');
    }

    document.addEventListener('click', function (e) {
        const accountWrapper = document.getElementById('account-dropdown-wrapper');
        if (accountWrapper && !accountWrapper.contains(e.target)) {
            document.getElementById('account-dropdown-menu').classList.add('hidden');
        }
    });

    // ===== SIDEBAR =====
    function openSidebar() {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.remove('hidden');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.add('hidden');
    }
</script>

</body>
</html>
