
import Alpine from 'alpinejs';
import './echo';
import './notification-listener';
import './chat-realtime';

// HANYA komponen global yang dibutuhkan di SEMUA halaman
// StaggeredMenu adalah navigasi yang muncul di semua halaman
import './components/StaggeredMenu.jsx';

window.Alpine = Alpine;
Alpine.start();

console.log('✅ Core bundle loaded (global components only)');
