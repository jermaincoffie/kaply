@extends('layouts.publiek')

@section('content')
<div class="max-w-md mx-auto px-4 py-16 text-center">

    <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h1 class="text-xl font-bold text-gray-900 dark:text-neutral-100 mb-2">Betaling geslaagd!</h1>
    <p class="text-sm text-gray-500 dark:text-neutral-400 mb-8">Je afspraak is bevestigd.</p>

    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-6 text-left space-y-3 mb-8">
        <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-neutral-400">Salon</span>
            <span class="font-medium text-gray-800 dark:text-neutral-100">{{ $afspraak->kapper->salon_naam }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-neutral-400">Dienst</span>
            <span class="font-medium text-gray-800 dark:text-neutral-100">{{ $afspraak->dienst->naam }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-neutral-400">Datum</span>
            <span class="font-medium text-gray-800 dark:text-neutral-100">{{ $afspraak->datum->isoFormat('dddd D MMMM YYYY') }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-neutral-400">Tijd</span>
            <span class="font-medium text-gray-800 dark:text-neutral-100">{{ $afspraak->start_tijd }}</span>
        </div>
        <div class="flex justify-between text-sm border-t border-gray-100 dark:border-neutral-700 pt-3 mt-3">
            <span class="text-gray-500 dark:text-neutral-400">Betaald</span>
            <span class="font-semibold text-green-600 dark:text-green-400">€ {{ $afspraak->dienst->prijs_in_euros }}</span>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('klant.afspraken') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">
            Mijn afspraken
        </a>
        <a href="{{ route('home') }}" class="px-5 py-2.5 border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 text-sm font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
            Terug naar zoeken
        </a>
    </div>

</div>
@endsection
