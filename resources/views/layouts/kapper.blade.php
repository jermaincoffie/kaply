<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — Kapper</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen flex">
    <aside class="w-56 bg-gray-900 text-gray-300 min-h-screen p-4 flex flex-col gap-1 shrink-0">
        <p class="text-white font-bold text-lg mb-6 truncate">{{ auth()->user()->kapper?->salon_naam ?? config('app.name') }}</p>
        <a href="{{ route('kapper.dashboard') }}" class="px-3 py-2 rounded hover:bg-gray-700 text-sm {{ request()->routeIs('kapper.dashboard') ? 'bg-gray-700 text-white' : '' }}">Agenda</a>
        <a href="{{ route('kapper.diensten') }}" class="px-3 py-2 rounded hover:bg-gray-700 text-sm {{ request()->routeIs('kapper.diensten') ? 'bg-gray-700 text-white' : '' }}">Diensten</a>
        <a href="{{ route('kapper.beschikbaarheid') }}" class="px-3 py-2 rounded hover:bg-gray-700 text-sm {{ request()->routeIs('kapper.beschikbaarheid') ? 'bg-gray-700 text-white' : '' }}">Beschikbaarheid</a>
        <a href="{{ route('kapper.profiel-beheer') }}" class="px-3 py-2 rounded hover:bg-gray-700 text-sm {{ request()->routeIs('kapper.profiel-beheer') ? 'bg-gray-700 text-white' : '' }}">Profiel</a>
        <div class="mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 hover:bg-gray-700 rounded text-sm">Uitloggen</button>
            </form>
        </div>
    </aside>
    <main class="flex-1 p-6 min-w-0">{{ $slot }}</main>
    @livewireScripts
</body>
</html>
