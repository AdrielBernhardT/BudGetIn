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
        payload = { title: 'BudGetIn', body: event.data.text() };
    }

    // WebPushMessage::toArray() (laravel-notification-channels/webpush) sends a FLAT
    // payload — {title, body, icon, badge, data, actions, ...} at the top level, not
    // nested under a "notification" key. Read fields directly from `payload`.
    const title = payload.title || 'BudGetIn';
    const options = {
        body: payload.body || '',
        icon: payload.icon || '/images/logo/logo-icon.png',
        badge: payload.badge || '/images/logo/logo-icon.png',
        data: payload.data || {},
        tag: payload.tag || undefined,
        requireInteraction: payload.requireInteraction || false,
        actions: payload.actions || [],
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
