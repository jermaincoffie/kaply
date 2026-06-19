<div class="p-4 sm:p-6 max-w-2xl mx-auto space-y-6">

    {{-- Success header --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-8 text-center">

        {{-- Checkmark icon --}}
        <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
        </div>

        <h1 class="text-lg font-semibold text-gray-900 dark:text-neutral-100 mb-2">
            Abonnement geactiveerd!
        </h1>

        <p class="text-sm text-gray-500 dark:text-neutral-400 mb-1">
            @if($planNaam)
                Je bent geabonneerd op <span class="font-medium text-gray-700 dark:text-neutral-300">{{ $planNaam }}</span>.
            @else
                Je Kaply abonnement is succesvol geactiveerd.
            @endif
        </p>

        @if($salonNaam)
        <p class="text-sm text-gray-500 dark:text-neutral-400 mb-6">
            <span class="font-medium text-gray-700 dark:text-neutral-300">{{ $salonNaam }}</span> is nu zichtbaar op Kaply.
        </p>
        @else
        <p class="text-sm text-gray-500 dark:text-neutral-400 mb-6">
            Je salon is nu zichtbaar op Kaply.
        </p>
        @endif

        {{-- Action buttons --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('kapper.dashboard') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                </svg>
                Naar dashboard
            </a>

            @if($session_id)
            <button wire:click="naarPortal"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg border border-gray-200 dark:border-neutral-700 text-sm font-medium text-gray-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 hover:border-gray-300 dark:hover:border-neutral-600 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <svg wire:loading class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Beheer abonnement
            </button>
            @endif
        </div>
    </div>

    {{-- Info block --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5">
        <p class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-2">Wat nu?</p>
        <ul class="space-y-1.5">
            @foreach([
                'Compleet je profiel met foto\'s en diensten',
                'Stel je beschikbaarheid in',
                'Deel je boeklink met klanten',
            ] as $tip)
            <li class="flex items-start gap-2 text-sm text-blue-700 dark:text-blue-400">
                <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                {{ $tip }}
            </li>
            @endforeach
        </ul>
    </div>

</div>
