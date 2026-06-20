<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-2">Afspraak boeken</h1>
    <p class="text-gray-600 mb-6">
        {{ $kapper->salon_naam }} — {{ $dienst->naam }}
        ({{ $dienst->duur_minuten }} min)
    </p>

    <form wire:submit="bevestig" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Kies een datum</label>
            <x-datepicker
                wire-model="gekozenDatum"
                :value="$gekozenDatum"
                :date-min="today()->addDay()->toDateString()"
                :date-max="$maxDatum"
                placeholder="Selecteer datum"
            />
            @error('gekozenDatum') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        @if($gekozenDatum)
        <div>
            <label class="block font-medium mb-2">Kies een tijdstip</label>
            @if(count($vrijeslots) === 0)
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 text-center">
                    <p class="text-sm font-medium text-gray-700 mb-1">Geen beschikbare tijdsloten op deze datum.</p>
                    <p class="text-xs text-gray-400 mb-4">Kies een andere dag of schrijf je in op de wachtlijst.</p>

                    @if($wachtlijstVerstuurd)
                        <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Je staat op de wachtlijst! We mailen je zodra er een plek vrijkomt.
                        </div>
                    @elseif($toonWachtlijstForm)
                        <div class="text-left space-y-3 mt-2">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Naam</label>
                                <input wire:model="wachtlijstNaam" type="text" placeholder="Jouw naam"
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                                @error('wachtlijstNaam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">E-mailadres</label>
                                <input wire:model="wachtlijstEmail" type="email" placeholder="jouw@email.nl"
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                                @error('wachtlijstEmail') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Telefoonnummer <span class="font-normal text-gray-400">(optioneel, voor snelle oproep)</span></label>
                                <input wire:model="wachtlijstTelefoon" type="tel" placeholder="06 12345678"
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                            </div>
                            @if($wachtlijstFout)
                                <p class="text-xs text-red-500">{{ $wachtlijstFout }}</p>
                            @endif
                            <div class="flex gap-2 pt-1">
                                <button type="button" wire:click="$set('toonWachtlijstForm', false)"
                                        class="flex-1 py-2 text-sm rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
                                    Annuleer
                                </button>
                                <button type="button" wire:click="wachtlijstAanmelden"
                                        class="flex-1 py-2 text-sm font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors">
                                    Inschrijven
                                </button>
                            </div>
                        </div>
                    @else
                        <button type="button" wire:click="$set('toonWachtlijstForm', true)"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Zet me op de wachtlijst
                        </button>
                    @endif
                </div>
            @else
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                @foreach($vrijeslots as $slot)
                <button type="button"
                    wire:click="$set('gekozenTijdslot', '{{ $slot }}')"
                    class="py-2 rounded text-sm font-medium border {{ $gekozenTijdslot === $slot ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 hover:border-indigo-400' }}">
                    {{ $slot }}
                </button>
                @endforeach
            </div>
            @error('gekozenTijdslot') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            @endif
        </div>
        @endif

        @if($gekozenTijdslot)
        {{-- Kortingscode --}}
        <div>
            <label class="block font-medium mb-2">Kortingscode</label>
            @if($toegepasdeCodeId)
            <div class="flex items-center gap-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <svg class="w-4 h-4 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-sm font-medium text-green-700 dark:text-green-400 flex-1">
                    <span class="font-mono">{{ $kortingscodeInput }}</span> — {{ $kortingLabel }}
                </span>
                <button type="button" wire:click="kortingscodeVerwijderen"
                        class="text-xs text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200 underline">
                    Verwijder
                </button>
            </div>
            @else
            <div class="flex gap-2">
                <input wire:model="kortingscodeInput" type="text" placeholder="Voer je kortingscode in"
                       class="flex-1 py-2 px-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 uppercase">
                <button type="button" wire:click="kortingscodeToepassen"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Toepassen
                </button>
            </div>
            @if($kortingFout)
            <p class="text-red-500 text-sm mt-1">{{ $kortingFout }}</p>
            @endif
            @endif
        </div>

        {{-- Prijsoverzicht --}}
        <div class="bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg p-4 space-y-2">
            <div class="flex justify-between text-sm text-gray-600 dark:text-neutral-400">
                <span>{{ $dienst->naam }}</span>
                <span>€ {{ $dienst->prijs_in_euros }}</span>
            </div>
            @if($kortingBedrag > 0)
            <div class="flex justify-between text-sm text-green-600 dark:text-green-400">
                <span>Korting ({{ $kortingscodeInput }})</span>
                <span>− € {{ number_format($kortingBedrag / 100, 2, ',', '.') }}</span>
            </div>
            <div class="border-t border-gray-200 dark:border-neutral-700 pt-2 flex justify-between font-semibold text-gray-800 dark:text-neutral-100">
                <span>Totaal</span>
                <span>€ {{ number_format($teBetalenCenten / 100, 2, ',', '.') }}</span>
            </div>
            @else
            <div class="border-t border-gray-200 dark:border-neutral-700 pt-2 flex justify-between font-semibold text-gray-800 dark:text-neutral-100">
                <span>Totaal</span>
                <span>€ {{ $dienst->prijs_in_euros }}</span>
            </div>
            @endif
        </div>

        <div>
            <label class="block font-medium mb-2">Betaalmethode</label>
            <div class="flex gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model="betaalmethode" type="radio" value="in_zaak" class="rounded">
                    In de zaak betalen
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model="betaalmethode" type="radio" value="online" class="rounded">
                    Online betalen
                </label>
            </div>
        </div>

        <button type="submit"
            class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700">
            Afspraak bevestigen
        </button>
        @endif
    </form>
</div>
