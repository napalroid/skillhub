# DESIGN.md — SkillHub Design Reference

> **Tujuan file ini:** Ini adalah *single source of truth* untuk styling website **SkillHub**.
> Setiap kali AI (Claude, Copilot, Cursor, dll) membuat atau mengubah UI di project ini,
> AI **WAJIB** membaca dan mengikuti aturan di file ini agar tampilan tetap konsisten,
> simple, dan mengikuti gaya visual **adidas.co.id** (clean, bold, monokrom + aksen tegas).
>
> Jangan menambahkan warna, font, shadow, radius, atau komponen baru di luar yang
> didefinisikan di sini tanpa alasan kuat. Kalau ragu, gunakan token yang sudah ada.

---

## 1. Prinsip Desain (baca dulu sebelum ngoding)

1. **Simplicity first** — Hindari elemen dekoratif yang tidak perlu. Setiap elemen di layar harus punya fungsi.
2. **Konsisten** — Tombol, ikon, card, dan spacing harus terlihat sama di semua halaman. Jangan buat varian baru kalau varian yang sudah ada bisa dipakai.
3. **Aturan warna 60-30-10**:
   - **60%** — warna latar/netral (putih & abu-abu terang) → dominan.
   - **30%** — warna utama (hitam) → teks, header, tombol utama, elemen struktural.
   - **10%** — warna aksen (merah/kuning) → CTA penting, badge diskon, status, highlight.
4. **White space itu fitur, bukan ruang kosong yang "kurang isi"** — beri jarak (padding/margin) generous, jangan menumpuk elemen.
5. **Hierarki visual jelas** — judul besar & tebal, subjudul medium, body text ringan. Gunakan ukuran & bobot font untuk membedakan prioritas, bukan warna berlebihan.
6. **Grid & alignment rapi** — semua elemen sejajar ke grid 12 kolom, jangan asal-asalan.
7. **Mobile-first & responsive** — desain dites dulu di layar kecil, baru discale ke desktop.
8. **Gaya visual acuan:** adidas.co.id → dominan hitam-putih, tipografi besar & tebal (bold, uppercase untuk judul/CTA), foto produk besar full-bleed, banyak white space, tombol solid berbentuk kotak/pill sederhana tanpa gradient.

---

## 2. Color Palette

Berdasarkan brand identity Adidas (dominan monokrom + aksen dari warna corporate), disesuaikan untuk kebutuhan UI SkillHub. **Jangan pakai warna di luar tabel ini.**

### 2.1 Warna Inti (Core / Neutral) — porsi 60%

| Token | Hex | Kegunaan |
|---|---|---|
| `--color-bg` | `#FFFFFF` | Background utama (page background) |
| `--color-bg-soft` | `#F5F5F5` | Background section sekunder, alternating section, card background netral |
| `--color-bg-muted` | `#EAEAEA` | Divider background, skeleton loading, disabled background |
| `--color-border` | `#DDDDDD` | Border tipis untuk card, input, table |

### 2.2 Warna Utama (Primary) — porsi 30%

| Token | Hex | Kegunaan |
|---|---|---|
| `--color-black` | `#000000` | Warna utama brand: header/navbar, teks judul, tombol primer, footer |
| `--color-text` | `#111111` | Warna teks body utama (hampir hitam, lebih soft dari pure black) |
| `--color-text-secondary` | `#555555` | Teks deskripsi, caption, label sekunder |
| `--color-text-muted` | `#999999` | Placeholder, teks disabled, metadata (tanggal, jumlah, dsb.) |
| `--color-white` | `#FFFFFF` | Teks di atas background gelap, tombol outline |

### 2.3 Warna Aksen (Accent) — porsi 10%, pakai secukupnya, jangan berlebihan

| Token | Hex | Kegunaan |
|---|---|---|
| `--color-accent` | `#E4002B` | Merah khas Adidas — CTA utama ("Daftar Kelas", "Beli Sekarang"), badge notifikasi, error state |
| `--color-accent-hover` | `#C40025` | Hover/active state dari `--color-accent` |
| `--color-warning` | `#EDE734` | Badge promo/diskon, highlight sale, warning state |
| `--color-success` | `#2C9F45` | Status sukses (checkout berhasil, kelas selesai, dsb.) |
| `--color-info` | `#0051BA` | Link, info banner, status "in progress" |

