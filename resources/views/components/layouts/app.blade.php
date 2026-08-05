<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SkillHub' }} - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ mobileOpen: false }" class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">

    {{-- NAVBAR --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                        </svg>
                    </span>
                    <span class="font-heading text-lg font-bold text-slate-900 tracking-tight">SkillHub</span>
                </a>

                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-blue-700 bg-blue-50 transition-colors">Dashboard</a>
                    <a href="{{ route('services.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-blue-700 hover:bg-slate-100 transition-colors">Jelajahi Jasa</a>
                    <a href="{{ route('services.create') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-blue-700 hover:bg-slate-100 transition-colors">Ajukan Jasa</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:text-blue-700 hover:bg-slate-100 transition-colors">Admin</a>
                    @endif
                </nav>

                <div class="hidden md:flex items-center gap-2">
                    <span class="hidden lg:flex items-center gap-2 text-sm text-slate-500 mr-1">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-xs uppercase">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        <span class="font-medium text-slate-700">{{ Auth::user()->name }}</span>
                    </span>
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-blue-700 px-3 py-2 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                        </svg>
                        Pesanan
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-red-600 px-3 py-2 rounded-lg hover:bg-red-50 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>

                <button type="button" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100" @click="mobileOpen = !mobileOpen" aria-label="Menu">
                    <svg x-show="!mobileOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div x-show="mobileOpen" x-cloak x-transition class="md:hidden border-t border-slate-200 py-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-blue-700 bg-blue-50">Dashboard</a>
                <a href="{{ route('services.index') }}" class="block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-100">Jelajahi Jasa</a>
                <a href="{{ route('services.create') }}" class="block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-100">Ajukan Jasa</a>
                <a href="{{ route('orders.index') }}" class="block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-100">Pesanan Saya</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-100">Dashboard Admin</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-100">Profil</a>
                <div class="pt-2 border-t border-slate-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-3 py-2.5 rounded-lg text-sm text-red-600 hover:bg-red-50">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 text-sm text-green-800 bg-green-50 border border-green-200 px-4 py-3 rounded-xl">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-700">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </span>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 text-sm text-red-800 bg-red-50 border border-red-200 px-4 py-3 rounded-xl">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-700">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </span>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    {{-- FOOTER HITAM PEKAT --}}
    <footer class="bg-slate-950 text-slate-300 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="md:col-span-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                            </svg>
                        </span>
                        <span class="font-heading text-lg font-bold text-white tracking-tight">SkillHub</span>
                    </a>
                    <p class="mt-4 text-sm text-slate-400 leading-relaxed max-w-sm">
                        Marketplace jasa digital untuk lingkungan sekolah. Tawarkan keahlianmu, pesan jasa dari teman, dan bertransaksi aman dengan escrow sekolah.
                    </p>
                    <p class="mt-4 text-xs text-slate-500 font-semibold uppercase tracking-widest">SMK Negeri 8 Semarang</p>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-4">Navigasi</h3>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('dashboard') }}" class="inline-block text-sm text-slate-400 hover:text-white hover:scale-110 transition-all duration-200 origin-left">Dashboard</a></li>
                        <li><a href="{{ route('services.index') }}" class="inline-block text-sm text-slate-400 hover:text-white hover:scale-110 transition-all duration-200 origin-left">Jelajahi Jasa</a></li>
                        <li><a href="{{ route('services.create') }}" class="inline-block text-sm text-slate-400 hover:text-white hover:scale-110 transition-all duration-200 origin-left">Ajukan Jasa</a></li>
                        <li><a href="{{ route('orders.index') }}" class="inline-block text-sm text-slate-400 hover:text-white hover:scale-110 transition-all duration-200 origin-left">Pesanan Saya</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-4">Akun</h3>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('profile.edit') }}" class="inline-block text-sm text-slate-400 hover:text-white hover:scale-110 transition-all duration-200 origin-left">Profil & Pengaturan</a></li>
                        @if(auth()->user()->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}" class="inline-block text-sm text-slate-400 hover:text-white hover:scale-110 transition-all duration-200 origin-left">Dashboard Admin</a></li>
                        @endif
                        <li><a href="{{ route('home') }}" class="inline-block text-sm text-slate-400 hover:text-white hover:scale-110 transition-all duration-200 origin-left">Beranda Publik</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-3">
                <p class="text-sm text-slate-500">&copy; {{ date('Y') }} SkillHub — Proyek UKK PPLG. All rights reserved.</p>
                <p class="text-xs text-slate-600">Escrow &middot; Negosiasi &middot; Review</p>
            </div>
        </div>
    </footer>

</body>
</html>
