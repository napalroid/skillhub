# ERROR.md - SkillHub Bug Tracking & Resolution

## BUG-017 — Edit Jasa Tidak Memperlihatkan Pratinjau Gambar Baru

### Severity

MEDIUM

### Date

2026-09-29

### Status

FIXED

### Symptom

Seller tidak mendapat umpan balik visual setelah memilih gambar baru pada halaman edit jasa; pilihan hanya terlihat setelah form disimpan dan halaman berpindah.

### Root Cause

Input file pada halaman edit tidak memiliki handler preview browser. Validasi edit juga tidak menerima WEBP, walaupun form pengajuan dan backend penyimpanan mendukung format tersebut.

### Additional Causes Checked

* Form memakai `multipart/form-data` dan route PUT yang tepat.
* `ServiceController@update` menyimpan file baru ke disk `public`, memperbarui kolom `image`, dan mengarahkan kembali ke Jasa Saya.
* Thumbnail Jasa Saya memakai nilai gambar yang diperbarui setelah redirect.
* Penghapusan gambar lama dilakukan hanya ketika file utama baru benar-benar diunggah.

### Affected Files

* `resources/views/services/edit.blade.php`
* `app/Http/Controllers/ServiceController.php`

### Fix Applied

Preview lokal dibuat dari file yang dipilih sebelum submit, dengan kontrol untuk membatalkan pilihan dan pesan validasi yang diumumkan. Dukungan WEBP diselaraskan antara edit, pengajuan, dan backend.

### Verification

* PHP lint dan cache Blade berhasil.
* Kode upload diverifikasi menyimpan gambar baru sebelum redirect ke Jasa Saya.
* Suite test tidak dapat berjalan karena migration SQLite lama menggunakan sintaks MySQL `ALTER TABLE ... MODIFY`.

### Prevention

Setiap input media pada form edit harus memberikan preview lokal dan memakai aturan format/ukuran yang sama dengan endpoint penyimpanannya.

## BUG-016 — Thumbnail Jasa Tidak Menggunakan Foto Portofolio Lama

### Severity

MEDIUM

### Date

2026-09-29

### Status

FIXED (untuk data yang memiliki gambar unggahan)

### Symptom

Sebagian jasa pada halaman Jasa Saya hanya menampilkan placeholder walaupun seller pernah mengunggah foto pada portofolio.

### Root Cause

Kartu Jasa Saya hanya membaca kolom `services.image`. Data jasa lama dapat menyimpan foto hanya pada `portfolio_images`, sehingga foto tersebut tidak pernah dipakai sebagai thumbnail.

### Additional Causes Checked

* Upload gambar utama dan portofolio disimpan pada disk `public`.
* Junction `public/storage` menuju `storage/app/public` tersedia dan valid.
* Gambar utama jasa ID 10 (`Jasa Joki Mobile Legend`) ada di database dan di filesystem.
* Jasa ID 3 (`JASJOK ML KRISNA`) tidak memiliki nilai `image`, `portfolio_images`, maupun file yang dapat dipetakan kembali, sehingga tidak dapat dipulihkan otomatis.

### Affected Files

* `app/Models/Service.php`
* `app/Http/Controllers/ServiceController.php`

### Fix Applied

Model memilih gambar utama yang benar, atau foto portofolio pertama yang benar-benar ada sebagai fallback. Halaman Jasa Saya memakai nilai fallback tersebut hanya untuk render kartu tanpa mengubah data yang tersimpan.

### Verification

* Service ID 7 yang tidak punya gambar utama kini menghasilkan path foto portofolio.
* Service ID 10 tetap menghasilkan gambar utama.
* Service ID 3 menghasilkan `null` secara benar karena tidak mempunyai unggahan sumber.
* PHP lint dan cache Blade berhasil.

### Prevention

Tetap simpan gambar utama saat tersedia; fallback portofolio menjaga tampilan data lama tanpa memalsukan gambar untuk jasa yang memang tidak punya unggahan.

## BUG-013 — Chat Tidak Real-Time karena Reverb Tidak Berjalan

### Severity

HIGH

### Status

FIXED

### Symptom

Browser gagal membuat koneksi WebSocket ke `127.0.0.1:8080`; pesan chat tetap tersimpan, tetapi tidak langsung muncul pada lawan bicara.

### Root Cause

Tidak ada proses Reverb yang mendengarkan port `8080`. Launcher lama juga tidak memulai Vite, sehingga aset frontend Echo dapat tetap memakai bundle lama.

### Additional Causes Checked

* Event `MessageSent`, private channel `conversation.{id}`, dan otorisasi channel tersedia.
* Konfigurasi lokal Reverb memakai `http`, sehingga koneksi lokal harus menggunakan `ws`, bukan `wss` tanpa TLS.
* Queue worker diperlukan karena broadcast memakai koneksi queue database.

