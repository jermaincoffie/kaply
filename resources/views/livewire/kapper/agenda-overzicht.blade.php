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
                 style="height: {{ $hoogte }}px"
                 x-data
                 @click="
                    const rect = $el.getBoundingClientRect();
                    const scrollTop = $el.closest('.overflow-y-auto')?.scrollTop ?? 0;
                    const y = $event.clientY - rect.top + scrollTop;
                    const minFromTop = Math.floor(y / {{ $pxPerUur }} * 60);
                    const roundedMin = Math.round(minFromTop / 15) * 15;
                    const hour = Math.floor(roundedMin / 60) + {{ $dagStart }};
                    const min = roundedMin % 60;
                    if (hour < {{ $dagStart }} || hour >= {{ $dagEind }}) return;
                    const tijd = String(hour).padStart(2,'0') + ':' + String(min).padStart(2,'0');
                    $wire.openNieuwFormulier('{{ $dagKey }}', tijd);
                 "
            >

                {{-- Uurlijnen (pointer-events-none zodat klikken doorgaan naar parent) --}}
                @for ($u = 0; $u < $uren; $u++)
                <div class="absolute w-full border-t pointer-events-none {{ $u === 0 ? 'border-gray-200 dark:border-neutral-600' : 'border-gray-100 dark:border-neutral-700/50' }}"
                     style="top: {{ $u * $pxPerUur }}px"></div>
                @endfor

                {{-- Halvuurslijnen --}}
                @for ($u = 0; $u < $uren; $u++)
                <div class="absolute w-full border-t pointer-events-none border-gray-50 dark:border-neutral-800"
                     style="top: {{ $u * $pxPerUur + $pxPerUur / 2 }}px"></div>
                @endfor

                {{-- Huidig tijdstip lijn --}}
                @if($day->isToday())
                @php
                    $now = now();
                    $nowMin = ($now->hour - $dagStart) * 60 + $now->minute;
                    $nowTop = ($nowMin / 60) * $pxPerUur;
                @endphp
                @if($nowMin >= 0 && $nowTop <= $hoogte)
                <div class="absolute w-full z-10 flex items-center pointer-events-none" style="top: {{ $nowTop }}px">
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
                    @click.stop
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

    {{-- ===== MODAL OVERLAY ===== --}}
    @if($toonNieuwFormulier || $geselecteerdeAfspraak)
    <div
        x-data
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50" wire:click="sluitAlles"></div>

        {{-- Modal card — bottom sheet op mobiel, centered card op desktop --}}
        <div
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
            class="relative w-full sm:max-w-md bg-white dark:bg-neutral-800 sm:rounded-2xl rounded-t-2xl shadow-2xl overflow-hidden"
        >
            {{-- Drag handle (mobiel) --}}
            <div class="sm:hidden flex justify-center pt-3 pb-1">
                <div class="w-10 h-1 bg-gray-200 dark:bg-neutral-600 rounded-full"></div>
            </div>

            @if($toonNieuwFormulier)
            {{-- ===== NIEUW FORMULIER ===== --}}
            <div class="px-5 pt-4 pb-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Afspraak inplannen</h3>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                            {{ \Carbon\Carbon::parse($nieuwDatum)->isoFormat('dddd D MMMM') }} · {{ $nieuwTijd }}
                        </p>
                    </div>
                    <button wire:click="sluitAlles" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="afspraakOpslaan" class="space-y-4">
                    {{-- Klant --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Klant</label>
                        <input wire:model.live="klantZoekterm"
                               type="text"
                               placeholder="Zoek of typ nieuwe naam..."
                               autocomplete="off"
                               class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('klantZoekterm') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

                        @if($toonKlantDropdown && $zoekKlanten->count())
                        <div class="absolute left-0 right-0 top-full mt-1 z-50 bg-white dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 rounded-xl shadow-xl overflow-hidden">
                            @foreach($zoekKlanten as $klant)
                            <button type="button"
                                    wire:click="selecteerKlant({{ $klant->id }}, '{{ addslashes($klant->name) }}')"
                                    class="w-full text-left flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                                <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                    <span class="text-blue-700 dark:text-blue-400 font-bold text-xs">{{ mb_strtoupper(mb_substr($klant->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-100">{{ str($klant->name)->title() }}</p>
                                    <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $klant->email }}</p>
                                </div>
                            </button>
                            @endforeach
                        </div>
                        @endif

                        @if($geselecteerdeKlantId)
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Bestaande klant geselecteerd
                        </p>
                        @else
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Geen match? Wordt opgeslagen als walk-in.</p>
                        @endif
                    </div>

                    {{-- Dienst --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Dienst</label>
                        <x-select
                            wire-target="nieuwDienstId"
                            :current="$nieuwDienstId"
                            :options="$eigenDiensten->mapWithKeys(fn($d) => [$d->id => $d->naam . ' — ' . $d->duur_minuten . ' min · €' . $d->prijs_in_euros])->toArray()"
                            placeholder="Kies dienst"
                        />
                        @error('nieuwDienstId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tijd + betaling --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Begintijd</label>
                            <input wire:model="nieuwTijd" type="time"
                                   class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Betaling</label>
                            <x-select
                                wire-target="nieuwBetaalmethode"
                                :current="$nieuwBetaalmethode"
                                :options="['in_zaak' => 'In de zaak', 'online' => 'Online']"
                                placeholder="Betaalmethode"
                            />
                        </div>
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button type="button" wire:click="sluitAlles"
                                class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                            Annuleer
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                            Inplannen
                        </button>
                    </div>
                </form>
            </div>

            @elseif($geselecteerdeAfspraak)
            {{-- ===== DETAIL PANEL ===== --}}
            @php $a = $geselecteerdeAfspraak; @endphp
            <div class="px-5 pt-4 pb-6">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">{{ str($a->klant->name)->title() }}</h3>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                            {{ $a->datum->isoFormat('dddd D MMMM') }} · {{ $a->start_tijd }} – {{ $a->eind_tijd }}
                        </p>
                    </div>
                    <button wire:click="sluitAlles" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-0.5">Dienst</p>
                        <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">{{ $a->dienst->naam }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-0.5">Prijs</p>
                        <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">€ {{ $a->dienst->prijs_in_euros }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-0.5">Betaling</p>
                        <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">
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
                <div class="flex gap-2 pt-4 border-t border-gray-100 dark:border-neutral-700">
                    <button wire:click="voltooid({{ $a->id }})"
                            class="flex-1 py-2.5 text-sm font-medium rounded-lg bg-green-600 text-white hover:bg-green-700 transition-colors">
                        Voltooid
                    </button>
                    <button wire:click="noShow({{ $a->id }})" wire:confirm="No-show markeren?"
                            class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-red-300 text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                        No-show
                    </button>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
