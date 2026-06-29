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
        <div x-data="{
                open: false,
                options: { week: 'Deze week', maand: 'Deze maand', jaar: 'Dit jaar' },
                get label() { return this.options[$wire.periode] ?? '...'; },
                choose(val) { $wire.set('periode', val); this.open = false; }
             }"
             class="relative self-start" @click.outside="open = false">
            <button @click="open = !open"
                    class="flex items-center gap-2 px-3 py-2 text-sm bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg text-gray-700 dark:text-neutral-300 hover:border-gray-300 dark:hover:border-neutral-600 shadow-sm transition-colors cursor-pointer">
                <span x-text="label"></span>
                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 top-full mt-1 z-50 bg-white dark:bg-neutral-900 border border-gray-100 dark:border-neutral-800 rounded-xl shadow-xl overflow-hidden min-w-[140px]"
                 style="display:none">
                <template x-for="key in Object.keys(options)" :key="key">
                    <button @click="choose(key)"
                            :class="$wire.periode === key
                                ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-medium'
                                : 'text-gray-700 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-800'"
                            class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-sm transition-colors">
                        <svg x-show="$wire.periode === key" class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-show="$wire.periode !== key" class="w-3.5 inline-block flex-shrink-0"></span>
                        <span x-text="options[key]"></span>
                    </button>
                </template>
            </div>
        </div>
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
        @if(array_sum($grafiekData['data']) > 0)
        <div x-data x-init="window._kaplyInitOmzet && window._kaplyInitOmzet()" style="position:relative; height:200px;">
            <canvas id="kaply-omzet-chart"
                    data-labels="{{ json_encode($grafiekData['labels']) }}"
                    data-data="{{ json_encode($grafiekData['data']) }}">
            </canvas>
        </div>
        @else
        <div style="height:200px;" class="flex flex-col items-center justify-center gap-2">
            <svg class="w-8 h-8 text-gray-200 dark:text-neutral-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l5-5 4 4 5-6 4 3"/>
            </svg>
            <p class="text-sm text-gray-400 dark:text-neutral-500">Geen omzetdata voor deze periode</p>
            <p class="text-xs text-gray-300 dark:text-neutral-600">Omzet telt alleen voltooide afspraken</p>
        </div>
        @endif
    </div>

    {{-- Onderste rij: omzet per dienst + top klanten --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Omzet per dienst --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-5">Omzet per dienst</h2>
            @if($omzetPerDienst->isNotEmpty())
            <div class="flex gap-6 items-center" x-data x-init="window._kaplyInitDienst && window._kaplyInitDienst()">
                <div style="width:120px; height:120px; flex-shrink:0;">
                    <canvas id="kaply-dienst-chart" width="120" height="120"
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

    <script>
(function() {
    if (typeof Chart === 'undefined') return;

    var _omzetChart = null;
    var _dienstChart = null;

    function isDark() { return document.documentElement.classList.contains('dark'); }
    function gridColor() { return isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)'; }

    window._kaplyInitOmzet = function() {
        var canvas = document.getElementById('kaply-omzet-chart');
        if (!canvas) return;
        if (_omzetChart) { _omzetChart.destroy(); _omzetChart = null; }
        var labels = JSON.parse(canvas.dataset.labels || '[]');
        var data   = JSON.parse(canvas.dataset.data   || '[]');
        var ctx    = canvas.getContext('2d');
        var gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(59,130,246,0.18)');
        gradient.addColorStop(1, 'rgba(59,130,246,0)');
        _omzetChart = new Chart(ctx, {
            type: 'line',
            data: { labels: labels, datasets: [{ data: data, borderColor: '#3B82F6', backgroundColor: gradient, borderWidth: 2.5, tension: 0.4, fill: true, pointRadius: 0, pointHoverRadius: 5, pointHoverBackgroundColor: '#3B82F6' }] },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(c) { return '€ ' + c.parsed.y.toLocaleString('nl-NL', { minimumFractionDigits: 2 }); } } } },
                scales: {
                    x: { grid: { color: gridColor() }, ticks: { color: isDark() ? '#9CA3AF' : '#6B7280', font: { size: 11 } } },
                    y: { grid: { color: gridColor() }, ticks: { color: isDark() ? '#9CA3AF' : '#6B7280', font: { size: 11 }, callback: function(v) { return '€ ' + v.toLocaleString('nl-NL'); } }, beginAtZero: true }
                }
            }
        });
    };

    window._kaplyInitDienst = function() {
        var canvas = document.getElementById('kaply-dienst-chart');
        if (!canvas) return;
        if (_dienstChart) { _dienstChart.destroy(); _dienstChart = null; }
        var labels = JSON.parse(canvas.dataset.labels || '[]');
        var data   = JSON.parse(canvas.dataset.data   || '[]');
        if (!data.length) return;
        _dienstChart = new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: { labels: labels, datasets: [{ data: data, backgroundColor: ['#3B82F6','#60A5FA','#93C5FD','#BFDBFE','#DBEAFE','#EFF6FF'], borderWidth: 0, hoverOffset: 4 }] },
            options: { responsive: false, cutout: '65%', plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(c) { return c.label + ': €' + c.parsed.toLocaleString('nl-NL', { minimumFractionDigits: 2 }); } } } } }
        });
    };

    document.addEventListener('livewire:updated', function() {
        window._kaplyInitOmzet && window._kaplyInitOmzet();
        window._kaplyInitDienst && window._kaplyInitDienst();
    });
})();
</script>
</div>
