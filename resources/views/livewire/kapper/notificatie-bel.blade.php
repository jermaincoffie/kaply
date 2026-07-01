<div class="relative"
     x-data="{ open: false }"
     @click.outside="open = false"
     wire:poll.30s="pollen">

    {{-- Bell knop --}}
    <button @click="open = !open; if (open) $wire.markAlsGelezen()"
            class="relative p-1.5 rounded-lg text-gray-500 dark:text-neutral-400 hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($ongelezen > 0)
        <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">
            {{ $ongelezen > 9 ? '9+' : $ongelezen }}
        </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="display:none"
         class="absolute right-0 mt-2 w-[calc(100vw-2rem)] sm:w-80 max-w-sm bg-white dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 rounded-xl shadow-xl z-50 overflow-hidden">

        <div class="px-4 py-3 border-b border-gray-100 dark:border-neutral-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-200">Notificaties</h3>
            @if($notificaties->isNotEmpty())
            <span class="text-xs text-gray-400 dark:text-neutral-500">{{ $notificaties->count() }} recent</span>
            @endif
        </div>

        @if($notificaties->isEmpty())
        <div class="px-4 py-8 text-center text-sm text-gray-400 dark:text-neutral-500">
            Geen notificaties
        </div>
        @else
        <div class="divide-y divide-gray-50 dark:divide-neutral-800 max-h-80 overflow-y-auto">
            @foreach($notificaties as $notificatie)
            @php
                $data = $notificatie->data;
                $type = $data['type'] ?? '';
                $isNieuw  = $type === 'nieuwe_afspraak';
                $isVerzet = $type === 'afspraak_verzet';
                $isGean   = $type === 'afspraak_geannuleerd';
            @endphp
            <div class="px-4 py-3 flex items-start gap-3 {{ $notificatie->read_at ? 'opacity-60' : '' }}">
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center mt-0.5
                    {{ $isNieuw ? 'bg-green-100 dark:bg-green-900/30' : ($isVerzet ? 'bg-amber-100 dark:bg-amber-900/30' : 'bg-red-100 dark:bg-red-900/30') }}">
                    @if($isNieuw)
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    @elseif($isVerzet)
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    @else
                    <svg class="w-4 h-4 text-red-500 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                        @if($isNieuw) Nieuwe afspraak
                        @elseif($isVerzet) Afspraak verzet
                        @else Afspraak geannuleerd
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 dark:text-neutral-400 truncate">
                        {{ $data['klant_naam'] }} · {{ $data['dienst_naam'] }}
                    </p>
                    @if($isVerzet)
                    <p class="text-xs text-gray-400 dark:text-neutral-500">
                        {{ $data['oude_datum'] }} {{ $data['oude_tijd'] }} → {{ $data['datum'] }} {{ $data['start_tijd'] }}
                    </p>
                    @else
                    <p class="text-xs text-gray-400 dark:text-neutral-500">
                        {{ $data['datum'] }} om {{ $data['start_tijd'] }}
                    </p>
                    @endif
                </div>
                <span class="text-[10px] text-gray-300 dark:text-neutral-600 flex-shrink-0 mt-1">
                    {{ $notificatie->created_at->diffForHumans(short: true) }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- Toast popup bij nieuwe notificatie --}}
<div x-data="{ toon: false, naam: '', dienst: '', tijd: '' }"
     @nieuwe-notificatie.window="
         naam = $event.detail.naam;
         dienst = $event.detail.dienst;
         tijd = $event.detail.tijd;
         toon = true;
         setTimeout(() => toon = false, 5000);
     "
     x-show="toon"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display:none"
     class="fixed bottom-5 right-5 z-[100] w-72 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl shadow-xl p-4 flex items-start gap-3">
    <div class="w-9 h-9 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Nieuwe afspraak!</p>
        <p class="text-xs text-gray-500 dark:text-neutral-400 truncate" x-text="naam + ' · ' + dienst"></p>
        <p class="text-xs text-gray-400 dark:text-neutral-500" x-text="tijd"></p>
    </div>
    <button @click="toon = false" class="text-gray-300 dark:text-neutral-600 hover:text-gray-500 dark:hover:text-neutral-400 flex-shrink-0">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
