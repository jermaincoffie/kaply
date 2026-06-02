<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen flex">
    <aside class="w-56 bg-gray-900 text-gray-300 min-h-screen p-4 shrink-0">
        <p class="text-white font-bold text-lg mb-6">Admin</p>
        <a href="{{ route('admin.kappers') }}" class="block px-3 py-2 rounded text-sm hover:bg-gray-700 {{ request()->routeIs('admin.kappers') ? 'bg-gray-700 text-white' : '' }}">Kappers</a>
    </aside>
    <main class="flex-1 p-6">{{ $slot }}</main>
    @livewireScripts
</body>
</html>
