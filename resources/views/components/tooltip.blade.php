@props(['position' => 'center'])

@php
$hPos = match($position) {
    'right'       => 'right-0',
    'left'        => 'left-0',
    'below-right' => 'right-0',
    'below-left'  => 'left-0',
    'below'       => 'left-1/2 -translate-x-1/2',
    default       => 'left-1/2 -translate-x-1/2',
};

$vPos = str_starts_with($position, 'below') ? 'top-6' : 'bottom-6';

$arrowPos = match($position) {
    'right'       => 'right-2',
    'left'        => 'left-2',
    'below-right' => 'right-2',
    'below-left'  => 'left-2',
    default       => 'left-1/2 -translate-x-1/2',
};

$arrowDir = str_starts_with($position, 'below')
    ? 'bottom-full border-b-gray-900 dark:border-b-neutral-950'
    : 'top-full border-t-gray-900 dark:border-t-neutral-950';
@endphp

<span class="relative inline-flex items-center" x-data="{ open: false }" @click.outside="open = false">
    <span tabindex="0" role="button"
          @mouseenter="open = true" @mouseleave="open = false"
          @focus="open = true" @blur="open = false"
          @click.stop="open = !open"
          class="inline-flex items-center justify-center ml-1 flex-shrink-0 cursor-pointer focus:outline-none text-gray-300 dark:text-neutral-600 hover:text-gray-500 dark:hover:text-neutral-400">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3m.08 4h.01"/>
        </svg>
    </span>
    <span x-show="open"
          x-transition:enter="transition ease-out duration-100"
          x-transition:enter-start="opacity-0 scale-95"
          x-transition:enter-end="opacity-100 scale-100"
          x-transition:leave="transition ease-in duration-75"
          x-transition:leave-start="opacity-100 scale-100"
          x-transition:leave-end="opacity-0 scale-95"
          class="absolute z-[999] {{ $vPos }} {{ $hPos }} w-56 sm:w-72 bg-gray-900 dark:bg-neutral-950 text-white text-xs rounded-xl px-3 py-2.5 shadow-xl pointer-events-none leading-relaxed font-normal normal-case tracking-normal whitespace-normal"
          style="display: none;">
        {{ $slot }}
        <span class="absolute {{ $arrowPos }} {{ $arrowDir }} border-4 border-transparent"></span>
    </span>
</span>