### Fix Applied

`start-midtrans-setup.bat` kini memulai Reverb, queue worker, Laravel, dan Vite; memulai ngrok bila tersedia; serta mencegah proses Reverb/Laravel/Vite ganda berdasarkan port yang digunakan. Launcher memakai `start /D` untuk direktori kerja agar path proyek yang mengandung spasi tidak merusak command `cmd /k`.

### Verification

* Port `8080` sebelumnya diverifikasi tidak memiliki listener, sesuai dengan error browser.
* Sintaks konfigurasi launcher dan perintah Reverb diverifikasi terhadap `php artisan reverb:start --help`.
* Parsing `start /D` pada path proyek dengan spasi berhasil menjalankan `php artisan --version`.

### Prevention

Jalankan `./start-midtrans-setup.bat` untuk lingkungan lokal, lalu pertahankan jendela layanan yang terbuka selama pengujian real-time.

## BUG-014 — Aset Vite Lokal Diblokir Saat Halaman Dibuka melalui ngrok

### Severity

HIGH

### Status

FIXED

### Symptom

Halaman ngrok mencoba memuat `@vite/client` dan `resources/js/app.js` dari `http://127.0.0.1:5173`, lalu browser memblokirnya dengan CORS.

### Root Cause

Vite development server meninggalkan `public/hot` berisi URL lokal. Directive `@vite` mengutamakan hot-file tersebut sehingga aset development lokal dipakai bahkan saat halaman berasal dari origin HTTPS ngrok.

### Fix Applied

Launcher Midtrans kini menjalankan `npm run build` dan menghapus `public/hot`. Laravel lalu memakai aset berversi dalam `public/build` dari origin aplikasi/ngrok yang sama.

### Verification

* `npm run build` dijalankan.
* `public/hot` dikonfirmasi tidak ada.
* Blade view cache dikompilasi ulang.

### Prevention

Gunakan Vite development server hanya saat membuka aplikasi lokal. Untuk URL ngrok, gunakan aset hasil build dan jangan biarkan hot-file aktif.

## BUG-015 — Chat HTTPS Menghubungi Reverb Lokal Tanpa TLS

### Severity

HIGH

### Status

FIXED — requires launcher restart

### Symptom

Halaman yang dibuka melalui HTTPS ngrok mencoba koneksi `wss://127.0.0.1:8080`, lalu handshake WebSocket gagal dan pesan baru hanya terlihat setelah refresh.

### Root Cause

Frontend Echo mengunci host Reverb ke `127.0.0.1`. Saat halaman menggunakan HTTPS, Pusher memilih WSS. Reverb lokal tidak punya TLS dan tidak dapat diakses oleh perangkat lain. Tunnel ngrok yang aktif hanya meneruskan port `8000`, sedangkan Reverb mendengarkan port `8080`.

Halaman percakapan juga merupakan view mandiri dan tidak memakai layout aplikasi utama. Karena itu ia tidak pernah memuat `realtime-config.js`; Echo menerima key kosong walaupun konfigurasi Reverb di `.env` sudah benar.

### Fix Applied

Konfigurasi Echo kini dimuat saat runtime melalui `public/realtime-config.js`. Launcher mengarahkan browser HTTPS ke host ngrok yang sama pada `wss` port `443`. Pada akses lokal biasa, konfigurasi tetap memakai `ws://127.0.0.1:8080`.

Launcher tidak lagi membuat tunnel ngrok baru. Endpoint Laravel/Midtrans yang sudah ada tetap meneruskan port `8000` ke proxy lokal. Proxy meneruskan HTTP ke Laravel port `8001`, dan upgrade WebSocket `/app/*` ke Reverb port `8080`. Ini menghilangkan penyebab `ERR_NGROK_334` dan membuat aplikasi serta WebSocket memakai origin publik yang sama.

Konfigurasi realtime kini dijadikan partial Blade bersama dan dipasang pada layout utama, layout komponen, serta halaman percakapan mandiri. Referensi Tailwind CDN pada halaman-halaman tersebut dihapus; CSS sekarang berasal dari aset Vite hasil build.

### Verification

* Bundle produksi Echo dikonfirmasi membaca `window.SkillHubRealtime` dan membatasi transport ke `ws` atau `wss` sesuai skema.
* API ngrok lokal diperiksa dan membuktikan tunnel yang ada meneruskan ke `localhost:8000`.
* Generator konfigurasi lokal dan publik berhasil dijalankan; konfigurasi publik menggunakan host endpoint ngrok yang aktif dan port `443`.
* Sintaks proxy Node dan perintah Reverb telah divalidasi.
* Perintah Reverb dijalankan langsung dan berhasil mulai mendengarkan `127.0.0.1:8080`.

### Follow-up Launcher Hardening

