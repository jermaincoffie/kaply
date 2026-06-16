<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina niet gevonden – Kaply</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 dark:bg-neutral-900 min-h-screen flex items-center justify-center px-4">

<div class="text-center max-w-md">
    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-blue-50 dark:bg-blue-900/20 mb-6">
        <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <h1 class="text-4xl font-bold text-gray-900 dark:text-neutral-100 mb-2">404</h1>
    <p class="text-lg font-medium text-gray-700 dark:text-neutral-300 mb-2">Pagina niet gevonden</p>
    <p class="text-sm text-gray-400 dark:text-neutral-500 mb-8">Deze pagina bestaat niet of is verplaatst.</p>
    <div class="flex items-center justify-center gap-3">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Naar home
        </a>
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-300 text-sm font-medium hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Terug
        </a>
    </div>
    <p class="text-xs text-gray-300 dark:text-neutral-600 mt-10">© {{ date('Y') }} Kaply</p>
</div>

</body>
</html>
