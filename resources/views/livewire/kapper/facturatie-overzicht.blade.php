<div class="p-4 sm:p-6 max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Facturatie</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Facturen van Kaply voor jouw abonnement</p>
    </div>

    {{-- Abonnement info --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-gray-800 dark:text-neutral-100">Kaply Abonnement</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">€ 25,00 / maand excl. BTW</p>
        </div>
        <a href="{{ route('kapper.abonnement') }}"
           class="flex-shrink-0 text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
            Beheer abonnement →
        </a>
    </div>

    {{-- Facturen tabel --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">

        <div class="px-4 py-3 border-b border-gray-100 dark:border-neutral-700">
            <p class="text-sm font-medium text-gray-800 dark:text-neutral-100">Factuurhistorie</p>
        </div>

        @if($stripeError)
        <div class="p-6 text-center">
            <svg class="w-8 h-8 text-gray-300 dark:text-neutral-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-gray-500 dark:text-neutral-400">Kon geen facturen ophalen. Probeer later opnieuw.</p>
        </div>

        @elseif(count($invoices) === 0)
        <div class="p-8 text-center">
            <svg class="w-10 h-10 text-gray-200 dark:text-neutral-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">Nog geen facturen</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Facturen verschijnen hier zodra jouw eerste betaling verwerkt is.</p>
        </div>

        @else
        <div class="divide-y divide-gray-50 dark:divide-neutral-700/50">
            @foreach($invoices as $invoice)
            <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-neutral-700/30 transition-colors">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-200 truncate">
                        {{ $invoice->number ?? 'Factuur' }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                        {{ \Carbon\Carbon::createFromTimestamp($invoice->date())->format('d M Y') }}
                        · Kaply Abonnement
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0 ml-4">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $invoice->paid
                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                            : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                        {{ $invoice->paid ? 'Betaald' : 'Openstaand' }}
                    </span>
                    <span class="text-sm font-semibold text-gray-700 dark:text-neutral-300">
                        € {{ number_format($invoice->total() / 100, 2, ',', '.') }}
                    </span>
                    <a href="{{ $invoice->invoicePdf() }}" target="_blank"
                       class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors"
                       title="Download PDF">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>

    <p class="text-xs text-gray-400 dark:text-neutral-500 text-center">
        Vragen over een factuur? Mail naar
        <a href="mailto:info@kaply.nl" class="underline hover:text-gray-600 dark:hover:text-neutral-300">info@kaply.nl</a>
    </p>

</div>
