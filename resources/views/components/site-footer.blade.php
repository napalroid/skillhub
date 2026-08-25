@php
    $socials = [
        ['label' => 'Instagram', 'href' => '#', 'path' => 'M12 2.25c2.67 0 2.99.01 4.04.058 1.05.048 1.62.22 2 .37.5.13.86.4 1.24.78.38.38.65.74.78 1.24.15.38.32.95.37 2 .048 1.05.058 1.37.058 4.04s-.01 2.99-.058 4.04c-.048 1.05-.22 1.62-.37 2-.13.5-.4.86-.78 1.24-.38.38-.74.65-1.24.78-.38.15-.95.32-2 .37-1.05.048-1.37.058-4.04.058s-2.99-.01-4.04-.058c-1.05-.048-1.62-.22-2-.37a3.3 3.3 0 0 1-1.24-.78 3.3 3.3 0 0 1-.78-1.24c-.15-.38-.32-.95-.37-2C2.26 14.99 2.25 14.67 2.25 12s.01-2.99.058-4.04c.048-1.05.22-1.62.37-2 .13-.5.4-.86.78-1.24.38-.38.74-.65 1.24-.78.38-.15.95-.32 2-.37C9.01 2.26 9.33 2.25 12 2.25Zm0 1.5c-2.62 0-2.93.01-3.96.057-1 .046-1.54.21-1.9.35-.48.18-.82.4-1.18.76-.36.36-.58.7-.76 1.18-.14.36-.31.9-.35 1.9C4.26 9.07 4.25 9.38 4.25 12s.01 2.93.057 3.96c.046 1 .21 1.54.35 1.9.18.48.4.82.76 1.18.36.36.7.58 1.18.76.36.14.9.31 1.9.35 1.03.047 1.34.057 3.96.057s2.93-.01 3.96-.057c1-.046 1.54-.21 1.9-.35.48-.18.82-.4 1.18-.76.36-.36.58-.7.76-1.18.14-.36.31-.9.35-1.9.047-1.03.057-1.34.057-3.96s-.01-2.93-.057-3.96c-.046-1-.21-1.54-.35-1.9a3.16 3.16 0 0 0-.76-1.18 3.16 3.16 0 0 0-1.18-.76c-.36-.14-.9-.31-1.9-.35C14.93 3.76 14.62 3.75 12 3.75Zm0 2.77a5.48 5.48 0 1 1 0 10.96 5.48 5.48 0 0 1 0-10.96Zm0 1.5a3.98 3.98 0 1 0 0 7.96 3.98 3.98 0 0 0 0-7.96Zm5.65-2.4a1.28 1.28 0 1 1 0 2.56 1.28 1.28 0 0 1 0-2.56Z'],
        ['label' => 'TikTok', 'href' => '#', 'path' => 'M16.5 3c.4 2.3 1.9 3.8 4.2 4v2.7c-1.5.1-2.9-.3-4.2-1.1v5.9c0 3.1-2.3 5.7-5.4 6-3.4.3-6.3-2.2-6.3-5.5 0-2.9 2.3-5.3 5.2-5.5.3 0 .6.1.8.2v2.8c-.2-.1-.5-.2-.8-.2-1.4 0-2.6 1.2-2.6 2.7 0 1.5 1.2 2.7 2.7 2.7 1.6 0 2.9-1.2 2.9-2.9V3h3.3Z'],
        ['label' => 'YouTube', 'href' => '#', 'path' => 'M21.6 7.2a2.6 2.6 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4A2.6 2.6 0 0 0 2.4 7.2 27 27 0 0 0 2 12a27 27 0 0 0 .4 4.8 2.6 2.6 0 0 0 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4a2.6 2.6 0 0 0 1.8-1.8A27 27 0 0 0 22 12a27 27 0 0 0-.4-4.8ZM10 15V9l5 3-5 3Z'],
        ['label' => 'X', 'href' => '#', 'path' => 'M17.53 3H20.5l-6.49 7.41L21.75 21h-5.96l-4.66-6.09L5.7 21H2.73l6.94-7.93L2.25 3h6.1l4.22 5.58L17.53 3Zm-1.04 16.2h1.65L7.6 4.71H5.83L16.49 19.2Z'],
    ];
@endphp

