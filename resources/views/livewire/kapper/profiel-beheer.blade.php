<div>
    @if(session('success'))
    <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 text-sm">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 text-sm">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Profiel</h1>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Jouw publieke profielpagina voor klanten</p>
    </div>


    <form wire:submit="opslaan" class="space-y-6" enctype="multipart/form-data">

        {{-- Salon foto --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-4">Salon foto</h2>
            <div class="flex flex-col items-start gap-3">
                {{-- Preview: nieuw > huidig > placeholder --}}
                @if($foto)
                <img src="{{ $foto->temporaryUrl() }}"
                     class="w-32 h-32 rounded-xl object-cover border-2 border-blue-400">
                @elseif(auth()->user()->kapper->foto)
                <img src="{{ asset('storage/' . auth()->user()->kapper->foto) }}"
                     alt="Salon foto"
                     class="w-32 h-32 rounded-xl object-cover border border-gray-200 dark:border-neutral-700">
                @else
                <div class="w-32 h-32 rounded-xl overflow-hidden relative bg-white flex items-center justify-center">
                    <div class="[--white-gradient:repeating-linear-gradient(100deg,white_0%,white_7%,transparent_10%,transparent_12%,white_16%)] [--aurora:repeating-linear-gradient(100deg,var(--blue-500)_10%,var(--indigo-300)_15%,var(--blue-300)_20%,var(--violet-200)_25%,var(--blue-400)_30%)] [background-image:var(--white-gradient),var(--aurora)] [background-size:300%,_200%] [background-position:50%_50%,50%_50%] blur-[20px] absolute -inset-[10px] opacity-60 will-change-transform animate-aurora"></div>
                    <svg class="relative w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @endif

                <div class="flex items-center gap-2">
                    <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg border border-gray-200 dark:border-neutral-700 text-sm text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Foto uploaden
                        <input wire:model="foto" type="file" accept="image/*" class="hidden">
                    </label>
                    @if(auth()->user()->kapper->foto && !$foto)
                    <button type="button" @click.prevent="$dispatch('open-confirm', { title: 'Foto verwijderen', message: 'Weet je zeker dat je de salon foto wilt verwijderen?', action: () => $wire.fotoVerwijderen() })"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-lg border border-red-200 dark:border-red-900 text-sm text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Verwijder
                    </button>
                    @endif
                </div>
                <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1.5">JPG, PNG — max 2 MB</p>
                @error('foto') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Salon info --}}
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-4">Salon informatie</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Saloonnaam</label>
                    <input wire:model="salon_naam" type="text"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('salon_naam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Stad</label>
                    <input wire:model="stad" type="text"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('stad') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Telefoon</label>
                    <input wire:model="telefoon" type="text"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Adres</label>
                    <input wire:model="adres" type="text"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Bio</label>
                    <textarea wire:model="bio" rows="4" placeholder="Beschrijf je salon..."
                              class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 resize-none"></textarea>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-1">Max 1000 tekens</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    x-data="{ saved: false }"
                    @profiel-opgeslagen.window="saved = true; setTimeout(() => saved = false, 3000)"
                    :class="saved ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span x-text="saved ? 'Profiel opgeslagen!' : 'Profiel opslaan'"></span>
            </button>
            @if(auth()->user()->kapper?->slug)
            <a href="{{ route('kapper.profiel', auth()->user()->kapper->slug) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Bekijk mijn pagina
            </a>
            @endif
        </div>
    </form>

    {{-- Boekingswidget --}}
    @if(auth()->user()->kapper?->slug)
    @php
        $widgetUrl = url('/kapper/' . auth()->user()->kapper->slug . '?embed=1');
        $iframeCode = '<iframe src="' . $widgetUrl . '" width="420" height="720" frameborder="0" style="border:none; border-radius:16px; box-shadow:0 4px 32px rgba(0,0,0,0.10); max-width:100%;"></iframe>';
    @endphp
    <div class="mt-6 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6"
         x-data="{ gekopieerd: false }">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-1">Boekingswidget</h2>
        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-4">Plak deze code in je eigen website zodat klanten daar direct kunnen boeken.</p>

        <div class="relative">
            <pre class="text-xs text-gray-600 dark:text-neutral-400 bg-gray-50 dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-lg p-4 pr-24 overflow-x-auto whitespace-pre-wrap break-all select-all font-mono">{{ $iframeCode }}</pre>
            <button @click="navigator.clipboard.writeText('{{ addslashes($iframeCode) }}'); gekopieerd = true; setTimeout(() => gekopieerd = false, 2000)"
                    class="absolute top-3 right-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    :class="gekopieerd ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-200 dark:bg-neutral-700 text-gray-600 dark:text-neutral-300 hover:bg-gray-300 dark:hover:bg-neutral-600'">
                <svg x-show="!gekopieerd" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <svg x-show="gekopieerd" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span x-text="gekopieerd ? 'Gekopieerd!' : 'Kopieer'"></span>
            </button>
        </div>

        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-3">
            Of deel je directe boekingspagina:
            <a href="{{ url('/kapper/' . auth()->user()->kapper->slug) }}" target="_blank"
               class="text-blue-600 dark:text-blue-400 hover:underline">
                kaply.nl/kapper/{{ auth()->user()->kapper->slug }}
            </a>
        </p>
    </div>
    @endif

    {{-- Stripe Connect --}}
    <div class="mt-6 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl p-6">
        <div class="flex items-center justify-between mb-1">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-neutral-200">Online betalingen</h2>
            @if(auth()->user()->kapper?->stripe_connect_onboarded)
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                Gekoppeld
            </span>
            @elseif(auth()->user()->kapper?->stripe_connect_id)
            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">Verificatie vereist</span>
            @else
            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-neutral-700 text-gray-500 dark:text-neutral-400">Niet gekoppeld</span>
            @endif
        </div>

        @if(auth()->user()->kapper?->stripe_connect_onboarded)
        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-4">Je Stripe account is gekoppeld. Klanten kunnen nu online betalen bij het boeken.</p>
        <a href="{{ route('kapper.stripe.dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 dark:bg-neutral-700 text-gray-700 dark:text-neutral-200 hover:bg-gray-200 dark:hover:bg-neutral-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Stripe Dashboard openen
        </a>
        @elseif(auth()->user()->kapper?->stripe_connect_id)
        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-4">Je Stripe account aanmaak is nog niet afgerond. Voltooi de verificatie om online betalingen te accepteren.</p>
        <a href="{{ route('kapper.stripe.onboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-yellow-500 text-white hover:bg-yellow-600 transition-colors">
            Verificatie voltooien →
        </a>
        @else
        <p class="text-xs text-gray-400 dark:text-neutral-500 mb-4">Verbind je Stripe account zodat klanten direct bij jou kunnen betalen bij het boeken. Je beheert je eigen uitbetalingen via Stripe.</p>
        <a href="{{ route('kapper.stripe.onboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Stripe account verbinden
        </a>
        @endif
    </div>
</div>
