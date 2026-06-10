<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-2">Afspraak boeken</h1>
    <p class="text-gray-600 mb-6">
        {{ $kapper->salon_naam }} — {{ $dienst->naam }}
        ({{ $dienst->duur_minuten }} min, € {{ $dienst->prijs_in_euros }})
    </p>

    <form wire:submit="bevestig" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Kies een datum</label>
            <x-datepicker
                wire-model="gekozenDatum"
                :value="$gekozenDatum"
                :date-min="today()->addDay()->toDateString()"
                placeholder="Selecteer datum"
            />
            @error('gekozenDatum') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        @if($gekozenDatum)
        <div>
            <label class="block font-medium mb-2">Kies een tijdstip</label>
            @if(count($vrijeslots) === 0)
                <p class="text-gray-500">Geen beschikbare tijdsloten op deze datum.</p>
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
