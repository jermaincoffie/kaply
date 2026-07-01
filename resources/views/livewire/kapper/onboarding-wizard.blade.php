<div>
@php
    $progressSteps = [2 => 'Profiel', 3 => 'Diensten', 4 => 'Beschikbaarheid', 5 => 'Media', 6 => 'Betalen'];
@endphp

{{-- Progress bar (stap 2 t/m 5) --}}
@if($stap > 1)
<div class="flex items-center mb-6">
    @foreach($progressSteps as $s => $label)
        @if(!$loop->first)
        <div class="flex-1 h-px mx-1 {{ $stap >= $s ? 'bg-blue-600' : 'bg-gray-200 dark:bg-neutral-700' }}"></div>
        @endif
        <div class="flex items-center gap-1.5 flex-shrink-0">
            @if($stap > $s)
            <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            @elseif($stap === $s)
            <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $loop->iteration }}</div>
            @else
            <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-neutral-700 text-gray-400 dark:text-neutral-500 flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $loop->iteration }}</div>
            @endif
            <span class="hidden sm:inline text-xs {{ $stap === $s ? 'font-medium text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-neutral-500' }}">{{ $label }}</span>
        </div>
    @endforeach
</div>
@endif

{{-- Stap 1: Welkom --}}
@if($stap === 1)
<div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-8 text-center">
    <h1 class="text-xl font-semibold text-gray-800 dark:text-neutral-100 mb-2">
        Welkom bij Kaply, {{ auth()->user()->name }}!
    </h1>
    <p class="text-sm text-gray-500 dark:text-neutral-400 mb-8 max-w-sm mx-auto">
        Laten we jouw salon instellen zodat klanten meteen een afspraak kunnen boeken. Dit duurt ongeveer 2 minuten.
    </p>

    <div class="space-y-3 text-left mb-8">
        <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 dark:bg-neutral-700/50 rounded-xl">
            <div class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">1</div>
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">Salonprofiel & locatie</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500">Naam, beschrijving en adres</p>
            </div>
        </div>
        <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 dark:bg-neutral-700/50 rounded-xl">
            <div class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">2</div>
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">Diensten toevoegen</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500">Wat bied je aan en voor welke prijs?</p>
            </div>
        </div>
        <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 dark:bg-neutral-700/50 rounded-xl">
            <div class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">3</div>
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">Beschikbaarheid instellen</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500">Op welke dagen en tijden werk je?</p>
            </div>
        </div>
        <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 dark:bg-neutral-700/50 rounded-xl">
            <div class="w-7 h-7 rounded-full bg-gray-300 dark:bg-neutral-600 text-gray-500 dark:text-neutral-400 flex items-center justify-center text-xs font-bold flex-shrink-0">4</div>
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">Foto's toevoegen <span class="text-xs font-normal text-gray-400 dark:text-neutral-500">— optioneel</span></p>
                <p class="text-xs text-gray-400 dark:text-neutral-500">Profielfoto en galerij</p>
            </div>
        </div>
        <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 dark:bg-neutral-700/50 rounded-xl">
            <div class="w-7 h-7 rounded-full bg-gray-300 dark:bg-neutral-600 text-gray-500 dark:text-neutral-400 flex items-center justify-center text-xs font-bold flex-shrink-0">5</div>
            <div>
                <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">Online betalingen <span class="text-xs font-normal text-gray-400 dark:text-neutral-500">— optioneel</span></p>
                <p class="text-xs text-gray-400 dark:text-neutral-500">Koppel Stripe zodat klanten online kunnen betalen</p>
            </div>
        </div>
    </div>

    <button wire:click="naarStap(2)"
            class="w-full py-3 px-6 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
        Begin setup
        <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
</div>
@endif

