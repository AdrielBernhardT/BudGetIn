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
            alert(config.notSupportedMessage);
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
            alert(config.failedMessage);
        } finally {
            this.loading = false;
        }
    },
});
