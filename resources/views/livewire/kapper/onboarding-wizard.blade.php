<div>
    {{-- Stap 1: Welkom --}}
    @if($stap === 1)
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl p-8 text-center">
        <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mx-auto mb-5">
            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

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
                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">Diensten toevoegen</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">Wat bied je aan en voor welke prijs?</p>
                </div>
            </div>
            <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 dark:bg-neutral-700/50 rounded-xl">
                <div class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">2</div>
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">Beschikbaarheid instellen</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">Op welke dagen en tijden werk je?</p>
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

    {{-- Stap 2: Diensten --}}
    @if($stap === 2)
    <div>
        {{-- Progress --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">1</div>
                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Diensten</span>
            </div>
            <div class="flex-1 h-px bg-gray-200 dark:bg-neutral-700"></div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-neutral-700 text-gray-400 flex items-center justify-center text-xs font-bold">2</div>
                <span class="text-sm text-gray-400 dark:text-neutral-500">Beschikbaarheid</span>
            </div>
        </div>

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

        <button wire:click="naarStap(3)" type="button"
                class="w-full py-3 px-6 rounded-xl {{ $diensten->isEmpty() ? 'bg-gray-300 dark:bg-neutral-700 text-gray-500 dark:text-neutral-500 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700 cursor-pointer' }} text-sm font-semibold transition-colors">
            Volgende: Beschikbaarheid
            <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
    @endif

    {{-- Stap 3: Beschikbaarheid --}}
    @if($stap === 3)
    <div>
        {{-- Progress --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="text-sm text-gray-400 dark:text-neutral-500">Diensten</span>
            </div>
            <div class="flex-1 h-px bg-blue-600"></div>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">2</div>
                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Beschikbaarheid</span>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl overflow-hidden mb-4">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Op welke dagen werk je?</h2>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Je kunt dit later altijd aanpassen</p>
            </div>

            {{-- Buffer --}}
            <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-neutral-300">Buffer na afspraak</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500">Vrije tijd na elke afspraak</p>
                </div>
                <x-select
                    wire-target="bufferMinuten"
                    :current="(string) $bufferMinuten"
                    :options="['0' => 'Geen buffer', '5' => '5 min', '10' => '10 min', '15' => '15 min', '30' => '30 min']"
                />
            </div>

            <div class="divide-y divide-gray-50 dark:divide-neutral-700">
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
        </div>

        <div class="flex gap-3">
            <button wire:click="naarStap(2)" type="button"
                    class="px-4 py-3 rounded-xl text-sm font-medium text-gray-500 dark:text-neutral-400 hover:text-gray-700 dark:hover:text-neutral-200 transition-colors">
                ← Terug
            </button>
            <button wire:click="afronden" type="button"
                    class="flex-1 py-3 px-6 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
                Klaar — open mijn salon! 🎉
            </button>
        </div>
    </div>
    @endif
</div>
