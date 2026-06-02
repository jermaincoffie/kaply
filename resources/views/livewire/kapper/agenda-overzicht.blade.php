<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Agenda</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                {{ \Carbon\Carbon::parse($geselecteerdeDatum)->isoFormat('dddd D MMMM YYYY') }}
            </p>
        </div>

        {{-- Preline datepicker (desktop) + native fallback (mobiel) --}}
        <div x-data id="agenda-datepicker-wrapper">
            {{-- Desktop: Preline hs-datepicker --}}
            <input
                id="agenda-dp"
                type="text"
                readonly
                autocomplete="off"
                placeholder="Selecteer datum"
                value="{{ \Carbon\Carbon::parse($geselecteerdeDatum)->format('d/m/Y') }}"
                class="hs-datepicker hidden sm:block py-2 px-3 w-44 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder:text-gray-400 dark:placeholder:text-neutral-500 shadow-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 cursor-pointer"
                data-hs-datepicker='{"type":"default","applyUtilityClasses":true,"mode":"custom-select","dateFormat":"DD/MM/YYYY"}'
            >
            {{-- Mobiel: native date input --}}
            <input
                type="date"
                value="{{ $geselecteerdeDatum }}"
                class="sm:hidden py-2 px-3 block w-full bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 shadow-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600"
                onchange="@this.set('geselecteerdeDatum', this.value)"
            >
        </div>
    </div>

    {{-- Afspraken lijst --}}
    <div class="space-y-3">
        @forelse($afspraken as $afspraak)
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4 flex justify-between items-center">
            <div>
                <p class="font-semibold text-gray-900 dark:text-neutral-100 text-sm">{{ $afspraak->start_tijd }} — {{ $afspraak->eind_tijd }}</p>
                <p class="text-gray-700 dark:text-neutral-300 text-sm mt-0.5">{{ str($afspraak->klant->name)->title() }}</p>
                <p class="text-gray-400 dark:text-neutral-500 text-xs mt-0.5">
                    {{ $afspraak->dienst->naam }}
                    <span class="mx-1">·</span>
                    {{ $afspraak->betaalmethode === 'online' ? 'Online betaald' : 'In zaak betalen' }}
                </p>
            </div>
            <div class="flex gap-2 items-center">
                @if($afspraak->status === 'gepland')
                <button wire:click="voltooid({{ $afspraak->id }})"
                        class="text-xs font-medium px-3 py-1.5 rounded-lg bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-100 transition-colors">
                    Voltooid
                </button>
                <button wire:click="noShow({{ $afspraak->id }})" wire:confirm="No-show markeren?"
                        class="text-xs font-medium px-3 py-1.5 rounded-lg bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400 hover:bg-red-100 transition-colors">
                    No-show
                </button>
                @else
                @php
                    $badge = match($afspraak->status) {
                        'voltooid'    => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                        default       => 'bg-gray-100 text-gray-500',
                    };
                @endphp
                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                    {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
                </span>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl px-6 py-12 text-center">
            <p class="text-sm text-gray-400 dark:text-neutral-500">Geen afspraken op deze dag</p>
        </div>
        @endforelse
    </div>
</div>

@script
<script>
    // Na elke Livewire render: Preline datepicker opnieuw initialiseren + sync koppelen
    function initAgendaDatepicker() {
        var dp = document.getElementById('agenda-dp');
        if (!dp) return;

        // Preline opnieuw initialiseren op dit element
        if (window.HSDatepicker) {
            try { window.HSDatepicker.autoInit(); } catch(e) {}
        }
        if (window.HSStaticMethods) {
            try { window.HSStaticMethods.autoInit(); } catch(e) {}
        }

        // Verwijder oude listener om dubbele triggers te voorkomen
        var newDp = dp.cloneNode(true);
        dp.parentNode.replaceChild(newDp, dp);

        newDp.addEventListener('change', function () {
            var raw = newDp.value.trim();
            if (!raw) return;
            // DD/MM/YYYY → YYYY-MM-DD
            var parts = raw.split('/');
            if (parts.length === 3) {
                var iso = parts[2] + '-' + parts[1].padStart(2, '0') + '-' + parts[0].padStart(2, '0');
                $wire.set('geselecteerdeDatum', iso);
            }
        });
    }

    initAgendaDatepicker();
</script>
@endscript
