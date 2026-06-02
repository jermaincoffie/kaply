<div>
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Dashboard</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Platform overzicht</p>
    </div>

    {{-- Statistiek kaarten --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Actieve kappers</p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-bold text-gray-900 dark:text-neutral-100">{{ $kappers_actief }}</span>
                <span class="text-xs text-gray-400 dark:text-neutral-500">van {{ $kappers_totaal }} totaal</span>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Afspraken vandaag</p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-bold text-gray-900 dark:text-neutral-100">{{ $afspraken_vandaag }}</span>
                <span class="text-xs text-gray-400 dark:text-neutral-500">{{ today()->format('d-m-Y') }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Afspraken deze week</p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-bold text-gray-900 dark:text-neutral-100">{{ $afspraken_week }}</span>
                <span class="text-xs text-gray-400 dark:text-neutral-500">week {{ today()->weekOfYear }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3">Klanten</p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-bold text-gray-900 dark:text-neutral-100">{{ $klanten_totaal }}</span>
                <span class="text-xs text-gray-400 dark:text-neutral-500">geregistreerd</span>
            </div>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5 {{ $nieuw_aangemeld > 0 ? 'ring-2 ring-amber-400 dark:ring-amber-500' : '' }}">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3 flex items-center">
                Nieuw aangemeld
                <x-tooltip>Kappers die zich hebben geregistreerd maar nog niet geactiveerd zijn. Ga naar Kappers om ze te activeren.</x-tooltip>
            </p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-bold {{ $nieuw_aangemeld > 0 ? 'text-amber-500' : 'text-gray-900 dark:text-neutral-100' }}">{{ $nieuw_aangemeld }}</span>
                @if($nieuw_aangemeld > 0)
                <a href="{{ route('admin.kappers') }}" class="text-xs font-medium text-amber-600 dark:text-amber-400 hover:underline">Bekijk →</a>
                @else
                <span class="text-xs text-gray-400 dark:text-neutral-500">wachtend</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Abonnement kaarten --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3 flex items-center">
                MRR
                <x-tooltip>Monthly Recurring Revenue — het bedrag dat maandelijks binnenkomt van alle actieve abonnees. Berekend als: aantal actieve abonnees × €20 per maand.</x-tooltip>
            </p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-bold text-gray-900 dark:text-neutral-100">€ {{ number_format($mrr / 100, 0, ',', '.') }}</span>
                <span class="text-xs text-gray-400 dark:text-neutral-500">per maand</span>
            </div>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3 flex items-center">
                Abonnees
                <x-tooltip>Kappers met een actief abonnement die momenteel €20 per maand betalen en zichtbaar zijn voor klanten op het platform.</x-tooltip>
            </p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-bold text-gray-900 dark:text-neutral-100">{{ $abonnees_actief }}</span>
                <span class="text-xs text-gray-400 dark:text-neutral-500">betalend</span>
            </div>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3 flex items-center">
                Gepauzeerd
                <x-tooltip>Kappers waarvan het abonnement is gepauzeerd of verlopen. Hun profiel is niet zichtbaar voor klanten. Neem contact op om ze te reactiveren.</x-tooltip>
            </p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-bold {{ $abonnees_gepauzeerd > 0 ? 'text-amber-500' : 'text-gray-900 dark:text-neutral-100' }}">{{ $abonnees_gepauzeerd }}</span>
                <span class="text-xs text-gray-400 dark:text-neutral-500">kappers</span>
            </div>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3 flex items-center">
                Prognose MRR
                <x-tooltip position="right">Het maximale maandbedrag als alle {{ $kappers_totaal }} geregistreerde kappers een actief abonnement hadden. Geeft aan hoeveel groeiruimte er nog is.</x-tooltip>
            </p>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-bold text-gray-900 dark:text-neutral-100">€ {{ number_format($prognose_mrr / 100, 0, ',', '.') }}</span>
                <span class="text-xs text-gray-400 dark:text-neutral-500">als allen betalen</span>
            </div>
        </div>
    </div>

    {{-- Recente afspraken --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">Recente afspraken</h2>
            <a href="{{ route('admin.afspraken') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Alle afspraken →</a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-neutral-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Klant</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Kapper</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Dienst</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Datum</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-neutral-700">
                @forelse($recente_afspraken as $afspraak)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-neutral-700/20">
                    <td class="px-6 py-3.5 font-medium text-gray-800 dark:text-neutral-100">{{ str($afspraak->klant->name)->title() }}</td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400">{{ str($afspraak->kapper->salon_naam)->title() }}</td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400">{{ $afspraak->dienst->naam }}</td>
                    <td class="px-6 py-3.5 text-gray-400 dark:text-neutral-500 text-xs">{{ $afspraak->datum->format('d-m-Y') }} {{ $afspraak->start_tijd }}</td>
                    <td class="px-6 py-3.5">
                        @php
                            $badge = match($afspraak->status) {
                                'gepland'     => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'voltooid'    => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                                'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                default       => 'bg-gray-100 text-gray-500',
                            };
                        @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                            {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-neutral-500">
                        Nog geen afspraken
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
