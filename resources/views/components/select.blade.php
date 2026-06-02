@props(['wire' => null])

<div class="relative">
    <select {{ $attributes->merge([
        'class' => 'w-full appearance-none py-2 pl-3 pr-9 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 shadow-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 cursor-pointer'
    ]) }}>
        {{ $slot }}
    </select>
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5">
        <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</div>
