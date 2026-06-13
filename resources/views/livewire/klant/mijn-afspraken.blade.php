<div>
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Mijn afspraken</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Jouw geplande en eerdere afspraken</p>
    </div>

    {{-- Aankomend --}}
    <div class="mb-8">
        <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Aankomend</p>
        <div class="space-y-3">
            @forelse($aankomend as $afspraak)
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100">
                            {{ $afspraak->datum->isoFormat('dddd D MMMM') }} · {{ $afspraak->start_tijd }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-neutral-300">{{ $afspraak->kapper->salon_naam }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                            {{ $afspraak->dienst->naam }} · {{ $afspraak->betaalmethode === 'online' ? 'Online betaald' : 'Betalen in de zaak' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button wire:click="openVerzetten({{ $afspraak->id }})"
                            class="text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        Verzetten
                    </button>
                    <button @click.prevent="$dispatch('open-confirm', { title: 'Afspraak annuleren', message: 'Weet je zeker dat je de afspraak op {{ $afspraak->datum->isoFormat('D MMM') }} wilt annuleren?', action: () => $wire.annuleer({{ $afspraak->id }}) })"
                            class="text-xs font-medium px-3 py-1.5 rounded-lg border border-red-200 dark:border-red-900 text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        Annuleer
                    </button>
                </div>
            </div>
            @empty
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl px-6 py-10 text-center">
                <svg class="w-10 h-10 text-gray-200 dark:text-neutral-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-gray-400 dark:text-neutral-500">Geen aankomende afspraken</p>
                <a href="{{ route('home') }}" class="mt-3 inline-flex text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                    Zoek een kapper →
                </a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Geschiedenis --}}
    @if($geschiedenis->isNotEmpty())
    <div>
        <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Geschiedenis</p>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-neutral-700">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Datum</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden sm:table-cell">Salon</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden md:table-cell">Dienst</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-neutral-700">
                    @foreach($geschiedenis as $afspraak)
                    @php
                        $badge = match($afspraak->status) {
                            'voltooid'    => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                            'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            default       => 'bg-gray-100 text-gray-500',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-neutral-700/20">
                        <td class="px-5 py-3 text-gray-700 dark:text-neutral-300">
                            {{ $afspraak->datum->format('d-m-Y') }}
                            <span class="text-xs text-gray-400 dark:text-neutral-500 ml-1">{{ $afspraak->start_tijd }}</span>
                        </td>
                        <td class="px-5 py-3 text-gray-500 dark:text-neutral-400 hidden sm:table-cell">{{ $afspraak->kapper->salon_naam }}</td>
                        <td class="px-5 py-3 text-gray-500 dark:text-neutral-400 hidden md:table-cell">{{ $afspraak->dienst->naam }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            @if($afspraak->status === 'voltooid')
                                @if($afspraak->review)
                                <span class="text-xs text-gray-400 dark:text-neutral-500 flex items-center justify-end gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3 h-3 {{ $i <= $afspraak->review->rating ? 'text-amber-400' : 'text-gray-200 dark:text-neutral-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </span>
                                @else
                                <button wire:click="openReview({{ $afspraak->id }})"
                                        class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                    Beoordeel
                                </button>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Verzetten modal --}}
    @if($verzetAfspraakId)
    @php $verzetAfspraak = $aankomend->firstWhere('id', $verzetAfspraakId); @endphp
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="absolute inset-0 bg-black/50" wire:click="sluitVerzetten"></div>
        <div class="relative w-full sm:max-w-sm bg-white dark:bg-neutral-800 sm:rounded-2xl rounded-t-2xl shadow-2xl overflow-hidden">
            <div class="sm:hidden flex justify-center pt-3 pb-1">
                <div class="w-10 h-1 bg-gray-200 dark:bg-neutral-600 rounded-full"></div>
            </div>
            <div class="px-5 pt-4 pb-6">
                @if($verzetGeslaagd)
                <div class="text-center py-4">
                    <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-1">Afspraak verzet!</h3>
                    <p class="text-sm text-gray-500 dark:text-neutral-400 mb-5">
                        {{ \Carbon\Carbon::parse($verzetDatum)->isoFormat('dddd D MMMM') }} om {{ $verzetTijd }}
                    </p>
                    <button wire:click="sluitVerzetten" class="w-full py-2.5 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                        Sluiten
                    </button>
                </div>
                @else
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Afspraak verzetten</h3>
                        @if($verzetAfspraak)
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $verzetAfspraak->kapper->salon_naam }} · {{ $verzetAfspraak->dienst->naam }}</p>
                        @endif
                    </div>
                    <button wire:click="sluitVerzetten" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Datum --}}
                <div class="mb-4 overflow-visible">
                    <label class="block text-xs font-medium text-gray-500 dark:text-neutral-400 mb-1.5">Nieuwe datum</label>
                    <x-datepicker
                        wire-model="verzetDatum"
                        :value="$verzetDatum"
                        :date-min="today()->toDateString()"
                        placeholder="Selecteer datum"
                    />
                </div>

                {{-- Tijdsloten --}}
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-500 dark:text-neutral-400 mb-1.5">Nieuw tijdstip</label>
                    <div wire:loading.delay wire:target="verzetDatum" class="text-xs text-gray-400 dark:text-neutral-500 py-2">Beschikbaarheid laden...</div>
                    @if(count($verzetTijdsloten) > 0)
                    <div class="grid grid-cols-3 gap-1.5 max-h-40 overflow-y-auto">
                        @foreach($verzetTijdsloten as $slot)
                        <button wire:click="$set('verzetTijd', '{{ $slot }}')" type="button"
                                class="py-2 rounded-lg border text-xs font-medium transition-colors
                                    {{ $verzetTijd === $slot
                                        ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'
                                        : 'border-gray-200 dark:border-neutral-700 text-gray-700 dark:text-neutral-300 hover:border-blue-400 hover:bg-blue-50/50' }}">
                            {{ $slot }}
                        </button>
                        @endforeach
                    </div>
                    @elseif($verzetDatum)
                    <p class="text-xs text-gray-400 dark:text-neutral-500 py-2">Geen vrije tijdsloten op deze dag.</p>
                    @else
                    <p class="text-xs text-gray-400 dark:text-neutral-500 py-2">Kies eerst een datum.</p>
                    @endif
                </div>

                <div class="flex gap-2">
                    <button wire:click="sluitVerzetten" class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        Annuleer
                    </button>
                    <button wire:click="bevestigVerzetten"
                            @if(!$verzetTijd) disabled @endif
                            class="flex-1 py-2.5 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        Bevestigen
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Review modal --}}
    @if($reviewAfspraakId)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="absolute inset-0 bg-black/50" wire:click="$set('reviewAfspraakId', null)"></div>
        <div class="relative w-full sm:max-w-md bg-white dark:bg-neutral-800 sm:rounded-2xl rounded-t-2xl shadow-2xl overflow-hidden">
            <div class="sm:hidden flex justify-center pt-3 pb-1">
                <div class="w-10 h-1 bg-gray-200 dark:bg-neutral-600 rounded-full"></div>
            </div>
            <div class="px-5 pt-4 pb-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Beoordeling achterlaten</h3>
                    <button wire:click="$set('reviewAfspraakId', null)" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Sterren --}}
                <div class="flex justify-center gap-2 mb-5" x-data>
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" wire:click="$set('reviewRating', {{ $i }})"
                            class="transition-transform hover:scale-110 focus:outline-none">
                        <svg class="w-9 h-9 {{ $reviewRating >= $i ? 'text-amber-400' : 'text-gray-200 dark:text-neutral-600' }} transition-colors"
                             fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>
                    @endfor
                </div>
                @error('reviewRating') <p class="text-xs text-red-500 text-center -mt-3 mb-3">{{ $message }}</p> @enderror

                <div class="mb-4">
                    <textarea wire:model="reviewTekst" rows="3"
                              placeholder="Vertel iets over je ervaring... (optioneel)"
                              class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 resize-none"></textarea>
                </div>

                <div class="flex gap-2">
                    <button wire:click="$set('reviewAfspraakId', null)"
                            class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        Annuleer
                    </button>
                    <button wire:click="submitReview"
                            class="flex-1 py-2.5 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                        Verstuur
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
