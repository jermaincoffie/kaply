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
    <form wire:submit="opslaan" class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden mb-6">
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

        <div class="divide-y divide-gray-50 dark:divide-neutral-700">
            @foreach($rooster as $dag => $data)
            <div class="flex items-center gap-4 px-6 py-3.5">
                <label class="flex items-center gap-3 w-32 cursor-pointer flex-shrink-0">
                    <input wire:model.live="rooster.{{ $dag }}.actief" type="checkbox"
                           class="rounded border-gray-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500 dark:bg-neutral-700">
                    <span class="text-sm font-medium text-gray-700 dark:text-neutral-300">{{ $data['naam'] }}</span>
                </label>

                @if($data['actief'])
                <div class="flex items-center gap-2">
                    <input wire:model="rooster.{{ $dag }}.start_tijd" type="time"
                           class="py-1.5 px-2.5 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    <span class="text-xs text-gray-400 dark:text-neutral-500">tot</span>
                    <input wire:model="rooster.{{ $dag }}.eind_tijd" type="time"
                           class="py-1.5 px-2.5 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>
                @else
                <span class="text-xs text-gray-400 dark:text-neutral-500">Gesloten</span>
                @endif
            </div>
            @endforeach
        </div>
    </form>

    {{-- Sluitingsdagen --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">Sluitingsdagen / Vakantie</h2>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Dagen waarop je niet beschikbaar bent</p>
        </div>

        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 overflow-visible">
            <form wire:submit="sluitingsdagToevoegen" class="space-y-3 overflow-visible">
                <div class="flex flex-wrap gap-3 items-center overflow-visible">
                    <div class="flex items-center gap-2 overflow-visible">
                        <span class="text-xs font-medium text-gray-500 dark:text-neutral-400 whitespace-nowrap">Van</span>
                        <x-datepicker
                            wire-model="sluitingsDatum"
                            :value="$sluitingsDatum"
                            :date-min="today()->toDateString()"
                            placeholder="Startdatum"
                        />
                    </div>
                    <div class="flex items-center gap-2 overflow-visible">
                        <span class="text-xs font-medium text-gray-500 dark:text-neutral-400 whitespace-nowrap">Tot</span>
                        <x-datepicker
                            wire-model="sluitingsDatumTot"
                            :value="$sluitingsDatumTot"
                            :date-min="$sluitingsDatum ?: today()->toDateString()"
                            placeholder="Einddatum (optioneel)"
                        />
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <input wire:model="sluitingsReden" type="text" placeholder="Reden (optioneel)"
                           class="flex-1 min-w-40 py-2 px-3 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg text-sm text-gray-800 dark:text-neutral-200 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Toevoegen
                    </button>
                </div>
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
            <div class="flex items-center justify-between px-6 py-3.5">
                <div>
                    <span class="text-sm font-medium text-gray-700 dark:text-neutral-300">
                        {{ $dag->datum->isoFormat('D MMM YYYY') }}
                        @if($dag->datum_tot && !$dag->datum->equalTo($dag->datum_tot))
                        <span class="text-gray-400 dark:text-neutral-500 font-normal"> – {{ $dag->datum_tot->isoFormat('D MMM YYYY') }}</span>
                        @endif
                    </span>
                    @if($dag->reden)
                    <span class="text-xs text-gray-400 dark:text-neutral-500 ml-2">— {{ $dag->reden }}</span>
                    @endif
                </div>
                <button wire:click="sluitingsdagVerwijderen({{ $dag->id }})"
                        class="text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                    Verwijder
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
