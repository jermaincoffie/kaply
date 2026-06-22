<div x-data="{ filterOpen: false }">
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Afspraken</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Overzicht van al je afspraken</p>
    </div>

    {{-- Filters --}}
    @if($heeftAfspraken)

    {{-- Mobiel: tabs + filterknop --}}
    <div class="sm:hidden flex items-center gap-2 mb-4">
        <div class="flex flex-1 rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden bg-white dark:bg-neutral-800">
            @foreach(['aankomend' => 'Aankomend', 'verleden' => 'Verleden', 'alles' => 'Alles'] as $val => $label)
            <button wire:click="$set('periode', '{{ $val }}')"
                    class="flex-1 px-2 py-1.5 text-sm font-medium transition-colors
                        {{ $periode === $val
                            ? 'bg-blue-600 text-white'
                            : 'text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>

        <button @click="filterOpen = true"
                class="relative flex-shrink-0 flex items-center justify-center w-9 h-9 rounded-lg border transition-colors
                    {{ $filterStatus
                        ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
                        : 'border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-500 dark:text-neutral-400' }}">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            @if($filterStatus)
            <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-blue-600"></span>
            @endif
        </button>
    </div>

    {{-- Desktop: tabs + select --}}
    <div class="hidden sm:flex flex-wrap gap-3 mb-4">
        <div class="flex rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden bg-white dark:bg-neutral-800">
            @foreach(['aankomend' => 'Aankomend', 'verleden' => 'Verleden', 'alles' => 'Alles'] as $val => $label)
            <button wire:click="$set('periode', '{{ $val }}')"
                    class="px-3 py-1.5 text-sm font-medium transition-colors
                        {{ $periode === $val
                            ? 'bg-blue-600 text-white'
                            : 'text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>
        <x-select
            wire-target="filterStatus"
            :current="$filterStatus"
            :options="['' => 'Alle statussen', 'gepland' => 'Gepland', 'voltooid' => 'Voltooid', 'geannuleerd' => 'Geannuleerd', 'no_show' => 'No-show']"
            placeholder="Alle statussen"
        />
    </div>

    {{-- Bottom sheet (mobiel) --}}
    <div x-show="filterOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 sm:hidden"
         style="display: none;">
        <div class="absolute inset-0 bg-black/40" @click="filterOpen = false"></div>
        <div class="absolute bottom-0 left-0 right-0 bg-white dark:bg-neutral-800 rounded-t-2xl pb-safe"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full">
            <div class="flex items-center justify-between px-5 pt-5 pb-3">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Filter op status</h3>
                <button @click="filterOpen = false" class="text-gray-400 dark:text-neutral-500 hover:text-gray-600 dark:hover:text-neutral-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-3 pb-6 space-y-1">
                @foreach(['' => 'Alle statussen', 'gepland' => 'Gepland', 'voltooid' => 'Voltooid', 'geannuleerd' => 'Geannuleerd', 'no_show' => 'No-show'] as $val => $label)
                <button wire:click="$set('filterStatus', '{{ $val }}')" @click="filterOpen = false"
                        class="w-full flex items-center justify-between px-4 py-3.5 rounded-xl text-sm font-medium transition-colors
                            {{ $filterStatus === $val
                                ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'
                                : 'text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700' }}">
                    {{ $label }}
                    @if($filterStatus === $val)
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    @endif
                </button>
                @endforeach
            </div>
        </div>
    </div>

    @endif

    {{-- Mobile cards --}}
    <div class="sm:hidden space-y-2">
        @php $vorigeDatumMobiel = null; @endphp
        @forelse($afspraken as $afspraak)
        @php
            $dagKeyM   = $afspraak->datum->toDateString();
            $isNieuwM  = $dagKeyM !== $vorigeDatumMobiel;
            $vorigeDatumMobiel = $dagKeyM;
            $dagLabelM = match(true) {
                $afspraak->datum->isToday()    => 'Vandaag',
                $afspraak->datum->isTomorrow() => 'Morgen',
                default => $afspraak->datum->isoFormat('dddd D MMMM'),
            };
            $badgeM = match($afspraak->status) {
                'gepland'     => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                'voltooid'    => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                default       => 'bg-gray-100 text-gray-500',
            };
        @endphp

        @if($isNieuwM)
        <div class="flex items-center gap-2 pt-3 pb-1 px-1">
            <span class="text-xs font-semibold text-gray-600 dark:text-neutral-300">{{ $dagLabelM }}</span>
            @if($afspraak->datum->isToday())
            <span class="inline-flex px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-600 text-white">Vandaag</span>
            @endif
        </div>
        @endif

        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl px-4 py-3">
            <div class="flex items-center gap-3">
                <p class="text-sm font-medium text-gray-500 dark:text-neutral-400 flex-shrink-0">{{ $afspraak->start_tijd }}</p>
                <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100 truncate flex-1">
                    {{ str($afspraak->klant?->name ?? $afspraak->walk_in_naam ?? 'Onbekend')->title() }}
                    @if($afspraak->medewerker)
                    <span class="font-normal text-gray-400 dark:text-neutral-500 text-xs">({{ $afspraak->medewerker->naam }})</span>
                    @endif
                </p>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeM }}">
                        {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
                    </span>
                    @if($afspraak->status === 'gepland' && $afspraak->datum->lt(today()))
                    <button wire:click="openNoShowModal({{ $afspraak->id }})"
                        class="text-xs font-medium text-red-500 dark:text-red-400 hover:underline">
                        No-show
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="py-12 text-center">
            <p class="text-sm text-gray-400 dark:text-neutral-500">Geen afspraken gevonden</p>
        </div>
        @endforelse

        @if($heeftMeer)
        <button wire:click="laadMeer" wire:loading.attr="disabled" wire:target="laadMeer"
                class="w-full mt-2 py-3 text-sm font-medium text-gray-500 dark:text-neutral-400 hover:text-gray-700 dark:hover:text-neutral-200 disabled:opacity-50 transition-colors">
            <span wire:loading.remove wire:target="laadMeer">Laad meer afspraken</span>
            <span wire:loading wire:target="laadMeer">Laden...</span>
        </button>
        @endif
    </div>

    {{-- Tabel --}}
    <div class="hidden sm:block bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-neutral-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Tijd</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Klant</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden md:table-cell">Dienst</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden md:table-cell">Betaling</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            @php $vorigeDatum = null; @endphp
            <tbody>
                @forelse($afspraken as $afspraak)
                @php
                    $dagKey = $afspraak->datum->toDateString();
                    $isNieuweDag = $dagKey !== $vorigeDatum;
                    $vorigeDatum = $dagKey;

                    $dagLabel = match(true) {
                        $afspraak->datum->isToday()    => 'Vandaag',
                        $afspraak->datum->isTomorrow() => 'Morgen',
                        $afspraak->datum->diffInDays(today()) === 2 && $afspraak->datum->isFuture() => 'Overmorgen',
                        default => $afspraak->datum->isoFormat('dddd D MMMM'),
                    };
                @endphp

                {{-- Datum header rij --}}
                @if($isNieuweDag)
                <tr class="{{ $loop->first ? '' : 'border-t border-gray-100 dark:border-neutral-700' }} bg-gray-50 dark:bg-neutral-700/40">
                    <td colspan="5" class="px-6 py-2.5">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-gray-600 dark:text-neutral-300">
                                {{ $dagLabel }}
                            </span>
                            @if($afspraak->datum->isToday())
                            <span class="inline-flex px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-600 text-white">Vandaag</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endif

                <tr class="hover:bg-gray-50/50 dark:hover:bg-neutral-700/20">
                    <td class="px-6 py-3">
                        <p class="font-medium text-gray-800 dark:text-neutral-100">{{ $afspraak->start_tijd }} – {{ $afspraak->eind_tijd }}</p>
                    </td>
                    <td class="px-6 py-3.5 text-gray-700 dark:text-neutral-300">{{ str($afspraak->klant?->name ?? $afspraak->walk_in_naam ?? 'Onbekend')->title() }}</td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400 hidden md:table-cell">
                        {{ $afspraak->dienst->naam }}
                        <span class="text-xs text-gray-400 dark:text-neutral-500 ml-1">€ {{ $afspraak->dienst->prijs_in_euros }}</span>
                    </td>
                    <td class="px-6 py-3.5 hidden md:table-cell">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $afspraak->betaalmethode === 'online' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                            {{ $afspraak->betaalmethode === 'online' ? 'Online' : 'In zaak' }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        @php
                            $badge = match($afspraak->status) {
                                'gepland'     => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'voltooid'    => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                                'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                default       => 'bg-gray-100 text-gray-500',
                            };
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
                            </span>
                            @if($afspraak->status === 'gepland' && $afspraak->datum->lt(today()))
                            <button wire:click="openNoShowModal({{ $afspraak->id }})"
                                    class="text-xs font-medium text-red-500 dark:text-red-400 hover:underline whitespace-nowrap">
                                No-show
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-neutral-500">
                        Geen afspraken gevonden
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($heeftMeer)
        <div class="px-6 py-4 border-t border-gray-100 dark:border-neutral-700">
            <button wire:click="laadMeer" wire:loading.attr="disabled" wire:target="laadMeer"
                    class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline disabled:opacity-50 transition-colors">
                <span wire:loading.remove wire:target="laadMeer">Laad meer afspraken</span>
                <span wire:loading wire:target="laadMeer">Laden...</span>
            </button>
        </div>
        @endif
    </div>

    {{-- No-show modal --}}
    @if($noShowAfspraakId)
    @php $nsAfspraak = $afspraken->firstWhere('id', $noShowAfspraakId) ?? \App\Models\Afspraak::with(['klant','dienst'])->find($noShowAfspraakId); @endphp
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="absolute inset-0 bg-black/50" wire:click="sluitNoShowModal"></div>
        <div class="relative w-full sm:max-w-sm bg-white dark:bg-neutral-800 sm:rounded-2xl rounded-t-2xl shadow-2xl">
            <div class="sm:hidden flex justify-center pt-3 pb-1">
                <div class="w-10 h-1 bg-gray-200 dark:bg-neutral-600 rounded-full"></div>
            </div>
            <div class="px-5 pt-5 pb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">No-show registreren</h3>
                        @if($nsAfspraak)
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                            {{ str($nsAfspraak->klant?->name ?? $nsAfspraak->walk_in_naam ?? 'Onbekend')->title() }}
                            · {{ $nsAfspraak->datum->format('d M Y') }} om {{ $nsAfspraak->start_tijd }}
                        </p>
                        @endif
                    </div>
                    <button wire:click="sluitNoShowModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-2 mb-5">
                    <label class="flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition-colors
                        {{ $noShowOptie === 'waarschuwing' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-neutral-700 hover:border-gray-300' }}">
                        <input type="radio" wire:model.live="noShowOptie" value="waarschuwing" class="mt-0.5 accent-blue-600">
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-neutral-100">Alleen waarschuwing</p>
                            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Klant ontvangt een email dat hij/zij niet is komen opdagen.</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition-colors
                        {{ $noShowOptie === 'fee' ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-200 dark:border-neutral-700 hover:border-gray-300' }}">
                        <input type="radio" wire:model.live="noShowOptie" value="fee" class="mt-0.5 accent-red-600">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-neutral-100">No-show fee in rekening brengen</p>
                            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Klant ontvangt een betaallink via email.</p>
                        </div>
                    </label>

                    @if($noShowOptie === 'fee')
                    <div class="pl-2 pt-1">
                        <label class="block text-xs font-medium text-gray-600 dark:text-neutral-400 mb-1.5">Bedrag</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-neutral-500">€</span>
                            <input wire:model="noShowFeeEuros"
                                   type="text"
                                   inputmode="decimal"
                                   placeholder="0,00"
                                   class="w-full pl-7 pr-3 py-2 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                        </div>
                        @if($noShowFout)
                        <p class="text-xs text-red-500 mt-1">{{ $noShowFout }}</p>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="flex gap-2">
                    <button wire:click="sluitNoShowModal"
                            class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        Annuleer
                    </button>
                    <button wire:click="bevestigNoShow"
                            wire:loading.attr="disabled"
                            class="flex-1 py-2.5 text-sm font-semibold rounded-lg transition-colors disabled:opacity-60
                                {{ $noShowOptie === 'fee' ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-gray-800 hover:bg-gray-900 dark:bg-neutral-600 dark:hover:bg-neutral-500 text-white' }}">
                        <span wire:loading.remove>{{ $noShowOptie === 'fee' ? 'Stuur betaallink' : 'Verstuur waarschuwing' }}</span>
                        <span wire:loading>Verwerken...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
