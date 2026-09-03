# 🚀 CODE SPLITTING - DEVELOPER GUIDE

## 📖 Cara Menambah Animasi Baru

### Scenario 1: Animasi untuk Route Baru

Jika Anda ingin menambah animasi untuk route baru (contoh: `/profile`):

**1. Buat komponen animasi:**
```bash
# Buat file di animations/
resources/js/animations/ProfileAnimation.jsx
```

**2. Buat route bundle:**
```javascript
// resources/js/routes/profile.js
import '../animations/ProfileAnimation.jsx';

console.log('✅ Profile bundle loaded');
```

**3. Update vite.config.js:**
```javascript
laravel({
    input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/routes/welcome.js',
        'resources/js/routes/wallet.js',
        'resources/js/routes/admin.js',
        'resources/js/routes/profile.js',  // ← Tambahkan ini
    ],
    refresh: true,
}),
```

**4. Load di Blade template:**
```blade
{{-- resources/views/profile/index.blade.php --}}
@extends('layouts.app')

@push('scripts')
@vite('resources/js/routes/profile.js')
@endpush

@section('content')
    <div id="profile-animation"></div>
@endsection
```

**5. Build:**
```bash
npm run build
```

---

### Scenario 2: Animasi untuk Halaman yang Sudah Ada

Jika route sudah punya bundle (misal: menambah animasi ke homepage):

**1. Buat komponen:**
```javascript
// resources/js/animations/NewHomeAnimation.jsx
import React from 'react';
import { createRoot } from 'react-dom/client';
import { motion } from 'framer-motion';

function NewHomeAnimation() {
    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
        >
            Your content here
        </motion.div>
    );
}

const mountPoint = document.getElementById('new-home-animation');
if (mountPoint) {
    createRoot(mountPoint).render(<NewHomeAnimation />);
}

export default NewHomeAnimation;
```

**2. Import di route bundle yang sudah ada:**
```javascript
// resources/js/routes/welcome.js
import '../animations/FeatureMotion.jsx';
import '../animations/OrbitStats.jsx';
import '../animations/NewHomeAnimation.jsx';  // ← Tambahkan
```

**3. Tambahkan mount point di Blade:**
```blade
{{-- resources/views/welcome.blade.php --}}
<div id="new-home-animation"></div>
```

**4. Build:**
```bash
npm run build
```

---

## 🔧 Troubleshooting

### Error: "Module not found"

**Penyebab:** Path import salah

**Solusi:**
```javascript
// ❌ Salah
import Component from './Component';

// ✅ Benar (dari animations/)
import Component from '../components/Component';

// ✅ Benar (dari routes/)
import Animation from '../animations/Animation';
```

---

### Error: "Uncaught ReferenceError: React is not defined"

**Penyebab:** Lupa import React

**Solusi:**
```javascript
// Tambahkan di awal file
import React from 'react';
import { createRoot } from 'react-dom/client';
```

---

### Animasi Tidak Muncul

**Debug steps:**

1. **Check console:**
```javascript
// Pastikan bundle log muncul
✅ Welcome bundle loaded
```

2. **Check Network tab:**
```
Pastikan route-specific bundle ter-load
```

3. **Check mount point:**
```javascript
// Tambahkan log di komponen
const mountPoint = document.getElementById('your-id');
console.log('Mount point found:', mountPoint);
```

4. **Check Blade template:**
```blade
{{-- Pastikan ID match --}}
<div id="your-id"></div>

{{-- Pastikan @vite() sudah ada --}}
@vite('resources/js/routes/your-route.js')
```

---

### Build Error: "Could not resolve"

**Penyebab:** File tidak ditemukan atau path salah

**Solusi:**
1. Pastikan file ada di lokasi yang benar
2. Check case sensitivity (Linux case-sensitive)
3. Gunakan relative path yang benar

---

## 📦 Bundle Management Best Practices

### DO ✅

1. **Kelompokkan animasi per-route:**
   - Homepage animations → `routes/welcome.js`
   - Admin animations → `routes/admin.js`

2. **Gunakan lazy loading untuk komponen besar:**
   ```javascript
   const HeavyComponent = React.lazy(() => import('../components/Heavy'));
   ```