Jendela Reverb sekarang dijalankan melalui `scripts/start-reverb.bat`, bukan command bertingkat dengan beberapa operator `&`. Launcher menunggu dua detik lalu memastikan port `8080` benar-benar terbuka. Jika tidak, launcher berhenti dengan pesan yang dapat ditindaklanjuti alih-alih meneruskan tanpa WebSocket.

Pemeriksaan tersebut kemudian diperkuat menjadi WebSocket handshake nyata oleh `scripts/check-reverb.ps1`; port yang dipakai proses lain tidak lagi dianggap Reverb sehat. Event `MessageSent` juga memakai `ShouldBroadcastNow`, sehingga pesan chat tidak menunggu queue worker. Browser mencatat status koneksi serta hasil subscription private channel untuk verifikasi langsung.

### Additional Cause Found

Beberapa proses Reverb lama yang tidak memiliki jendela sendiri masih memakai port `8080`. Launcher lama hanya mengecek apakah port terbuka, sehingga salah menganggap Reverb sudah siap. Proses-proses sisa tersebut dihentikan setelah PID dan waktu mulainya diverifikasi; port `8080` kini kosong dan launcher terbaru akan membuka satu jendela Reverb yang dapat dilihat serta memverifikasi handshake-nya.

### Final Root Cause — Connected but Not Subscribed

`chat-realtime.js` dimuat lewat `import()` dinamis setelah Echo. Modul tersebut hanya mendaftarkan callback `DOMContentLoaded`. Pada halaman yang memuat modul setelah event itu sudah terjadi, callback tidak pernah berjalan: WebSocket terlihat connected, tetapi chat tidak berlangganan ke private channel dan form memakai fallback HTML yang baru memperbarui tampilan setelah refresh.

Inisialisasi chat sekarang langsung dijalankan bila DOM sudah siap, atau menunggu `DOMContentLoaded` hanya bila masih loading. Build produksi yang memuat perubahan ini telah berhasil dibuat.
* Build frontend serta cache Blade berhasil dibuat ulang.

### Remaining Manual Verification

Jalankan launcher terbaru, pastikan jendela `SkillHub - Reverb WebSocket` dan `SkillHub - ngrok Reverb` tidak memunculkan error, lalu refresh keras halaman ngrok pada dua akun/perangkat berbeda dan kirim pesan.

## BUG-012 — Kalkulator Joki ML Menampilkan Total Lama dan Bintang Immortal Berlebih

### Severity

HIGH

### Status

FIXED

### Symptom

Saat target diganti ke Mythical Immortal, divisi lama dari rank sebelumnya dapat membuat target ditandai tidak valid. Kalkulator kemudian tetap menampilkan rincian lama. Selain itu, rentang menuju Immortal menghitung satu bintang lebih banyak dari selisih posisi rank.

### Root Cause

Rank Mythic+ tidak memiliki divisi, tetapi nilai divisi state sebelumnya tidak selalu dinormalisasi ke `1`. Ketika validasi gagal, nilai total dan breakdown sebelumnya tidak direset. Batas Immortal `100` juga dihitung sebagai bintang Immortal, walaupun itu adalah batas promosi dari Mythical Glory.

### Fix Applied

Divisi untuk rank tanpa divisi kini selalu `1`, hasil live dibersihkan sebelum validasi, dan interval harga Immortal dimulai setelah batas `100`.

### Verification

* Epic V 0 → Mythical Immortal 600: selisih dan total breakdown sama-sama 650 bintang; Immortal menyumbang 500 bintang.
* Kasus Mythic 5 → Mythical Glory 78 tetap 73 bintang.

## BUG-011 — Checkout Joki ML Kembali ke Form dan Order Belum Dibayar Bocor ke Seller

### Severity

HIGH

### Status

FIXED

### Symptom

Buyer mengirim checkout Joki ML tetapi kembali ke halaman form, bukan ke pembayaran. Saat order berstatus `menunggu_pembayaran`, seller juga menerima notifikasi dan order tampil pada riwayat pesanan kedua pihak.

### Root Cause

Input rank/divisi/bintang di dalam komponen Livewire tidak menuliskan nilai HTML eksplisit, padahal checkout dikirim oleh form HTTP induk. Nilai yang tampil dapat tidak terserialisasi secara andal saat submit. Selain itu, kedua jalur pembuatan order membuat notifikasi `new_order` sebelum pembayaran dan indeks pesanan memasukkan status belum dibayar.

### Additional Causes Checked

* Route POST Joki ML dan redirect sukses sudah benar menuju `orders.payment.show`.
* Perhitungan harga tetap dihitung ulang di server, relasi Joki ML dan transaksi database tetap dipakai.
* Notifikasi setelah pembayaran tetap disediakan oleh alur pembayaran/escrow.

