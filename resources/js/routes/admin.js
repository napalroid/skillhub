// Admin dashboard bundle - lazy load untuk halaman admin
// Ini akan di-load HANYA di halaman admin/*

import gsap from 'gsap';
import '../animations/AdminMount.jsx';

// Expose GSAP globally for admin layout animations
window.gsap = gsap;

console.log('✅ Admin bundle loaded');
console.log('✅ GSAP loaded:', !!window.gsap);
console.log('✅ Alpine loaded:', !!window.Alpine);

// Debug: Check animation setup
document.addEventListener('DOMContentLoaded', () => {
    console.log('🔍 Checking animation elements:');
    console.log('  - .stat-card elements:', document.querySelectorAll('.stat-card').length);
    console.log('  - [data-stagger-container] elements:', document.querySelectorAll('[data-stagger-container]').length);
    console.log('  - [data-stagger-item] elements:', document.querySelectorAll('[data-stagger-item]').length);
});
