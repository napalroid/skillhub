import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (csrfToken) {
    const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;
    const publicReverbHost = import.meta.env.VITE_REVERB_PUBLIC_HOST;
    const isNgrokPage = window.location.hostname.endsWith('ngrok-free.dev');
    const usePublicTunnel = isNgrokPage;
    const reverbScheme = usePublicTunnel ? 'https' : (import.meta.env.VITE_REVERB_SCHEME || 'http');
    const reverbHost = usePublicTunnel ? (publicReverbHost || window.location.hostname) : (import.meta.env.VITE_REVERB_HOST || window.location.hostname);
    const reverbPort = Number(usePublicTunnel ? 443 : (import.meta.env.VITE_REVERB_PORT || 8080));

    if (!reverbKey) {
        console.error('SkillHub realtime tidak aktif: VITE_REVERB_APP_KEY belum dikonfigurasi.');
    }

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
}
