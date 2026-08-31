import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    server: {
        host: true,
        https: true,
        hmr: {
            host: 'elastic-landmass-shortcut.ngrok-free.dev',
            protocol: 'wss',
        },
    },


    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});