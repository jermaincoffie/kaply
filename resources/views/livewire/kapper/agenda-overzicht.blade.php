<div>
    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-2">Omzet {{ now()->isoFormat('MMMM') }}</p>
            <span class="text-2xl font-bold text-gray-900 dark:text-neutral-100">€ {{ number_format($omzet_maand / 100, 0, ',', '.') }}</span>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-2">Afspraken {{ now()->isoFormat('MMMM') }}</p>
            <span class="text-2xl font-bold text-gray-900 dark:text-neutral-100">{{ $afspraken_maand }}</span>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-2">Komende</p>
            <span class="text-2xl font-bold text-gray-900 dark:text-neutral-100">{{ $komende_afspraken }}</span>
        </div>
    </div>

    {{-- Week navigatie --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">

        {{-- Header toolbar --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-neutral-700">
            <div class="flex items-center gap-2">
                <button wire:click="vorigeWeek"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button wire:click="volgendeWeek"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-neutral-700 dark:hover:text-neutral-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <span class="text-sm font-semibold text-gray-700 dark:text-neutral-200">
                    {{ $weekStartDate->isoFormat('D MMMM') }} – {{ $weekStartDate->copy()->endOfWeek()->isoFormat('D MMMM YYYY') }}
                </span>
            </div>
            <button wire:click="naarVandaag"
                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                Vandaag
            </button>
        </div>

        {{-- Dag headers --}}
        <div class="flex border-b border-gray-100 dark:border-neutral-700">
            <div class="w-14 flex-shrink-0"></div>
            @foreach($days as $day)
            <div class="flex-1 px-2 py-2 text-center border-l border-gray-100 dark:border-neutral-700
                {{ $day->isToday() ? 'bg-blue-50 dark:bg-blue-900/10' : '' }}">
                <p class="text-xs text-gray-400 dark:text-neutral-500 uppercase tracking-wide">{{ $day->isoFormat('ddd') }}</p>
                <p class="text-sm font-semibold mt-0.5
                    {{ $day->isToday() ? 'text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-neutral-300' }}">
                    {{ $day->format('d') }}
                </p>
            </div>
            @endforeach
        </div>

        {{-- Kalender grid --}}
        @php
            $dagStart = 8;   // 08:00
            $dagEind  = 19;  // 19:00
            $uren     = $dagEind - $dagStart;
            $pxPerUur = 64;
            $hoogte   = $uren * $pxPerUur; // totaal px
        @endphp

        <div class="flex overflow-y-auto" style="max-height: 600px">
            {{-- Tijdlijn --}}
            <div class="w-14 flex-shrink-0 relative" style="height: {{ $hoogte }}px">
                @for ($u = $dagStart; $u < $dagEind; $u++)
                <div class="absolute w-full flex items-start justify-end pr-2"
                     style="top: {{ ($u - $dagStart) * $pxPerUur }}px; height: {{ $pxPerUur }}px">
                    <span class="text-xs text-gray-400 dark:text-neutral-600 -mt-2">
                        {{ str_pad($u, 2, '0', STR_PAD_LEFT) }}:00
                    </span>
                </div>
                @endfor
            </div>

            {{-- Dag kolommen --}}
            @foreach($days as $day)
            @php $dagKey = $day->toDateString(); @endphp
            <div class="flex-1 relative border-l border-gray-100 dark:border-neutral-700
                {{ $day->isToday() ? 'bg-blue-50/30 dark:bg-blue-900/5' : '' }}"
                 style="height: {{ $hoogte }}px">

                {{-- Uurlijnen --}}
                @for ($u = 0; $u < $uren; $u++)
                <div class="absolute w-full border-t {{ $u === 0 ? 'border-gray-200 dark:border-neutral-600' : 'border-gray-100 dark:border-neutral-700/50' }}"
                     style="top: {{ $u * $pxPerUur }}px"></div>
                @endfor

                {{-- Halvuurslijnen --}}
                @for ($u = 0; $u < $uren; $u++)
                <div class="absolute w-full border-t border-gray-50 dark:border-neutral-800"
                     style="top: {{ $u * $pxPerUur + $pxPerUur / 2 }}px"></div>
                @endfor

                {{-- Huidig tijdstip lijn (alleen vandaag) --}}
                @if($day->isToday())
                @php
                    $now = now();
                    $nowMin = ($now->hour - $dagStart) * 60 + $now->minute;
                    $nowTop = ($nowMin / 60) * $pxPerUur;
                @endphp
                @if($nowMin >= 0 && $nowTop <= $hoogte)
                <div class="absolute w-full z-10 flex items-center" style="top: {{ $nowTop }}px">
                    <div class="w-2 h-2 rounded-full bg-blue-500 -ml-1 flex-shrink-0"></div>
                    <div class="flex-1 h-px bg-blue-500"></div>
                </div>
                @endif
                @endif

                {{-- Afspraken --}}
                @foreach($afsprakenPerDag[$dagKey] ?? [] as $afspraak)
                @php
                    [$sh, $sm] = explode(':', $afspraak->start_tijd);
                    $startMinFromTop = ((int)$sh - $dagStart) * 60 + (int)$sm;
                    $top    = ($startMinFromTop / 60) * $pxPerUur;
                    $height = max(24, ($afspraak->dienst->duur_minuten / 60) * $pxPerUur - 2);
                    $isSelected = $geselecteerdeAfspraak?->id === $afspraak->id;

                    $kleur = match($afspraak->status) {
                        'gepland'     => 'bg-blue-500 hover:bg-blue-600 border-blue-600 text-white',
                        'voltooid'    => 'bg-green-500 hover:bg-green-600 border-green-600 text-white',
                        'no_show'     => 'bg-red-400 hover:bg-red-500 border-red-500 text-white',
                        'geannuleerd' => 'bg-gray-300 hover:bg-gray-400 border-gray-400 text-gray-600 dark:bg-neutral-600 dark:text-neutral-300',
                        default       => 'bg-blue-500 text-white',
                    };
                @endphp
                <button
                    wire:click="selecteerAfspraak({{ $afspraak->id }})"
                    class="absolute left-1 right-1 rounded-md border-l-2 px-1.5 py-0.5 text-left transition-all cursor-pointer {{ $kleur }}
                        {{ $isSelected ? 'ring-2 ring-offset-1 ring-blue-400 z-20' : 'z-10' }}"
                    style="top: {{ $top }}px; height: {{ $height }}px"
                >
                    <p class="text-xs font-semibold truncate leading-tight">{{ str($afspraak->klant->name)->title() }}</p>
                    @if($height > 35)
                    <p class="text-xs opacity-80 truncate leading-tight">{{ $afspraak->dienst->naam }}</p>
                    @endif
                </button>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

    {{-- Detail panel --}}
    @if($geselecteerdeAfspraak)
    @php $a = $geselecteerdeAfspraak; @endphp
    <div class="mt-4 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">
                    {{ str($a->klant->name)->title() }}
                </h3>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                    {{ $a->datum->isoFormat('dddd D MMMM') }} · {{ $a->start_tijd }} – {{ $a->eind_tijd }}
                </p>
            </div>
            <button wire:click="selecteerAfspraak(null)"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1 rounded">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
            <div>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mb-0.5">Dienst</p>
                <p class="font-medium text-gray-700 dark:text-neutral-300">{{ $a->dienst->naam }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mb-0.5">Prijs</p>
                <p class="font-medium text-gray-700 dark:text-neutral-300">€ {{ $a->dienst->prijs_in_euros }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mb-0.5">Betaling</p>
                <p class="font-medium text-gray-700 dark:text-neutral-300">
                    {{ $a->betaalmethode === 'online' ? 'Online betaald' : 'In de zaak' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mb-0.5">Status</p>
                @php
                    $badge = match($a->status) {
                        'gepland'     => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                        'voltooid'    => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                        'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        default       => 'bg-gray-100 text-gray-500',
                    };
                @endphp
                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                    {{ ucfirst(str_replace('_', ' ', $a->status)) }}
                </span>
            </div>
        </div>

        @if($a->status === 'gepland')
        <div class="flex gap-2 pt-3 border-t border-gray-100 dark:border-neutral-700">
            <button wire:click="voltooid({{ $a->id }})"
                    class="flex-1 py-2 text-sm font-medium rounded-lg bg-green-600 text-white hover:bg-green-700 transition-colors">
                Voltooid
            </button>
            <button wire:click="noShow({{ $a->id }})" wire:confirm="No-show markeren?"
                    class="flex-1 py-2 text-sm font-medium rounded-lg border border-red-300 text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                No-show
            </button>
        </div>
        @endif
    </div>
    @endif
</div>
