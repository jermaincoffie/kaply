<div class="min-h-screen bg-gray-50 dark:bg-neutral-900 flex flex-col items-center justify-center px-4 py-12">

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="mb-8 hover:opacity-80 transition-opacity">
        <img src="{{ asset('images/kaply-logo-light.png') }}" class="block dark:hidden h-16 w-auto" alt="Kaply">
        <img src="{{ asset('images/dark modus kaply bg removed.PNG') }}" class="hidden dark:block h-16 w-auto" alt="Kaply">
    </a>

    {{-- Card --}}
    <div class="w-full max-w-md bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl shadow-sm overflow-hidden">

        @if($stap < 3)
        {{-- Stap indicator --}}
        <div class="px-6 pt-6 pb-4">
            <div class="flex items-center gap-3">
                @foreach([1 => 'Account', 2 => 'Salon'] as $n => $label)
                <div class="flex items-center gap-2 {{ $loop->first ? '' : 'flex-1' }}">
                    @if(!$loop->first)
                    <div class="flex-1 h-px {{ $stap > 1 ? 'bg-blue-600' : 'bg-gray-200 dark:bg-neutral-700' }}"></div>
                    @endif
                    <div class="flex items-center gap-1.5">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                            {{ $stap > $n ? 'bg-blue-600 text-white' : ($stap === $n ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-neutral-700 text-gray-400 dark:text-neutral-500') }}">
                            @if($stap > $n)
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                            @else
                            {{ $n }}
                            @endif
                        </div>
                        <span class="text-xs font-medium {{ $stap === $n ? 'text-gray-800 dark:text-neutral-200' : 'text-gray-400 dark:text-neutral-500' }}">
                            {{ $label }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="h-px bg-gray-100 dark:bg-neutral-700"></div>
        @endif

        {{-- ===== STAP 1: Account ===== --}}
        @if($stap === 1)
        <div class="px-6 py-6">
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100 mb-1">Maak je account aan</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mb-6">Je persoonlijke inloggegevens</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Naam</label>
                    <input wire:model="name" type="text" autocomplete="name" placeholder="Jan Jansen"
                           class="w-full py-2 px-3 rounded-lg border bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-1 transition-colors
                                  {{ $errors->has('name') ? 'border-red-400 dark:border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 dark:border-neutral-700 focus:border-blue-600 focus:ring-blue-600' }}">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">E-mailadres</label>
                    <input wire:model="email" type="email" autocomplete="email" placeholder="jan@jouwsalon.nl"
                           class="w-full py-2 px-3 rounded-lg border bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-1 transition-colors
                                  {{ $errors->has('email') ? 'border-red-400 dark:border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 dark:border-neutral-700 focus:border-blue-600 focus:ring-blue-600' }}">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                            @if(str_contains($message, 'al geregistreerd'))
                                <a href="{{ route('login') }}" class="underline font-medium">Inloggen?</a>
                            @endif
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Wachtwoord</label>
                    <input wire:model="password" type="password" autocomplete="new-password" placeholder="Minimaal 8 tekens"
                           class="w-full py-2 px-3 rounded-lg border bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-1 transition-colors
                                  {{ $errors->has('password') ? 'border-red-400 dark:border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 dark:border-neutral-700 focus:border-blue-600 focus:ring-blue-600' }}">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Wachtwoord bevestigen</label>
                    <input wire:model="password_confirmation" type="password" autocomplete="new-password" placeholder="Herhaal wachtwoord"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-colors">
                </div>

                <div>
                    <label class="flex items-start gap-2.5 cursor-pointer">
                        <input wire:model="akkoordVoorwaarden" type="checkbox"
                               class="mt-0.5 w-4 h-4 rounded border-gray-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500 flex-shrink-0">
                        <span class="text-xs text-gray-500 dark:text-neutral-400 leading-relaxed">
                            Ik ga akkoord met de
                            <a href="{{ route('voorwaarden') }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">algemene voorwaarden</a>
                            en het
                            <a href="{{ route('privacy') }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">privacybeleid</a>
                            van Kaply.
                        </span>
                    </label>
                    @error('akkoordVoorwaarden') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <button type="button" wire:click="volgende" wire:loading.attr="disabled"
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-colors mt-2 flex items-center justify-center gap-2">
                    <svg wire:loading wire:target="volgende" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span wire:loading.remove wire:target="volgende">Volgende</span>
                    <span wire:loading wire:target="volgende">Bezig...</span>
                    <svg wire:loading.remove wire:target="volgende" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- ===== STAP 2: Salon ===== --}}
        @elseif($stap === 2)
        <div class="px-6 py-6">
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1 text-xs font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Account aangemaakt
                </span>
            </div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100 mb-1">Jouw salon</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mb-6">Vul je salongegevens in om zichtbaar te worden op Kaply</p>

            <form wire:submit="registreer" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Saloonnaam</label>
                    <input wire:model="salon_naam" type="text" placeholder="Salon Jansen"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('salon_naam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Stad</label>
                        <input wire:model="stad" type="text" placeholder="Amsterdam"
                               class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('stad') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Telefoon</label>
                        <input wire:model="telefoon" type="tel" placeholder="0612345678" maxlength="10"
                               class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('telefoon') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Adres</label>
                    <input wire:model="adres" type="text" placeholder="Kalverstraat 12"
                           class="w-full py-2 px-3 rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-sm text-gray-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('adres') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" wire:click="vorige"
                            class="flex-1 py-2.5 border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                        Terug
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        Account aanmaken
                    </button>
                </div>
            </form>
        </div>

        {{-- ===== STAP 3: Klaar ===== --}}
        @elseif($stap === 3)
        <div class="px-6 py-10 text-center">
            <div class="w-14 h-14 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-5">
                <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100 mb-2">Account aangemaakt!</h1>
            <p class="text-sm text-gray-500 dark:text-neutral-400 mb-1">
                Je salon <span class="font-medium text-gray-700 dark:text-neutral-300">{{ $salon_naam }}</span> is geregistreerd.
            </p>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mb-6 max-w-xs mx-auto">
                Je hebt een welkomstmail ontvangen. Activeer je abonnement om zichtbaar te worden op Kaply.
            </p>

            {{-- Abonnement CTA --}}
            <a href="{{ route('kapper.subscription.checkout') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                </svg>
                Abonneer nu Â· â‚¬25/maand excl. BTW
            </a>

            <div>
                <a href="{{ route('kapper.dashboard') }}"
                   class="text-xs text-gray-400 dark:text-neutral-500 hover:text-gray-600 dark:hover:text-neutral-300 transition-colors">
                    Later doen â†’ naar dashboard
                </a>
            </div>
        </div>
        @endif

        {{-- Footer --}}
        @if($stap < 3)
        <div class="px-6 pb-6 text-center">
            <p class="text-xs text-gray-400 dark:text-neutral-500">
                Al een account?
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Inloggen</a>
            </p>
        </div>
        @endif

    </div>
</div>



