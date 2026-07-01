<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - {{ $title ?? 'Dashboard' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Zet dark mode vÃ³Ã³r render om flicker te voorkomen
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
       class="flex flex-row items-center gap-2 px-4 h-14 border-b border-gray-100 dark:border-neutral-700 flex-shrink-0 hover:opacity-80 transition-opacity overflow-hidden">
        <img src="{{ asset('images/kaply-logo-light.png') }}" class="block dark:hidden h-16 w-auto" alt="Kaply">
        <img src="{{ asset('images/dark modus kaply bg removed.PNG') }}" class="hidden dark:block h-16 w-auto" alt="Kaply">
        @if(auth()->user()->kapper?->salon_naam)
            <span class="text-xs text-gray-500 dark:text-neutral-400 truncate leading-tight">
                {{ auth()->user()->kapper->salon_naam }}
            </span>
        @endif
    </a>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

        {{-- Dashboard --}}
        <a href="{{ route('kapper.dashboard') }}" class="{{ $linkClass('kapper.dashboard') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Agenda --}}
        <a href="{{ route('kapper.agenda') }}" class="{{ $linkClass('kapper.agenda') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Agenda
        </a>

        {{-- Afspraken --}}
        <a href="{{ route('kapper.afspraken') }}" class="{{ $linkClass('kapper.afspraken') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Afsprakenlijst
        </a>

        {{-- Klanten --}}
        <a href="{{ route('kapper.klanten') }}" class="{{ $linkClass('kapper.klanten') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Klanten
        </a>

        {{-- Statistieken --}}
        <a href="{{ route('kapper.statistieken') }}" class="{{ $linkClass('kapper.statistieken') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Statistieken
        </a>

        {{-- Marketing dropdown --}}
        @php
            $marketingActief = request()->routeIs('kapper.reviews') || request()->routeIs('kapper.kortingscodes');
        @endphp
        <div x-data="{ open: {{ $marketingActief ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                        {{ $marketingActief ? 'bg-gray-100 dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 font-medium' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <span class="flex-1 text-left">Marketing</span>
                <svg class="w-3.5 h-3.5 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="mt-0.5 ml-4 pl-3 border-l-2 border-gray-100 dark:border-neutral-700 space-y-0.5">
                <a href="{{ route('kapper.reviews') }}" class="{{ $linkClass('kapper.reviews') }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    Reviews
                </a>
                <a href="{{ route('kapper.kortingscodes') }}" class="{{ $linkClass('kapper.kortingscodes') }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Kortingscodes
                </a>
            </div>
        </div>

        {{-- Instellingen dropdown --}}
        @php
            $instellingenActief = request()->routeIs('kapper.diensten') || request()->routeIs('kapper.beschikbaarheid') || request()->routeIs('kapper.medewerkers') || request()->routeIs('kapper.profiel-beheer') || request()->routeIs('kapper.galerij') || request()->routeIs('kapper.abonnement') || request()->routeIs('kapper.facturatie');
        @endphp
        <div x-data="{ open: {{ $instellingenActief ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                        {{ $instellingenActief ? 'bg-gray-100 dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 font-medium' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-200' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="flex-1 text-left">Instellingen</span>
                <svg class="w-3.5 h-3.5 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="mt-0.5 ml-4 pl-3 border-l-2 border-gray-100 dark:border-neutral-700 space-y-0.5">
                <a href="{{ route('kapper.diensten') }}" class="{{ $linkClass('kapper.diensten') }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Diensten
                </a>
                <a href="{{ route('kapper.beschikbaarheid') }}" class="{{ $linkClass('kapper.beschikbaarheid') }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                    </svg>
                    Beschikbaarheid
                </a>
                <a href="{{ route('kapper.medewerkers') }}" class="{{ $linkClass('kapper.medewerkers') }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Medewerkers
                </a>
                <a href="{{ route('kapper.profiel-beheer') }}" class="{{ $linkClass('kapper.profiel-beheer') }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profiel
                </a>
                <a href="{{ route('kapper.galerij') }}" class="{{ $linkClass('kapper.galerij') }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 9.75A6.75 6.75 0 019.75 3h4.5A6.75 6.75 0 0121 9.75v4.5A6.75 6.75 0 0114.25 21H9.75A6.75 6.75 0 013 14.25V9.75z"/>
                    </svg>
                    Galerij
                </a>
                <a href="{{ route('kapper.abonnement') }}" class="{{ $linkClass('kapper.abonnement') }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                    Abonnement
                </a>
                <a href="{{ route('kapper.facturatie') }}" class="{{ $linkClass('kapper.facturatie') }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Facturatie
                </a>
            </div>
        </div>

    </nav>

    {{-- Onderin: Setup wizard + Uitloggen --}}
    <div class="px-3 pb-4 pt-2 border-t border-gray-100 dark:border-neutral-700 flex-shrink-0 space-y-0.5">
        @php $onboardingKlaar = auth()->user()->kapper?->onboarding_voltooid; @endphp
        <a href="{{ route('kapper.onboarding') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $onboardingKlaar ? 'text-gray-500 dark:text-neutral-500 hover:bg-gray-100 dark:hover:bg-neutral-700 hover:text-gray-700 dark:hover:text-neutral-300' : 'text-orange-600 hover:bg-orange-50 dark:text-orange-400 dark:hover:bg-orange-950/30' }}">
            @if($onboardingKlaar)
                <svg class="w-4 h-4 flex-shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @else
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            @endif
            Setup wizard
            @if(!$onboardingKlaar)
            <span class="ml-auto w-2 h-2 rounded-full bg-orange-500 flex-shrink-0"></span>
            @endif
        </a>
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

    {{-- Notificatie bel --}}
    @livewire('kapper.notificatie-bel')

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
                @if(auth()->user()->kapper?->slug)
                <a href="{{ route('kapper.profiel', auth()->user()->kapper->slug) }}" target="_blank"
                   class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 dark:text-neutral-200 hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors">
                    <svg class="shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Bekijk mijn pagina
                </a>
                @endif
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
        @php
            $trialSub   = auth()->user()->subscription('default');
            $inTrial    = $trialSub?->onTrial() ?? false;
            $trialDagen = $inTrial ? max(0, (int) now()->diffInDays($trialSub->trial_ends_at)) : null;
            $bannerKleur = match(true) {
                $trialDagen !== null && $trialDagen <= 3 => 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950/30 dark:border-red-800 dark:text-red-300',
                $trialDagen !== null && $trialDagen <= 7 => 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-950/30 dark:border-amber-800 dark:text-amber-300',
                default                                  => 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-950/30 dark:border-blue-800 dark:text-blue-300',
            };
        @endphp
        @if($inTrial && $trialDagen !== null)
        <div class="mb-4 flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl border text-xs sm:text-sm {{ $bannerKleur }}">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                @if($trialDagen === 0)
                    <strong>Proefperiode verloopt vandaag</strong> — activeer nu.
                @elseif($trialDagen === 1)
                    Proefperiode verloopt <strong>morgen</strong>.
                @else
                    Proefperiode: nog <strong>{{ $trialDagen }} dagen</strong> gratis.
                @endif
            </div>
            <a href="{{ route('kapper.abonnement') }}" class="flex-shrink-0 font-semibold underline hover:no-underline whitespace-nowrap">
                Abonneer nu
            </a>
        </div>
        @endif

        @if(!auth()->user()->kapper?->actief)
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
            <div class="w-16 h-16 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mb-5">
                <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-100 mb-2">Account wacht op goedkeuring</h2>
            <p class="text-sm text-gray-500 dark:text-neutral-400 max-w-sm">Je registratie is ontvangen. Een beheerder zal je account binnenkort goedkeuren. Je krijgt dan toegang tot je dashboard.</p>
        </div>
        @else
        @if(session('abonnement_past_due'))
        <div class="mb-4 flex items-start gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl px-4 py-3">
            <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-red-700 dark:text-red-400">Betaling mislukt — je abonnement verloopt over {{ session('abonnement_past_due') }} {{ session('abonnement_past_due') === 1 ? 'dag' : 'dagen' }}</p>
                <p class="text-xs text-red-600 dark:text-red-500 mt-0.5">Update je betaalmethode om je account actief te houden.</p>
            </div>
            <a href="{{ route('kapper.subscription.portal') }}"
               class="flex-shrink-0 text-xs font-semibold text-red-700 dark:text-red-400 underline hover:no-underline">
                Betaling bijwerken
            </a>
        </div>
        @endif
        {{ $slot }}
        @endif
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

