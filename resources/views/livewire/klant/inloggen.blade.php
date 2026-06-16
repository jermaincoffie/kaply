<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/kaply-logo-light.png') }}" class="h-16 w-auto mx-auto dark:hidden" alt="Kaply">
            <img src="{{ asset('images/kaply-logo-dark.png') }}" class="h-16 w-auto mx-auto hidden dark:block" alt="Kaply">
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

            @else
            {{-- Stap 2: code --}}
            <div>
                <button wire:click="terugNaarEmail" class="flex items-center gap-1 text-sm text-gray-400 hover:text-gray-600 dark:hover:text-neutral-300 mb-5 transition-colors">
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

                <form wire:submit="verifieerCode" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-3 text-center">Inlogcode</label>

                        <div
                            x-data="{
                                digits: ['','','','','',''],
                                init() { this.$nextTick(() => this.$refs.d0.focus()); },
                                onInput(idx, e) {
                                    const v = e.target.value.replace(/\D/g,'').slice(-1);
                                    this.digits[idx] = v;
                                    e.target.value = v;
                                    $wire.set('code', this.digits.join(''));
                                    if (v && idx < 5) this.$refs['d'+(idx+1)].focus();
                                    if (v && idx === 5 && this.digits.join('').length === 6) $wire.call('verifieerCode');
                                },
                                onKeydown(idx, e) {
                                    if (e.key === 'Backspace' && !e.target.value && idx > 0) {
                                        this.digits[idx-1] = '';
                                        this.$refs['d'+(idx-1)].value = '';
                                        this.$refs['d'+(idx-1)].focus();
                                        $wire.set('code', this.digits.join(''));
                                    }
                                },
                                onPaste(e) {
                                    const text = (e.clipboardData||window.clipboardData).getData('text').replace(/\D/g,'').slice(0,6);
                                    e.preventDefault();
                                    for (let i = 0; i < 6; i++) {
                                        this.digits[i] = text[i] ?? '';
                                        if (this.$refs['d'+i]) this.$refs['d'+i].value = text[i] ?? '';
                                    }
                                    $wire.set('code', this.digits.join(''));
                                    const last = Math.min(text.length, 5);
                                    this.$refs['d'+last].focus();
                                    if (text.length === 6) $wire.call('verifieerCode');
                                }
                            }"
                            class="flex gap-2 justify-center"
                        >
                            <input x-ref="d0" @input="onInput(0,$event)" @keydown="onKeydown(0,$event)" @paste.prevent="onPaste($event)"
                                   type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code"
                                   class="w-11 h-14 text-center text-2xl font-bold rounded-xl border-2 border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 focus:outline-none focus:border-blue-500 transition-colors">
                            <input x-ref="d1" @input="onInput(1,$event)" @keydown="onKeydown(1,$event)" @paste.prevent="onPaste($event)"
                                   type="text" inputmode="numeric" maxlength="1"
                                   class="w-11 h-14 text-center text-2xl font-bold rounded-xl border-2 border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 focus:outline-none focus:border-blue-500 transition-colors">
                            <input x-ref="d2" @input="onInput(2,$event)" @keydown="onKeydown(2,$event)" @paste.prevent="onPaste($event)"
                                   type="text" inputmode="numeric" maxlength="1"
                                   class="w-11 h-14 text-center text-2xl font-bold rounded-xl border-2 border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 focus:outline-none focus:border-blue-500 transition-colors">
                            <input x-ref="d3" @input="onInput(3,$event)" @keydown="onKeydown(3,$event)" @paste.prevent="onPaste($event)"
                                   type="text" inputmode="numeric" maxlength="1"
                                   class="w-11 h-14 text-center text-2xl font-bold rounded-xl border-2 border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 focus:outline-none focus:border-blue-500 transition-colors">
                            <input x-ref="d4" @input="onInput(4,$event)" @keydown="onKeydown(4,$event)" @paste.prevent="onPaste($event)"
                                   type="text" inputmode="numeric" maxlength="1"
                                   class="w-11 h-14 text-center text-2xl font-bold rounded-xl border-2 border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 focus:outline-none focus:border-blue-500 transition-colors">
                            <input x-ref="d5" @input="onInput(5,$event)" @keydown="onKeydown(5,$event)" @paste.prevent="onPaste($event)"
                                   type="text" inputmode="numeric" maxlength="1"
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
                    <button wire:click="terugNaarEmail" class="text-blue-600 dark:text-blue-400 hover:underline">Opnieuw versturen</button>
                </p>
            </div>
            @endif

        </div>
    </div>
</div>
