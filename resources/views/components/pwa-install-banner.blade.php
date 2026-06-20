<div x-data="pwaBanner()" x-show="tonen" x-cloak
     class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-sm px-4">
    <div class="bg-neutral-900 border border-neutral-700 rounded-2xl shadow-2xl p-4 flex items-center gap-3">
        <img src="/images/PWA-icon-192.png" class="w-12 h-12 rounded-xl flex-shrink-0" alt="Kaply">
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-white">Voeg Kaply toe aan je scherm</p>
            <p x-show="isIos" class="text-xs text-neutral-200 mt-0.5">
                Tik op het deel-icoon <svg class="inline w-3.5 h-3.5 mb-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2v13M7 7l5-5 5 5M3 18h18v3H3z"/></svg> onderaan Safari, scroll omlaag en kies "Zet op beginscherm"
            </p>
            <p x-show="!isIos" class="text-xs text-neutral-200 mt-0.5">Installeer de app voor snelle toegang</p>
        </div>
        <div class="flex flex-col gap-1.5 flex-shrink-0">
            <button x-show="!isIos" @click="installeren()"
                    class="text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg transition-colors">
                Installeer
            </button>
            <button @click="sluiten()"
                    class="text-xs text-neutral-500 hover:text-neutral-300 transition-colors">
                Sluiten
            </button>
        </div>
    </div>
</div>

<script>
function pwaBanner() {
    return {
        tonen: false,
        isIos: false,
        deferredPrompt: null,
        init() {
            if (localStorage.getItem('kaply_pwa_gesloten')) return;
            if (window.matchMedia('(display-mode: standalone)').matches) return;
            if (window.navigator.standalone === true) return;
            if (window.innerWidth >= 768) return;

            const ios = /iphone|ipad|ipod/i.test(navigator.userAgent) && !window.MSStream;
            const safari = /safari/i.test(navigator.userAgent) && !/chrome|crios|fxios/i.test(navigator.userAgent);

            if (ios && safari) {
                this.isIos = true;
                setTimeout(() => { this.tonen = true; }, 3000);
                return;
            }

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.deferredPrompt = e;
                setTimeout(() => { this.tonen = true; }, 3000);
            });
        },
        async installeren() {
            if (!this.deferredPrompt) return;
            this.deferredPrompt.prompt();
            const { outcome } = await this.deferredPrompt.userChoice;
            this.deferredPrompt = null;
            this.tonen = false;
            if (outcome === 'accepted') localStorage.setItem('kaply_pwa_gesloten', '1');
        },
        sluiten() {
            this.tonen = false;
            localStorage.setItem('kaply_pwa_gesloten', '1');
        }
    }
}
</script>
