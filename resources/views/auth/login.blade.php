<!DOCTYPE html>
<html lang="nl" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — Inloggen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-neutral-900 min-h-screen flex flex-col items-center justify-center px-4 py-12">

    <a href="{{ route('home') }}" class="font-bold text-xl tracking-tight text-gray-900 dark:text-neutral-100 mb-8 hover:opacity-80 transition-opacity">
        {{ config('app.name') }}
    </a>

    <div class="w-full max-w-md bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-6">
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100 mb-1">Inloggen</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mb-6">Welkom terug</p>

            @if(session('status'))
            <div class="flex items-center gap-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl text-sm mb-4">
                {{ session('status') }}
            </div>
            @endif

            @if($errors->any())
            <div class="flex items-center gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm mb-4">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">E-mailadres</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Wachtwoord</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500 dark:bg-neutral-700">
                        <span class="text-sm text-gray-600 dark:text-neutral-400">Onthoud mij</span>
                    </label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Wachtwoord vergeten?</a>
                    @endif
                </div>
                <button type="submit"
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                    Inloggen
                </button>
            </form>
        </div>

        <div class="px-6 pb-6 text-center border-t border-gray-100 dark:border-neutral-700 pt-4">
            <p class="text-xs text-gray-400 dark:text-neutral-500">
                Nog geen account?
                <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Registreren als klant</a>
            </p>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">
                Kapper?
                <a href="{{ route('kapper.registreer') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Kapperszaak aanmelden</a>
            </p>
        </div>
    </div>

</body>
</html>
