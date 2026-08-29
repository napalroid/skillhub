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

<footer class="site-footer">
    <style>
        .site-footer {
            --sf-black: #000000;
            --sf-dark: #0a0a0a;
            --sf-gray-1: #333333;
            --sf-gray-2: #666666;
            --sf-gray-3: #999999;
            --sf-white: #ffffff;
            --sf-dur: 240ms;
        }
        
        .site-footer * { box-sizing: border-box; }
        
        .sf-wrapper {
            background: var(--sf-black);
            color: var(--sf-white);
        }
        
        .sf-container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 1.2rem;
        }
        
        @media (min-width: 768px) {
            .sf-container { padding: 0 2.7rem; }
        }
        
        /* ===== HERO SECTION ===== */
        .sf-hero {
            display: grid;
            grid-template-columns: 1fr;
            gap: 4rem;
            padding: 6rem 0 5rem;
            border-bottom: 2px solid var(--sf-gray-1);
        }
        
        @media (min-width: 1024px) {
            .sf-hero {
                grid-template-columns: 1.4fr 1fr;
                gap: 8rem;
                padding: 8rem 0 6rem;
            }
        }
        
        .sf-hero-content h1 {
            margin: 0;
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 900;
            letter-spacing: -0.05em;
            text-transform: uppercase;
            line-height: 1;
            color: var(--sf-white);
            text-decoration: none;
            pointer-events: none;
        }
        
        .sf-hero-content p {
            margin: 1.75rem 0 0;
            max-width: 34rem;
            font-size: 1.05rem;
            line-height: 1.65;
            color: var(--sf-gray-3);
        }
        
        .sf-hero-cta {
            margin-top: 2.25rem;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            border: 2px solid var(--sf-white);
            background: var(--sf-white);
            color: var(--sf-black);
            font-size: 0.75rem;
            font-weight: 900;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            text-decoration: none;
            transition: all var(--sf-dur);
        }
        
        .sf-hero-cta:hover {
            background: var(--sf-white);
            color: var(--sf-black);
        }
        
        .sf-hero-cta svg {
            width: 1rem;
            height: 1rem;
            stroke-width: 3;
        }
        
        .sf-newsletter {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        
        .sf-newsletter-label {
            margin: 0;
            font-size: 0.68rem;
            font-weight: 900;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--sf-gray-2);
        }
        
        .sf-newsletter-form {
            display: flex;
            gap: 0;
            border: 2px solid var(--sf-white);
            height: 3.5rem;
        }
        
        .sf-newsletter-input {
            flex: 1;
            min-width: 0;
            border: none;
            background: var(--sf-black);
            color: var(--sf-white);
            font-size: 0.95rem;
            padding: 0 1.5rem;
            font-family: inherit;
            outline: none;
        }
        
        .sf-newsletter-input::placeholder {
            color: var(--sf-gray-3);
        }
        
        .sf-newsletter-btn {
            min-width: 4.5rem;
            border: none;
            background: var(--sf-black);
            color: var(--sf-white);
            font-weight: 900;
            font-size: 0.75rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--sf-dur);
        }
        
        .sf-newsletter-btn:hover {
            background: var(--sf-white);
            color: var(--sf-black);
        }
        
        .sf-newsletter-btn svg {
            width: 1.25rem;
            height: 1.25rem;
            stroke-width: 3;
        }
        
        /* ===== NAVIGATION SECTION ===== */
        .sf-nav-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 3rem;
            padding: 5rem 0 4rem;
        }
        
        @media (min-width: 768px) {
            .sf-nav-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 4rem;
                padding: 6.5rem 0;
            }
        }
        
        .sf-nav-col h3 {
            margin: 0 0 1.5rem;
            font-size: 0.7rem;
            font-weight: 900;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--sf-white);
            line-height: 1;
        }
        
        .sf-nav-col p {
            margin: 0 0 0.75rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--sf-gray-2);
            line-height: 1.4;
            position: relative;
            cursor: pointer;
            transition: color var(--sf-dur);
        }
        
        .sf-nav-col p::before {
            content: '';
            position: absolute;
            left: -1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 1px;
            background: var(--sf-white);
            transition: width var(--sf-dur) cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .sf-nav-col p:hover {
            color: var(--sf-white);
        }
        
        .sf-nav-col p:hover::before {
            width: 0.75rem;
        }
        
        /* ===== SOCIAL ICONS ===== */
        .sf-social-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .sf-social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            border: 2px solid var(--sf-white);
            background: var(--sf-black);
            color: var(--sf-white);
            cursor: pointer;
            text-decoration: none;
            transition: all var(--sf-dur);
        }
        
        .sf-social-link:hover {
            background: var(--sf-white);
            color: var(--sf-black);
            transform: translateY(-3px);
        }
        
        .sf-social-link svg {
            width: 1.2rem;
            height: 1.2rem;
            stroke-width: 2;
        }
        
        /* ===== BOTTOM SECTION ===== */
        .sf-footer-bottom {
            display: none;
        }
        
        @media (min-width: 768px) {
            .sf-footer-bottom {
                display: none;
            }
        }
        
        /* ===== SCARFACE SECTION ===== */
        .sf-scarface-section {
            width: 100%;
            background: #000000;
            padding: 0 1.2rem 1rem;
        }
        
        @media (min-width: 768px) {
            .sf-scarface-section {
                padding: 0 2.7rem 0;
                margin-top: -4rem;
            }
        }
        
        .sf-scarface-wrapper {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            padding-top: 40%;
        }
        
        @media (min-width: 768px) {
            .sf-scarface-wrapper {
                padding-top: 25%;
            }
        }
        
        .sf-scarface-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center center;
            background: transparent;
        }
        
        @media (min-width: 768px) {
            .sf-scarface-image {
                transform: translateX(15%);
            }
        }
        
        @media (prefers-reduced-motion: reduce) {
            .sf-hero-cta,
            .sf-nav-col a,
            .sf-social-link,
            .sf-footer-links a,
            .sf-newsletter-btn {
                transition: none;
            }
        }
    </style>

    <div class="sf-wrapper">
        <div class="sf-container">
            <!-- HERO SECTION -->
            <section class="sf-hero" aria-label="Footer header">
                <div class="sf-hero-content">
                    <h1>SkillHub</h1>
                    <p>Marketplace jasa untuk lingkungan sekolah. Tawarkan keahlian atau temukan bantuan — dengan sistem escrow yang aman.</p>
                    <a href="{{ route('services.index') }}" class="sf-hero-cta">
                        Jelajahi Jasa
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>

                <div class="sf-newsletter">
                    <label class="sf-newsletter-label">Dapatkan update</label>
                    <form class="sf-newsletter-form" action="#" method="POST" novalidate>
                        <input
                            type="email"
                            placeholder="EMAIL ANDA"
                            class="sf-newsletter-input"
                            aria-label="Email untuk newsletter"
                        >
                        <button type="submit" class="sf-newsletter-btn" aria-label="Berlangganan">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </form>
                </div>
            </section>

            <!-- NAVIGATION -->
            <div class="sf-nav-grid" aria-label="Footer navigation">
                <div class="sf-nav-col">
                    <h3>SkillHub</h3>
                    <p>Tentang Kami</p>
                    <p>Cara Kerja</p>
                    <p>Sistem Escrow</p>
                    <p>Ulasan Pengguna</p>
                </div>

                <div class="sf-nav-col">
                    <h3>Bantuan</h3>
                    <p>FAQ</p>
                    <p>Hubungi Kami</p>
                    <p>Pusat Bantuan</p>
                    <p>Status Layanan</p>
                </div>

                <div class="sf-nav-col">
                    <h3>Layanan</h3>
                    <p>Marketplace Jasa</p>
                    <p>Ajukan Jasa</p>
                    <p>Kategori</p>
                    <p>Top Seller</p>
                </div>

                <div class="sf-nav-col">
                    <h3>Sosial Media</h3>
                    <div class="sf-social-group">
                        <a href="#" class="sf-social-link" title="Instagram">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="sf-social-link" title="Facebook">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                        </a>
                        <a href="#" class="sf-social-link" title="X">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="sf-social-link" title="YouTube">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.376.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.376-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                        <a href="#" class="sf-social-link" title="TikTok">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93v6.16c0 2.52-1.12 4.84-2.9 6.34-1.73 1.45-4.06 2.08-6.32 1.62-2.46-.49-4.45-2.4-5.47-4.81-1.01-2.33-1.05-4.96-.09-7.32.95-2.36 3.25-3.95 5.86-4.48 1.14-.22 2.32-.32 3.49-.31v3.63c-1.11.02-2.22.46-3.08 1.2-.76.68-1.26 1.64-1.39 2.71-.13 1.06.25 2.14.97 2.97.72.83 1.77 1.34 2.89 1.41 1.12.07 2.24-.26 3.07-1.02.83-.76 1.32-1.86 1.33-2.99v-5.4c-.04-.12-.07-.25-.11-.37-.03-.13-.07-.26-.11-.39-.06-.2-.13-.39-.21-.58-.08-.19-.19-.37-.32-.53-.13-.16-.29-.3-.47-.42-.18-.12-.39-.21-.61-.27-.22-.06-.45-.09-.68-.09H12.52z"/></svg>
                        </a>
                        <a href="#" class="sf-social-link" title="Instagram">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 7c2.761 0 5 2.239 5 5s-2.239 5-5 5-5-2.239-5-5 2.239-5 5-5zm0-2c-3.866 0-7 3.134-7 7s3.134 7 7 7 7-3.134 7-7-3.134-7-7-7zm6.5-3.5c-.828 0-1.5.672-1.5 1.5s.672 1.5 1.5 1.5 1.5-.672 1.5-1.5-.672-1.5-1.5-1.5zm-4.378 5.54c1.341 0 2.422-1.08 2.422-2.422s-1.08-2.422-2.422-2.422-2.422 1.08-2.422 2.422 1.08 2.422 2.422 2.422zm-4.844 0c1.341 0 2.422-1.08 2.422-2.422s-1.08-2.422-2.422-2.422-2.422 1.08-2.422 2.422 1.08 2.422 2.422 2.422zm4.844 3.61c-2.359 0-4.383 1.888-4.76 4.325l-2.854.572c-.965.194-1.672.963-1.804 1.928-.132.965.457 1.857 1.37 2.203 1.058.403 2.264.167 3.19-.668l1.61-1.465c.667.377 1.43.646 2.245.767.815.122 1.663-.033 2.41-.443.746-.41 1.372-1.058 1.78-1.855.409-.798.594-1.72.528-2.662-.066-.941-.51-1.827-1.24-2.502-.731-.676-1.702-1.077-2.712-1.077zm-2.422 3.61c-1.341 0-2.422 1.08-2.422 2.422s1.08 2.422 2.422 2.422 2.422-1.08 2.422-2.422-1.08-2.422-2.422-2.422zm4.844 0c-1.341 0-2.422 1.08-2.422 2.422s1.08 2.422 2.422 2.422 2.422-1.08 2.422-2.422-1.08-2.422-2.422-2.422zm-4.844 4.844c-2.359 0-4.383 1.888-4.76 4.325l-1.835.368c-.965.194-1.672.963-1.804 1.928-.132.965.457 1.857 1.37 2.203 1.058.403 2.264.167 3.19-.668l1.61-1.465c.667.377 1.43.646 2.245.767.815.122 1.663-.033 2.41-.443.746-.41 1.372-1.058 1.78-1.855.409-.798.594-1.72.528-2.662-.066-.941-.51-1.827-1.24-2.502-.731-.676-1.702-1.077-2.712-1.077z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCARFACE IMAGE SECTION -->
    <section class="sf-scarface-section">
        <div class="sf-scarface-wrapper">
            <img 
                src="{{ $skillhubscarfaceUrl }}" 
                alt="SkillHub Marketplace"
                class="sf-scarface-image"
                loading="lazy"
            >
        </div>
    </section>
</footer>
