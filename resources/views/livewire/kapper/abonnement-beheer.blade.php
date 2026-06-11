<div class="p-4 sm:p-6 max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Abonnement</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Beheer je Kaply abonnement</p>
    </div>

    {{-- Status card --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">

        @if($actief)
            {{-- Actief --}}
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Kaply Abonnement</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                            Actief
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">€20 per maand · automatisch verlengd</p>
                    @if($gepauzeerd && $eindDatum)
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                        Opgezegd · loopt door tot {{ $eindDatum->format('d M Y') }}
                    </p>
                    @endif
                </div>
            </div>

            <div class="mt-5 pt-5 border-t border-gray-100 dark:border-neutral-700">
                <a href="{{ route('kapper.subscription.portal') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 dark:border-neutral-700 text-sm font-medium text-gray-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 hover:border-gray-300 dark:hover:border-neutral-600 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Beheer abonnement & facturen
                </a>
            </div>

        @else
            {{-- Geen abonnement --}}
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-neutral-700 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Geen actief abonnement</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400">
                            Inactief
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">Activeer je abonnement om je salon zichtbaar te maken op Kaply.</p>
                </div>
            </div>

            @if(request()->query('stripe') === 'success')
            <div class="mt-4 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-sm text-green-700 dark:text-green-400">
                Betaling geslaagd! Je abonnement wordt binnen enkele seconden geactiveerd.
            </div>
            @elseif(request()->query('stripe') === 'cancel')
            <div class="mt-4 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-sm text-amber-700 dark:text-amber-400">
                Betaling geannuleerd. Je abonnement is nog niet actief.
            </div>
            @endif

            <div class="mt-5 pt-5 border-t border-gray-100 dark:border-neutral-700">
                <a href="{{ route('kapper.subscription.checkout') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                    </svg>
                    Abonneer nu · €20/maand
                </a>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-2">Betaal veilig via Stripe · iDEAL, creditcard of SEPA</p>
            </div>
        @endif
    </div>

    {{-- Wat zit erin --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-4">Wat zit er in het abonnement?</p>
        <ul class="space-y-2.5">
            @foreach([
                'Jouw salon zichtbaar op Kaply.nl',
                'Online boekingen van klanten',
                'Agenda & afsprakenbeheer',
                'Klantenoverzicht & notities',
                'Reviews en beoordelingen',
                'Deelbare boeklink',
            ] as $feature)
            <li class="flex items-center gap-2.5 text-sm text-gray-600 dark:text-neutral-400">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                {{ $feature }}
            </li>
            @endforeach
        </ul>
    </div>

</div>
