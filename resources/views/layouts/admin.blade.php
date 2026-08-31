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
<body class="min-h-screen bg-[#F5F5F5] font-body antialiased" x-data="adminLayout()" x-init="init()">
    <x-loading-screen />
    <div id="page-transition-overlay" aria-hidden="true" class="fixed inset-0 bg-white z-[9999] opacity-0 pointer-events-none transition-opacity duration-300"></div>

    <!-- SIDEBAR OVERLAY (Mobile) -->
    <div 
        x-show="sidebarOpen" 
        x-transition.opacity 
        class="fixed inset-0 bg-black/50 z-40 lg:hidden" 
        @click="sidebarOpen = false"
        aria-hidden="true"></div>

    <!-- SIDEBAR -->
    <aside 
        class="fixed inset-y-0 left-0 z-50 w-64 bg-black text-white flex flex-col transition-transform duration-300 ease-in-out transform"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        x-on:keydown.escape.window="sidebarOpen = false">
        
        <div class="flex items-center justify-between h-16 px-6 border-b border-white/10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2" aria-label="SkillHub Admin Home">
                <span class="font-heading font-bold text-base uppercase tracking-wide">SkillHub Admin</span>
            </a>
            <button 
                @click="sidebarOpen = false" 
                class="lg:hidden p-2 rounded-sm hover:bg-white/10 transition-colors"
                aria-label="Close menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <template x-for="item in navItems" :key="item.route">
                <a 
                    :href="item.route" 
                    :class="['flex', 'items-center', 'gap-3', 'px-4', 'py-3', 'rounded-sm', 'text-sm', 'font-medium', 'uppercase', 'tracking-wide', 'transition-colors', 'duration-150',
                        activeRoute === item.route ? 'bg-white text-black' : 'text-white/70 hover:text-white hover:bg-white/10']"
                    @click="setActiveRoute(item.route); if(window.innerWidth < 1024) sidebarOpen = false">
                    <span x-html="item.icon"></span>
                    <span x-text="item.label"></span>
                </a>
            </template>
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="px-4 py-3 mb-2">
                <p class="font-heading font-bold text-sm text-white" x-text="authUser.name"></p>
                <p class="text-xs text-white/60 truncate" x-text="authUser.email"></p>
            </div>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-sm text-sm font-medium text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                Profil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 rounded-sm text-sm font-medium text-[#E4002B] hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m-3-3h8.25m0 0-3-3m3 3-3 3"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- TOP BAR -->
    <div class="fixed top-0 right-0 z-30 h-16 bg-white border-b border-[#DDDDDD] flex items-center justify-between px-6 transition-all duration-300"
         :class="sidebarOpen ? 'left-64' : 'left-0'">
        <div class="flex items-center gap-4">
            <button 
                @click="sidebarOpen = !sidebarOpen" 
                class="p-2 rounded-sm hover:bg-[#F5F5F5] transition-colors"
                aria-label="Toggle menu">
                <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
            <h1 class="font-heading font-bold text-lg text-black uppercase tracking-tight" x-text="pageTitle"></h1>
        </div>
        <a href="{{ route('dashboard') }}" class="px-3 py-2 text-xs font-bold uppercase tracking-wide text-[#555555] hover:text-black transition-colors">Dashboard User</a>
    </div>

    <!-- MAIN CONTENT WRAPPER -->
    <main class="min-h-screen pt-16 transition-all duration-300" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'">
        <div class="max-w-[1400px] mx-auto px-4 lg:px-8 py-8 lg:py-12">
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
                sidebarOpen: false,
                showCategoryModal: false,
                showCreateModal: false,
                showSubCreateModal: false,
                activeRoute: window.location.pathname,
                pageTitle: 'Dashboard',
                authUser: @json(auth()->user() ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
                navItems: [
                    { route: '{{ route('admin.dashboard') }}', label: 'Dashboard', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 0 0 2 2h10l8-8V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 11l3-3 6 6"/></svg>' },
                    { route: '{{ route('admin.services.index') }}', label: 'Semua Jasa', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 14.15c.864.864 1.545 2.05.864 3.297a4.643 4.643 0 01-1.323 2.426M15 18c0 2.485-2.015 4.5-4.5 4.5s-4.5-2.015-4.5-4.5M15 10.5A4.5 4.5 0 007.5 15M15 10.5A4.5 4.5 0 0122.5 15M15 10.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 10.5h1.5M2.25 15h1.5"/></svg>' },
                    { route: '{{ route('admin.services.pending') }}', label: 'Approval', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>' },
                    { route: '{{ route('admin.categories.index') }}', label: 'Kategori', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 12h16.5M6 6h12v12.75a2.25 2.25 0 0 0 2.25 2.25H6A2.25 2.25 0 0 1 3.75 12z"/></svg>' },
                    { route: '{{ route('admin.payments.index') }}', label: 'Transaksi', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l2.5 2.5M15 15l3-3"/></svg>' },
                    { route: '{{ route('admin.escrow.index') }}', label: 'Escrow', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>' },
                    { route: '{{ route('admin.payouts.index') }}', label: 'Pencairan', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v6.75A2.25 2.25 0 0 1 18.75 16.5H5.25A2.25 2.25 0 0 1 3 14.25V6.75z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75h18"/></svg>' },
                    { route: '{{ route('admin.reports.index') }}', label: 'Laporan', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9V5m0 0L8 9m4-4v14m-4-7h8"/></svg>' },
                ],
                toast: { message: '', type: 'info' },

                init() {
                    this.setActiveRoute(window.location.pathname);
                    this.handlePageLoad();
                    this.sidebarOpen = window.innerWidth >= 1024;
                    
                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 1024) {
                            this.sidebarOpen = true;
                        } else {
                            this.sidebarOpen = false;
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

                showToast(message, type = 'info') {
                    this.toast = { message, type };
                    setTimeout(() => this.toast = { message: '', type: 'info' }, 5000);
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