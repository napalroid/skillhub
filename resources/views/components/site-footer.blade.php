@php
    $socials = [
        ['label' => 'Instagram', 'href' => '#', 'icon' => 'tabler:brand-instagram'],
        ['label' => 'Facebook', 'href' => '#', 'icon' => 'tabler:brand-facebook'],
        ['label' => 'X', 'href' => '#', 'icon' => 'tabler:brand-x'],
        ['label' => 'YouTube', 'href' => '#', 'icon' => 'tabler:brand-youtube'],
        ['label' => 'TikTok', 'href' => '#', 'icon' => 'tabler:brand-tiktok'],
    ];

    $skillhubscarfaceUrl = asset('storage/marketplace-image/skillhubscarfaceasli.png');
@endphp

<footer class="site-footer bg-[#000000] text-white">
    <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
        <!-- Top Area: Brand + Newsletter -->
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8 py-12 lg:py-16">
            <div class="flex-1">
                <h1 class="skillhub-brand-label" style="font-family:'Archivo',sans-serif;font-size:2.5rem;letter-spacing:.14em;text-transform:uppercase;color:#56CCF2;-webkit-text-stroke:1px #080808;paint-order:stroke fill">SKILLHUB</h1>
                <p class="mt-6 text-lg text-white">
                    Marketplace jasa untuk lingkungan sekolah. Tawarkan keahlian atau temukan bantuan — dengan sistem escrow yang aman.
                </p>
            </div>

            <div class="flex-1 lg:max-w-md">
                <h3 class="text-xs font-bold uppercase tracking-widest text-white mb-4">Tetap Terhubung</h3>
                <div class="flex gap-2">
                    <input
                        type="email"
                        placeholder="EMAIL"
                        class="flex-1 min-w-0 bg-transparent border border-white/20 text-white text-sm px-4 py-3 rounded-lg transition-all duration-250 focus:border-white focus:outline-none placeholder-white/30"
                        aria-label="Email untuk newsletter"
                    >
                    <button class="bg-white text-black font-bold text-sm px-6 py-3 rounded-lg hover:bg-white/90 transition-colors duration-250 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Separator -->
        <div class="border-t border-white/10 my-6 lg:my-8"></div>

        <!-- Navigation Columns -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 lg:gap-12 mb-12">
            <!-- Column 1: SkillHub -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-white mb-5">SkillHub</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="footer-link text-white/50 text-sm hover:text-white transition-colors">Tentang Kami</a></li>
                    <li><a href="#cara-kerja" class="footer-link text-white/50 text-sm hover:text-white transition-colors">Cara Kerja</a></li>
                    <li><a href="#escrow" class="footer-link text-white/50 text-sm hover:text-white transition-colors">Sistem Escrow</a></li>
                    <li><a href="#review" class="footer-link text-white/50 text-sm hover:text-white transition-colors">Ulasan Pengguna</a></li>
                </ul>
            </div>

            <!-- Column 2: Bantuan -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-white mb-5">Bantuan</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="footer-link text-white/50 text-sm hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="#" class="footer-link text-white/50 text-sm hover:text-white transition-colors">Hubungi Kami</a></li>
                    <li><a href="#" class="footer-link text-white/50 text-sm hover:text-white transition-colors">Pusat Bantuan</a></li>
                    <li><a href="#" class="footer-link text-white/50 text-sm hover:text-white transition-colors">Status Layanan</a></li>
                </ul>
            </div>

            <!-- Column 3: Layanan -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-white mb-5">Layanan</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('services.index') }}" class="footer-link text-white/50 text-sm hover:text-white transition-colors">Marketplace Jasa</a></li>
                    <li><a href="{{ route('services.create') }}" class="footer-link text-white/50 text-sm hover:text-white transition-colors">Ajukan Jasa</a></li>
                    <li><a href="#" class="footer-link text-white/50 text-sm hover:text-white transition-colors">Kategori</a></li>
                    <li><a href="#" class="footer-link text-white/50 text-sm hover:text-white transition-colors">Top Seller</a></li>
                </ul>
            </div>

            <!-- Column 4: Ikuti Kami -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-white mb-5">Ikuti Kami</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach ($socials as $social)
                        <a 
                            href="{{ $social['href'] }}" 
                            aria-label="{{ $social['label'] }} SkillHub"
                            class="footer-social flex items-center justify-center w-10 h-10 rounded-full border border-white/15 text-white/60 transition-all duration-250 hover:border-white hover:bg-white hover:text-black hover:-translate-y-1"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <x-icon name="{{ $social['icon'] }}" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- SkillHub Scarface Section - AFTER FOOTER, SEPARATE SECTION, FULL WIDTH -->
    <section class="skillhubscarface-section w-full">
        <div class="w-full relative aspect-[2.5/1] lg:aspect-[3/1]">
            <img 
                src="{{ $skillhubscarfaceUrl }}" 
                alt="SkillHub Marketplace"
                class="absolute inset-0 w-full h-full object-contain object-center"
                loading="lazy"
            >
        </div>
    </section>
</footer>

<script>
    (function () {
        var footer = document.currentScript.closest('.site-footer');
        if (!footer) return;

        // Link underline animation
        var links = footer.querySelectorAll('.footer-link');
        links.forEach(function (link) {
            var underline = document.createElement('span');
            underline.className = 'footer-underline';
            underline.setAttribute('style', 'position:absolute;left:0;bottom:-4px;width:100%;height:2px;background:#fff;transform:scaleX(0);transform-origin:left;transition:transform 250ms cubic-bezier(0.32,0.72,0,1);pointer-events:none;');
            link.style.position = 'relative';
            link.appendChild(underline);

            link.addEventListener('mouseenter', function () {
                underline.style.transform = 'scaleX(1)';
            });

            link.addEventListener('mouseleave', function () {
                underline.style.transform = 'scaleX(0)';
            });
        });

        // Social icons hover
        var socialIcons = footer.querySelectorAll('.footer-social');
        socialIcons.forEach(function (icon) {
            icon.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-2px)';
            });

            icon.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        });

        // Reduced motion support
        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduce) {
            links.forEach(function (link) {
                var underline = link.querySelector('.footer-underline');
                if (underline) {
                    underline.style.transition = 'none';
                }
            });

            socialIcons.forEach(function (icon) {
                icon.style.transition = 'none';
            });
        }
    })();
</script>
