@php
    $images = [
        'login' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1400&q=80',
        'register' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1400&q=80',
        'forgot' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1400&q=80',
    ];

    $panelTitles = [
        'login' => 'Transaksi aman dengan escrow sekolah',
        'register' => 'Bergabung dengan komunitas siswa',
        'forgot' => 'Pulihkan akses akunmu dengan aman',
    ];

    $panelDescriptions = [
        'login' => 'Marketplace jasa untuk siswa, jual keahlianmu atau cari bantuan dari teman sekolah.',
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
<body class="font-sans text-slate-900 antialiased min-h-screen bg-white pt-16">
    <div id="skillhub-staggered-menu"
         data-home="{{ route('home') }}"
         data-marketplace="{{ route('services.index') }}"
         data-chat="{{ route('conversations.seller-index') }}"
         data-login="{{ route('login') }}"
         data-register="{{ route('register') }}"
         data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
         data-user-name="{{ auth()->user()?->name ?? '' }}"
         data-profile-url="{{ route('profile.edit') }}"
         data-logout-url="{{ route('logout') }}"
         data-notifications-url="{{ auth()->check() ? route('notifications.index') : '' }}"
         data-notifications-read-all-url="{{ auth()->check() ? route('notifications.read-all') : '' }}"
         data-dompet="{{ route('wallet.index') }}"
         data-pesanan="{{ route('orders.index') }}"
         data-csrf-token="{{ csrf_token() }}"></div>
    <script id="skillhub-account-notifications-data" type="application/json">@json($accountNotifications ?? collect(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT)</script>

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

            <div class="relative z-10 flex flex-col justify-between p-10 xl:p-14 w-full">
                <div>
                    <x-brand-logo :href="route('home')" surface="dark" class="relative z-10" />
                    <a href="{{ route('home') }}" class="hidden inline-flex items-center gap-2.5 group">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/15 border border-white/20 text-white backdrop-blur-sm">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" />
                            </svg>
                        </span>
                        <span class="font-heading text-xl font-bold text-white group-hover:text-blue-100 transition-colors">SkillHub</span>
                    </a>
                </div>

                <div class="max-w-md">
                    <h2 class="font-heading text-3xl xl:text-4xl font-bold text-white leading-tight">{{ $panelTitle }}</h2>
                    <p class="mt-4 text-blue-100 leading-relaxed">{{ $panelDescription }}</p>

                    @if ($variant === 'login')
                        <div class="mt-8 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-5">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-[11px] text-blue-200 uppercase tracking-wide font-medium">Pesanan #042</p>
                                    <p class="text-white font-semibold text-sm mt-0.5">Desain Poster UKK</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-400/20 text-amber-200 border border-amber-300/30">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                    Dana ditahan
                                </span>
                            </div>
                            <div class="flex justify-between text-sm mb-3">
                                <span class="text-blue-200">Escrow</span>
                                <span class="text-white font-medium">Rp 75.000</span>
                            </div>
                            <div class="flex gap-1">
                                @foreach (['Bayar', 'Escrow', 'Kerja', 'Selesai'] as $i => $step)
                                    <div class="flex-1">
                                        <div @class(['h-1.5 rounded-full', 'bg-white' => $i <= 1, 'bg-white/25' => $i > 1])></div>
                                        <p @class(['text-[10px] mt-1', 'text-white' => $i <= 1, 'text-blue-300/60' => $i > 1])>{{ $step }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif ($variant === 'register')
                        <ul class="mt-8 space-y-3">
                            @foreach (['Ajukan jasa ke marketplace sekolah', 'Negosiasi harga sebelum transaksi', 'Pembayaran escrow via admin', 'Review setelah pekerjaan selesai'] as $feature)
                                <li class="flex items-center gap-3 text-sm text-blue-100">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-white/15 border border-white/20">
                                        <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                    </span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="mt-8 flex items-start gap-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-5">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/15 border border-white/20">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-white font-medium text-sm">Keamanan akun terjaga</p>
                                <p class="text-blue-200 text-sm mt-1 leading-relaxed">Link reset hanya dikirim ke email yang terdaftar di database SkillHub.</p>
                            </div>
                        </div>
                    @endif
                </div>

                    <p class="text-xs text-blue-200/70">&copy; {{ date('Y') }} Proyek UKK PPLG</p>
            </div>
        </div>

        {{-- Panel form kanan --}}
        <div class="flex-1 flex flex-col min-h-screen">
            {{-- Header mobile + banner gambar --}}
            <div class="lg:hidden relative h-44 sm:h-52 overflow-hidden shrink-0">
                <img src="{{ $bgImage }}" alt="" class="absolute inset-0 h-full w-full object-cover">
                <div class="absolute inset-0 bg-blue-900/70"></div>
                <div class="relative z-10 h-full flex flex-col justify-between p-6">
                    <x-brand-logo :href="route('home')" surface="dark" class="relative z-10" />
                    <a href="{{ route('home') }}" class="hidden inline-flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/15 border border-white/20 text-white">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" />
                            </svg>
                        </span>
                        <span class="font-heading text-lg font-bold text-white">SkillHub</span>
                    </a>
                    <p class="font-heading text-lg font-bold text-white leading-snug max-w-xs">{{ $panelTitle }}</p>
                </div>
            </div>

            <div class="flex-1 flex items-center justify-center px-4 sm:px-8 py-8 lg:py-12 bg-slate-50 lg:bg-white">
                <div class="w-full max-w-md">
                    {{-- Navigasi atas form dipindahkan ke hamburger menu global --}}

                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 sm:p-8">
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
