<div>
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Kortingscodes</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Maak codes aan die klanten kunnen invoeren bij het boeken</p>
    </div>

    @if(session('message'))
    <div class="flex items-center gap-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl text-sm mb-6">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('message') }}
    </div>
    @endif

    {{-- Nieuwe code aanmaken --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl mb-6">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">Nieuwe code aanmaken</h2>
        </div>

        <form wire:submit="aanmaken" class="px-6 py-5 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Code --}}
                <div>
                    <label class="flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
                        Code
                        <x-tooltip position="below-right">Klanten voeren deze code in bij het boeken. Gebruik alleen letters, cijfers en koppeltekens. Wordt automatisch omgezet naar hoofdletters.</x-tooltip>
                    </label>
                    <input wire:model="code" type="text" placeholder="ZOMER10"
                           class="w-full py-2 px-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 uppercase">
                    @error('code') <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Type korting</label>
                    <x-select
                        wire-target="type"
                        :current="$type"
                        :options="['percentage' => 'Percentage (%)', 'vast' => 'Vast bedrag (€)']"
                    />
                    @error('type') <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Waarde --}}
                <div>
                    <label class="flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
                        Waarde
                        <x-tooltip position="below-right">Bij percentage: 10 = 10% korting. Bij vast bedrag: 5 = €5,00 korting.</x-tooltip>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-neutral-500 pointer-events-none">
                            {{ $type === 'percentage' ? '%' : '€' }}
                        </span>
                        <input wire:model="waarde" type="number" min="1" placeholder="{{ $type === 'percentage' ? '10' : '5' }}"
                               class="w-full py-2 pl-8 pr-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    </div>
                    @error('waarde') <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Max gebruik --}}
                <div>
                    <label class="flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
                        Max. gebruik
                        <x-tooltip position="below-right">Hoe vaak mag deze code totaal worden gebruikt? Leeg laten = onbeperkt.</x-tooltip>
                    </label>
                    <input wire:model="maxGebruik" type="number" min="1" placeholder="Onbeperkt"
                           class="w-full py-2 px-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('maxGebruik') <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Geldig van --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Geldig vanaf</label>
                    <x-datepicker wire-model="geldigVan" :value="$geldigVan" placeholder="Geen startdatum" />
                    @error('geldigVan') <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Geldig tot --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Geldig tot en met</label>
                    <x-datepicker wire-model="geldigTot" :value="$geldigTot" :date-min="$geldigVan ?: null" placeholder="Geen einddatum" />
                    @error('geldigTot') <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-1">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Code aanmaken
                </button>
            </div>
        </form>
    </div>

    {{-- Overzicht codes --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">Jouw codes</h2>
        </div>

        @if($codes->isEmpty())
        <div class="px-6 py-10 text-center">
            <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-neutral-700 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-400 dark:text-neutral-500">Nog geen kortingscodes aangemaakt</p>
        </div>
        @else
        <div class="divide-y divide-gray-50 dark:divide-neutral-700">
            @foreach($codes as $code)
            <div wire:key="code-{{ $code->id }}" class="flex items-center justify-between px-6 py-4 gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <span class="font-mono text-sm font-semibold tracking-wide text-gray-800 dark:text-neutral-100 bg-gray-100 dark:bg-neutral-700 px-2.5 py-1 rounded-lg flex-shrink-0">
                        {{ $code->code }}
                    </span>

                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $code->type === 'percentage' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400' }}">
                                {{ $code->label }}
                            </span>

                            @if(!$code->isGeldig() && $code->actief)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                Verlopen / vol
                            </span>
                            @elseif($code->actief)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                Actief
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400">
                                Gepauzeerd
                            </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-400 dark:text-neutral-500 flex-wrap">
                            <span>{{ $code->gebruik_teller }}× gebruikt{{ $code->max_gebruik ? ' / max ' . $code->max_gebruik : '' }}</span>
                            @if($code->geldig_van || $code->geldig_tot)
                            <span>{{ $code->geldig_van?->format('d M') ?? '∞' }} → {{ $code->geldig_tot?->format('d M Y') ?? '∞' }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-shrink-0">
                    <button wire:click="toggleActief({{ $code->id }})"
                            class="text-xs font-medium {{ $code->actief ? 'text-amber-500 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300' : 'text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300' }} transition-colors">
                        {{ $code->actief ? 'Pauzeren' : 'Activeren' }}
                    </button>
                    <button wire:click="verwijderen({{ $code->id }})"
                            wire:confirm="Weet je zeker dat je code '{{ $code->code }}' wilt verwijderen?"
                            class="text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                        Verwijder
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
