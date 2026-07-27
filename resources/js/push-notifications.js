// Web Push (Browser Push API) subscribe/unsubscribe helper for BudGetIn.

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }

    return outputArray;
}

const PushNotifications = {
    isSupported() {
        return 'serviceWorker' in navigator && 'PushManager' in window;
    },

    async register() {
        return navigator.serviceWorker.register('/sw.js');
    },

    async isSubscribed() {
        if (!this.isSupported()) {
            return false;
        }

        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.getSubscription();

        return !!subscription;
    },

    async subscribe() {
        if (!this.isSupported()) {
            throw new Error('Your browser does not support Web Push notifications.');
        }

        const vapidPublicKey = document.querySelector('meta[name="vapid-public-key"]')?.content;

        if (!vapidPublicKey) {
            throw new Error('VAPID public key has not been configured on the server.');
        }

        const permission = await Notification.requestPermission();

        if (permission !== 'granted') {
            throw new Error('Notification permission was not granted.');
        }

        const registration = await this.register();
        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
        });

        await window.axios.post('/push-subscriptions', subscription.toJSON());

        return subscription;
    },

    async unsubscribe() {
        if (!this.isSupported()) {
            return;
        }

        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.getSubscription();

        if (!subscription) {
            return;
        }

        await window.axios.delete('/push-subscriptions', { data: { endpoint: subscription.endpoint } });
        await subscription.unsubscribe();
    },
};

window.PushNotifications = PushNotifications;

export default PushNotifications;
