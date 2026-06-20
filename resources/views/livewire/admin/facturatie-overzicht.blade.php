<div class="p-4 sm:p-6 space-y-6">

    <div>
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Facturatie</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Abonnementen kappers · €25/maand excl. BTW</p>
    </div>

    {{-- Stat kaarten --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs text-gray-500 dark:text-neutral-400">Actief</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-neutral-100 mt-1">{{ $totaalActief }}</p>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs text-gray-500 dark:text-neutral-400">Trial</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-neutral-100 mt-1">{{ $totaalTrial }}</p>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs text-gray-500 dark:text-neutral-400">Inactief</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-neutral-100 mt-1">{{ $totaalInactief }}</p>
        </div>
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-4">
            <p class="text-xs text-gray-500 dark:text-neutral-400">Maandomzet (excl. BTW)</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">€ {{ number_format($maandOmzet / 100, 2, ',', '.') }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="zoekterm" type="search"
                   placeholder="Zoek kapper of e-mail..."
                   class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 dark:border-neutral-700 rounded-lg bg-white dark:bg-neutral-800 text-gray-800 dark:text-neutral-200 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
        <select wire:model.live="statusFilter"
                class="text-sm border border-gray-200 dark:border-neutral-700 rounded-lg bg-white dark:bg-neutral-800 text-gray-700 dark:text-neutral-300 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">Alle statussen</option>
            <option value="actief">Actief</option>
            <option value="trial">Trial</option>
            <option value="inactief">Inactief</option>
            <option value="geen">Geen</option>
        </select>
    </div>

    {{-- Tabel --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
        @if($kappers->isEmpty())
        <div class="text-center py-12">
            <p class="text-sm text-gray-400 dark:text-neutral-500">Geen kappers gevonden.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-neutral-700">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-neutral-400 uppercase tracking-wide">Kapper</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-neutral-400 uppercase tracking-wide">E-mail</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-neutral-400 uppercase tracking-wide">Abonnement</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-neutral-400 uppercase tracking-wide">Stripe status</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-neutral-400 uppercase tracking-wide">Bedrag</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 dark:text-neutral-400 uppercase tracking-wide">Aangemeld</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-neutral-700/50">
                    @foreach($kappers as $kapper)
                    @php
                        $sub = $subscriptions[$kapper->user_id] ?? null;
                        $statusLabel = match($kapper->abonnement_status) {
                            'actief'   => ['label' => 'Actief',   'class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
                            'trial'    => ['label' => 'Trial',    'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'],
                            'inactief' => ['label' => 'Inactief', 'class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'],
                            default    => ['label' => 'Geen',     'class' => 'bg-gray-100 text-gray-600 dark:bg-neutral-700 dark:text-neutral-400'],
                        };
                        $stripeStatus = $sub ? match($sub->stripe_status) {
                            'active'            => ['label' => 'Betaald',       'class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
                            'trialing'          => ['label' => 'Trial',         'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'],
                            'past_due'          => ['label' => 'Te laat',       'class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'],
                            'canceled'          => ['label' => 'Geannuleerd',   'class' => 'bg-gray-100 text-gray-600 dark:bg-neutral-700 dark:text-neutral-400'],
                            'incomplete'        => ['label' => 'Onvolledig',    'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'],
                            'incomplete_expired'=> ['label' => 'Verlopen',      'class' => 'bg-gray-100 text-gray-600 dark:bg-neutral-700 dark:text-neutral-400'],
                            default             => ['label' => $sub->stripe_status, 'class' => 'bg-gray-100 text-gray-600'],
                        } : null;
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/30 transition-colors">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-800 dark:text-neutral-200">{{ $kapper->salon_naam }}</div>
                            <div class="text-xs text-gray-400 dark:text-neutral-500">{{ $kapper->user?->name }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-neutral-400">
                            {{ $kapper->user?->email ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusLabel['class'] }}">
                                {{ $statusLabel['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($stripeStatus)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $stripeStatus['class'] }}">
                                {{ $stripeStatus['label'] }}
                            </span>
                            @if($sub->ends_at)
                            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Eindigt {{ \Carbon\Carbon::parse($sub->ends_at)->format('d M Y') }}</p>
                            @endif
                            @else
                            <span class="text-xs text-gray-400 dark:text-neutral-500">Geen Stripe data</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-neutral-300 font-medium">
                            @if($kapper->abonnement_status === 'actief' || $kapper->abonnement_status === 'trial')
                                € 25,00
                                <span class="text-xs text-gray-400 dark:text-neutral-500 font-normal">/maand</span>
                            @else
                                <span class="text-gray-400 dark:text-neutral-500">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-neutral-400 text-xs">
                            {{ \Carbon\Carbon::parse($kapper->created_at)->format('d M Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    @if(!$kappers->isEmpty())
    <p class="text-xs text-gray-400 dark:text-neutral-500 text-right">
        {{ $kappers->count() }} kapper{{ $kappers->count() !== 1 ? 's' : '' }} · Stripe factuurdata beschikbaar zodra live betalingen actief zijn
    </p>
    @endif

</div>
