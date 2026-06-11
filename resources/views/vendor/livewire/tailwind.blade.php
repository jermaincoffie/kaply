@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-start gap-4">

            {{-- Resultaten teller --}}
            <p class="text-xs text-gray-400 dark:text-neutral-500">
                <span class="font-medium text-gray-600 dark:text-neutral-300">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
                van
                <span class="font-medium text-gray-600 dark:text-neutral-300">{{ $paginator->total() }}</span>
                resultaten
            </p>

            {{-- Knoppen --}}
            <div class="flex items-center gap-1">

                {{-- Vorige --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-300 dark:text-neutral-600 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <button type="button"
                        wire:click="previousPage('{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 text-gray-500 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 hover:text-gray-800 dark:hover:text-neutral-100 active:bg-gray-100 dark:active:bg-neutral-600 transition-colors duration-100 shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                @endif

                {{-- Paginanummers --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center w-8 h-8 text-xs text-gray-400 dark:text-neutral-500">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                @if ($page == $paginator->currentPage())
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-600 text-white text-xs font-semibold">{{ $page }}</span>
                                @else
                                    <button type="button"
                                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 text-xs text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 hover:text-gray-900 dark:hover:text-neutral-100 active:bg-gray-100 dark:active:bg-neutral-600 transition-colors duration-100 shadow-sm">
                                        {{ $page }}
                                    </button>
                                @endif
                            </span>
                        @endforeach
                    @endif
                @endforeach

                {{-- Volgende --}}
                @if ($paginator->hasMorePages())
                    <button type="button"
                        wire:click="nextPage('{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 text-gray-500 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 hover:text-gray-800 dark:hover:text-neutral-100 active:bg-gray-100 dark:active:bg-neutral-600 transition-colors duration-100 shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                @else
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-300 dark:text-neutral-600 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
        </nav>
    @endif
</div>
