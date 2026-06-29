@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@php
    $euro = fn($cents) => '€ ' . number_format($cents / 100, 2, ',', '.');
    $pct  = fn($h, $v) => $v > 0 ? round(($h - $v) / $v * 100) : null;

    $omzetPct       = $pct($omzetHuidig, $omzetVorig);
    $afsprakenPct   = $pct($afsprakenHuidig, $afsprakenVorig);
    $klantPct       = $pct($nieuweKlantenHuidig, $nieuweKlantenVorig);
    $gemPct         = $pct($gemBesteding, $gemBestedingVorig);

    $dienstKleuren = ['#3B82F6','#60A5FA','#93C5FD','#BFDBFE','#DBEAFE','#EFF6FF'];
@endphp

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Statistieken</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ $periodeLabel }}</p>
        </div>
        <x-select
            wire-target="periode"
            :current="$periode"
            :options="['week' => 'Deze week', 'maand' => 'Deze maand', 'jaar' => 'Dit jaar']"
            placeholder="Periode"
        />
    </div>

    {{-- 4 stat kaarten --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- Totale omzet --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-3">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.121 15.536c-1.171 1.952-3.07 1.952-4.242 0-1.172-1.953-1.172-5.119 0-7.072 1.171-1.952 3.07-1.952 4.242 0M8 10.5h8m-8 3h8M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-neutral-100 leading-none">{{ $euro($omzetHuidig) }}</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Totale omzet</p>
            @if($omzetPct !== null)
            <p class="text-xs mt-2 flex items-center gap-1 {{ $omzetPct >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                <svg class="w-3 h-3 flex-shrink-0 {{ $omzetPct < 0 ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                {{ abs($omzetPct) }}%
            </p>
            @endif
        </div>

        {{-- Afspraken --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-3">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-neutral-100 leading-none">{{ $afsprakenHuidig }}</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Afspraken</p>
            @if($afsprakenPct !== null)
            <p class="text-xs mt-2 flex items-center gap-1 {{ $afsprakenPct >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                <svg class="w-3 h-3 flex-shrink-0 {{ $afsprakenPct < 0 ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                {{ abs($afsprakenPct) }}%
            </p>
            @endif
        </div>

        {{-- Nieuwe klanten --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-3">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-neutral-100 leading-none">{{ $nieuweKlantenHuidig }}</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Nieuwe klanten</p>
            @if($klantPct !== null)
            <p class="text-xs mt-2 flex items-center gap-1 {{ $klantPct >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                <svg class="w-3 h-3 flex-shrink-0 {{ $klantPct < 0 ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                {{ abs($klantPct) }}%
            </p>
            @endif
        </div>

        {{-- Gem. besteding --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-5">
            <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-3">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-neutral-100 leading-none">{{ $euro($gemBesteding) }}</p>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Gem. besteding</p>
            @if($gemPct !== null)
            <p class="text-xs mt-2 flex items-center gap-1 {{ $gemPct >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                <svg class="w-3 h-3 flex-shrink-0 {{ $gemPct < 0 ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                {{ abs($gemPct) }}%
            </p>
            @endif
        </div>

    </div>

    {{-- Omzet lijndiagram --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6 mb-6">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-5">Omzet</h2>
        <div style="position:relative; height:200px;">
            <canvas id="kaply-omzet-chart"
                    data-labels="{{ json_encode($grafiekData['labels']) }}"
                    data-data="{{ json_encode($grafiekData['data']) }}">
            </canvas>
        </div>
    </div>

    {{-- Onderste rij: omzet per dienst + top klanten --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Omzet per dienst --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-5">Omzet per dienst</h2>
            @if($omzetPerDienst->isNotEmpty())
            <div class="flex gap-6 items-center">
                <div style="position:relative; width:120px; height:120px; flex-shrink:0;">
                    <canvas id="kaply-dienst-chart"
                            data-labels="{{ json_encode($omzetPerDienst->pluck('naam')->toArray()) }}"
                            data-data="{{ json_encode($omzetPerDienst->map(fn($d) => round($d->totaal / 100, 2))->toArray()) }}">
                    </canvas>
                </div>
                <div class="flex-1 space-y-2">
                    @foreach($omzetPerDienst->take(5) as $i => $dienst)
                    @php $kleur = $dienstKleuren[$i] ?? '#E2E8F0'; @endphp
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $kleur }}"></span>
                            <span class="text-xs text-gray-600 dark:text-neutral-400 truncate">{{ $dienst->naam }}</span>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-xs font-semibold text-gray-800 dark:text-neutral-200">{{ $euro($dienst->totaal) }}</span>
                            <span class="text-xs text-gray-400 dark:text-neutral-500 ml-1">{{ round($dienst->totaal / $totaalOmzetDienst * 100) }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <p class="text-sm text-gray-400 dark:text-neutral-500">Geen data voor deze periode.</p>
            @endif
        </div>

        {{-- Top klanten --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-5">Top klanten</h2>
            @if($topKlanten->isNotEmpty())
            <div class="space-y-3">
                @foreach($topKlanten as $klant)
                @php
                    $init = mb_strtoupper(mb_substr($klant->name, 0, 1));
                    $kleuren2 = ['bg-blue-100 text-blue-700','bg-emerald-100 text-emerald-700','bg-violet-100 text-violet-700','bg-rose-100 text-rose-700','bg-amber-100 text-amber-700'];
                    $k2 = $kleuren2[abs(crc32($klant->name)) % count($kleuren2)];
                @endphp
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-sm font-bold {{ $k2 }}">
                        {{ $init }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-100 truncate">{{ $klant->name }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $klant->aantal_afspraken }} {{ $klant->aantal_afspraken === 1 ? 'afspraak' : 'afspraken' }}</p>
                    </div>
                    <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200 flex-shrink-0">{{ $euro($klant->totaal) }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400 dark:text-neutral-500">Geen data voor deze periode.</p>
            @endif
        </div>

    </div>
</div>

@push('scripts')
<script>
(function() {
    let omzetChart = null;
    let dienstChart = null;

    const isDark = () => document.documentElement.classList.contains('dark');

    function gridColor() {
        return isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
    }

    function initOmzetChart() {
        const canvas = document.getElementById('kaply-omzet-chart');
        if (!canvas) return;
        if (omzetChart) { omzetChart.destroy(); omzetChart = null; }

        const labels = JSON.parse(canvas.dataset.labels || '[]');
        const data   = JSON.parse(canvas.dataset.data   || '[]');
        const ctx    = canvas.getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(59,130,246,0.18)');
        gradient.addColorStop(1, 'rgba(59,130,246,0)');

        omzetChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    data,
                    borderColor: '#3B82F6',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#3B82F6',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: {
                    callbacks: {
                        label: ctx => '€ ' + ctx.parsed.y.toLocaleString('nl-NL', { minimumFractionDigits: 2 })
                    }
                }},
                scales: {
                    x: { grid: { color: gridColor() }, ticks: { color: isDark() ? '#9CA3AF' : '#6B7280', font: { size: 11 } } },
                    y: {
                        grid: { color: gridColor() },
                        ticks: {
                            color: isDark() ? '#9CA3AF' : '#6B7280',
                            font: { size: 11 },
                            callback: v => '€ ' + v.toLocaleString('nl-NL')
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function initDienstChart() {
        const canvas = document.getElementById('kaply-dienst-chart');
        if (!canvas) return;
        if (dienstChart) { dienstChart.destroy(); dienstChart = null; }

        const labels = JSON.parse(canvas.dataset.labels || '[]');
        const data   = JSON.parse(canvas.dataset.data   || '[]');
        if (!data.length) return;

        dienstChart = new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data,
                    backgroundColor: ['#3B82F6','#60A5FA','#93C5FD','#BFDBFE','#DBEAFE','#EFF6FF'],
                    borderWidth: 0,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.label + ': €' + ctx.parsed.toLocaleString('nl-NL', { minimumFractionDigits: 2 })
                        }
                    }
                }
            }
        });
    }

    function initCharts() {
        initOmzetChart();
        initDienstChart();
    }

    document.addEventListener('DOMContentLoaded', initCharts);
    document.addEventListener('livewire:updated', initCharts);
})();
</script>
@endpush
