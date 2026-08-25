<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SkillHub Admin' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.route = "{{ route('admin.dashboard') }}".replace('dashboard', '');
        window.csrfToken = "{{ csrf_token() }}";
    </script>
</head>
<body class="min-h-screen bg-white font-body antialiased" x-data="adminLayout()" x-init="init()">
    <div id="page-transition-overlay" aria-hidden="true" class="fixed inset-0 bg-white z-[9999] opacity-0 pointer-events-none transition-opacity duration-300"></div>

    <!-- TOP NAVBAR - Black solid like adidas.co.id -->
    <header class="sticky top-0 z-50 bg-black text-white border-b border-black">
        <div class="max-w-[1280px] mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-14">
                <!-- Left: Logo + Desktop Nav -->
                <div class="flex items-center gap-6 lg:gap-8">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 shrink-0" aria-label="SkillHub Admin Home">
                        <span class="w-8 h-8 rounded bg-white text-black flex items-center justify-center text-xs font-bold font-heading">S</span>
                        <span class="hidden lg:block font-heading font-bold text-sm uppercase tracking-wide">SkillHub</span>
                    </a>
                    
                    <!-- Desktop Navigation -->
                    <nav class="hidden lg:flex items-center gap-1" aria-label="Navigasi utama admin">
                        <template x-for="item in navItems" :key="item.route">
                            <a 
                                :href="item.route" 
                                :class="['nav-link-top', 'px-3', 'py-2', 'rounded-sm', 'text-xs', 'font-bold', 'uppercase', 'tracking-wider', 'transition-colors', 'duration-150',
                                    activeRoute === item.route ? 'text-white bg-white/10' : 'text-white/70 hover:text-white hover:bg-white/10']"
                                @click="setActiveRoute(item.route)">
                                <span class="flex items-center gap-1.5" x-html="item.icon + ' ' + item.label"></span>
                            </a>
                        </template>
                    </nav>
                </div>

                <!-- Right: Actions -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" target="_blank" class="hidden lg:block px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-white/70 hover:text-white hover:bg-white/10 rounded-sm transition-colors duration-150">Lihat Website</a>
                    
                    <!-- Mobile Menu Toggle -->
                    <button 
                        @click="toggleMobileMenu()" 
                        class="lg:hidden p-2 rounded-sm text-white/70 hover:text-white hover:bg-white/10 transition-colors duration-150"
                        aria-label="Buka menu"
                        aria-expanded="false"
                        :aria-expanded="mobileMenuOpen.toString()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenuOpen"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenuOpen"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ userMenuOpen: false }">
                        <button 
                            @click="userMenuOpen = !userMenuOpen" 
                            class="flex items-center gap-2 p-1.5 rounded-sm hover:bg-white/10 transition-colors duration-150"
                            aria-label="Menu user"
                            aria-expanded="false"
                            :aria-expanded="userMenuOpen.toString()"
                            @click.outside="userMenuOpen = false">
                            <div class="w-8 h-8 rounded-full bg-white text-black flex items-center justify-center text-xs font-bold font-heading" x-text="userInitials"></div>
                            <span class="hidden lg:block text-xs font-medium uppercase tracking-wide">Admin</span>
                            <svg class="w-4 h-4 lg:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        
                        <div x-show="userMenuOpen" x-transition class="absolute right-0 top-full mt-2 min-w-[200px] bg-white border border-[#DDDDDD] rounded-sm shadow-lg overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-[#DDDDDD]">
                                <p class="font-heading font-bold text-sm text-black" x-text="authUser.name"></p>
                                <p class="text-xs text-[#555555] truncate" x-text="authUser.email"></p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-[#111111] hover:bg-[#F5F5F5] transition-colors">Profil</a>
                            <button @click="logout()" class="w-full text-left px-4 py-2.5 text-sm text-[#E4002B] hover:bg-[#F5F5F5] transition-colors">Keluar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- MOBILE SIDEBAR OVERLAY -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition.opacity 
        class="fixed inset-0 bg-black/50 z-40 lg:hidden" 
        @click="toggleMobileMenu()" 
        aria-hidden="true"></div>

    <!-- MOBILE SIDEBAR -->
    <aside 
        x-show="mobileMenuOpen" 
        x-transition 
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-[#DDDDDD] lg:hidden flex flex-col"
        @click.outside="toggleMobileMenu()">
        <nav class="flex-1 p-6 space-y-1 overflow-y-auto">
            <template x-for="item in navItems" :key="item.route">
                <a 
                    :href="item.route" 
                    :class="['nav-link-side', 'flex', 'items-center', 'gap-3', 'px-4', 'py-3', 'rounded-sm', 'text-sm', 'font-medium', 'uppercase', 'tracking-wide', 'transition-colors', 'duration-150',
                        activeRoute === item.route ? 'text-black bg-[#F5F5F5]' : 'text-[#555555] hover:text-black hover:bg-[#F5F5F5]']"
                    @click="setActiveRoute(item.route); toggleMobileMenu()">
                    <span x-html="item.icon"></span>
                    <span x-text="item.label"></span>
                </a>
            </template>
        </nav>
        <div class="p-4 border-t border-[#DDDDDD]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link-side flex items-center gap-3 w-full justify-start px-4 py-3 text-sm font-medium uppercase tracking-wide text-[#E4002B] hover:bg-[#F5F5F5]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m-3-3h8.25m0 0-3-3m3 3-3 3"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <main class="min-h-screen bg-[#F5F5F5]" :class="{ 'lg:pl-0': true }">
        <div class="max-w-[1280px] mx-auto px-4 lg:px-8 py-8 lg:py-12">
            <!-- FLASH MESSAGES -->
            <div 
                x-show="toast.message" 
                x-transition 
                class="fixed top-20 right-6 z-50 px-4 py-3 rounded-sm font-heading text-xs font-bold uppercase tracking-wider shadow-lg border-2 transition-transform transition-opacity duration-300"
                :class="{ 
                    'translate-y-0 opacity-100': toast.message, 
                    'translate-y-4 opacity-0': !toast.message,
                    'bg-[#2C9F45] text-white border-[#2C9F45]': toast.type === 'success',
                    'bg-[#E4002B] text-white border-[#E4002B]': toast.type === 'error',
                    'bg-black text-white border-black': toast.type === 'info'
                }" 
                x-text="toast.message"
                role="alert"
                aria-live="polite"></div>

            @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition class="fixed top-20 right-6 z-50 bg-[#2C9F45] text-white border-[#2C9F45] border-2 px-4 py-3 rounded-sm font-heading text-xs font-bold uppercase tracking-wider shadow-lg" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition class="fixed top-20 right-6 z-50 bg-[#E4002B] text-white border-[#E4002B] border-2 px-4 py-3 rounded-sm font-heading text-xs font-bold uppercase tracking-wider shadow-lg" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <script>
        function adminLayout() {
            return {
                mobileMenuOpen: false,
                showCategoryModal: false,
                activeRoute: window.location.pathname,
                pageTitle: 'Dashboard',
                authUser: @json(auth()->user() ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
                navItems: [
                    { route: '{{ route('admin.dashboard') }}', label: 'Dashboard', icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 0 0 2 2h10l8-8V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 11l3-3 6 6"/></svg>' },
                    { route: '{{ route('admin.services.index') }}', label: 'Semua Jasa', icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 14.15c.864.864 1.545 2.05.864 3.297a4.643 4.643 0 01-1.323 2.426M15 18c0 2.485-2.015 4.5-4.5 4.5s-4.5-2.015-4.5-4.5M15 10.5A4.5 4.5 0 007.5 15M15 10.5A4.5 4.5 0 0122.5 15M15 10.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 10.5h1.5M2.25 15h1.5"/></svg>' },
                    { route: '{{ route('admin.services.pending') }}', label: 'Antrian Approval', icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>' },
                    { route: '{{ route('admin.categories.index') }}', label: 'Kategori', icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 12h16.5M6 6h12v12.75a2.25 2.25 0 0 0 2.25 2.25H6A2.25 2.25 0 0 1 3.75 12z"/></svg>' },
                    { route: '{{ route('admin.subcategories.index') }}', label: 'Subkategori', icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7.25a2.25 2.25 0 0 1 2.25-2.25h7.5a2.25 2.25 0 0 1 2.25 2.25v9.5a2.25 2.25 0 0 1-2.25 2.25h-7.5A2.25 2.25 0 0 1 4 16.75z"/></svg>' },
                    { route: '{{ route('admin.payments.index') }}', label: 'Transaksi', icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l2.5 2.5M15 15l3-3"/></svg>' },
                    { route: '{{ route('admin.payouts.index') }}', label: 'Pencairan', icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v6.75A2.25 2.25 0 0 1 18.75 16.5H5.25A2.25 2.25 0 0 1 3 14.25V6.75z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75h18"/></svg>' },
                    { route: '{{ route('admin.reports.index') }}', label: 'Laporan', icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9V5m0 0L8 9m4-4v14m-4-7h8"/></svg>' },
                ],
                toast: { message: '', type: 'info' },

                init() {
                    this.setActiveRoute(window.location.pathname);
                    this.handlePageLoad();
                    
                    // Handle resize
                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 1024) {
                            this.mobileMenuOpen = false;
                        }
                    });
                },

                setActiveRoute(route) {
                    this.activeRoute = route;
                    const item = this.navItems.find(i => i.route === route);
                    this.pageTitle = item ? item.label : 'Dashboard';
                },

                handlePageLoad() {
                    const overlay = document.getElementById('page-transition-overlay');
                    if (overlay) {
                        overlay.style.opacity = '1';
                        overlay.style.pointerEvents = 'auto';
                        requestAnimationFrame(() => {
                            overlay.style.opacity = '0';
                            overlay.style.pointerEvents = 'none';
                        });
                    }
                    this.runEntryAnimations();
                },

                runEntryAnimations() {
                    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                    // Fallback: jika GSAP tidak tersedia atau user memilih reduce-motion,
                    // pastikan elemen yang dianimasikan tetap terlihat (tidak stuck di opacity:0).
                    if (!window.gsap || prefersReduced) {
                        document.querySelectorAll('.row-enter').forEach(el => {
                            el.style.opacity = '1';
                            el.style.transform = 'none';
                        });
                        return;
                    }

                    if (window.gsap) {
                        gsap.from('.stat-card', {
                            opacity: 0, y: 16, duration: 0.5, ease: 'power2.out',
                            stagger: 0.06, delay: 0.1
                        });

                        document.querySelectorAll('[data-stagger-container]').forEach(container => {
                            const items = container.querySelectorAll('[data-stagger-item]');
                            if (items.length) {
                                gsap.from(items, {
                                    opacity: 0, y: 12, duration: 0.4, ease: 'power2.out',
                                    stagger: 0.05, delay: 0.15
                                });
                            }
                        });

                        gsap.fromTo('.row-enter',
                            { opacity: 0, y: 12 },
                            { opacity: 1, y: 0, duration: 0.4, ease: 'power2.out', stagger: 0.03, delay: 0.1 }
                        );
                    }
                },

                toggleMobileMenu() {
                    this.mobileMenuOpen = !this.mobileMenuOpen;
                },

                logout() {
                    document.querySelector('form[action="{{ route('logout') }}"]').submit();
                },

                showToast(message, type = 'info') {
                    this.toast = { message, type };
                    setTimeout(() => this.toast = { message: '', type: 'info' }, 5000);
                },
                
                get userInitials() {
                    const name = this.authUser?.name ?? 'A';
                    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
                }
            };
        }

        // Global page transition
        document.addEventListener('DOMContentLoaded', function() {
            const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const overlay = document.getElementById('page-transition-overlay');
            let isFirstNavigation = true;

            function triggerPageTransition(url) {
                if (prefersReduced || !overlay) {
                    window.location.href = url;
                    return;
                }
                if (isFirstNavigation) {
                    isFirstNavigation = false;
                    overlay.style.opacity = '1';
                    overlay.style.pointerEvents = 'auto';
                    setTimeout(() => window.location.href = url, 200);
                } else {
                    window.location.href = url;
                }
            }

            document.querySelectorAll('a[href^="{{ url('admin') }}"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                    if (this.target === '_blank') return;
                    const href = this.getAttribute('href');
                    if (href && href !== window.location.pathname) {
                        e.preventDefault();
                        triggerPageTransition(href);
                    }
                });
            });

            window.addEventListener('pageshow', function(event) {
                if (overlay) {
                    overlay.style.opacity = '0';
                    overlay.style.pointerEvents = 'none';
                }
                if (event.persisted) isFirstNavigation = true;
            });
        });
    </script>
</body>
</html>