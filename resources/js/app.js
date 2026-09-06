
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

console.log('✅ Alpine.js loaded');

// Check if navigation menu exists
const menuRoot = document.getElementById('skillhub-staggered-menu');

if (menuRoot) {
    console.log('🔄 Loading navigation modules...');
    
    // Load modules sequentially to ensure proper initialization
    import('./echo')
        .then(() => import('./notification-listener'))
        .then(() => import('./chat-realtime'))
        .then(() => import('./components/StaggeredMenu.jsx'))
        .then(() => {
            console.log('✅ Navigation & realtime modules loaded successfully');
        })
        .catch(err => {
            console.error('❌ Failed to load navigation modules:', err);
        });
} else {
    console.log('✅ Core bundle loaded (minimal - no navigation)');
}
