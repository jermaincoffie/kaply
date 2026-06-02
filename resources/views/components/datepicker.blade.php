@props([
    'wireModel'   => null,  // camelCase: wire-model="x" → $wireModel
    'value'       => '',
    'dateMin'     => null,
    'dateMax'     => '2050-12-31',
    'placeholder' => 'Selecteer datum',
])

@php
    $inputId = 'dp-' . uniqid();
    $config  = [
        'type'                => 'default',
        'dateMax'             => $dateMax,
        'applyUtilityClasses' => true,
        'mode'                => 'custom-select',
        'dateFormat'          => 'DD/MM/YYYY',
    ];
    if ($dateMin) $config['dateMin'] = $dateMin;

    // YYYY-MM-DD → DD/MM/YYYY voor weergave
    $displayValue = $value
        ? \Carbon\Carbon::parse($value)->format('d/m/Y')
        : '';
@endphp

{{-- wire:ignore zodat Livewire Preline niet overschrijft na elke render --}}
<div wire:ignore class="relative">
    <div
        x-data
        x-init="
            const el = document.getElementById('{{ $inputId }}');
            if (!el) return;

            const parseAndSync = (raw) => {
                if (!raw) { @if($wireModel) $wire.set('{{ $wireModel }}', ''); @endif return; }
                const parts = raw.split('/');
                if (parts.length === 3) {
                    const iso = parts[2] + '-' + parts[1].padStart(2,'0') + '-' + parts[0].padStart(2,'0');
                    @if($wireModel) $wire.set('{{ $wireModel }}', iso); @endif
                }
            };

            // Intercept value setter zodat Preline's programmatische update altijd sync
            const proto = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value');
            Object.defineProperty(el, 'value', {
                set(val) { proto.set.call(this, val); parseAndSync(val); },
                get()    { return proto.get.call(this); },
                configurable: true,
            });

            // Fallback: native change + input events
            ['change', 'input'].forEach(evt => el.addEventListener(evt, () => parseAndSync(el.value)));

            if (window.HSDatepicker) {
                new window.HSDatepicker(el);
            }
        "
    >
        {{-- Desktop: Preline datepicker --}}
        <input
            id="{{ $inputId }}"
            type="text"
            value="{{ $displayValue }}"
            placeholder="{{ $placeholder }}"
            readonly
            autocomplete="off"
            class="hs-datepicker hidden sm:block py-2 px-3 w-full bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder:text-gray-400 dark:placeholder:text-neutral-500 shadow-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 cursor-pointer"
            data-hs-datepicker="{{ json_encode($config) }}"
        >

        {{-- Mobiel: native date input --}}
        <input
            type="date"
            value="{{ $value }}"
            @if($dateMin) min="{{ $dateMin }}" @endif
            @if($dateMax !== '2050-12-31') max="{{ $dateMax }}" @endif
            class="sm:hidden py-2 px-3 block w-full bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 shadow-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600"
            @if($wireModel) x-on:change="$wire.set('{{ $wireModel }}', $event.target.value)" @endif
        >
    </div>
</div>
