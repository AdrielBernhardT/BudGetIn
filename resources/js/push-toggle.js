// resources/js/push-toggle.js
// Thin Alpine wrapper around window.PushNotifications (defined in push-notifications.js).
// Import this AFTER push-notifications.js and BEFORE Alpine.start() in app.js.

window.pushToggle = (config) => ({
    subscribed: config.initialSubscribed,
    loading: false,

    async init() {
        // The DB row can say "subscribed" while the browser permission was
        // revoked outside the app (browser settings, OS settings, etc).
        // Keep the switch honest on load instead of trusting the DB blindly.
        if (this.subscribed && !(await window.PushNotifications.isSubscribed())) {
            this.subscribed = false;
        }
    },

    async toggle() {
        if (!window.PushNotifications.isSupported()) {
            this.notify(config.notSupportedMessage);
            this.subscribed = false;
            return;
        }

        this.loading = true;

        try {
            if (this.subscribed) {
                await window.PushNotifications.subscribe();
            } else {
                await window.PushNotifications.unsubscribe();
            }
        } catch (error) {
            console.error(error);
            this.subscribed = !this.subscribed; // revert the switch on failure
            this.notify(config.failedMessage);
        } finally {
            this.loading = false;
        }
    },
    notify(message) {
        window.Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'custom-popup'
            },
        });
    },
});