### 2.4 Cara Pakai (Do & Don't)

- ✅ **Background**: selalu putih (`--color-bg`) atau abu sangat terang (`--color-bg-soft`). Jangan pakai background hitam full di halaman biasa — hitam hanya untuk navbar, footer, atau section "hero" tertentu.
- ✅ **Tombol utama (Primary CTA)**: background `--color-black`, teks putih. Untuk CTA promosi/urgent boleh pakai `--color-accent` (merah).
- ✅ **Tombol sekunder**: outline hitam (`border: 1px solid --color-black`), background transparan, teks hitam. Saat hover → background hitam, teks putih.
- ✅ Warna aksen (merah/kuning) **maksimal dipakai untuk 1–2 elemen per layar** — jangan sampai satu halaman penuh warna-warni.
- ❌ Jangan pakai warna gradient.
- ❌ Jangan pakai lebih dari 1 warna aksen dalam satu komponen yang sama.

---

## 3. Tipografi

Adidas.co.id memakai font sans-serif geometris, tebal untuk judul, huruf besar (uppercase) untuk penekanan/CTA. Untuk web, gunakan padanan Google Fonts berikut supaya mudah diimplementasi:

| Kegunaan | Font | Fallback |
|---|---|---|
| Heading / Judul / CTA | **"Archivo Black"** atau **"Montserrat" (700/800)** | `"Helvetica Neue", Arial, sans-serif` |
| Body text / paragraf | **"Inter"** atau **"Roboto"** (400/500) | `Arial, sans-serif` |
| Angka / harga / label kecil | **"Inter"** (600, tabular numbers) | `Arial, sans-serif` |

### 3.1 Type Scale (rem, basis 16px)

| Token | Ukuran | Weight | Contoh Pemakaian |
|---|---|---|---|
| `--text-h1` | 48px / 3rem | 800, uppercase, letter-spacing tight | Hero title halaman utama |
| `--text-h2` | 32px / 2rem | 700, uppercase | Judul section ("KELAS TERPOPULER") |
| `--text-h3` | 24px / 1.5rem | 700 | Judul card / sub-section |
| `--text-h4` | 18px / 1.125rem | 600 | Judul komponen kecil (card title) |
| `--text-body` | 16px / 1rem | 400 | Paragraf, deskripsi |
| `--text-small` | 14px / 0.875rem | 400 | Caption, metadata, helper text |
| `--text-xs` | 12px / 0.75rem | 500, uppercase, letter-spacing wide | Label/tag/badge |

### 3.2 Aturan

- Judul besar (H1/H2) **selalu bold/extra-bold dan boleh UPPERCASE**, meniru gaya headline Adidas.
- Body text tidak pernah bold, jaga line-height 1.5–1.6 agar mudah dibaca.
- Jangan pakai lebih dari 2 jenis font dalam 1 halaman (1 untuk heading, 1 untuk body).
- Hindari huruf miring (italic) kecuali untuk quote.

---

## 4. Layout & Spacing

### 4.1 Grid

- Gunakan **grid 12 kolom** dengan max-width container **1280px**, margin kiri-kanan otomatis (center).
- Gutter antar kolom: **24px** (desktop), **16px** (mobile).
- Padding horizontal halaman: **24px** (mobile), **64px** (desktop).

### 4.2 Spacing Scale (dipakai konsisten untuk margin/padding/gap)

Gunakan skala 4px sebagai basis (mirip Tailwind spacing):

| Token | Nilai |
|---|---|
| `--space-1` | 4px |
| `--space-2` | 8px |
| `--space-3` | 12px |
| `--space-4` | 16px |
| `--space-6` | 24px |
| `--space-8` | 32px |
| `--space-12` | 48px |
| `--space-16` | 64px |
| `--space-24` | 96px |

### 4.3 Aturan Spacing