{{-- Stap 2: Profiel + Locatie --}}
@if($stap === 2)
<div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
        <h2 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Salonprofiel & locatie</h2>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Dit zien klanten op jouw profielpagina</p>
    </div>

    <div class="px-6 py-5 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Salonnaam <span class="text-red-500">*</span></label>
            <input wire:model="salonNaam" type="text" placeholder="Bijv. Kapper Jan"
                   class="w-full py-2 px-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
            @error('salonNaam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Stad <span class="text-red-500">*</span></label>
                <input wire:model="stad" type="text" placeholder="Amsterdam"
                       class="w-full py-2 px-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                @error('stad') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Adres</label>
                <input wire:model="adres" type="text" placeholder="Straat 12"
                       class="w-full py-2 px-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                @error('adres') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Telefoonnummer <span class="text-gray-400 font-normal text-xs">— optioneel</span></label>
            <input wire:model="telefoon" type="tel" placeholder="+31 6 12345678"
                   class="w-full py-2 px-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
            @error('telefoon') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Over jouw salon <span class="text-gray-400 font-normal text-xs">— optioneel</span></label>
            <textarea wire:model="bio" rows="3" placeholder="Vertel klanten iets over jouw salon..."
                      class="w-full py-2 px-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 resize-none"></textarea>
            @error('bio') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="px-6 pb-6">
        <button wire:click="naarStap(3)"
                class="w-full py-3 px-6 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
            Volgende: Diensten
            <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>
@endif

{{-- Stap 3: Diensten --}}
@if($stap === 3)
<div>
    {{-- Form nieuwe dienst --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-6 mb-4">
        <h2 class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-4">Dienst toevoegen</h2>

        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Naam dienst</label>
                <input wire:model="dienstNaam" type="text" placeholder="Bijv. Knippen heren"
                       class="w-full py-2 px-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                @error('dienstNaam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Duur</label>
                    <x-select
                        wire-target="dienstDuur"
                        :current="$dienstDuur"
                        :options="['15' => '15 min', '20' => '20 min', '30' => '30 min', '45' => '45 min', '60' => '60 min', '75' => '75 min', '90' => '90 min', '120' => '2 uur']"
                    />
                    @error('dienstDuur') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Prijs</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-neutral-500 pointer-events-none">€</span>
                        <input wire:model="dienstPrijs" type="text" placeholder="25"
                               class="w-full py-2 pl-7 pr-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    </div>
                    @error('dienstPrijs') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
                    No-show bedrag
                    <span class="text-gray-400 font-normal text-xs">— optioneel</span>
                </label>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mb-1">Bedrag dat klant betaalt bij no-show (0 = uitgeschakeld)</p>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-neutral-500 pointer-events-none">€</span>
                    <input wire:model="dienstNoShowBedrag" type="text" placeholder="0"
                           class="w-full py-2 pl-7 pr-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>
                @error('dienstNoShowBedrag') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <button wire:click="dienstToevoegen" type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 dark:bg-neutral-700 text-gray-700 dark:text-neutral-200 hover:bg-gray-200 dark:hover:bg-neutral-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Toevoegen
            </button>
        </div>
    </div>

    {{-- Toegevoegde diensten --}}
    @if($diensten->isNotEmpty())
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl overflow-hidden mb-4">
        <div class="px-5 py-3.5 border-b border-gray-100 dark:border-neutral-700">
            <p class="text-xs font-semibold text-gray-500 dark:text-neutral-400 uppercase tracking-wide">Toegevoegde diensten</p>
        </div>
        <div class="divide-y divide-gray-50 dark:divide-neutral-700">
            @foreach($diensten as $dienst)
            <div class="flex items-center justify-between px-5 py-3">
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $dienst->naam }}</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $dienst->duur_minuten }} min · € {{ $dienst->prijs_in_euros }}</p>
                </div>
                <button wire:click="dienstVerwijderen({{ $dienst->id }})" type="button"
                        class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                    Verwijder
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @error('dienst')
    <p class="text-sm text-red-500 dark:text-red-400 mb-4">{{ $message }}</p>
    @enderror

    <div class="flex gap-3">
        <button wire:click="naarStap(2)" type="button"
                class="px-4 py-3 rounded-xl text-sm font-medium text-gray-500 dark:text-neutral-400 hover:text-gray-700 dark:hover:text-neutral-200 transition-colors">
            ← Terug
        </button>
        <button wire:click="naarStap(4)" type="button"
                class="flex-1 py-3 px-6 rounded-xl {{ $diensten->isEmpty() ? 'bg-gray-300 dark:bg-neutral-700 text-gray-500 dark:text-neutral-500 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700 cursor-pointer' }} text-sm font-semibold transition-colors">
            Volgende: Beschikbaarheid
            <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>
