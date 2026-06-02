<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-sm border-b px-4 py-3 flex justify-between items-center">
        <a href="{{ route('home') }}" class="font-bold text-indigo-600 text-lg">{{ config('app.name') }}</a>
        <div class="flex gap-4 text-sm">
            @auth
                <a href="{{ auth()->user()->isKapper() ? route('kapper.dashboard') : route('klant.afspraken') }}" class="text-gray-600 hover:text-indigo-600">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="text-gray-600 hover:text-indigo-600">Uitloggen</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Inloggen</a>
                <a href="{{ route('kapper.registreer') }}" class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">Kapper worden</a>
            @endauth
        </div>
    </nav>
    <main>
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot }}
        @endif
    </main>
    @livewireScripts
</body>
</html>
