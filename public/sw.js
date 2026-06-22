// Service worker for Web Push notifications (BudGetIn)

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
        payload = { notification: { title: 'BudGetIn', body: event.data.text() } };
    }

    const notification = payload.notification || {};
    const title = notification.title || 'BudGetIn';
    const options = {
        body: notification.body || '',
        icon: notification.icon || '/images/logo/logo-icon.png',
        badge: notification.badge || '/images/logo/logo-icon.png',
        data: notification.data || {},
        tag: notification.tag || undefined,
        requireInteraction: notification.requireInteraction || false,
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
