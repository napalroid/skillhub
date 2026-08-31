# ⚠️ PERINGATAN: HTTPS Issue di Development

## Masalah
Page diakses via HTTPS (ngrok), tapi Vite dev server jalan via HTTP.

## Solusi: Access via LOCALHOST

Untuk development, akses langsung dari browser:
```
http://127.0.0.1:8000
```
atau
```
http://localhost:8000
```

Jangan pakai ngrok URL untuk testing notifikasi/chat karena:
- Ngrok URL = HTTPS
- Vite dev server = HTTP (default)
- Browser block mixed content (HTTP di HTTPS page)

## Cara Benar

### 1. Start Services (Terminal 1 & 2)
```
# Terminal 1 - Reverb (notifications)
php artisan reverb:start

# Terminal 2 - Laravel Dev Server
php artisan serve
```

### 2. Akses via Localhost
```
http://127.0.0.1:8000/messages/3
```

### 3. Test Notifikasi
```bash
php test-notification.php 2
```

---

## Cara Pakai Ngrok (Untuk Testing External)

Jika mau test via ngrok URL:

### 1. Install mkcert (Self-signed SSL)
```bash
winget install F-Droids.Mkcert
mkcert -install
```

### 2. Generate SSL Certificate
```bash
mkcert localhost
```

### 3. Start Vite dengan HTTPS
```bash
npm run dev -- --https --cert localhost.pem --key localhost-key.pem
```

### 4. Proxy dengan ngrok
```bash
ngrok http 5173
```

### 5. Update .env
```
VITE_REVERB_HOST=YOUR_NGROK_URL
VITE_REVERB_PORT=443
VITE_REVERB_SCHEME=https
```

---

## Rekomendasi Saya (Development)

**UNTUK TESTING NOTIFIKASI/CHAT:**
- Gunakan `http://127.0.0.1:8000` (bukan ngrok URL)
- Ini akan work dengan baik tanpa HTTPS issue
- Notifikasi real-time tetap berfungsi via Reverb

**UNTUK TESTING DENGAN EXTERNAL DEVICE:**
- Set up HTTPS untuk Vite
- Atau gunakan ngrok untuk Laravel server saja
- Access Laravel via ngrok, akses Vite via localhost

---

## Quick Test Sekarang

1. Stop semua server (Ctrl+C)
2. Jalankan:
   ```bash
   php artisan serve
   php artisan reverb:start
   ```
3. Akses: `http://127.0.0.1:8000/messages/3`
4. Test kirim pesan
