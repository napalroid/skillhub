<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SkillHub' }} — Marketplace Jasa Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900 antialiased bg-white" x-data="{ mobileOpen: false }">

    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <x-brand-logo :href="route('home')" />
                <a href="{{ route('home') }}" class="hidden flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                        </svg>
                    </span>
                    <span class="font-heading text-lg font-bold text-slate-900">SkillHub</span>
                </a>

                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('services.index') }}" class="text-sm text-slate-600 hover:text-blue-600 transition-colors">Jelajahi Jasa</a>
                    <a href="#cara-kerja" class="text-sm text-slate-600 hover:text-blue-600 transition-colors">Cara Kerja</a>
                    <a href="#escrow" class="text-sm text-slate-600 hover:text-blue-600 transition-colors">Escrow</a>
                </nav>

                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <x-ui.button variant="ghost" href="{{ route('dashboard') }}">Dashboard</x-ui.button>
                        <x-ui.button href="{{ route('services.create') }}">Ajukan Jasa</x-ui.button>
                    @else
                        <x-ui.button variant="ghost" href="{{ route('login') }}">Masuk</x-ui.button>
                        <x-ui.button href="{{ route('register') }}">Daftar Gratis</x-ui.button>
                    @endauth
                </div>

                <button
                    type="button"
                    class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100"
                    @click="mobileOpen = !mobileOpen"
                    aria-label="Menu"
                >
                    <svg x-show="!mobileOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div x-show="mobileOpen" x-cloak x-transition class="md:hidden border-t border-slate-200 py-4 space-y-1">
                <a href="{{ route('services.index') }}" class="block px-2 py-2 text-sm text-slate-600 hover:text-blue-600">Jelajahi Jasa</a>
                <a href="#cara-kerja" class="block px-2 py-2 text-sm text-slate-600 hover:text-blue-600" @click="mobileOpen = false">Cara Kerja</a>
                <a href="#escrow" class="block px-2 py-2 text-sm text-slate-600 hover:text-blue-600" @click="mobileOpen = false">Escrow</a>
                <div class="pt-3 flex flex-col gap-2">
                    @auth
                        <x-ui.button variant="secondary" href="{{ route('dashboard') }}">Dashboard</x-ui.button>
                        <x-ui.button href="{{ route('services.create') }}">Ajukan Jasa</x-ui.button>
                    @else
                        <x-ui.button variant="secondary" href="{{ route('login') }}">Masuk</x-ui.button>
                        <x-ui.button href="{{ route('register') }}">Daftar Gratis</x-ui.button>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <x-site-footer />

</body>
</html>