- Jarak antar section besar (hero, kategori, footer) minimal `--space-16` (64px) — beri banyak white space seperti adidas.co.id.
- Jarak antar card dalam grid: `--space-6` (24px).
- Padding dalam card: `--space-4` sampai `--space-6`.
- Jangan menumpuk teks tanpa jarak — minimal `--space-2` antar baris elemen berbeda (label → judul → deskripsi).

### 4.4 Breakpoints

| Nama | Lebar |
|---|---|
| `mobile` | < 640px |
| `tablet` | 640px – 1024px |
| `desktop` | > 1024px |
| `wide` | > 1440px |

---

## 5. Komponen UI (referensi gaya adidas.co.id)

### 5.1 Navbar
- Background **hitam solid** (`--color-black`), teks/ikon putih.
- Logo/brand di kiri, menu utama di tengah/kiri, ikon akun-wishlist-cart di kanan.
- Sticky di top saat scroll, tanpa shadow berlebihan (cukup border-bottom tipis atau tanpa border sama sekali).
- Menu item: uppercase, `--text-small`, letter-spacing sedikit lebar.

### 5.2 Hero Section
- Full-width image/banner besar (rasio lebar, ~16:7 atau full-bleed), dengan overlay teks (judul besar uppercase + CTA button) di atas gambar.
- CTA button solid (hitam atau merah) dengan teks uppercase pendek ("BELANJA SEKARANG", "MULAI BELAJAR").

### 5.3 Card (produk / kelas / kursus)
- Background putih, border tipis (`--color-border`) atau tanpa border dengan sedikit shadow halus (`box-shadow: 0 1px 3px rgba(0,0,0,0.08)`), radius kecil (`--radius-sm`, lihat §6).
- Struktur: gambar/thumbnail di atas (rasio 4:5 atau 1:1) → badge (opsional, pojok kiri/kanan atas) → judul (`--text-h4`) → deskripsi singkat (`--text-small`, warna `--color-text-secondary`) → harga/info + CTA di bawah.
- Hover: sedikit zoom pada gambar (scale 1.03) atau elevasi shadow, transisi halus 200ms.

### 5.4 Tombol (Button)
| Varian | Style |
|---|---|
| Primary | Background `--color-black`, teks putih, uppercase, radius kecil/pill, padding `12px 24px` |
| Accent | Background `--color-accent` (merah), teks putih — hanya untuk CTA promo/urgent |
| Secondary/Outline | Border 1px hitam, background transparan, teks hitam; hover → isi hitam |
| Ghost/Text | Tanpa background/border, teks hitam dengan underline saat hover |
| Disabled | Background `--color-bg-muted`, teks `--color-text-muted`, cursor not-allowed |

Semua tombol: font uppercase, weight 600–700, letter-spacing sedikit lebar, transisi hover 150–200ms, **tanpa gradient**.

### 5.5 Badge / Tag
- Bentuk pill kecil atau kotak sudut tajam, ukuran teks `--text-xs`, uppercase.
- Contoh: badge "BARU", "DISKON 20%" (background `--color-accent` atau `--color-warning`, teks hitam/putih sesuai kontras).

### 5.6 Form & Input
- Border tipis `--color-border`, radius kecil, padding nyaman (`12px 16px`).
- Focus state: border berubah jadi hitam (`--color-black`), tanpa glow warna-warni.
- Label di atas input, uppercase kecil (`--text-xs`), warna `--color-text-secondary`.

### 5.7 Footer
- Background hitam (`--color-black`), teks abu terang/putih.
- Layout multi-kolom (link grouped by kategori), garis pemisah tipis abu gelap sebelum copyright.

---

## 6. Radius, Shadow, & Motion

| Token | Nilai | Kegunaan |
|---|---|---|
| `--radius-sm` | 4px | Button, input, badge |
| `--radius-md` | 8px | Card |
| `--radius-lg` | 16px | Modal, image banner besar |
| `--radius-full` | 999px | Pill button, avatar |

| Token | Nilai |
|---|---|
| `--shadow-sm` | `0 1px 3px rgba(0,0,0,0.08)` |
| `--shadow-md` | `0 4px 12px rgba(0,0,0,0.10)` |