@endif

{{-- Stap 4: Beschikbaarheid --}}
@if($stap === 4)
<div>
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl mb-4">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
            <h2 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Op welke dagen werk je?</h2>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Je kunt dit later altijd aanpassen</p>
        </div>

        {{-- Dagen --}}
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

        {{-- Buffer --}}
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">Buffer tijd</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Pauze na elke afspraak</p>
            </div>
            <x-select
                class="w-full sm:w-auto"
                wire-target="bufferMinuten"
                :current="(string) $bufferMinuten"
                :options="['0' => 'Geen buffer', '5' => '5 minuten', '10' => '10 minuten', '15' => '15 minuten', '30' => '30 minuten']"
            />
        </div>

        {{-- Vooruit boeken --}}
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">Vooruitboeken</p>
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
                <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">Annuleringstermijn</p>
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
                <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">Annuleringskosten</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Klant betaalt dit bedrag bij annuleren binnen de termijn (leeg = gratis)</p>
            </div>
            <div class="relative w-full sm:w-auto">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 dark:text-neutral-500 pointer-events-none">€</span>
                <input wire:model="annuleringKosten" type="number" step="0.01" min="0" max="999" placeholder="0.00"
                       class="pl-7 pr-3 py-2 w-full sm:w-28 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-300 dark:placeholder-neutral-600 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button wire:click="naarStap(3)" type="button"
                class="px-4 py-3 rounded-xl text-sm font-medium text-gray-500 dark:text-neutral-400 hover:text-gray-700 dark:hover:text-neutral-200 transition-colors">
            ← Terug
        </button>
        <button wire:click="naarStap(5)" type="button"
                class="flex-1 py-3 px-6 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
            Volgende: Foto's
            <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>
@endif

{{-- Stap 6: Stripe Connect (optioneel) --}}
@if($stap === 6)
<div>
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl mb-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
            <h2 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Online betalingen instellen</h2>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Optioneel — je kunt dit ook later doen via Instellingen → Profiel</p>
        </div>

        <div class="px-6 py-5">
            <div class="flex items-start gap-4 mb-5">
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-200 mb-1">Betaling direct naar jouw rekening</p>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">Verbind je eigen Stripe account zodat klanten online betalen en het geld direct naar jou gaat. Heb je al een Stripe account? Dan duurt dit minder dan 5 minuten.</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 mb-2">
                <div class="text-center px-3 py-3 bg-gray-50 dark:bg-neutral-700/50 rounded-xl">
                    <p class="text-xs font-semibold text-gray-700 dark:text-neutral-300 mb-0.5">Direct uitbetaald</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">Geld naar jouw bankrekening</p>
                </div>
                <div class="text-center px-3 py-3 bg-gray-50 dark:bg-neutral-700/50 rounded-xl">
                    <p class="text-xs font-semibold text-gray-700 dark:text-neutral-300 mb-0.5">Eigen dashboard</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">Beheer via Stripe zelf</p>
                </div>
                <div class="text-center px-3 py-3 bg-gray-50 dark:bg-neutral-700/50 rounded-xl">
                    <p class="text-xs font-semibold text-gray-700 dark:text-neutral-300 mb-0.5">Geen verplicht</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">Klanten kunnen ook ter plaatse betalen</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button wire:click="naarStap(5)" type="button"
                class="px-4 py-3 rounded-xl text-sm font-medium text-gray-500 dark:text-neutral-400 hover:text-gray-700 dark:hover:text-neutral-200 transition-colors">
            ← Terug
        </button>
        <button wire:click="afronden" type="button"
                class="py-3 px-5 rounded-xl text-sm font-medium text-gray-500 dark:text-neutral-400 border border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
            Overslaan
        </button>
        <button wire:click="voltooienEnStripe" type="button"
                wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                class="flex-1 py-3 px-6 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
            <span wire:loading.remove wire:target="voltooienEnStripe">Stripe account verbinden →</span>
            <span wire:loading wire:target="voltooienEnStripe">Doorsturen...</span>
        </button>
    </div>
