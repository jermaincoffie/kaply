<div class="relative" x-data @click.outside="$wire.open = false">

    {{-- Bell knop --}}
    <button wire:click="toggle"
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
    @if($open)
    <div class="absolute right-0 mt-2 w-80 bg-white dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 rounded-xl shadow-xl z-50 overflow-hidden">
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
                $isNieuw = $data['type'] === 'nieuwe_afspraak';
            @endphp
            <div class="px-4 py-3 flex items-start gap-3 {{ $notificatie->read_at ? 'opacity-60' : '' }}">
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center mt-0.5
                    {{ $isNieuw ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                    @if($isNieuw)
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    @else
                    <svg class="w-4 h-4 text-red-500 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{ $isNieuw ? 'Nieuwe afspraak' : 'Afspraak geannuleerd' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-neutral-400 truncate">
                        {{ $data['klant_naam'] }} · {{ $data['dienst_naam'] }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">
                        {{ $data['datum'] }} om {{ $data['start_tijd'] }}
                    </p>
                </div>
                <span class="text-[10px] text-gray-300 dark:text-neutral-600 flex-shrink-0 mt-1">
                    {{ $notificatie->created_at->diffForHumans(short: true) }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif
</div>