- Transisi standar: `all 150–250ms ease-in-out`.
- Jangan pakai animasi berlebihan (bounce, spin berlebihan) — gunakan transisi halus & cepat, sesuai kesan "clean & sporty" ala Adidas.

---

## 7. Ikonografi & Gambar

- Ikon: gunakan set line-icon minimalis dan konsisten (contoh: [Lucide Icons](https://lucide.dev) atau [Phosphor Icons]), stroke width seragam, warna mengikuti teks di sekitarnya (hitam/putih).
- Foto/gambar produk atau materi kelas: rasio konsisten (misal 4:5 untuk card, 16:9 untuk banner), full-bleed pada hero, tanpa border/frame dekoratif.
- Gunakan `object-fit: cover` agar gambar tidak gepeng.

---

## 8. Contoh Implementasi (CSS Variables)

```css
:root {
  /* Neutral */
  --color-bg: #FFFFFF;
  --color-bg-soft: #F5F5F5;
  --color-bg-muted: #EAEAEA;
  --color-border: #DDDDDD;

  /* Primary */
  --color-black: #000000;
  --color-text: #111111;
  --color-text-secondary: #555555;
  --color-text-muted: #999999;
  --color-white: #FFFFFF;

  /* Accent */
  --color-accent: #E4002B;
  --color-accent-hover: #C40025;
  --color-warning: #EDE734;
  --color-success: #2C9F45;
  --color-info: #0051BA;

  /* Typography */
  --font-heading: "Montserrat", "Helvetica Neue", Arial, sans-serif;
  --font-body: "Inter", Arial, sans-serif;

  /* Radius */
  --radius-sm: 4px;
  --radius-md: 8px;
  --radius-lg: 16px;
  --radius-full: 999px;

  /* Shadow */
  --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
  --shadow-md: 0 4px 12px rgba(0,0,0,0.10);

  /* Spacing */
  --space-1: 4px;  --space-2: 8px;  --space-3: 12px;
  --space-4: 16px; --space-6: 24px; --space-8: 32px;
  --space-12: 48px; --space-16: 64px; --space-24: 96px;
}
```

---

## 9. Checklist untuk AI sebelum generate UI

Sebelum membuat/mengedit komponen apapun di project SkillHub, pastikan:

- [ ] Warna yang dipakai ada di §2 (tidak menambah warna baru).
- [ ] Rasio warna kira-kira mengikuti 60% netral - 30% hitam - 10% aksen.
- [ ] Font heading & body sesuai §3, maksimal 2 font.
- [ ] Spacing pakai token dari §4.2, bukan angka acak.
- [ ] Tombol memakai salah satu varian di §5.4 (jangan buat varian baru).
- [ ] Tidak ada gradient, tidak ada shadow warna-warni, tidak ada animasi berlebihan.
- [ ] Layout mengikuti grid 12 kolom & breakpoint di §4.
- [ ] Ada cukup white space antar section (minimal `--space-16` antar section besar).
- [ ] Hierarki visual jelas: judul besar/tebal, body ringan, aksen dipakai sedikit dan tepat sasaran.

---

## 10. Catatan Tambahan

- File ini dibuat untuk project **UKK — SkillHub** (platform belajar skill/kursus online).
- Referensi visual: [adidas.co.id](https://www.adidas.co.id/) — gaya monokrom bold, tipografi besar, foto besar, white space luas, CTA tegas.
- Referensi palet warna brand: kombinasi warna resmi Adidas (hitam #000000, putih #FFFFFF, merah #E4002B, kuning #EDE734, hijau #2C9F45, biru #0051BA) — dipetakan ulang menjadi token semantik di §2 supaya cocok untuk kebutuhan UI SkillHub (bukan e-commerce sepatu, tapi platform edukasi).
- Kalau ada kebutuhan komponen baru yang belum diatur di sini, **tambahkan token/aturan baru ke file ini terlebih dahulu**, baru implementasikan — jangan langsung hardcode nilai baru di kode.
