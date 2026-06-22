<div>
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Beschikbaarheid</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Stel je weekrooster en sluitingsdagen in</p>
    </div>

    @if(session('message'))
    <div class="flex items-center gap-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl text-sm mb-6">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('message') }}
    </div>
    @endif

    {{-- Weekrooster --}}
    <form wire:submit="opslaan" class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl mb-6">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">Weekrooster</h2>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Opslaan
            </button>
        </div>

        <div class="divide-y divide-gray-50 dark:divide-neutral-700 border-b border-gray-100 dark:border-neutral-700">
            @foreach($rooster as $dag => $data)
            <div class="px-4 sm:px-6 py-3 flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4">
                <label class="flex items-center gap-3 cursor-pointer sm:flex-shrink-0 sm:w-32">
                    <input wire:model.live="rooster.{{ $dag }}.actief" type="checkbox"
                           class="rounded border-gray-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500 dark:bg-neutral-700">
                    <span class="text-sm font-medium text-gray-700 dark:text-neutral-300">{{ $data['naam'] }}</span>
                </label>

                @if($data['actief'])
                <div class="flex items-center gap-2 ml-7 sm:ml-0">
                    <input wire:model="rooster.{{ $dag }}.start_tijd" type="time"
                           class="py-1.5 px-2 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    <span class="text-xs text-gray-400 dark:text-neutral-500">tot</span>
                    <input wire:model="rooster.{{ $dag }}.eind_tijd" type="time"
                           class="py-1.5 px-2 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>
                @else
                <span class="text-xs text-gray-400 dark:text-neutral-500 ml-7 sm:ml-0">Gesloten</span>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Buffer tijd --}}
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3">
            <div class="min-w-0">
                <div class="flex items-center gap-1">
                    <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">Buffer tijd</p>
                    <x-tooltip position="below-right">Vrije tijd die automatisch na elke afspraak wordt gereserveerd. Klanten kunnen in die periode niet boeken.</x-tooltip>
                </div>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Pauze na elke afspraak</p>
            </div>
            <x-select
                class="w-full sm:w-auto"
                wire-target="bufferMinuten"
                :current="(string) $bufferMinuten"
                :options="['0' => 'Geen buffer', '5' => '5 minuten', '10' => '10 minuten', '15' => '15 minuten', '30' => '30 minuten']"
            />
        </div>

        {{-- Vooruitboeken --}}
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3">
            <div class="min-w-0">
                <div class="flex items-center gap-1">
                    <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">Vooruitboeken</p>
                    <x-tooltip position="below-right">Hoe ver in de toekomst klanten een afspraak kunnen inplannen.</x-tooltip>
                </div>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Maximale boekingsperiode</p>
            </div>
            <x-select
                class="w-full sm:w-auto"
                wire-target="vooruitboekenMaanden"
                :current="(string) $vooruitboekenMaanden"
                :options="['1' => '1 maand', '2' => '2 maanden', '3' => '3 maanden', '6' => '6 maanden']"
            />
        </div>

        {{-- Annuleringstermijn --}}
        <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4 border-b border-gray-100 dark:border-neutral-700">
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">Annuleringstermijn</p>
                    <x-tooltip position="below-right">Stel in hoe lang van tevoren een klant gratis kan annuleren. Annuleert de klant binnen deze tijd? Dan ziet hij een waarschuwing en — als je annuleringskosten hebt ingesteld — een betaalverzoek. Zonder ingestelde kosten wordt annuleren binnen de termijn geblokkeerd.</x-tooltip>
                </div>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Klant kan niet meer gratis annuleren binnen deze tijd</p>
            </div>
            <x-select
                class="w-full sm:w-auto"
                wire-target="annuleringUren"
                :current="$annuleringUren"
                :options="['' => 'Geen limiet', '1' => '1 uur', '2' => '2 uur', '4' => '4 uur', '8' => '8 uur', '12' => '12 uur', '24' => '24 uur', '48' => '48 uur']"
            />
        </div>

        {{-- Annuleringskosten --}}
        <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400 dark:text-neutral-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">Annuleringskosten</p>
                    <x-tooltip position="below-right">Bedrag dat de klant betaalt bij te-late annulering. De klant ziet een betaalverzoek en moet betalen vóór de afspraak geannuleerd wordt. Heeft de klant een opgeslagen betaalmethode, dan wordt dit automatisch afgeschreven. Anders krijgt hij een Stripe betaalpagina. Laat leeg om te-late annulering volledig te blokkeren.</x-tooltip>
                </div>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Klant betaalt dit bedrag bij laat annuleren (leeg = blokkeren)</p>
            </div>
            <div class="relative w-full sm:w-auto">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-neutral-500 pointer-events-none">€</span>
                <input wire:model="annuleringKosten" type="number" step="0.01" min="0" max="999" placeholder="0.00"
                       class="pl-7 pr-3 py-2 w-full sm:w-28 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-300 dark:placeholder-neutral-600 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
            </div>
        </div>
    </form>

    {{-- Kalender sync --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl mb-6" x-data="{ gekopieerd: false }">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex items-center gap-3">
            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">Kalender synchroniseren</h2>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Bekijk afspraken in Google Calendar of iPhone</p>
            </div>
        </div>

        <div class="px-6 py-5 space-y-4">
            {{-- URL kopiëren --}}
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-neutral-400 mb-2">Je persoonlijke kalender-link</p>
                <div class="flex items-center gap-2">
                    <input type="text" readonly value="{{ $icalUrl }}"
                           class="flex-1 min-w-0 py-2 px-3 bg-gray-50 dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-xs text-gray-600 dark:text-neutral-400 font-mono focus:outline-none cursor-text select-all">
                    <button @click="navigator.clipboard.writeText('{{ $icalUrl }}'); gekopieerd = true; setTimeout(() => gekopieerd = false, 2000)"
                            class="flex-shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                            :class="gekopieerd ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-neutral-700 text-gray-600 dark:text-neutral-300 hover:bg-gray-200 dark:hover:bg-neutral-600'">
                        <svg x-show="!gekopieerd" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <svg x-show="gekopieerd" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="gekopieerd ? 'Gekopieerd!' : 'Kopieer'"></span>
                    </button>
                </div>
            </div>

            {{-- Instructies --}}
            <div class="grid sm:grid-cols-2 gap-3">
                <div class="rounded-xl border border-gray-100 dark:border-neutral-700 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none">
                            <rect width="24" height="24" rx="5" fill="#4285F4"/>
                            <path d="M17 8H7a1 1 0 00-1 1v8a1 1 0 001 1h10a1 1 0 001-1V9a1 1 0 00-1-1z" fill="white"/>
                            <path d="M9 7V5M15 7V5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M6 11h12" stroke="#4285F4" stroke-width="1"/>
                        </svg>
                        <span class="text-xs font-semibold text-gray-700 dark:text-neutral-200">Google Calendar</span>
                    </div>
                    <ol class="space-y-1 text-xs text-gray-500 dark:text-neutral-400 list-decimal list-inside">
                        <li>Open Google Calendar</li>
                        <li>Klik <span class="font-medium text-gray-700 dark:text-neutral-300">+ Andere agenda's</span></li>
                        <li>Kies <span class="font-medium text-gray-700 dark:text-neutral-300">Via URL</span></li>
                        <li>Plak de link hierboven</li>
                    </ol>
                </div>

                <div class="rounded-xl border border-gray-100 dark:border-neutral-700 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none">
                            <rect width="24" height="24" rx="5" fill="#1C1C1E"/>
                            <rect x="4" y="6" width="16" height="14" rx="2" fill="white"/>
                            <path d="M8 5V7M16 5V7" stroke="#1C1C1E" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M4 10h16" stroke="#E5E5EA" stroke-width="1"/>
                        </svg>
                        <span class="text-xs font-semibold text-gray-700 dark:text-neutral-200">iPhone Agenda</span>
                    </div>
                    <ol class="space-y-1 text-xs text-gray-500 dark:text-neutral-400 list-decimal list-inside">
                        <li>Ga naar <span class="font-medium text-gray-700 dark:text-neutral-300">Instellingen → Agenda</span></li>
                        <li>Tik op <span class="font-medium text-gray-700 dark:text-neutral-300">Accounts → Voeg account toe</span></li>
                        <li>Kies <span class="font-medium text-gray-700 dark:text-neutral-300">Overige → Voeg abo. agenda toe</span></li>
                        <li>Plak de link hierboven</li>
                    </ol>
                </div>
            </div>

            <p class="text-xs text-gray-400 dark:text-neutral-500">
                Houd deze link privé. Iedereen met de link kan je afspraken zien.
            </p>
        </div>
    </div>

    {{-- Sluitingsdagen --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">Sluitingsdagen / Vakantie</h2>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Dagen waarop je niet beschikbaar bent</p>
        </div>

        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 overflow-visible">
            <form wire:submit="sluitingsdagToevoegen" class="space-y-3 overflow-visible">
                <div class="flex items-center gap-3 overflow-visible">
                    <span class="text-xs font-medium text-gray-500 dark:text-neutral-400 w-8 flex-shrink-0">Van</span>
                    <div class="flex-1 overflow-visible">
                        <x-datepicker
                            wire-model="sluitingsDatum"
                            :value="$sluitingsDatum"
                            :date-min="today()->toDateString()"
                            placeholder="Startdatum"
                        />
                    </div>
                </div>
                <div class="flex items-center gap-3 overflow-visible">
                    <span class="text-xs font-medium text-gray-500 dark:text-neutral-400 w-8 flex-shrink-0">Tot</span>
                    <div class="flex-1 overflow-visible">
                        <x-datepicker
                            wire-model="sluitingsDatumTot"
                            :value="$sluitingsDatumTot"
                            :date-min="$sluitingsDatum ?: today()->toDateString()"
                            placeholder="Einddatum (optioneel)"
                        />
                    </div>
                </div>
                <input wire:model="sluitingsReden" type="text" placeholder="Reden (optioneel)"
                       class="w-full py-2 px-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Toevoegen
                </button>
            </form>
            @error('sluitingsDatum')
            <p class="text-xs text-red-500 dark:text-red-400 mt-2">{{ $message }}</p>
            @enderror
            @error('sluitingsDatumTot')
            <p class="text-xs text-red-500 dark:text-red-400 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="divide-y divide-gray-50 dark:divide-neutral-700">
            @forelse($sluitingsdagen as $dag)
            <div class="flex items-center gap-3 px-6 py-3.5">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">
                        {{ $dag->datum->isoFormat('D MMM YYYY') }}
                        @if($dag->datum_tot && !$dag->datum->equalTo($dag->datum_tot))
                        – {{ $dag->datum_tot->isoFormat('D MMM YYYY') }}
                        @endif
                    </p>
                    @if($dag->reden)
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $dag->reden }}</p>
                    @endif
                </div>
                <button wire:click="sluitingsdagVerwijderen({{ $dag->id }})"
                        class="flex-shrink-0 p-1.5 rounded-lg text-gray-400 hover:text-red-500 dark:text-neutral-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
            @empty
            <div class="px-6 py-8 text-center">
                <p class="text-sm text-gray-400 dark:text-neutral-500">Geen sluitingsdagen gepland</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
