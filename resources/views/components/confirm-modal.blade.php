<div
    x-data="{
        open: false,
        title: '',
        message: '',
        action: null,
        init() {
            window.addEventListener('open-confirm', (e) => {
                this.title   = e.detail.title   ?? 'Bevestigen';
                this.message = e.detail.message ?? 'Weet je het zeker?';
                this.action  = e.detail.action  ?? null;
                this.open    = true;
            });
        },
        confirm() {
            if (this.action) this.action();
            this.open = false;
        }
    }"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-4"
    style="display: none"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

    {{-- Modal --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
        class="relative w-full sm:max-w-sm bg-white dark:bg-neutral-800 sm:rounded-2xl rounded-t-2xl shadow-2xl overflow-hidden"
    >
        {{-- Drag handle mobiel --}}
        <div class="sm:hidden flex justify-center pt-3 pb-1">
            <div class="w-10 h-1 bg-gray-200 dark:bg-neutral-600 rounded-full"></div>
        </div>

        <div class="px-5 pt-4 pb-6">
            {{-- Icoon --}}
            <div class="w-11 h-11 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <h3 class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-1" x-text="title"></h3>
            <p class="text-sm text-gray-500 dark:text-neutral-400 mb-5" x-text="message"></p>

            <div class="flex gap-2">
                <button @click="open = false"
                        class="flex-1 py-2.5 text-sm font-medium rounded-lg border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-400 hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                    Annuleer
                </button>
                <button @click="confirm()"
                        class="flex-1 py-2.5 text-sm font-semibold rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors">
                    Bevestigen
                </button>
            </div>
        </div>
    </div>
</div>