### Fix Applied

Nilai Livewire kini juga dirender sebagai nilai form HTML yang eksplisit. Notifikasi seller saat order dibuat dihapus; seller hanya diberi tahu setelah pembayaran. Order `menunggu_pembayaran` dikecualikan dari riwayat, filter, dan statistik pesanan. Notifikasi lama untuk order yang masih belum dibayar dibersihkan lewat migration.

### Verification

* Lint PHP, cache Blade, dan daftar route checkout dijalankan.
* Pengujian database end-to-end tetap memerlukan MySQL aktif.

## BUG-010 — Upload Gambar Pengajuan Gagal Tanpa Pesan Jelas

### Severity

MEDIUM

### Status

FIXED

### Root Cause

PHP membatasi setiap upload menjadi 2 MB dan total request menjadi 8 MB, sedangkan form tidak memeriksa ukuran sebelum submit dan kegagalan ukuran request tidak dipetakan ke pesan yang terlihat.

### Fix Applied

Form sekarang menolak file lebih dari 2 MB sebelum submit, menampilkan pesan di area upload serta ringkasan error di atas form. Request yang melebihi batas total juga kembali dengan penjelasan yang dapat ditindaklanjuti.


## BUG-009 — Pengajuan Jasa Barber Gagal Karena Field Wajib Tidak Muncul

### Severity

HIGH

### Status

FIXED

### Symptom

Seller tidak dapat mengajukan jasa barber; server menolak data jenis potongan dan durasi yang wajib.

### Root Cause

Form hanya memuat field khusus untuk service type dengan harga kustom. Barber memakai harga normal, sehingga field wajib barber tidak pernah dikirim ke `ServiceController@store`.

### Additional Causes Checked

* Route pengajuan, relasi subkategori/service type, dan status awal `pending` tersedia.
* Validasi backend barber memang mensyaratkan data tersebut.
* Field umum buyer belum dapat dibuat pada saat pengajuan jasa.

### Fix Applied

* Field khusus kini dimuat untuk `joki_ml` dan `barber` tanpa bergantung pada mode harga.
* Seller dapat membuat hingga 10 field buyer aman yang disimpan sebagai `booking_config`.
* Admin dapat memfilter antrian berdasarkan konfigurasi buyer dan meninjau seluruh field pada halaman preview.

### Verification

* PHP lint controller dan kompilasi cache Blade berhasil.


## BUG-008 — Aksi Payout Admin dan Upload File Buyer Tidak Tersambung

### Severity

HIGH

### Status

FIXED

### Symptom

Tombol proses payout admin merujuk nama route yang tidak ada. Buyer juga dapat mengunggah file `kebutuhan` melalui controller, tetapi tidak memiliki form upload pada detail pesanan.

### Root Cause

Blade payout menggunakan prefix route `admin.payouts.*` untuk aksi, sementara route terdaftar sebagai `admin.payout.*`. UI order hanya menyediakan upload hasil untuk seller.

### Fix Applied

Nama route action payout diselaraskan dengan route terdaftar. Form upload file kebutuhan ditambahkan pada File Pesanan untuk buyer selama pesanan belum selesai atau dibatalkan.

### Verification

* Cache Blade berhasil dikompilasi ulang.
* Semua route payout admin yang dipakai UI ditemukan pada route list.

## BUG-007 — Checkout Joki ML Tidak Mengarahkan Buyer ke Pembayaran

### Severity

MEDIUM

### Status

FIXED

### Symptom

Setelah mengirim pesanan Joki ML, buyer tidak diarahkan langsung ke halaman pembayaran. Jika validasi menolak data, halaman checkout juga tidak menampilkan alasan penolakan secara jelas.

### Root Cause

Redirect sukses `storeJokiMl` menuju detail pesanan, bukan route pembayaran. View checkout tidak memiliki ringkasan error server di luar komponen Livewire.

### Fix Applied

Pesanan Joki ML yang berhasil dibuat sekarang langsung dialihkan ke `orders.payment.show`. Harga yang dipakai tetap `final_price` dari perhitungan ulang server berdasarkan rank/bintang dan add-on yang valid. Pesan validasi server kini terlihat di atas checkout bila diperlukan.

### Verification

* Sintaks controller valid.
* Cache Blade berhasil dikompilasi ulang.
* Route POST Joki ML dan redirect route pembayaran diperiksa.

## BUG-006 — Kalkulator Livewire Joki ML Gagal saat Rank Diubah

### Severity

HIGH

### Status

FIXED

### Symptom

Mengubah rank pada kalkulator Joki ML menghasilkan HTTP 500 karena nilai bintang dari input Livewire masih berupa string. Perhitungan pada rentang Mythic hingga Mythical Immortal juga tidak menghasilkan rincian harga yang benar.

### Root Cause