<footer class="site-footer bg-black text-white">
    <div class="mx-auto max-w-6xl px-5 py-14 sm:px-6">
        <div class="grid gap-12 md:grid-cols-[1.4fr_1fr_1fr_1fr]">
            {{-- Brand --}}
            <div data-reveal>
                <x-brand-logo :href="route('home')" surface="dark" />
                <p class="mt-4 max-w-xs text-sm leading-6 text-white/60">
                    Marketplace jasa untuk lingkungan sekolah. Tawarkan keahlian atau temukan bantuan — dengan sistem escrow yang aman.
                </p>
                <div class="mt-6 flex items-center gap-3">
                    @foreach ($socials as $social)
                        <a href="{{ $social['href'] }}" aria-label="{{ $social['label'] }}" class="footer-social">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                                <path d="{{ $social['path'] }}" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Jelajahi --}}
            <div data-reveal>
                <h3 class="footer-heading">Jelajahi</h3>
                <ul class="footer-list">
                    <li><a href="{{ route('services.index') }}">Marketplace Jasa</a></li>
                    <li><a href="{{ route('home') }}#cara-kerja">Cara Kerja</a></li>
                    <li><a href="{{ route('home') }}#escrow">Sistem Escrow</a></li>
                    <li><a href="{{ route('home') }}#review">Ulasan Pengguna</a></li>
                </ul>
            </div>

            {{-- Akun --}}
            <div data-reveal>
                <h3 class="footer-heading">Akun</h3>
                <ul class="footer-list">
                    @guest
                        <li><a href="{{ route('login') }}">Masuk</a></li>
                        <li><a href="{{ route('register') }}">Daftar Gratis</a></li>
                    @else
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('orders.index') }}">Pesanan Saya</a></li>
                        <li><a href="{{ route('services.create') }}">Ajukan Jasa</a></li>
                        <li><a href="{{ route('profile.edit') }}">Profil</a></li>
                    @endguest
                </ul>
            </div>

            {{-- Perusahaan --}}
            <div data-reveal>
                <h3 class="footer-heading">SkillHub</h3>
                <ul class="footer-list">
                    <li><a href="{{ route('home') }}">Tentang Kami</a></li>
                    <li><a href="#">Bantuan &amp; Kontak</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat &amp; Ketentuan</a></li>
                </ul>
            </div>
        </div>

        {{-- CTA / Newsletter --}}
        <div class="mt-12 border-t border-white/10 pt-10" data-reveal>
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-heading text-lg font-bold text-white">Punya keahlian? Jadilah seller.</h3>
                    <p class="mt-1 text-sm text-white/60">Dapatkan orderan dari siswa lain di sekolahmu — gratis untuk memulai.</p>
                </div>
                <form class="flex w-full max-w-md gap-2" onsubmit="return false;">
                    <label class="sr-only" for="footer-email">Email</label>
                    <input id="footer-email" type="email" placeholder="Email kamu" class="footer-input" autocomplete="email">
                    <button type="submit" class="footer-btn">Mulai</button>
                </form>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-10 flex flex-col gap-4 border-t border-white/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-white/50">&copy; {{ date('Y') }} SkillHub — Proyek UKK PPLG.</p>
            <div class="flex flex-wrap items-center gap-5">
                <a href="#" class="footer-mini">Privasi</a>
                <a href="#" class="footer-mini">Ketentuan</a>
                <a href="#" class="footer-mini">Bantuan</a>
                <button type="button" class="footer-mini footer-top">Kembali ke atas &uarr;</button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var footer = document.currentScript.closest('.site-footer');
            if (!footer) return;
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var items = footer.querySelectorAll('[data-reveal]');

            function revealAll() { items.forEach(function (el) { el.classList.add('is-visible'); }); }

            if (reduce || !('IntersectionObserver' in window)) {
                revealAll();
            } else {
                footer.classList.add('js-anim');
                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            var i = Array.prototype.indexOf.call(items, entry.target);
                            entry.target.style.transitionDelay = (i * 0.06) + 's';
                            entry.target.classList.add('is-visible');
                            io.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.15 });
                items.forEach(function (el) { io.observe(el); });
            }

            var top = footer.querySelector('.footer-top');
            if (top) {
                top.addEventListener('click', function () {
                    window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
                });
            }
        })();
    </script>
</footer>