</div>
@endif

{{-- Stap 5: Media (optioneel) --}}
@if($stap === 5)
<div>
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl mb-4">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
            <h2 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Foto's toevoegen</h2>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Optioneel — je kunt dit ook later doen</p>
        </div>

        <div class="px-6 py-5 space-y-5">
            {{-- Profielfoto --}}
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
                    Profielfoto
                    <x-tooltip position="below-left">
                        Zichtbaar naast jouw naam in de zoekresultaten en bovenaan jouw profielpagina. Upload een nette foto van jezelf of je salon — klanten boeken sneller bij een herkenbaar gezicht.
                    </x-tooltip>
                </label>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mb-2">Max 2MB · JPG, PNG, WebP</p>
                <input wire:model="foto" type="file" accept="image/*"
                       class="block w-full text-sm text-gray-500 dark:text-neutral-400
                              file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                              file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700
                              dark:file:bg-neutral-700 dark:file:text-neutral-200
                              hover:file:bg-gray-200 dark:hover:file:bg-neutral-600 file:cursor-pointer file:transition-colors">
                @error('foto') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @if($foto)
                <div class="mt-3">
                    <img src="{{ $foto->temporaryUrl() }}" class="w-20 h-20 rounded-xl object-cover border border-gray-200 dark:border-neutral-700">
                </div>
                @endif
            </div>

            {{-- Galerij --}}
            <div>
                <label class="flex items-center text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
                    Galerij
                    <x-tooltip position="below-left">
                        Klanten zien deze foto's op jouw profielpagina als een grid. Upload foto's van je werk — kapsels, de salon of sfeerbeelden. Goede galerij = meer boekingen.
                    </x-tooltip>
                </label>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mb-2">Meerdere foto's mogelijk · Max 4MB per foto · Max 12 foto's</p>
                <input wire:model="galerijFotos" type="file" accept="image/*" multiple
                       class="block w-full text-sm text-gray-500 dark:text-neutral-400
                              file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                              file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700
                              dark:file:bg-neutral-700 dark:file:text-neutral-200
                              hover:file:bg-gray-200 dark:hover:file:bg-neutral-600 file:cursor-pointer file:transition-colors">
                @error('galerijFotos') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @error('galerijFotos.*') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @if(!empty($galerijFotos))
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($galerijFotos as $gFoto)
                    <img src="{{ $gFoto->temporaryUrl() }}" class="w-16 h-16 rounded-lg object-cover border border-gray-200 dark:border-neutral-700">
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button wire:click="naarStap(4)" type="button"
                class="px-4 py-3 rounded-xl text-sm font-medium text-gray-500 dark:text-neutral-400 hover:text-gray-700 dark:hover:text-neutral-200 transition-colors">
            ← Terug
        </button>
        <button wire:click="naarStap(6)" type="button"
                class="py-3 px-5 rounded-xl text-sm font-medium text-gray-500 dark:text-neutral-400 border border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
            Overslaan
        </button>
        <button wire:click="slaMediaOpEnNaarStap6" type="button"
                wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                class="flex-1 py-3 px-6 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
            <span wire:loading.remove wire:target="slaMediaOpEnNaarStap6">Opslaan & volgende</span>
            <span wire:loading wire:target="slaMediaOpEnNaarStap6">Uploaden...</span>
        </button>
    </div>
</div>
@endif

</div>