Nilai input Livewire dapat berupa string atau kosong saat elemen form berganti. Properti angka yang sempat dibuat bertipe `int` menjadi tidak terinisialisasi ketika nilai kosong disinkronkan, lalu hook update gagal membaca `target_stars`. Selain itu, posisi absolut tiap rank Mythical menambahkan offset rank sebelumnya sekaligus nilai bintang kumulatif, membuat batas Mythic, Honor, Glory, dan Immortal tidak berurutan.

### Fix Applied

Input rank dinormalisasi menjadi integer aman tepat sebelum kalkulasi; nilai target kosong memakai minimum rank terkait. Perhitungan posisi/rentang Mythical memakai satu base dari total rank normal, lalu menambahkan nilai bintang kumulatifnya.

### Verification

* Sintaks PHP dan cache Blade berhasil dibuat ulang.
* Mythic 5 ke Mythical Glory 78 menghasilkan 73 bintang: Mythic 19, Honor 25, Glory 29.
* Rincian tarif service ID 4 berhasil dihitung pada setiap rank tersebut.
* Urutan Livewire target Mythical Immortal → nilai kosong sementara → 789 berhasil tanpa exception.

### Prevention

Normalisasi input Livewire di batas komponen sebelum dipakai service bertipe ketat. Sistem rank kumulatif harus selalu memakai satu base bersama.

## BUG-005 — Kalkulator Livewire Tidak Dimuat di Checkout Joki ML

### Severity

HIGH

### Status

FIXED

### Symptom

Halaman `/pesanan/buat/{service}` untuk jasa Joki Mobile Legends hanya menampilkan form Blade biasa. Kalkulator bintang dan estimasi harga Livewire tidak terlihat, walaupun komponen Livewire sudah ada di proyek.

### Root Cause

`OrderController@create` memang memilih view `orders.create-joki-ml`, tetapi view tersebut memasukkan partial form statis, bukan komponen `joki-ml-order-form`. Aset Livewire juga tidak dimuat oleh halaman checkout itu.

### Additional Causes Checked

* Route GET pesanan dan percabangan tipe layanan `joki_ml` sudah mengarah ke view yang benar.
* `JokiMlOrderForm` dan kalkulator `MlRankCalculator` tersedia, tetapi tidak pernah dipasang pada halaman.
* Checkout server di `OrderController@storeJokiMl` sudah menghitung ulang harga dan menyimpan akun terenkripsi; alur ini dipertahankan sebagai sumber kebenaran.
* Jalur submit lama di komponen Livewire tidak sesuai dengan struktur order sekarang, sehingga dihapus agar tidak ada dua alur pembuatan pesanan.
* Batas input bintang rank biasa diselaraskan dengan validasi kalkulator agar UI tidak menawarkan angka bintang yang ditolak server.

### Affected Files

* `resources/views/orders/create-joki-ml.blade.php`
* `resources/views/livewire/joki-ml-order-form.blade.php`
* `app/Livewire/JokiMlOrderForm.php`
* `app/Services/MlRankCalculator.php`
* `app/Http/Controllers/OrderController.php`

### Fix Applied

Checkout Joki ML sekarang memasang komponen Livewire pada panel pesanan, memuat asetnya, dan menampilkan kalkulator rank/bintang, rincian tarif per rank, serta detail akun ML. Tampilan disesuaikan menjadi checkout terang dua kolom tanpa booking slots. Field Livewire tetap memiliki `name` form biasa sehingga POST checkout yang telah tervalidasi server tetap menerima data yang sama.

### Verification

* Sintaks PHP dan cache Blade dikompilasi ulang.
* Route, relasi `jokiMlService`, kalkulasi client-side Livewire, serta validasi/hitung ulang server diperiksa.
* Harga dan data akun tetap diproses oleh `storeJokiMl`, bukan dipercaya dari tampilan.

### Prevention

Setiap view yang membutuhkan komponen Livewire harus memasang komponen dan asetnya bersama-sama. Jangan buat jalur submit kedua di komponen bila controller checkout adalah pemilik transaksi order.

## BUG-004 — Status Revisi Ditampilkan sebagai Pengerjaan Awal

### Severity

MEDIUM

### Status

FIXED

### Symptom

Setelah buyer meminta revisi, pesanan kembali ke status `dikerjakan`, tetapi halaman buyer tetap menampilkan instruksi generik bahwa seller baru mulai mengerjakan.

### Root Cause

Alur revisi menyimpan catatan `[Minta Revisi]` dan mengubah status ke `dikerjakan`, sementara seluruh tampilan hanya memeriksa status tersebut tanpa membedakan revisi aktif dari pengerjaan awal.

### Additional Causes Checked

