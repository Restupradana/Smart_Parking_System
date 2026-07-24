import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// ─── Pusher Real-Time Listener ────────────────────────
if (typeof window.Echo !== 'undefined') {
    window.Echo.channel('parking-channel')
        .listen('.App\\Events\\ParkingUpdated', (e) => {
            // Reload dashboard when parking status changes
            if (document.querySelector('[data-parking-dashboard]')) {
                window.location.reload();
            }
        });
}
