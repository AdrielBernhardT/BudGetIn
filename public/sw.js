// Service worker for Web Push notifications (BudGetIn)
// console.log('swjs loaded');

self.addEventListener('install', () => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('push', (event) => {
    if (!event.data) {
        return;
    }

    let payload;
    try {
        payload = event.data.json();
    } catch (e) {
        payload = { title: 'BudGetIn', body: event.data.text() };
    }

    const title = payload.title || 'BudGetIn';
    // console.log(payload);
    const options = {
        body: payload.body || '',
        icon: payload.icon || '/images/logo/logo-icon.png',
        badge: payload.badge || '/images/logo/logo-icon.png',
        image: payload.image || undefined,
        actions: payload.actions || [],
        data: payload.data || {},
        tag: payload.tag || undefined,
        requireInteraction: payload.requireInteraction || false,
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const url = (event.notification.data && event.notification.data.url) || '/dashboard';

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }
            if (self.clients.openWindow) {
                return self.clients.openWindow(url);
            }
        })
    );
});
