
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

console.log('✅ Alpine.js loaded');

// Check if service editor exists
const serviceEditorRoot = document.getElementById('skillhub-service-editor');
if (serviceEditorRoot) {
    console.log('🔄 Loading Service Editor...');
    import('./components/ServiceEditor.jsx')
        .then(() => {
            console.log('✅ Service Editor loaded successfully');
        })
        .catch(err => {
            console.error('❌ Failed to load Service Editor:', err);
        });
}

// Check if navigation menu exists
const menuRoot = document.getElementById('skillhub-staggered-menu');

if (menuRoot) {
    console.log('🔄 Loading navigation modules...');

    // Navigation must not wait for WebSocket or chat modules. Loading those
    // sequentially delayed the only visible navigation on every page.
    const menuPromise = import('./components/StaggeredMenu.jsx')
        .then(() => console.log('✅ Staggered menu loaded successfully'));

    const echoPromise = import('./echo');
    const realtimePromise = echoPromise
        .then(() => {
            const modules = [import('./notification-listener')];

            if (document.getElementById('skillhub-chat')) {
                modules.push(import('./chat-realtime'));
            }

            return Promise.all(modules);
        })
        .then(() => console.log('✅ Realtime modules loaded successfully'));

    Promise.all([menuPromise, realtimePromise])
        .then(() => console.log('✅ Navigation & realtime modules loaded successfully'))
        .catch(err => console.error('❌ Failed to load navigation modules:', err));
} else {
    console.log('✅ Core bundle loaded (minimal - no navigation)');
}

if (document.getElementById('marketplace-image-stack')) {
    import('./components/MarketplaceImageStack.jsx')
        .catch(err => console.error('Failed to load marketplace image stack:', err));
}

const typewriterSearch = document.querySelector('[data-typewriter-search]');

if (typewriterSearch && !typewriterSearch.value && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    const phrases = ['Desain logo untuk acara sekolah', 'Landing page untuk proyekmu', 'Editor video yang kamu butuhkan', 'Bantuan coding dan presentasi'];
    let phrase = 0;
    let character = 0;
    let deleting = false;
    let timer;

    const type = () => {
        const text = phrases[phrase];
        typewriterSearch.placeholder = text.slice(0, character);

        if (!deleting && character === text.length) {
            deleting = true;
            timer = window.setTimeout(type, 1600);
            return;
        }
        if (deleting && character === 0) {
            deleting = false;
            phrase = (phrase + 1) % phrases.length;
        }

        character += deleting ? -1 : 1;
        timer = window.setTimeout(type, deleting ? 34 : 56);
    };

    const stop = () => window.clearTimeout(timer);
    typewriterSearch.addEventListener('focus', stop, { once: true });
    type();
}
