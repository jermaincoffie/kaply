<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/kaply-logo-light.png') }}" class="h-16 w-auto mx-auto dark:hidden" alt="Kaply">
            <img src="{{ asset('images/dark modus kaply bg removed.PNG') }}" class="h-16 w-auto mx-auto hidden dark:block" alt="Kaply" >
        </div>

        <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-gray-200 dark:border-neutral-700 shadow-sm p-8">

            @if($stap === 'email')
            {{-- Stap 1: email --}}
            <div>
                <h1 class="text-lg font-bold text-gray-900 dark:text-neutral-100 mb-1">Inloggen</h1>
                <p class="text-sm text-gray-500 dark:text-neutral-400 mb-6">Vul je emailadres in en ontvang een inlogcode.</p>

                @if($fout)
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-600 dark:text-red-400">
                    {{ $fout }}
                </div>
                @endif

                <form wire:submit="verstuurCode" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1.5">Emailadres</label>
                        <input wire:model="email" type="email" autocomplete="email" autofocus placeholder="jouw@email.nl"
                               class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                        <span wire:loading.remove>Stuur inlogcode</span>
                        <span wire:loading>Code versturen...</span>
                    </button>
                </form>

                <p class="text-center text-xs text-gray-400 dark:text-neutral-500 mt-5">
                    Ben je kapper?
                    <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Kapper inloggen</a>
                </p>
            </div>

            @elseif($stap === 'profiel')
            {{-- Stap 2: profiel (alleen voor nieuwe gebruikers) --}}
            <div>
                <button type="button"
                        onclick="kaplyLW(this,'terugNaarEmail')"
                        class="flex items-center gap-1 text-sm text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 mb-5 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Terug
                </button>

                <h1 class="text-lg font-bold text-gray-900 dark:text-neutral-100 mb-1">Account aanmaken</h1>
                <p class="text-sm text-gray-500 dark:text-neutral-400 mb-6">Vul je gegevens in. Daarna ontvang je een inlogcode op <span class="font-medium text-gray-700 dark:text-neutral-300">{{ $email }}</span>.</p>

                @if($fout)
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-600 dark:text-red-400">
                    {{ $fout }}
                </div>
                @endif

                <form id="kaply-profiel-form" onsubmit="kaplyProfielVerzend(event)" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1.5">Voornaam</label>
                            <input name="voornaam" type="text" autocomplete="given-name" autofocus placeholder="Jan"
                                   class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('voornaam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1.5">Achternaam</label>
                            <input name="achternaam" type="text" autocomplete="family-name" placeholder="Jansen"
                                   class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('achternaam') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1.5">Telefoonnummer</label>
                        <input name="telefoon" type="tel" autocomplete="tel" placeholder="06 12345678"
                               class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('telefoon') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit"
                            id="kaply-profiel-btn"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                        Verstuur inlogcode
                    </button>
                </form>
            </div>

            @else
            {{-- Stap 3: code --}}
            <div>
                <button type="button"
                        onclick="kaplyLW(this,'terugNaarEmail')"
                        class="flex items-center gap-1 text-sm text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 mb-5 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Terug
                </button>

                <h1 class="text-lg font-bold text-gray-900 dark:text-neutral-100 mb-1">Code invoeren</h1>
                <p class="text-sm text-gray-500 dark:text-neutral-400 mb-6">
                    We hebben een 6-cijferige code gestuurd naar<br>
                    <span class="font-medium text-gray-700 dark:text-neutral-300">{{ $email }}</span>
                </p>

                @if($fout)
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-600 dark:text-red-400">
                    {{ $fout }}
                </div>
                @endif

                <form id="kaply-otp-form" onsubmit="kaplyOtpVerzend(event)" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-3 text-center">Inlogcode</label>
                        <div class="flex gap-2 justify-center" id="kaply-otp-velden">
                            <input data-otp type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code"
                                   oninput="kaplyOtpInput(this)" onkeydown="kaplyOtpKeydown(event,this)" onpaste="kaplyOtpPlak(event,this)"
                                   class="w-11 h-14 text-center text-2xl font-bold rounded-xl border-2 border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 focus:outline-none focus:border-blue-500 transition-colors">
                            <input data-otp type="text" inputmode="numeric" maxlength="1"
                                   oninput="kaplyOtpInput(this)" onkeydown="kaplyOtpKeydown(event,this)" onpaste="kaplyOtpPlak(event,this)"
                                   class="w-11 h-14 text-center text-2xl font-bold rounded-xl border-2 border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 focus:outline-none focus:border-blue-500 transition-colors">
                            <input data-otp type="text" inputmode="numeric" maxlength="1"
                                   oninput="kaplyOtpInput(this)" onkeydown="kaplyOtpKeydown(event,this)" onpaste="kaplyOtpPlak(event,this)"
                                   class="w-11 h-14 text-center text-2xl font-bold rounded-xl border-2 border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 focus:outline-none focus:border-blue-500 transition-colors">
                            <input data-otp type="text" inputmode="numeric" maxlength="1"
                                   oninput="kaplyOtpInput(this)" onkeydown="kaplyOtpKeydown(event,this)" onpaste="kaplyOtpPlak(event,this)"
                                   class="w-11 h-14 text-center text-2xl font-bold rounded-xl border-2 border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 focus:outline-none focus:border-blue-500 transition-colors">
                            <input data-otp type="text" inputmode="numeric" maxlength="1"
                                   oninput="kaplyOtpInput(this)" onkeydown="kaplyOtpKeydown(event,this)" onpaste="kaplyOtpPlak(event,this)"
                                   class="w-11 h-14 text-center text-2xl font-bold rounded-xl border-2 border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 focus:outline-none focus:border-blue-500 transition-colors">
                            <input data-otp type="text" inputmode="numeric" maxlength="1"
                                   oninput="kaplyOtpInput(this)" onkeydown="kaplyOtpKeydown(event,this)" onpaste="kaplyOtpPlak(event,this)"
                                   class="w-11 h-14 text-center text-2xl font-bold rounded-xl border-2 border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>
                        @error('code') <p class="text-xs text-red-500 mt-2 text-center">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                        <span wire:loading.remove>Inloggen</span>
                        <span wire:loading>Inloggen...</span>
                    </button>
                </form>

                <p class="text-center text-xs text-gray-400 dark:text-neutral-500 mt-5">
                    Geen code ontvangen?
                    <button type="button" onclick="kaplyLW(this,'terugNaarEmail')" class="text-blue-600 dark:text-blue-400 hover:underline">Opnieuw versturen</button>
                </p>
            </div>
            @endif

        </div>
    </div>
</div>

@script
<script>
window.kaplyLW = function(el, method) {
    var root = (el && el.closest) ? el.closest('[wire\\:id]') : null;
    if (!root) root = document.querySelector('[wire\\:id]');
    if (root) Livewire.find(root.getAttribute('wire:id')).call(method);
};

window.kaplyOtpInput = function(el) {
    var v = el.value.replace(/\D/g, '').slice(-1);
    el.value = v;
    if (v && el.nextElementSibling) el.nextElementSibling.focus();
};

window.kaplyOtpKeydown = function(e, el) {
    if (e.key === 'Backspace' && !el.value && el.previousElementSibling) {
        el.previousElementSibling.value = '';
        el.previousElementSibling.focus();
    }
};

window.kaplyOtpPlak = function(e, el) {
    e.preventDefault();
    var tekst = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
    var velden = document.querySelectorAll('#kaply-otp-velden [data-otp]');
    velden.forEach(function(inp, i) { inp.value = tekst[i] || ''; });
    var fi = Math.min(tekst.length, velden.length - 1);
    if (velden[fi]) velden[fi].focus();
    if (tekst.length === 6) {
        var form = document.getElementById('kaply-otp-form');
        if (form) form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
    }
};

window.kaplyProfielVerzend = function(e) {
    e.preventDefault();
    var form = e.target;
    var v = form.querySelector('[name=voornaam]').value;
    var a = form.querySelector('[name=achternaam]').value;
    var t = form.querySelector('[name=telefoon]').value;
    var btn = document.getElementById('kaply-profiel-btn');
    if (btn) btn.disabled = true;
    var root = form.closest('[wire\\:id]') || document.querySelector('[wire\\:id]');
    if (root) Livewire.find(root.getAttribute('wire:id')).call('vulProfielIn', v, a, t);
};

window.kaplyOtpVerzend = function(e) {
    e.preventDefault();
    var velden = document.querySelectorAll('#kaply-otp-velden [data-otp]');
    var code = Array.from(velden).map(function(i) { return i.value; }).join('');
    var root = (e.target && e.target.closest) ? e.target.closest('[wire\\:id]') : null;
    if (!root) root = document.querySelector('[wire\\:id]');
    if (root) Livewire.find(root.getAttribute('wire:id')).call('verifieerCode', code);
};

window.addEventListener('otp-fokus', function() {
    setTimeout(function() {
        var el = document.querySelector('#kaply-otp-velden [data-otp]');
        if (el) el.focus();
    }, 100);
});
</script>
@endscript
