const CACHE = 'kaply-v6';

self.addEventListener('push', function (event) {
    event.waitUntil((async () => {
        try {
            let title = 'Kaply';
            let body  = 'Nieuwe melding';
            let icon  = '/images/PWA-icon-192.png';
            let data  = {};

            if (event.data) {
                try {
                    const parsed = event.data.json();
                    title = parsed.title || title;
                    body  = parsed.body  || body;
                    icon  = parsed.icon  || icon;
                    data  = parsed.data  || data;
                } catch (e) {
                    body = event.data.text() || body;
                }
            }

            await self.registration.showNotification(title, {
                body: body,
                icon: icon,
                data: data,
            });
        } catch (err) {
            await self.registration.showNotification('Kaply debug', {
                body: 'SW fout: ' + (err && err.message ? err.message : String(err)),
                icon: '/images/PWA-icon-192.png',
            });
        }
    })());
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    const url = (event.notification.data?.url || '/agenda');
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (list) {
            for (const client of list) {
                if ('focus' in client) return client.focus();
            }
            if (clients.openWindow) return clients.openWindow(url);
        })
    );
});
const STATIC = [
    '/offline.html',
];

self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(CACHE).then(c => c.addAll(STATIC))
    );
    self.skipWaiting();
});

self.addEventListener('activate', e => {
    e.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE).map(k => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', e => {
    if (e.request.method !== 'GET') return;
    if (e.request.url.includes('/livewire/')) return;
    if (e.request.url.includes('/stripe/')) return;

    // Statische assets: cache first
    if (e.request.destination === 'style' || e.request.destination === 'script' || e.request.destination === 'image') {
        e.respondWith(
            caches.match(e.request).then(cached => cached || fetch(e.request).then(res => {
                const clone = res.clone();
                caches.open(CACHE).then(c => c.put(e.request, clone));
                return res;
            }))
        );
        return;
    }

    // Pagina's: network first, offline fallback
    e.respondWith(
        fetch(e.request).catch(() => caches.match('/offline.html'))
    );
});
