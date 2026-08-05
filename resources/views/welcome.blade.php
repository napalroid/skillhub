<x-layouts.landing>

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-slate-50 border-b border-slate-200">
        {{-- Dekorasi ringan --}}
        <div class="pointer-events-none absolute -top-32 -right-24 h-96 w-96 rounded-full bg-blue-100/50 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-40 -left-24 h-96 w-96 rounded-full bg-blue-50/60 blur-3xl"></div>
        <div class="pointer-events-none absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"56\" height=\"56\" viewBox=\"0 0 56 56\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%232563eb\" fill-opacity=\"0.045\"%3E%3Cpath d=\"M28 24v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-28V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 24v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                {{-- Kiri: copy --}}
                <div>
                    <x-ui.badge variant="info" class="mb-6">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                        Pembayaran escrow untuk transaksi aman
                    </x-ui.badge>

                    <h1 class="font-heading text-4xl sm:text-5xl font-bold text-slate-900 leading-tight tracking-tight">
                        Marketplace jasa
                        <span class="relative inline-block text-blue-600">
                            antar siswa sekolah
                            <svg class="absolute -bottom-2 left-0 w-full text-blue-200" viewBox="0 0 300 12" fill="none" preserveAspectRatio="none" aria-hidden="true">
                                <path d="M2 9C60 3 180 2 298 7" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </h1>

                    <p class="mt-7 text-lg text-slate-600 leading-relaxed max-w-lg">
                        Tawarkan keahlianmu atau cari bantuan dari teman sekolah. SkillHub menghubungkan seller dan buyer dalam satu platform — dengan admin sekolah sebagai penjaga dana escrow.
                    </p>

                    <div class="mt-9 flex flex-col sm:flex-row gap-3">
                        <x-ui.button href="{{ route('services.index') }}">
                            Jelajahi Jasa
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </x-ui.button>
                        @guest
                            <x-ui.button variant="secondary" href="{{ route('register') }}">Daftar & Mulai</x-ui.button>
                        @else
                            <x-ui.button variant="secondary" href="{{ route('services.create') }}">Ajukan Jasa Baru</x-ui.button>
                        @endguest
                    </div>

                    <div class="mt-10 flex flex-wrap gap-x-6 gap-y-3">
                        @foreach ([
                            ['icon' => 'M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5', 'label' => 'Negosiasi harga'],
                            ['icon' => 'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z', 'label' => 'Dana ditahan escrow'],
                            ['icon' => 'M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z', 'label' => 'Review setelah selesai'],
                        ] as $trust)
                            <span class="inline-flex items-center gap-2 text-sm text-slate-500">
                                <svg class="h-4 w-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $trust['icon'] }}" />
                                </svg>
                                {{ $trust['label'] }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Kanan: mockup status escrow --}}
                <div class="relative">
                    <div class="relative bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-md mx-auto lg:ml-auto">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Pesanan #042</p>
                                <h3 class="font-heading text-base font-bold text-slate-900 mt-1">Desain Poster UKK</h3>
                            </div>
                            <x-ui.badge variant="warning">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                Dana ditahan
                            </x-ui.badge>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <div class="rounded-lg border border-slate-100 bg-slate-50 px-3.5 py-3">
                                <p class="text-[11px] text-slate-400 font-medium">Seller</p>
                                <p class="text-sm font-semibold text-slate-900 mt-0.5">Ahmad R.</p>
                            </div>
                            <div class="rounded-lg border border-slate-100 bg-slate-50 px-3.5 py-3">
                                <p class="text-[11px] text-slate-400 font-medium">Harga disepakati</p>
                                <p class="text-sm font-semibold text-blue-700 mt-0.5">Rp 75.000</p>
                            </div>
                        </div>

                        <div class="border-t border-slate-200 pt-5">
                            <p class="text-xs text-slate-400 mb-3 font-medium">Alur pesanan</p>
                            <div class="flex items-center gap-1">
                                @foreach (['Bayar', 'Escrow', 'Kerja', 'Setujui', 'Selesai'] as $i => $step)
                                    <div class="flex-1 flex flex-col items-center gap-1.5">
                                        <div @class([
                                            'h-2 w-full rounded-full',
                                            'bg-blue-600' => $i <= 2,
                                            'bg-slate-200' => $i > 2,
                                        ])></div>
                                        <span @class([
                                            'text-[10px] leading-none text-center',
                                            'text-blue-600 font-medium' => $i <= 2,
                                            'text-slate-400' => $i > 2,
                                        ])>{{ $step }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-5 flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-lg p-3">
                            <svg class="h-4 w-4 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                Dana ditahan admin sekolah. Seller mulai mengerjakan setelah pembayaran terverifikasi.
                            </p>
                        </div>
                    </div>

                    {{-- Floating card: pembayaran verified --}}
                    <div class="absolute -bottom-4 -left-4 hidden lg:block bg-white rounded-xl border border-slate-200 shadow-sm p-4 w-44">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="h-7 w-7 rounded-lg bg-green-50 border border-green-200 flex items-center justify-center">
                                <svg class="h-3.5 w-3.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-slate-900">Pembayaran verified</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Admin sekolah konfirmasi bukti transfer</p>
                    </div>

                    {{-- Floating chip: saldo escrow --}}
                    <div class="absolute -top-4 -right-2 hidden lg:flex items-center gap-2 bg-white rounded-xl border border-slate-200 shadow-sm px-3.5 py-2.5">
                        <div class="h-7 w-7 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center">
                            <svg class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 leading-none">Dana escrow</p>
                            <p class="text-xs font-bold text-slate-900 mt-0.5">Rp 75.000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CARA KERJA --}}
    <section id="cara-kerja" class="scroll-mt-20 py-16 lg:py-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16">
                <span class="inline-flex items-center rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Cara Kerja</span>
                <h2 class="font-heading text-3xl font-bold text-slate-900 mt-4">Dari menemukan jasa hingga transaksi selesai</h2>
                <p class="mt-4 text-slate-600">Empat langkah sederhana — semua dalam satu platform sekolah.</p>
            </div>

            <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Garis penghubung antar langkah (desktop) --}}
                <div class="absolute top-8 left-10 right-10 hidden lg:block h-px bg-slate-200"></div>

                @php
                    $steps = [
                        [
                            'num' => '01',
                            'title' => 'Temukan Jasa',
                            'desc' => 'Jelajahi katalog jasa dari teman sekolah berdasarkan kategori dan kebutuhanmu.',
                            'icon' => 'm21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z',
                        ],
                        [
                            'num' => '02',
                            'title' => 'Negosiasi Harga',
                            'desc' => 'Sepakati harga final dengan seller sebelum transaksi dimulai.',
                            'icon' => 'M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5',
                        ],
                        [
                            'num' => '03',
                            'title' => 'Bayar via Escrow',
                            'desc' => 'Transfer manual/QRIS, admin verifikasi, dan dana ditahan aman hingga pekerjaan selesai.',
                            'icon' => 'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z',
                        ],
                        [
                            'num' => '04',
                            'title' => 'Setujui & Review',
                            'desc' => 'Buyer setujui hasil kerja, dana dicairkan ke seller, lalu beri rating.',
                            'icon' => 'M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z',
                        ],
                    ];
                @endphp

                @foreach ($steps as $step)
                    <div class="group relative bg-white rounded-xl border border-slate-200 p-6 transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                        <div class="relative z-10 flex items-center justify-between mb-5">
                            <span class="relative z-10 flex h-16 w-16 items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-200">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}" />
                                </svg>
                            </span>
                            <span class="font-heading text-4xl font-extrabold text-slate-100 group-hover:text-blue-100 transition-colors duration-200">{{ $step['num'] }}</span>
                        </div>
                        <h3 class="font-heading text-base font-bold text-slate-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ESCROW --}}
    <section id="escrow" class="scroll-mt-20 bg-slate-50 border-y border-slate-200 py-16 lg:py-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div>
                    <x-ui.badge variant="info" class="mb-4">Sistem Escrow</x-ui.badge>
                    <h2 class="font-heading text-3xl font-bold text-slate-900">Transaksi aman dengan penjaga dana sekolah</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">
                        SkillHub menggunakan escrow sederhana: buyer transfer dana ke rekening sekolah, admin verifikasi bukti pembayaran, dan dana ditahan sampai buyer menyetujui hasil kerja seller. Baru setelah itu dana dicairkan.
                    </p>

                    <div class="mt-8 flex items-center gap-3">
                        @foreach ([
                            ['label' => 'Buyer bayar', 'color' => 'text-blue-700 bg-blue-50 border-blue-100'],
                            ['label' => 'Admin tahan', 'color' => 'text-amber-700 bg-amber-50 border-amber-100'],
                            ['label' => 'Seller cairkan', 'color' => 'text-green-700 bg-green-50 border-green-100'],
                        ] as $i => $party)
                            @if ($i > 0)
                                <svg class="h-4 w-4 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            @endif
                            <span class="rounded-lg border px-3 py-1.5 text-xs font-semibold {{ $party['color'] }}">{{ $party['label'] }}</span>
                        @endforeach
                    </div>

                    <ul class="mt-8 space-y-4">
                        @foreach ([
                            'Buyer dan seller bisa jadi satu akun yang sama',
                            'Negosiasi harga sebelum pesanan dibuat',
                            'Upload kebutuhan, hasil kerja, dan bukti pembayaran per pesanan',
                            'Diskusi per pesanan tanpa chat real-time',
                            'Laporan penyalahgunaan jika ada masalah',
                        ] as $item)
                            <li class="flex items-start gap-3">
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-green-200 bg-green-50 mt-0.5">
                                    <svg class="h-3 w-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </span>
                                <span class="text-sm text-slate-600">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Alur status escrow --}}
                <div class="bg-white rounded-xl border border-slate-200 p-6 lg:p-8">
                    <h3 class="font-heading text-sm font-bold text-slate-900 mb-6">Alur status pesanan</h3>
                    <ol class="relative space-y-5">
                        <div class="absolute left-5 top-5 bottom-5 w-px bg-slate-100" aria-hidden="true"></div>

                        @foreach ([
                            ['status' => 'menunggu_pembayaran', 'label' => 'Menunggu Pembayaran', 'desc' => 'Buyer transfer ke rekening/QRIS sekolah', 'variant' => 'default', 'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                            ['status' => 'menunggu_verifikasi', 'label' => 'Menunggu Verifikasi', 'desc' => 'Admin memeriksa bukti pembayaran', 'variant' => 'warning', 'icon' => 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z'],
                            ['status' => 'dibayar', 'label' => 'Dibayar (Escrow)', 'desc' => 'Dana ditahan aman oleh admin sekolah', 'variant' => 'info', 'icon' => 'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'],
                            ['status' => 'dikerjakan', 'label' => 'Sedang Dikerjakan', 'desc' => 'Seller mengerjakan pesanan sesuai kebutuhan', 'variant' => 'info', 'icon' => 'M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10'],
                            ['status' => 'menunggu_persetujuan', 'label' => 'Menunggu Persetujuan', 'desc' => 'Buyer memeriksa dan menyetujui hasil kerja', 'variant' => 'warning', 'icon' => 'M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z'],
                            ['status' => 'selesai', 'label' => 'Selesai — Dana Dicairkan', 'desc' => 'Dana escrow masuk ke rekening seller', 'variant' => 'success', 'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                        ] as $flow)
                            <li class="relative flex items-start gap-4">
                                <span class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border bg-white">
                                    <x-ui.badge :variant="$flow['variant']" class="!rounded-full !p-0 !h-10 !w-10 !justify-center !border-0 !bg-transparent">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $flow['icon'] }}" />
                                        </svg>
                                    </x-ui.badge>
                                </span>
                                <div class="pt-0.5">
                                    <p class="text-sm font-semibold text-slate-900">{{ $flow['label'] }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $flow['desc'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- KATEGORI --}}
    @if ($categories->isNotEmpty())
        <section class="py-16 lg:py-24">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
                    <div>
                        <span class="inline-flex items-center rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Kategori</span>
                        <h2 class="font-heading text-3xl font-bold text-slate-900 mt-4">Temukan jasa sesuai kebutuhanmu</h2>
                        <p class="mt-2 text-slate-600">Dari desain, tulis-menulis, sampai bantuan tugas sekolah.</p>
                    </div>
                    <x-ui.button variant="outline" href="{{ route('services.index') }}">
                        Lihat semua jasa
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </x-ui.button>
                </div>

                @php
                    $categoryIcons = [
                        'M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z',
                        'M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10',
                        'M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z',
                        'm15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z',
                        'M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5',
                        'M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5',
                        'M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46',
                        'M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085',
                    ];
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($categories as $category)
                        <a href="{{ route('services.index') }}"
                           class="group bg-white rounded-xl border border-slate-200 p-5 hover:border-blue-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                            <div class="flex items-start justify-between mb-3">
                                <div class="h-10 w-10 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center group-hover:bg-blue-600 group-hover:border-blue-600 transition-colors duration-200">
                                    <svg class="h-5 w-5 text-blue-600 group-hover:text-white transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $categoryIcons[$loop->index % count($categoryIcons)] }}" />
                                    </svg>
                                </div>
                                <svg class="h-4 w-4 text-slate-200 group-hover:text-blue-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-900 group-hover:text-blue-700 transition-colors">{{ $category->name }}</h3>
                            @if ($category->subcategories->isNotEmpty())
                                <p class="text-xs text-slate-400 mt-1">{{ $category->subcategories->count() }} subkategori</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- JASA TERBARU --}}
    <section class="bg-slate-50 border-t border-slate-200 py-16 lg:py-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
                <div>
                    <span class="inline-flex items-center rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Terbaru</span>
                    <h2 class="font-heading text-3xl font-bold text-slate-900 mt-4">Jasa yang siap dipesan</h2>
                    <p class="mt-2 text-slate-600">Sudah disetujui admin dan langsung bisa dinegosiasikan.</p>
                </div>
                <x-ui.button variant="outline" href="{{ route('services.index') }}">
                    Lihat semua
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </x-ui.button>
            </div>

            @if ($featuredServices->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($featuredServices as $service)
                        <article class="group bg-white rounded-xl border border-slate-200 p-5 hover:border-blue-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col">
                            <div class="flex items-center justify-between mb-4">
                                @if ($service->subcategory)
                                    <x-ui.badge variant="info">{{ $service->subcategory->name }}</x-ui.badge>
                                @else
                                    <span></span>
                                @endif
                                <div class="flex items-center gap-1.5">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                                        </svg>
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $service->seller->name }}</span>
                                </div>
                            </div>

                            <h3 class="font-heading text-base font-bold text-slate-900 line-clamp-1">{{ $service->title }}</h3>
                            <p class="text-sm text-slate-500 mt-2 line-clamp-2 flex-1">{{ $service->description }}</p>

                            <div class="flex items-center justify-between mt-5 pt-4 border-t border-slate-100">
                                <span class="text-blue-700 font-semibold text-sm">Rp{{ number_format($service->price, 0, ',', '.') }}</span>
                                <x-ui.button variant="ghost" href="{{ route('services.show', $service) }}" class="!px-3 !py-1.5 group-hover:!text-blue-700">
                                    Detail
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </x-ui.button>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
                    <div class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5a1.125 1.125 0 0 0-1.125-1.125H3.375a1.125 1.125 0 0 0-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                    </div>
                    <h3 class="font-heading text-base font-bold text-slate-900">Belum ada jasa tersedia</h3>
                    <p class="text-sm text-slate-500 mt-2 max-w-sm mx-auto">Jadilah yang pertama mendaftarkan jasamu di SkillHub.</p>
                    @auth
                        <x-ui.button href="{{ route('services.create') }}" class="mt-6">Ajukan Jasa Pertama</x-ui.button>
                    @else
                        <x-ui.button href="{{ route('register') }}" class="mt-6">Daftar & Ajukan Jasa</x-ui.button>
                    @endauth
                </div>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 lg:py-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-800 via-indigo-950 to-slate-950 p-8 lg:p-14 text-center">
                {{-- Dekorasi: glow ungu + aksen kuning --}}
                <div class="pointer-events-none absolute -top-24 -right-20 h-72 w-72 rounded-full bg-purple-500/30 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-32 -left-16 h-80 w-80 rounded-full bg-fuchsia-800/40 blur-3xl"></div>
                <div class="pointer-events-none absolute top-8 right-12 text-amber-300/70">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456Z" />
                    </svg>
                </div>
                <div class="pointer-events-none absolute bottom-10 left-12 text-amber-300/50">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456Z" />
                    </svg>
                </div>
                <div class="pointer-events-none absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"56\" height=\"56\" viewBox=\"0 0 56 56\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M28 24v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-28V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 24v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>

                <div class="relative z-10">
                    <div class="mx-auto h-1 w-16 rounded-full bg-gradient-to-r from-amber-300 to-yellow-400"></div>
                    <h2 class="font-heading text-2xl sm:text-3xl font-bold text-white mt-6">Siap mulai di SkillHub?</h2>
                    <p class="mt-4 text-purple-100 max-w-lg mx-auto">
                        Daftar dengan akun sekolah, tawarkan keahlianmu, atau pesan jasa dari teman — semua dengan perlindungan escrow.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                        @guest
                            <x-ui.button href="{{ route('register') }}" class="!bg-white !text-purple-700 hover:!bg-purple-50">
                                Buat Akun Gratis
                            </x-ui.button>
                            <x-ui.button variant="outline" href="{{ route('services.index') }}" class="!border-amber-300/40 !text-white hover:!bg-white/10">
                                Jelajahi Jasa Dulu
                            </x-ui.button>
                        @else
                            <x-ui.button href="{{ route('services.create') }}" class="!bg-white !text-purple-700 hover:!bg-purple-50">
                                Ajukan Jasa Baru
                            </x-ui.button>
                            <x-ui.button variant="outline" href="{{ route('services.index') }}" class="!border-amber-300/40 !text-white hover:!bg-white/10">
                                Jelajahi Jasa
                            </x-ui.button>
                        @endguest
                    </div>
                    <p class="mt-6 text-xs text-purple-200/70">
                        Gratis untuk siswa &middot; Tanpa kartu kredit &middot; Dana aman di escrow
                    </p>
                </div>
            </div>
        </div>
    </section>

</x-layouts.landing>
