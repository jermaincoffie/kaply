<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Diensten</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Beheer je diensten, prijzen en no-show bedragen</p>
        </div>
    </div>

    {{-- Formulier --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">
                {{ $bewerkenId ? 'Dienst bewerken' : 'Nieuwe dienst' }}
            </h2>
            @if($bewerkenId)
            <button wire:click="$set('bewerkenId', null)" class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 transition-colors">
                Annuleer
            </button>
            @endif
        </div>
        <form wire:submit="opslaan" class="px-6 py-5">
            <div class="grid grid-cols-2 md:grid-cols-6 gap-3 items-end">
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-neutral-400 mb-1">Naam</label>
                    <input wire:model="naam" type="text" placeholder="bijv. Knippen"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('naam') <p class="text-xs text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-neutral-400 mb-1">Duur (min)</label>
                    <input wire:model="duur_minuten" type="number" min="5" placeholder="30"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('duur_minuten') <p class="text-xs text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-neutral-400 mb-1">Prijs (€)</label>
                    <input wire:model="prijs" type="text" placeholder="15.00"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('prijs') <p class="text-xs text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-neutral-400 mb-1 flex items-center gap-1">
                        No-show (€)
                        <x-tooltip>Bedrag dat wordt afgeschreven als klant niet verschijnt.</x-tooltip>
                    </label>
                    <input wire:model="no_show_bedrag" type="text" placeholder="5.00"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('no_show_bedrag') <p class="text-xs text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 py-2 px-3 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $bewerkenId ? 'M5 13l4 4L19 7' : 'M12 4v16m8-8H4' }}"/>
                        </svg>
                        {{ $bewerkenId ? 'Bijwerken' : 'Toevoegen' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Diensten --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">

        {{-- Mobiel: cards --}}
        <div class="sm:hidden divide-y divide-gray-50 dark:divide-neutral-700">
            @forelse($diensten as $dienst)
            <div class="px-4 py-3 {{ $bewerkenId === $dienst->id ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-100">{{ $dienst->naam }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $dienst->duur_minuten }} min · € {{ $dienst->prijs_in_euros }} · no-show: € {{ $dienst->no_show_bedrag_in_euros }}</p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <button wire:click="bewerk({{ $dienst->id }})"
                                class="text-xs font-medium text-blue-600 dark:text-blue-400">Bewerk</button>
                        <button @click.prevent="$dispatch('open-confirm', { title: 'Dienst verwijderen', message: 'Weet je zeker dat je \'{{ addslashes($dienst->naam) }}\' wilt verwijderen?', action: () => $wire.verwijder({{ $dienst->id }}) })"
                                class="text-xs font-medium text-red-500 dark:text-red-400">Verwijder</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-4 py-12 text-center text-sm text-gray-400 dark:text-neutral-500">
                Nog geen diensten — voeg er een toe via het formulier hierboven.
            </div>
            @endforelse
        </div>

        {{-- Desktop: tabel --}}
        <table class="hidden sm:table w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-neutral-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Naam</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Duur</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Prijs</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden md:table-cell">No-show</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-neutral-700">
                @forelse($diensten as $dienst)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-neutral-700/20 {{ $bewerkenId === $dienst->id ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                    <td class="px-6 py-3.5 font-medium text-gray-800 dark:text-neutral-100">{{ $dienst->naam }}</td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400">{{ $dienst->duur_minuten }} min</td>
                    <td class="px-6 py-3.5 text-gray-700 dark:text-neutral-300 font-medium">€ {{ $dienst->prijs_in_euros }}</td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400 hidden md:table-cell">€ {{ $dienst->no_show_bedrag_in_euros }}</td>
                    <td class="px-6 py-3.5 text-right space-x-3">
                        <button wire:click="bewerk({{ $dienst->id }})"
                                class="text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                            Bewerk
                        </button>
                        <button @click.prevent="$dispatch('open-confirm', { title: 'Dienst verwijderen', message: 'Weet je zeker dat je \'{{ addslashes($dienst->naam) }}\' wilt verwijderen?', action: () => $wire.verwijder({{ $dienst->id }}) })"
                                class="text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                            Verwijder
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-neutral-500">
                        Nog geen diensten — voeg er een toe via het formulier hierboven.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
