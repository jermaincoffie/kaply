<div x-data="{ tonen: !localStorage.getItem('kaply_cookie_ok') }"
     x-show="tonen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"
     style="display:none"
     class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-xl px-4">
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-2xl shadow-xl p-4 sm:p-5">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 dark:text-neutral-100 mb-1">Cookies</p>
                <p class="text-xs text-gray-500 dark:text-neutral-400 leading-relaxed">
                    Kaply gebruikt functionele cookies voor inloggen en sessies. Geen tracking of advertenties.
                    <a href="{{ route('privacy') }}" class="text-blue-600 dark:text-blue-400 hover:underline ml-1">Lees meer</a>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 mt-3">
            <button @click="localStorage.setItem('kaply_cookie_ok', '1'); tonen = false"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 rounded-xl bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition-colors">
                Akkoord
            </button>
            <a href="{{ route('privacy') }}"
               class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 rounded-xl border border-gray-200 dark:border-neutral-700 text-gray-600 dark:text-neutral-300 text-xs font-medium hover:bg-gray-50 dark:hover:bg-neutral-700 transition-colors">
                Meer info
            </a>
        </div>
    </div>
</div>