* Buyer hanya dapat meminta revisi dari status `menunggu_persetujuan`.
* Seller mengunggah hasil/revisi mengembalikan pesanan ke `menunggu_persetujuan`.
* Status pembayaran dan instruksi seller memiliki teks `dikerjakan` terpisah yang juga perlu dibedakan.

### Affected Files

* `resources/views/orders/show.blade.php`

### Fix Applied

Halaman sekarang mengidentifikasi revisi aktif dari kombinasi status `dikerjakan` dan pesan revisi buyer, lalu menampilkan status serta instruksi yang sesuai untuk buyer dan seller.

### Verification

* Blade view dikompilasi ulang.
* Cabang pengerjaan awal dan revisi aktif diperiksa agar teks tidak tertukar.

### Prevention

Jika alur revisi berkembang, pertimbangkan penyimpanan state revisi eksplisit daripada hanya mengandalkan pesan aktivitas.

## BUG-003 — Halaman Buat Pesanan Gagal Dirender karena Namespace `Str`

### Severity

HIGH

### Status

FIXED

### Symptom

`GET /pesanan/buat/{service}` menghasilkan HTTP 500 dengan error `Class "IlluminateSupportStr" not found`.

### Root Cause

Pemanggilan pemotong deskripsi pada Blade memakai namespace yang kehilangan pemisah backslash, sehingga PHP membaca `IlluminateSupportStr` sebagai satu nama kelas yang tidak ada.

### Additional Causes Checked

* Route `orders.create` dan `OrderController@create` mengarah ke Blade yang benar.
* Tidak ada pemanggilan `IlluminateSupportStr` lain di halaman atau alur pesanan.
* Cache Blade dapat dibuat ulang setelah perbaikan.

### Affected Files

* `resources/views/orders/create.blade.php`

### Fix Applied

Mengganti pemanggilan namespace dengan helper Laravel `str(...)->limit(...)`, termasuk fallback untuk deskripsi kosong.

### Verification

* Pencarian kode memastikan referensi kelas rusak tidak tersisa.
* Cache Blade dikompilasi ulang.

### Prevention

Gunakan helper Laravel atau import yang valid untuk utilitas string di Blade; lakukan pencarian namespace setelah perubahan templating.

## BUG-002 — Pesanan Tertahan di Status Konfirmasi Harga

### Severity

MEDIUM

### Status

FIXED

### Symptom

Pesanan jasa dengan harga yang sudah diketahui masih dibuat dengan status `menunggu_konfirmasi_harga`, sehingga buyer harus menunggu seller sebelum dapat membayar.

### Root Cause

Alur pembuatan order umum selalu membuat status khusus konfirmasi harga dan membiarkan `final_price` kosong, walaupun total jasa serta layanan tambahan sudah dapat dihitung saat order dibuat.

### Additional Causes Checked

* Joki ML dengan harga manual tetap membutuhkan persetujuan seller.
* Endpoint dan halaman QRIS sebelumnya hanya memeriksa status order, sehingga perlu memeriksa `approval_status` untuk order Joki ML manual.
* Data order lama dengan status tersebut perlu dimigrasikan sebelum nilai ENUM dihapus.

### Fix Applied

* Order umum sekarang langsung memakai `menunggu_pembayaran` dengan `final_price` dari harga jasa dan layanan tambahan.
* Status `menunggu_konfirmasi_harga` dihapus dari ENUM setelah seluruh data lama dipindahkan ke `menunggu_pembayaran`.
* Persetujuan harga Joki ML manual dipertahankan melalui `approval_status`, dan QRIS diblokir selama nilainya `pending`.

### Verification

* Sintaks controller, model, dan migration valid.
* Blade view berhasil dikompilasi.
* Migration penghapusan status berhasil dijalankan pada database lokal.

### Regression Risk

Rendah untuk jasa berharga tetap. Joki ML mode manual tetap harus diuji secara manual dari pembuatan order sampai persetujuan seller dan pembayaran QRIS.

## BUG-001 — Joki ML Price Calculation with Cumulative Star Ranges

### Severity
HIGH

### Status
FIXED ✓

### Symptom
When calculating price for Joki ML orders with cumulative star ranges (Mythic+ ranks), the price breakdown showed incorrect star counts per rank. For example:
- User input: Mythic 5★ → Mythical Glory 78★
- Expected: Mythic 19 stars + Honor 25 stars + Glory 29 stars = 73 total
- Got: Mythic 20 stars + Honor 24 stars + Glory 28 stars (or similar incorrect breakdown)

### Expected Behavior
For cumulative rank system (Mythic+ with stars 0-24, 25-49, 50-99, 100+):
- Calculate absolute position for both current and target
- For each rank in between, calculate overlapping stars
- Breakdown shows per-rank stars with per-rank pricing
- Total stars = targetAbsolute - currentAbsolute

