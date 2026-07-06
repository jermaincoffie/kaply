const CACHE = 'kaply-v3';

self.addEventListener('push', function (event) {
    if (!event.data) return;
    const data = event.data.json();
    event.waitUntil(
        self.registration.showNotification(data.title || 'Kaply', {
            body:    data.body  || '',
            icon:    data.icon  || '/images/PWA-icon-192.png',
            badge:   data.badge || '/images/PWA-icon-192.png',
            data:    data.data  || {},
            vibrate: [200, 100, 200],
        })
    );
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
