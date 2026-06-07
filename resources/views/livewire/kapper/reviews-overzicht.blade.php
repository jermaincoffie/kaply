<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Reviews</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Beoordelingen van jouw klanten</p>
        </div>
        @if($gemiddeld)
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-0.5">
                @for($i = 1; $i <= 5; $i++)
                <svg class="w-4 h-4 {{ $i <= round($gemiddeld) ? 'text-amber-400' : 'text-gray-200 dark:text-neutral-600' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                @endfor
            </div>
            <span class="text-sm font-bold text-gray-800 dark:text-neutral-100">{{ number_format($gemiddeld, 1) }}</span>
            <span class="text-xs text-gray-400 dark:text-neutral-500">({{ $totaal }})</span>
        </div>
        @endif
    </div>

    @if($reviews->isEmpty())
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl px-6 py-16 text-center">
        <svg class="w-10 h-10 text-gray-200 dark:text-neutral-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
        </svg>
        <p class="text-sm text-gray-400 dark:text-neutral-500">Nog geen reviews ontvangen</p>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Klanten kunnen je beoordelen na een voltooide afspraak</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($reviews as $review)
        <div class="bg-white dark:bg-neutral-800 border {{ $review->zichtbaar ? 'border-gray-200 dark:border-neutral-700' : 'border-gray-100 dark:border-neutral-800 opacity-50' }} rounded-xl p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200 dark:text-neutral-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <span class="text-xs font-semibold text-gray-700 dark:text-neutral-300">{{ str($review->klant?->name ?? 'Anoniem')->title() }}</span>
                        @if(!$review->zichtbaar)
                        <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400">Verborgen</span>
                        @endif
                    </div>
                    @if($review->tekst)
                    <p class="text-sm text-gray-600 dark:text-neutral-400 leading-relaxed">{{ $review->tekst }}</p>
                    @endif
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1.5">{{ $review->created_at->isoFormat('D MMMM YYYY') }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