Example: Mythic 5★ → Mythical Glory 78★
- Mythic (0-24): from star 5 to star 24 = 19 stars × Rp 7.000 = Rp 133.000
- Mythical Honor (25-49): full range = 25 stars × Rp 8.000 = Rp 200.000
- Mythical Glory (50-99): from star 50 to star 78 = 29 stars × Rp 9.000 = Rp 261.000
- **Total: 73 stars, Rp 594.000**

### Actual Behavior (Before Fix)
System calculated overlaps incorrectly:
1. Used `targetAbsolute - 1` for all ranks instead of handling each rank specially
2. Didn't properly handle "target at rank start" case (e.g., Elite III 0★)
3. Didn't account for "promoting star" when current is at rank max

### Root Cause Analysis
1. **Overlap calculation was using single formula**: Applied `targetAbsolute - 1` uniformly across all ranks instead of checking if target is within rank or at rank boundary
2. **Target position handling**: When target was at rank start (division=max, stars=0), it still counted that position as part of the rank
3. **Rank max boundary issue**: When current was at rank max and target exceeded rank, the "promoting star" wasn't being counted

### Solution Applied

#### 1. Fixed `toAbsolutePosition()` logic
- For Mythic+ (order >= 7): `absolute = offset + relative_stars`
- For normal ranks: calculate through division hierarchy
- Offset accounts for all previous ranks' total stars

#### 2. Implemented proper overlap calculation in `calculatePriceWithBreakdown()`
- Calculate absolute positions for current and target
- For each rank in path:
  - If **current rank with stars > 0**: `overlapStart = currentAbsolute + 1`
  - If **current rank with stars = 0**: `overlapStart = currentAbsolute`
  - If **other ranks**: `overlapStart = rankMinAbsolute`
  
  - If **target rank AND target at start** (div=max, stars=0): `overlapEnd = rankMinAbsolute - 1`
  - If **target rank AND target_stars = 0**: `overlapEnd = targetAbsolute - 1`
  - If **target rank AND target_stars > 0**: `overlapEnd = targetAbsolute`
  - If **non-target rank**: `overlapEnd = min(rankMaxAbsolute, targetAbsolute - 1)`

#### 3. Added special case for rank-max advancement
- If `starsInThisRank = 0` AND current is at rankMax AND target exceeds rank
- Then `starsInThisRank = 1` (the "promoting star")

#### 4. Updated `validateRank()` 
- For Mythic+: validate against `getRelativeMinStars/MaxStars` (0-24, 25-49, etc)
- For normal ranks: validate division and relative stars

#### 5. Added helper methods
- `getRelativeMinStars()`: returns min stars for UI (0, 25, 50, 100)
- `getRelativeMaxStars()`: returns max stars for UI (24, 49, 99, 5863)

### Files Changed
- `app/Services/MlRankCalculator.php`:
  - Complete rewrite of `calculatePriceWithBreakdown()` with proper overlap logic
  - Fixed `toAbsolutePosition()` for cumulative calculation
  - Updated `validateRank()` to use relative ranges for Mythic+
  - Added `getRelativeMinStars()` and `getRelativeMaxStars()`
  - Fixed `getMinStars()` and `getMaxStars()` with offset calculation

- `app/Livewire/JokiMlOrderForm.php`: No changes needed (validation logic updated in service)

- `resources/views/livewire/joki-ml-order-form.blade.php`:
  - Updated to use `getRelativeMinStars/MaxStars` for UI dropdowns

### Test Cases Verified
✓ **Test 1**: Warrior III 0★ → Elite III 0★ = 9 stars (Warrior: 9)
✓ **Test 2**: Warrior III 0★ → Warrior I 0★ = 6 stars (Warrior: 6)
✓ **Test 3**: Warrior I 2★ → Elite III 0★ = 1 star (Warrior: 1) - promoting star case
✓ **Test 4**: Mythic 5★ → Mythical Glory 78★ = 73 stars (Mythic: 19, Honor: 25, Glory: 29)
✓ **Test 5**: Master II 2★ → Legend V 0★ = 56 stars (correct breakdown)

### Impact & Benefits
- Price calculations now correctly handle cumulative star ranges
- Breakdown accurately shows per-rank star distribution
- Buyers see correct pricing with transparent per-rank breakdown
- Sellers' pricing configurations work across all rank transitions
- Form validation prevents invalid rank/star combinations

### Prevention Notes
- The cumulative star system is fundamentally different from division-based system
- Key difference: absolute positions vs relative stars within division
- When adding new rank systems, ensure overlap logic handles boundary cases
- Always test: start at rank max, target at next rank start (promoting star case)

---

## BUG-009 — Data Slot Barber dan Metadata Pesanan Tidak Konsisten

### Severity

MID

### Status

FIXED

### Symptom

