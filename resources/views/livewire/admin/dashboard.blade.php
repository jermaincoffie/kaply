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
                <x-tooltip>Monthly Recurring Revenue — actieve abonnees × €25 per maand.</x-tooltip>
            </p>
            <p class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-1">€ {{ number_format($mrr / 100, 0, ',', '.') }}</p>
            <p class="text-xs mt-1">
                @if($mrr_verschil > 0)
                    <span class="text-green-600 dark:text-green-400 font-semibold">+€ {{ number_format($mrr_verschil / 100, 0, ',', '.') }}</span>
                    <span class="text-gray-400 dark:text-neutral-500"> nieuwe abonnees deze maand</span>
                @elseif($mrr_verschil < 0)
                    <span class="text-red-500 dark:text-red-400 font-semibold">−€ {{ number_format(abs($mrr_verschil) / 100, 0, ',', '.') }}</span>
                    <span class="text-gray-400 dark:text-neutral-500"> vs vorige maand</span>
                @else
                    <span class="text-gray-400 dark:text-neutral-500">Prognose <span class="font-medium text-gray-500 dark:text-neutral-400">€ {{ number_format($prognose_mrr / 100, 0, ',', '.') }}</span></span>
                @endif
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

        {{-- Trial --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5 {{ $trial_count > 0 ? 'ring-2 ring-blue-400 dark:ring-blue-600' : '' }}">
            <p class="text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide mb-3 flex items-center">
                Trial
                <x-tooltip position="right">Kappers in gratis proefperiode (14 dagen). Conversieratio = % dat betaald abonnement kiest.</x-tooltip>
            </p>
            <p class="text-3xl font-bold mb-1 {{ $trial_count > 0 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-neutral-100' }}">{{ $trial_count }}</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500">
                <span class="font-medium {{ $conversieratio >= 50 ? 'text-green-600 dark:text-green-400' : ($conversieratio >= 20 ? 'text-amber-500' : 'text-red-500') }}">{{ $conversieratio }}%</span>
                conversie tot nu toe
            </p>
        </div>

    </div>

    {{-- Recente afspraken --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden mb-4">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex flex-wrap items-center gap-4">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mr-auto">Recente afspraken</h2>
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
        {{-- Mobiel: cards --}}
        <div class="sm:hidden divide-y divide-gray-50 dark:divide-neutral-700">
            @forelse($recente_afspraken as $afspraak)
            @php
                $badgeM = match($afspraak->status) {
                    'gepland'     => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                    'voltooid'    => 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                    'geannuleerd' => 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400',
                    'no_show'     => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                    default       => 'bg-gray-100 text-gray-500',
                };
            @endphp
            <div class="px-4 py-3 flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">{{ str($afspraak->klant?->name ?? '—')->title() }}</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5 truncate">{{ str($afspraak->kapper?->salon_naam ?? '—')->title() }}</p>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $afspraak->datum->format('d-m-Y') }} · {{ $afspraak->start_tijd }}</p>
                </div>
                <span class="inline-flex flex-shrink-0 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeM }}">
                    {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
                </span>
            </div>
            @empty
            <div class="px-4 py-10 text-center text-sm text-gray-400 dark:text-neutral-500">Nog geen afspraken</div>
            @endforelse
        </div>

        {{-- Desktop: tabel --}}
        <table class="hidden sm:table w-full text-sm">
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
                    <td class="px-6 py-3.5 font-medium text-gray-800 dark:text-neutral-100">{{ str($afspraak->klant?->name ?? '—')->title() }}</td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400">{{ str($afspraak->kapper?->salon_naam ?? '—')->title() }}</td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400 hidden md:table-cell">{{ $afspraak->dienst?->naam ?? '—' }}</td>
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

    {{-- Trial kappers + Recente aanmeldingen --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

        {{-- Trial kappers --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">Trial kappers</h2>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Gratis proefperiode — 14 dagen</p>
                </div>
                @if($trial_count > 0)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                    {{ $trial_count }} actief
                </span>
                @endif
            </div>
            @if($trial_kappers->isEmpty())
            <div class="px-6 py-10 text-center text-sm text-gray-400 dark:text-neutral-500">Geen kappers in trial</div>
            @else
            <div class="divide-y divide-gray-50 dark:divide-neutral-700">
                @foreach($trial_kappers as $kapper)
                @php
                    $urgentie = $kapper->dagen_resterend <= 2
                        ? 'text-red-600 dark:text-red-400'
                        : ($kapper->dagen_resterend <= 5 ? 'text-amber-500' : 'text-blue-600 dark:text-blue-400');
                    $barPct = round((14 - $kapper->dagen_resterend) / 14 * 100);
                @endphp
                <div class="px-6 py-3">
                    <div class="flex items-center justify-between mb-1">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">{{ $kapper->salon_naam }}</p>
                            <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $kapper->stad }} · {{ $kapper->user?->email }}</p>
                        </div>
                        <span class="text-xs font-semibold {{ $urgentie }} ml-3 flex-shrink-0">
                            {{ $kapper->dagen_resterend }}d over
                        </span>
                    </div>
                    <div class="h-1 bg-gray-100 dark:bg-neutral-700 rounded-full overflow-hidden mt-2">
                        <div class="h-1 rounded-full {{ $kapper->dagen_resterend <= 2 ? 'bg-red-400' : ($kapper->dagen_resterend <= 5 ? 'bg-amber-400' : 'bg-blue-500') }}"
                             style="width: {{ $barPct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Recente aanmeldingen --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">Recente aanmeldingen</h2>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Nieuwste kappers die onboarding voltooid hebben</p>
            </div>
            @if($recente_aanmeldingen->isEmpty())
            <div class="px-6 py-10 text-center text-sm text-gray-400 dark:text-neutral-500">Nog geen aanmeldingen</div>
            @else
            <div class="divide-y divide-gray-50 dark:divide-neutral-700">
                @foreach($recente_aanmeldingen as $kapper)
                <div class="px-6 py-3 flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-white">{{ strtoupper(substr($kapper->salon_naam, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">{{ $kapper->salon_naam }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $kapper->stad }} · {{ $kapper->created_at->diffForHumans() }}</p>
                    </div>
                    @if($kapper->abonnement_status === 'actief')
                    <span class="inline-flex flex-shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400">Betaald</span>
                    @elseif($kapper->created_at->gt(now()->subDays(14)))
                    <span class="inline-flex flex-shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Trial</span>
                    @else
                    <span class="inline-flex flex-shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400">Verlopen</span>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

    {{-- Top kappers + Recente reviews --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Top kappers --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">Top kappers</h2>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Meeste boekingen in {{ now()->isoFormat('MMMM') }}</p>
            </div>
            @if($top_kappers->isEmpty())
            <div class="px-6 py-10 text-center text-sm text-gray-400 dark:text-neutral-500">Geen actieve kappers</div>
            @else
            @php $maxBoekingen = $top_kappers->first()->boekingen_maand ?: 1; @endphp
            <div class="divide-y divide-gray-50 dark:divide-neutral-700">
                @foreach($top_kappers as $idx => $kapper)
                <a href="{{ route('admin.kappers') }}" class="px-6 py-3 flex items-center gap-4 hover:bg-gray-50/50 dark:hover:bg-neutral-700/20 transition-colors">
                    <span class="text-xs font-bold text-gray-300 dark:text-neutral-600 w-4 flex-shrink-0">{{ $idx + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">{{ $kapper->salon_naam }}</p>
                        <div class="mt-1 h-1 bg-gray-100 dark:bg-neutral-700 rounded-full overflow-hidden">
                            <div class="h-1 bg-blue-500 rounded-full" style="width: {{ $maxBoekingen > 0 ? round(($kapper->boekingen_maand / $maxBoekingen) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-gray-700 dark:text-neutral-300 flex-shrink-0">{{ $kapper->boekingen_maand }}×</span>
                </a>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Recente reviews --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-neutral-700">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">Recente reviews</h2>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Klik om te verbergen / zichtbaar te maken</p>
            </div>
            @if($recente_reviews->isEmpty())
            <div class="px-6 py-10 text-center text-sm text-gray-400 dark:text-neutral-500">Nog geen reviews</div>
            @else
            <div class="divide-y divide-gray-50 dark:divide-neutral-700">
                @foreach($recente_reviews as $review)
                <div class="px-6 py-3 flex items-start gap-3 {{ !$review->zichtbaar ? 'opacity-40' : '' }}">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200 dark:text-neutral-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-neutral-300">{{ str($review->klant?->name ?? '—')->title() }}</span>
                            <span class="text-xs text-gray-400 dark:text-neutral-500">→ {{ str($review->kapper?->salon_naam ?? '—')->title() }}</span>
                        </div>
                        @if($review->tekst)
                        <p class="text-xs text-gray-500 dark:text-neutral-400 truncate">{{ $review->tekst }}</p>
                        @endif
                    </div>
                    <button wire:click="toggleReviewZichtbaar({{ $review->id }})"
                            class="flex-shrink-0 text-xs font-medium px-2 py-1 rounded border transition-colors
                                {{ $review->zichtbaar
                                    ? 'border-gray-200 dark:border-neutral-700 text-gray-500 dark:text-neutral-400 hover:border-red-300 hover:text-red-500'
                                    : 'border-green-300 dark:border-green-800 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20' }}">
                        {{ $review->zichtbaar ? 'Verberg' : 'Toon' }}
                    </button>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>
</div>
