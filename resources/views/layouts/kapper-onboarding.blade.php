<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Aan de slag</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-neutral-900 text-gray-800 dark:text-neutral-200 min-h-screen">

    <div class="min-h-screen flex flex-col">
        {{-- Header --}}
        <div class="flex items-center justify-center pt-8 pb-6">
            <img src="{{ asset('images/kaply-logo-light.png') }}" class="block dark:hidden h-16 w-auto" alt="Kaply">
            <img src="{{ asset('images/dark modus kaply bg removed.PNG') }}" class="hidden dark:block h-16 w-auto" alt="Kaply" >
        </div>

        {{-- Content --}}
        <div class="flex-1 flex items-start justify-center px-4 pb-16">
            <div class="w-full max-w-xl">
                {{ $slot }}
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>

