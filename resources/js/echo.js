import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

console.log('[Echo] echo.js loaded!');

function initializeEcho() {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (!csrfToken) {
            console.error('[Echo] CSRF token not found in meta tag!');
            return;
        }

        const reverbKey = '32d226a6168ea850466a7dca5de615ed';
        const reverbHost = '127.0.0.1';
        const reverbPort = 8080;
        const reverbScheme = 'http';

        window.Pusher = Pusher;
        
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: reverbKey,
            wsHost: reverbHost,
            wsPort: reverbPort,
            wssPort: reverbPort,
            forceTLS: reverbScheme === 'https',
            enabledTransports: ['ws', 'wss'],
            auth: { headers: { 'X-CSRF-TOKEN': csrfToken } },
        });
        
        console.log('[Echo] Echo initialized');

        if (window.Echo.connector && window.Echo.connector.pusher) {
            Pusher.logToConsole = false;

            window.Echo.connector.pusher.connection.bind('error', (err) => {
                console.error('[Echo] Connection error:', err);
            });

            window.Echo.connector.pusher.config.enableStats = false;
            window.Echo.connector.pusher.config.activityTimeout = 120000;
            window.Echo.connector.pusher.config.pongTimeout = 30000;
        }
    } catch (err) {
        console.error('[Echo] Failed to initialize Echo:', err.message);
        window.Echo = null;
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeEcho);
} else {
    initializeEcho();
}