* Form Barber tidak menerima data slot yang digunakan view.
* Penghapusan pesanan mengecek `seller_id`, padahal kolom itu tidak ada pada order.
* Metadata persetujuan dan relasi orderable diabaikan oleh mass assignment.

### Root Cause

Slot hanya disiapkan untuk form standar, seller di order diidentifikasi dengan kolom yang salah, dan kolom orders yang dipakai controller belum ada di `$fillable`.

### Additional Causes Checked

* Perhitungan slot dibandingkan dengan form standar.
* Pemilik jasa diverifikasi melalui `service.user_id`.
* Kolom approval dan orderable diverifikasi pada migrasi orders.

### Fix Applied

* Form Barber menerima slot aktif dan jumlah ketersediaannya.
* Otorisasi hapus memakai pemilik jasa yang benar serta status aman dihapus.
* Metadata order yang sudah dipakai controller ditambahkan ke `$fillable`.

### Verification

* PHP lint dan cache Blade dijalankan setelah perubahan.

---

## BUG-016 — Notifikasi Tidak Konsisten Real-Time di Halaman Home

### Severity

HIGH

### Status

FIXED

### Symptom

Notifikasi tidak terhubung secara real-time di home; console menampilkan `Reverb public key is not configured`. Listener notifikasi juga dapat tidak berjalan ketika dimuat melalui dynamic import setelah DOM siap.

### Root Cause

`welcome.blade.php` tidak memuat konfigurasi runtime Reverb. Selain itu, `notification-listener.js` hanya memasang callback `DOMContentLoaded`, sedangkan modul tersebut dimuat dinamis setelah Echo. Event notifikasi memakai queue database sehingga delivery real-time ikut bergantung pada queue worker.

### Fix Applied

* Home kini memuat partial konfigurasi realtime yang sama seperti layout dan chat.
* Listener notifikasi langsung diinisialisasi jika DOM sudah siap.
* `NotificationCreated` memakai `ShouldBroadcastNow`, sehingga broadcast notifikasi tidak menunggu queue worker.

### Verification

* PHP lint untuk event berhasil.
* `npm run build` berhasil.

---

## BUG-017 — Pesan Chat Menunggu Respons Server Sebelum Tampil

### Severity

MID

### Status

FIXED

### Root Cause

Pesan hanya ditambahkan setelah respons HTTP diterima. Handler juga dipasang pada `click` tombol, sehingga submit HTML bawaan dapat berjalan paralel.

### Fix Applied

Pesan kini ditampilkan segera sebagai `Mengirim...`, lalu direkonsiliasi dengan respons server atau ditandai gagal sambil mengembalikan teks ke input. Pengiriman dipusatkan pada event `submit` dan mencegah submit ganda.

### Verification

* Build frontend dijalankan setelah perubahan.

---

## BUG-019 — Jeda Jasa Seller Tidak Tersimpan

### Severity

HIGH

### Status

FIXED

### Symptom

Menekan tombol nonaktifkan hanya me-reload halaman; status tetap aktif dan jasa masih tampil di marketplace.

### Root Cause

Kolom `services.is_paused` telah ada dan controller memanggil `update()`, tetapi atribut tersebut tidak masuk `$fillable` pada model `Service`. Laravel mengabaikan mass assignment atribut tersebut tanpa mengubah database.

### Fix Applied

`is_paused` ditambahkan ke `$fillable`. Scope marketplace sudah mengecualikan jasa yang dijeda sehingga perubahan kini langsung menghilangkan jasa dari listing.

### Verification

* PHP lint model berhasil.
* Cache Blade dan route endpoint ketersediaan berhasil dibuat.

---

## BUG-020 — Jasa yang Dijeda Masih Tampil di Marketplace

### Severity

HIGH

### Status

FIXED

### Root Cause

Daftar marketplace memakai filter langsung `status = approved`, bukan scope `Service::approved()`. Filter langsung tersebut tidak memeriksa `is_paused`.

### Fix Applied

Query `/jasa` kini memakai scope `approved()`, yang memerlukan status approved dan `is_paused = false`. Halaman detail serta alur pembuatan pesanan telah memakai scope yang sama.

### Verification

* Semua query status approved di controller layanan ditelusuri.
* Cache Blade berhasil dibuat ulang.

---

## BUG-018 — Navigasi Staggered Menunggu Modul Realtime

### Severity

HIGH

### Status

FIXED

### Root Cause

`app.js` memuat Echo, listener notifikasi, dan chat secara berantai sebelum mulai mengimpor StaggeredMenu. Akibatnya menu menunggu beberapa request chunk yang tidak diperlukan untuk merender navigasi.

### Fix Applied

StaggeredMenu kini mulai dimuat paralel dengan modul realtime. Modul chat hanya dimuat bila halaman benar-benar memiliki root chat.

### Verification

* Build frontend dijalankan setelah perubahan.
