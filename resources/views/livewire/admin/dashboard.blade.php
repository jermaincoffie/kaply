<div>
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Dashboard</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Platform overzicht</p>
    </div>

    {{-- 4 KPI kaarten --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- MRR --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3 flex items-center">
                MRR
                <x-tooltip>Monthly Recurring Revenue — actieve abonnees × €20 per maand.</x-tooltip>
            </p>
            <p class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-1">€ {{ number_format($mrr / 100, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500">
                Prognose
                <x-tooltip position="below">Als alle {{ $kappers_totaal }} geregistreerde kappers betaalden: € {{ number_format($prognose_mrr / 100, 0, ',', '.') }} / maand.</x-tooltip>
                <span class="font-medium text-gray-500 dark:text-neutral-400">€ {{ number_format($prognose_mrr / 100, 0, ',', '.') }}</span>
            </p>
        </div>

        {{-- Abonnees --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3 flex items-center">
                Abonnees
                <x-tooltip>Kappers met actief abonnement — zichtbaar op platform.</x-tooltip>
            </p>
            <p class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-1">{{ $abonnees_actief }}</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500">
                van <span class="font-medium text-gray-500 dark:text-neutral-400">{{ $kappers_totaal }}</span> geregistreerd
            </p>
        </div>

        {{-- Nieuw aangemeld --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5 {{ $nieuw_aangemeld > 0 ? 'ring-2 ring-amber-400 dark:ring-amber-500' : '' }}">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3 flex items-center">
                Nieuw aangemeld
                <x-tooltip>Kappers die wachten op activatie.</x-tooltip>
            </p>
            <p class="text-3xl font-bold mb-1 {{ $nieuw_aangemeld > 0 ? 'text-amber-500' : 'text-gray-900 dark:text-neutral-100' }}">{{ $nieuw_aangemeld }}</p>
            @if($nieuw_aangemeld > 0)
            <a href="{{ route('admin.kappers') }}" class="text-xs font-medium text-amber-600 dark:text-amber-400 hover:underline">Activeer nu →</a>
            @else
            <p class="text-xs text-gray-400 dark:text-neutral-500">geen wachtenden</p>
            @endif
        </div>

        {{-- Gepauzeerd --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3 flex items-center">
                Gepauzeerd
                <x-tooltip position="right">Kappers met verlopen abonnement — niet zichtbaar voor klanten.</x-tooltip>
            </p>
            <p class="text-3xl font-bold mb-1 {{ $abonnees_gepauzeerd > 0 ? 'text-amber-500' : 'text-gray-900 dark:text-neutral-100' }}">{{ $abonnees_gepauzeerd }}</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500">kappers inactief</p>
        </div>

    </div>

    {{-- Recente afspraken --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex flex-wrap items-center gap-4">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mr-auto">Recente afspraken</h2>
            {{-- Inline stats --}}
            <span class="text-xs text-gray-400 dark:text-neutral-500">
                Vandaag: <span class="font-semibold text-gray-600 dark:text-neutral-300">{{ $afspraken_vandaag }}</span>
            </span>
            <span class="text-xs text-gray-400 dark:text-neutral-500">
                Week: <span class="font-semibold text-gray-600 dark:text-neutral-300">{{ $afspraken_week }}</span>
            </span>
            <span class="text-xs text-gray-400 dark:text-neutral-500">
                Klanten: <span class="font-semibold text-gray-600 dark:text-neutral-300">{{ $klanten_totaal }}</span>
            </span>
            <a href="{{ route('admin.afspraken') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Alle →</a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-neutral-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Klant</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Kapper</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide hidden md:table-cell">Dienst</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Datum</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-neutral-700">
                @forelse($recente_afspraken as $afspraak)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-neutral-700/20">
                    <td class="px-6 py-3.5 font-medium text-gray-800 dark:text-neutral-100">{{ str($afspraak->klant->name)->title() }}</td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400">{{ str($afspraak->kapper->salon_naam)->title() }}</td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400 hidden md:table-cell">{{ $afspraak->dienst->naam }}</td>
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
