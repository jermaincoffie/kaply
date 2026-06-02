{{--
    Custom Alpine.js dropdown ter vervanging van native <select>.
    Gebruik: <x-select :options="[''=>'Alle', 'a'=>'Label A']" wire-model="eigenschap" />
    Of met Livewire: wire-model="filterStatus"
--}}
@props([
    'options'   => [],
    'wireModel' => null,
    'placeholder' => 'Selecteer...',
])

@php
    // Haal huidige waarde op via Livewire als wireModel opgegeven
    $currentValue = $wireModel ? null : null;
@endphp

<div
    x-data="{
        open: false,
        value: @entangle($attributes->wire('model')),
        options: {{ Js::from($options) }},
        get label() {
            if (this.value === '' || this.value === null || this.value === undefined) return '{{ $placeholder }}';
            return this.options[this.value] ?? '{{ $placeholder }}';
        }
    }"
    @click.outside="open = false"
    class="relative"
>
    {{-- Trigger --}}
    <button
        type="button"
        @click="open = !open"
        class="inline-flex items-center justify-between gap-2 py-2 pl-3 pr-3 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 shadow-sm hover:border-gray-300 dark:hover:border-neutral-600 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-colors cursor-pointer min-w-[140px]"
        :class="open ? 'border-blue-600 ring-1 ring-blue-600' : ''"
    >
        <span x-text="label" class="truncate"></span>
        <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500 flex-shrink-0 transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Dropdown panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute left-0 top-full mt-1 z-50 min-w-full w-48 bg-white dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 rounded-xl shadow-xl overflow-hidden"
        style="display: none;"
    >
        <template x-for="(label, val) in options" :key="val">
            <button
                type="button"
                @click="value = val; open = false"
                class="w-full text-left flex items-center gap-2 px-3 py-2 text-sm transition-colors"
                :class="value === val
                    ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-medium'
                    : 'text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-800'"
            >
                <span x-show="value === val" class="w-4 h-4 flex-shrink-0">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <span x-show="value !== val" class="w-4 h-4 flex-shrink-0"></span>
                <span x-text="label"></span>
            </button>
        </template>
    </div>
</div>
