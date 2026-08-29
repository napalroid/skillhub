@php
    $images = [
        'login' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1400&q=80',
        'register' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1400&q=80',
        'forgot' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1400&q=80',
    ];

    $panelTitles = [
        'login' => 'Transaksi aman dengan escrow sekolah',
        'register' => 'Bergabung dengan komunitas',
        'forgot' => 'Pulihkan akses akunmu dengan aman',
    ];

    $panelDescriptions = [
        'login' => 'Marketplace jasa untuk siswa — jual keahlianmu atau cari bantuan dari teman sekolah.',
        'register' => 'Satu akun untuk jadi seller dan buyer. Ajukan jasa, pesan jasa, dan kelola transaksi di satu tempat.',
        'forgot' => 'Kami akan kirim link reset password ke email terdaftar. Link berlaku terbatas untuk keamanan akun.',
    ];

    $bgImage = $images[$variant] ?? $images['login'];
    $panelTitle = $panelTitles[$variant] ?? $panelTitles['login'];
    $panelDescription = $panelDescriptions[$variant] ?? $panelDescriptions['login'];
    $pageTitle = $title ?? match ($variant) {
        'register' => 'Daftar',
        'forgot' => 'Lupa Password',
        default => 'Masuk',
    };
    @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} — SkillHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900 antialiased min-h-screen bg-white">

    <div class="min-h-screen flex flex-col lg:flex-row">

        {{-- Panel visual kiri (desktop) --}}
        <div class="hidden lg:flex lg:w-[48%] xl:w-[52%] relative overflow-hidden">
            <img
                src="{{ $bgImage }}"
                alt=""
                class="absolute inset-0 h-full w-full object-cover"
                loading="eager"
            >
            <div class="absolute inset-0 bg-blue-900/75"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.04\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-60"></div>

            <div class="relative z-10 flex flex-col justify-center p-10 xl:p-14 w-full h-full">
                <div>
                    <h2 class="font-heading text-3xl xl:text-4xl font-bold text-white leading-tight">{{ $panelTitle }}</h2>
                    <p class="mt-4 text-blue-100 leading-relaxed max-w-md">{{ $panelDescription }}</p>
                </div>
            </div>
        </div>

        {{-- Panel form kanan --}}
        <div class="flex-1 flex flex-col min-h-screen">
            {{-- Header mobile + banner gambar --}}
            <div class="lg:hidden relative h-44 sm:h-52 overflow-hidden shrink-0">
                <img src="{{ $bgImage }}" alt="" class="absolute inset-0 h-full w-full object-cover">
                <div class="absolute inset-0 bg-blue-900/70"></div>
                <div class="relative z-10 h-full flex flex-col justify-center p-6">
                    <h2 class="font-heading text-2xl font-bold text-white leading-snug">{{ $panelTitle }}</h2>
                    <p class="mt-2 text-sm text-blue-100 max-w-xs">{{ $panelDescription }}</p>
                </div>
            </div>

            <div class="flex-1 flex items-center justify-center px-4 sm:px-6 py-6 lg:py-8 bg-slate-50 lg:bg-white">
                <div class="w-full max-w-md">
                    {{-- Navigasi atas form --}}
                    <div class="flex items-center justify-between mb-6">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-blue-600 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                            Kembali ke Beranda
                        </a>
                        <div class="flex items-center gap-2 ml-auto">
                            @if ($variant !== 'login')
                                <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-blue-600 transition-colors">Masuk</a>
                            @endif
                            @if ($variant !== 'register')
                                <a href="{{ route('register') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">Daftar</a>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 sm:p-6">
                        @if ($title)
                            <h1 class="font-heading text-2xl font-bold text-slate-900">{{ $title }}</h1>
                        @endif
                        @if ($subtitle)
                            <p class="text-sm text-slate-500 mt-1.5 mb-6">{{ $subtitle }}</p>
                        @else
                            <div class="mb-6"></div>
                        @endif

                        {{ $slot }}
                    </div>

                    <p class="text-center text-xs text-slate-400 mt-6">
                        Dengan masuk atau mendaftar, kamu setuju menggunakan layanan ini untuk lingkungan sekolah.
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
