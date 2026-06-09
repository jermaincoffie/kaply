<?php
$intended = session('url.intended', '');
if (preg_match('#/(boeken|mijn-afspraken|mijn-account)#', $intended)) {
    header('Location: ' . route('klant.inloggen'));
    exit;
}
?>
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif
            </div>

            <div class="mt-4">
                <x-button class="w-full justify-center">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700 inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Terug naar zoeken
            </a>
        </div>

        <div class="mt-4 pt-5 border-t border-gray-100 space-y-1 text-center">
            <p class="text-xs text-gray-500">
                Klant? Boek direct via
                <a href="{{ route('klant.inloggen') }}" class="text-indigo-600 hover:underline font-medium">inloggen met email</a>
            </p>
            <p class="text-xs text-gray-500">
                Kapper?
                <a href="{{ route('kapper.registreer') }}" class="text-indigo-600 hover:underline font-medium">Kapperszaak aanmelden</a>
            </p>
        </div>
    </x-authentication-card>
</x-guest-layout>
