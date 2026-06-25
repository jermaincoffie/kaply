<div>
    @if($annuleerFout)
    <div class="mb-4 flex items-start gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl px-4 py-3">
        <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <p class="text-xs text-red-700 dark:text-red-400">{{ $annuleerFout }}</p>
    </div>
    @endif

    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Mijn afspraken</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Jouw geplande en eerdere afspraken</p>
    </div>

    {{-- Favoriete kappers --}}
    @if($favorieteKappers->isNotEmpty())
    <div class="mb-8">
        <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Favoriete kappers</p>
        <div class="flex gap-3 overflow-x-auto pb-1 scrollbar-hide">
            @foreach($favorieteKappers as $favKapper)
            <a href="{{ route('kapper.profiel', $favKapper->slug) }}"
               class="flex-shrink-0 flex flex-col items-center gap-1.5 w-20 group">
                @if($favKapper->foto)
                <img src="{{ asset('public/storage/' . $favKapper->foto) }}" alt="{{ $favKapper->salon_naam }}"
                     class="w-14 h-14 rounded-xl object-cover border-2 border-transparent group-hover:border-blue-500 transition-colors">
                @else
                @php
                    $kleuren = ['bg-blue-100 text-blue-700','bg-emerald-100 text-emerald-700','bg-violet-100 text-violet-700','bg-rose-100 text-rose-700','bg-amber-100 text-amber-700'];
                    $kleur   = $kleuren[abs(crc32($favKapper->salon_naam)) % count($kleuren)];
                @endphp
                <div class="w-14 h-14 rounded-xl flex items-center justify-center border-2 border-transparent group-hover:border-blue-500 transition-colors {{ $kleur }}">
                    <span class="text-lg font-bold">{{ mb_strtoupper(mb_substr($favKapper->salon_naam, 0, 1)) }}</span>
                </div>
                @endif
                <span class="text-xs text-gray-600 dark:text-neutral-400 text-center leading-tight truncate w-full group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $favKapper->salon_naam }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Wachtlijst --}}
    @if($wachtlijst->isNotEmpty())
    <div class="mb-8">
        <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Op de wachtlijst</p>
        <div class="space-y-2">
            @foreach($wachtlijst as $w)
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl px-4 py-3 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100">{{ $w->kapper->salon_naam }}</p>
                    <p class="text-xs text-gray-500 dark:text-neutral-400 mt-0.5">
                        @if($w->gewenste_datum)
                            Gewenste datum: <span class="font-medium text-amber-700 dark:text-amber-400">{{ \Carbon\Carbon::parse($w->gewenste_datum)->translatedFormat('d F Y') }}</span>
                        @else
                            Geen specifieke datum opgegeven
                        @endif
                    </p>
                </div>
                <button type="button"
                        @click.prevent="$dispatch('open-confirm', { title: 'Afmelden van wachtlijst', message: 'Jezelf verwijderen van de wachtlijst bij {{ addslashes($w->kapper->salon_naam) }}?', action: () => $wire.wachtlijstAfmelden({{ $w->id }}) })"
                        class="flex-shrink-0 text-xs text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors underline">
                    Afmelden
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Aankomend --}}
    <div class="mb-8">
        <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Aankomend</p>
        <div class="space-y-3">
            @forelse($aankomend as $afspraak)
            @php
                $kanNietAnnuleren = $afspraak->kapper->annulering_uren
                    && now()->isAfter(
                        \Carbon\Carbon::parse($afspraak->datum->format('Y-m-d') . ' ' . $afspraak->start_tijd)
                            ->subHours($afspraak->kapper->annulering_uren)
                    );
            @endphp
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                {{-- Info --}}
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-900/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100">{{ $afspraak->kapper->salon_naam }}</p>
                        <p class="text-sm text-gray-500 dark:text-neutral-400 mt-0.5">
                            {{ $afspraak->datum->isoFormat('dddd D MMMM') }} · {{ $afspraak->start_tijd }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                            {{ $afspraak->dienst->naam }} · {{ $afspraak->betaalmethode === 'online' ? 'Online betaald' : 'Betalen in de zaak' }}
                        </p>
                    </div>
                </div>
                {{-- Knoppen --}}
                <div class="flex gap-2 sm:flex-shrink-0">
                    <button wire:click="openVerzetten({{ $afspraak->id }})"
                            class="flex-1 sm:flex-none text-xs font-medium px-3 py-2 rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        Verzetten
                    </button>
                    @if($kanNietAnnuleren && $afspraak->kapper->annulering_kosten > 0)
                    <button wire:click="annuleer({{ $afspraak->id }})"
                            class="flex-1 sm:flex-none text-xs font-medium px-3 py-2 rounded-lg border border-amber-300 dark:border-amber-700 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors">
                        Annuleer (€ {{ number_format($afspraak->kapper->annulering_kosten / 100, 2, ',', '') }})
                    </button>
                    @elseif($kanNietAnnuleren)
                    <span title="Annuleren niet meer mogelijk vanwege het annuleringsbeleid"
                          class="flex-1 sm:flex-none text-xs px-3 py-2 rounded-lg border border-gray-100 dark:border-neutral-800 text-gray-300 dark:text-neutral-600 cursor-not-allowed select-none text-center">
                        Annuleer
                    </span>
                    @else
                    <button @click.prevent="$dispatch('open-confirm', { title: 'Afspraak annuleren', message: 'Weet je zeker dat je de afspraak op {{ $afspraak->datum->isoFormat('D MMM') }} wilt annuleren?', action: () => $wire.annuleer({{ $afspraak->id }}) })"
                            class="flex-1 sm:flex-none text-xs font-medium px-3 py-2 rounded-lg border border-red-200 dark:border-red-900 text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        Annuleer
                    </button>
                    @endif
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

        {{-- Mobiel: cards --}}
        <div class="sm:hidden space-y-2">
            @foreach($geschiedenis as $afspraak)
            @php
                $isVoltooide = in_array($afspraak->status, ['gepland', 'voltooid']);
                $badge = match(true) {
                    $isVoltooide                        => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                    $afspraak->status === 'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                    $afspraak->status === 'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                    default                             => 'bg-gray-100 text-gray-500',
                };
                $statusLabel = match(true) {
                    $isVoltooide                        => 'Voltooid',
                    $afspraak->status === 'geannuleerd' => 'Geannuleerd',
                    $afspraak->status === 'no_show'     => 'No-show',
                    default                             => ucfirst(str_replace('_', ' ', $afspraak->status)),
                };
                $isFav = $favorietKapperIds->contains($afspraak->kapper_id);
            @endphp
            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl px-4 py-3">
                <div class="flex items-start justify-between gap-2 mb-1.5">
                    <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100 leading-tight">{{ $afspraak->kapper->salon_naam }}</p>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium flex-shrink-0 {{ $badge }}">{{ $statusLabel }}</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-neutral-400">{{ $afspraak->datum->translatedFormat('d M Y') }} · {{ $afspraak->start_tijd }}</p>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $afspraak->dienst->naam }}</p>
                <div class="flex items-center gap-3 mt-2.5 pt-2.5 border-t border-gray-50 dark:border-neutral-700">
                    @if($isVoltooide)
                        @if($afspraak->review)
                        <span class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3 h-3 {{ $i <= $afspraak->review->rating ? 'text-amber-400' : 'text-gray-200 dark:text-neutral-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </span>
                        @else
                        <button wire:click="openReview({{ $afspraak->id }})" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">Beoordeel</button>
                        @endif
                    @endif
                    <a href="{{ route('kapper.profiel', $afspraak->kapper->slug) }}?dienst_id={{ $afspraak->dienst_id }}" class="text-xs font-medium text-gray-500 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 hover:underline">Boek weer</a>
                    <button wire:click="toggleFavoriet({{ $afspraak->kapper_id }})"
                            title="{{ $isFav ? 'Verwijder uit favorieten' : 'Voeg toe aan favorieten' }}"
                            class="ml-auto p-1 rounded hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors group">
                        <svg class="w-3.5 h-3.5 transition-colors {{ $isFav ? 'text-red-500' : 'text-gray-300 dark:text-neutral-600 group-hover:text-red-400' }}"
                             fill="{{ $isFav ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Desktop: tabel --}}
        <div class="hidden sm:block bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-neutral-700">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Datum</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Salon</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden md:table-cell">Dienst</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-neutral-700">
                    @foreach($geschiedenis as $afspraak)
                    @php
                        $isVoltooide = in_array($afspraak->status, ['gepland', 'voltooid']);
                        $badge = match(true) {
                            $isVoltooide                        => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            $afspraak->status === 'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                            $afspraak->status === 'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            default                             => 'bg-gray-100 text-gray-500',
                        };
                        $statusLabel = match(true) {
                            $isVoltooide                        => 'Voltooid',
                            $afspraak->status === 'geannuleerd' => 'Geannuleerd',
                            $afspraak->status === 'no_show'     => 'No-show',
                            default                             => ucfirst(str_replace('_', ' ', $afspraak->status)),
                        };
                        $isFav = $favorietKapperIds->contains($afspraak->kapper_id);
                    @endphp
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-neutral-700/20">
                        <td class="px-5 py-3 text-gray-700 dark:text-neutral-300 whitespace-nowrap">
                            {{ $afspraak->datum->format('d-m-Y') }}
                            <span class="text-xs text-gray-400 dark:text-neutral-500 ml-1">{{ $afspraak->start_tijd }}</span>
                        </td>
                        <td class="px-5 py-3 text-gray-500 dark:text-neutral-400">{{ $afspraak->kapper->salon_naam }}</td>
                        <td class="px-5 py-3 text-gray-500 dark:text-neutral-400 hidden md:table-cell">{{ $afspraak->dienst->naam }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                @if($isVoltooide)
                                    @if($afspraak->review)
                                    <span class="text-xs text-gray-400 dark:text-neutral-500 flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= $afspraak->review->rating ? 'text-amber-400' : 'text-gray-200 dark:text-neutral-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        @endfor
                                    </span>
                                    @else
                                    <button wire:click="openReview({{ $afspraak->id }})" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline whitespace-nowrap">Beoordeel</button>
                                    @endif
                                @endif
                                <a href="{{ route('kapper.profiel', $afspraak->kapper->slug) }}?dienst_id={{ $afspraak->dienst_id }}" class="text-xs font-medium text-gray-500 dark:text-neutral-400 hover:text-blue-600 dark:hover:text-blue-400 hover:underline whitespace-nowrap">Boek weer</a>
                                <button wire:click="toggleFavoriet({{ $afspraak->kapper_id }})"
                                        title="{{ $isFav ? 'Verwijder uit favorieten' : 'Voeg toe aan favorieten' }}"
                                        class="p-1 rounded hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors group">
                                    <svg class="w-3.5 h-3.5 transition-colors {{ $isFav ? 'text-red-500' : 'text-gray-300 dark:text-neutral-600 group-hover:text-red-400' }}"
                                         fill="{{ $isFav ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </div>
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
        <div class="relative w-full sm:max-w-sm bg-white dark:bg-neutral-800 sm:rounded-2xl rounded-t-2xl shadow-2xl">
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
                <div class="mb-4">
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

    {{-- Annuleringskosten modal --}}
    @if($annuleringFeeAfspraakId)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="absolute inset-0 bg-black/50" wire:click="sluitAnnuleringFee"></div>
        <div class="relative w-full sm:max-w-sm bg-white dark:bg-neutral-800 sm:rounded-2xl rounded-t-2xl shadow-2xl">
            <div class="sm:hidden flex justify-center pt-3 pb-1">
                <div class="w-10 h-1 bg-gray-200 dark:bg-neutral-600 rounded-full"></div>
            </div>
            <div class="px-5 pt-4 pb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100">Annuleringskosten</h3>
                    <button wire:click="sluitAnnuleringFee" class="text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mb-5">
                    <p class="text-sm text-amber-800 dark:text-amber-300 font-medium mb-1">Te laat voor gratis annuleren</p>
                    <p class="text-xs text-amber-700 dark:text-amber-400">Je kunt deze afspraak nog annuleren, maar er zijn annuleringskosten van toepassing.</p>
                </div>

                <div class="flex justify-between items-center mb-3 px-1">
                    <span class="text-sm text-gray-500 dark:text-neutral-400">Annuleringskosten</span>
                    <span class="text-lg font-bold text-gray-800 dark:text-neutral-100">€ {{ number_format($annuleringFeeKosten / 100, 2, ',', '') }}</span>
                </div>

                @if(auth()->user()->stripe_payment_method_id)
                <div class="flex items-center gap-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg px-3 py-2.5 mb-5">
                    <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <p class="text-xs text-blue-700 dark:text-blue-300">€ {{ number_format($annuleringFeeKosten / 100, 2, ',', '') }} wordt <strong>automatisch afgeschreven</strong> van je opgeslagen betaalmethode.</p>
                </div>
                @else
                <div class="flex items-center gap-2 bg-gray-50 dark:bg-neutral-700/50 border border-gray-200 dark:border-neutral-600 rounded-lg px-3 py-2.5 mb-5">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <p class="text-xs text-gray-500 dark:text-neutral-400">Je wordt doorgestuurd naar de betaalpagina om dit bedrag te voldoen.</p>
                </div>
                @endif

                <form method="POST" action="{{ route('afspraak.annulering.checkout') }}">
                    @csrf
                    <input type="hidden" name="afspraak_id" value="{{ $annuleringFeeAfspraakId }}">
                    <div class="flex gap-2">
                        <button type="button" wire:click="sluitAnnuleringFee"
                                class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                            Terug
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 text-sm font-semibold rounded-lg bg-amber-500 hover:bg-amber-600 text-white transition-colors">
                            Betalen & annuleren
                        </button>
                    </div>
                </form>
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
