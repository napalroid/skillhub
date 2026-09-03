import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

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
        react(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Route-specific bundles untuk code splitting
                'resources/js/routes/welcome.js',
                'resources/js/routes/wallet.js',
                'resources/js/routes/admin.js',
            ],
            refresh: true,
        }),
    ],

    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    // Vendor splitting untuk optimal caching
                    if (id.includes('node_modules')) {
                        if (id.includes('react') || id.includes('react-dom')) {
                            return 'react-vendor';
                        }
                        if (id.includes('framer-motion')) {
                            return 'framer-motion-vendor';
                        }
                        if (id.includes('@tabler/icons-react')) {
                            return 'tabler-icons';
                        }
                    }
                },
            },
        },
        chunkSizeWarningLimit: 1000,
    },
});