3. **Reuse vendor chunks:**
   - React/ReactDOM otomatis di-share
   - Framer Motion otomatis di-share

4. **Test setelah perubahan:**
   ```bash
   npm run build
   npm run analyze
   ```

### DON'T ❌

1. **Jangan import semua di app.js:**
   ```javascript
   // ❌ Jangan
   import './all-animations.js';
   
   // ✅ Buat route-specific
   import './routes/specific.js';
   ```

2. **Jangan duplikasi dependencies:**
   ```javascript
   // ❌ Jangan bundle vendor sendiri
   // Biarkan Vite handle automatic vendor splitting
   ```

3. **Jangan load semua bundle di satu halaman:**
   ```blade
   {{-- ❌ Jangan --}}
   @vite(['routes/welcome.js', 'routes/admin.js', 'routes/wallet.js'])
   
   {{-- ✅ Load sesuai kebutuhan --}}
   @vite('routes/welcome.js')
   ```

---

## 🎯 Performance Tips

### 1. Lazy Load Non-Critical Animations

```javascript
// Load saat user scroll ke section
const observer = new IntersectionObserver((entries) => {
    entries.forEach(async (entry) => {
        if (entry.isIntersecting) {
            const { default: Animation } = await import('./animations/Heavy.jsx');
            Animation();
            observer.unobserve(entry.target);
        }
    });
});

observer.observe(document.getElementById('heavy-section'));
```

### 2. Preload Critical Routes

```blade
{{-- Di layout untuk user yang sering ke admin --}}
@if(auth()->user()->isAdmin())
    <link rel="modulepreload" href="{{ Vite::asset('resources/js/routes/admin.js') }}">
@endif
```

### 3. Use `useReducedMotion`

```javascript
import { useReducedMotion } from 'framer-motion';

function MyAnimation() {
    const shouldReduceMotion = useReducedMotion();
    
    return (
        <motion.div
            animate={shouldReduceMotion ? {} : { scale: 1.1 }}
        >
            Content
        </motion.div>
    );
}
```

---

## 📊 Monitoring Bundle Size

### Analyze After Build

```bash
npm run analyze
```

Output:
```
📊 CODE SPLITTING ANALYSIS
======================================
🎯 BUNDLE SIZE PER ROUTE:

Homepage (/)
  - app.js: 140.86 KB
  - welcome.js: 34.73 KB
  TOTAL: 175.59 KB
```

### Warning Thresholds

- ⚠️ Route bundle > 100KB → Consider splitting further
- ⚠️ Total page load > 300KB → Check what's included
- ⚠️ Vendor chunk > 300KB → Check dependencies

---

## 🔄 Migration dari Global ke Route-Specific

Jika ada animasi di `app.js` yang perlu dipindah:

**Before:**
```javascript
// app.js
import './my-animation.jsx';  // Loaded everywhere
```

**After:**
```javascript
// 1. Pindahkan file
mv resources/js/my-animation.jsx resources/js/animations/MyAnimation.jsx

// 2. Import di route-specific bundle
// resources/js/routes/specific.js
import '../animations/MyAnimation.jsx';

// 3. Remove dari app.js
// ✅ Selesai
```

---

## 🧪 Testing Workflow

```bash
# 1. Develop
npm run dev

# 2. Build production
npm run build

# 3. Analyze bundles
npm run analyze

# 4. Test di browser
# - Check Network tab
# - Check Console
# - Test animasi

# 5. Clear cache
php artisan view:clear
php artisan cache:clear

# 6. Deploy
git add .
git commit -m "feat: add new animation with code splitting"
git push
```

---

## 📚 Resources

- **Vite Code Splitting:** https://vitejs.dev/guide/features.html#code-splitting
- **React.lazy:** https://react.dev/reference/react/lazy
- **Framer Motion:** https://www.framer.com/motion/
- **Laravel Vite:** https://laravel.com/docs/vite

---

## ✨ Quick Commands

```bash
# Development
npm run dev

# Production build
npm run build

# Analyze bundles
npm run analyze

# Clean Laravel cache
php artisan view:clear && php artisan cache:clear

# Full rebuild
rm -rf public/build && npm run build
```

---

**Maintainer:** SkillHub Dev Team  
**Last Updated:** 2 September 2026  
**Version:** 1.0.0
