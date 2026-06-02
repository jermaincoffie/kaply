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
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600">Kappers zoeken</a>
            <a href="{{ route('klant.afspraken') }}" class="text-gray-600 hover:text-indigo-600">Mijn afspraken</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-gray-600 hover:text-indigo-600">Uitloggen</button>
            </form>
        </div>
    </nav>
    <main class="max-w-4xl mx-auto px-4 py-8">{{ $slot }}</main>
    @livewireScripts
</body>
</html>
