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
                        <p class="text-sm text-gray-600 dark:text-neutral-300">{{ str($afspraak->kapper->salon_naam)->title() }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">
                            {{ $afspraak->dienst->naam }} · {{ $afspraak->betaalmethode === 'online' ? 'Online betaald' : 'Betalen in de zaak' }}
                        </p>
                    </div>
                </div>
                <button @click.prevent="$dispatch('open-confirm', { title: 'Afspraak annuleren', message: 'Weet je zeker dat je de afspraak op {{ $afspraak->datum->isoFormat('D MMM') }} wilt annuleren?', action: () => $wire.annuleer({{ $afspraak->id }}) })"
                        class="flex-shrink-0 text-xs font-medium px-3 py-1.5 rounded-lg border border-red-200 dark:border-red-900 text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    Annuleer
                </button>
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
                        <td class="px-5 py-3 text-gray-500 dark:text-neutral-400 hidden sm:table-cell">{{ str($afspraak->kapper->salon_naam)->title() }}</td>
                        <td class="px-5 py-3 text-gray-500 dark:text-neutral-400 hidden md:table-cell">{{ $afspraak->dienst->naam }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
