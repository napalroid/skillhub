<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillHub - Marketplace Jasa Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'DM Sans', sans-serif; }
    </style>
    @vite('resources/js/app.js')
</head>

<body id="top" class="overflow-x-hidden bg-white text-[#171717]">
    <div id="skillhub-staggered-menu"
         data-home="#top"
         data-marketplace="#jasa"
         data-how="#how-we-work"
         data-why-us="#keunggulan"
         data-login="{{ route('login') }}"
         data-register="{{ route('register') }}"
         data-get-started="#cara-kerja"
         data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
         data-user-name="{{ auth()->user()?->name ?? '' }}"
         data-profile-url="{{ route('profile.edit') }}"
         data-logout-url="{{ route('logout') }}"
         data-notifications-url="{{ auth()->check() ? route('notifications.index') : '' }}"
         data-notifications-read-all-url="{{ auth()->check() ? route('notifications.read-all') : '' }}"
         data-csrf-token="{{ csrf_token() }}"></div>
    <script id="skillhub-account-notifications-data" type="application/json">@json($accountNotifications ?? collect(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)</script>

    <x-notification-toast />

    <main>
        <section class="relative isolate overflow-hidden px-5 pb-28 pt-36 sm:pb-36 sm:pt-44">
            <div class="absolute inset-0 -z-10">
                <img src="{{ asset('images/skillhub-hero.png') }}"
                     alt="Ilustrasi SkillHub"
                     class="h-full w-full object-cover object-[65%_center]">
                <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/75 via-45% to-white/10"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-white/60 via-transparent to-white/10"></div>
            </div>

            <div class="mx-auto grid max-w-6xl items-center gap-12 lg:grid-cols-2">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-black bg-white/90 px-4 py-2 text-xs font-bold text-black">
                        <span class="h-2 w-2 rounded-full bg-black"></span>
                        Marketplace jasa khusus siswa
                    </span>

                    <h1 class="mt-6 max-w-2xl text-4xl font-bold leading-[.9] tracking-[-.07em] text-black sm:text-5xl lg:text-7xl">
                        Ubah keahlianmu menjadi <span class="text-black/45">peluang.</span>
                    </h1>

                    <p class="mt-6 max-w-xl text-base leading-8 text-black/70 sm:text-lg">
                        SkillHub mempertemukan siswa yang memiliki keahlian dengan teman sekolah yang membutuhkan bantuan. Transaksi aman, komunikasi mudah, dan semua dalam satu platform.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 bg-black px-5 py-3 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-black/75">
                            Jelajahi Jasa <span aria-hidden="true">→</span>
                        </a>
                        @auth
                            <a href="{{ route('services.create') }}" class="inline-flex items-center gap-2 border border-black bg-white px-5 py-3 text-sm font-bold text-black transition hover:-translate-y-0.5 hover:bg-black hover:text-white">
                                Ajukan Jasa
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 border border-black bg-white px-5 py-3 text-sm font-bold text-black transition hover:-translate-y-0.5 hover:bg-black hover:text-white">
                                Mulai Bergabung
                            </a>
                        @endauth
                    </div>

                    <div class="mt-8 flex flex-wrap gap-x-6 gap-y-3 text-sm text-black/65">
                        <span>✓ Transaksi escrow</span>
                        <span>✓ Siswa terverifikasi</span>
                        <span>✓ Review terpercaya</span>
                    </div>
                </div>

                <div class="relative mx-auto w-full max-w-lg">
                    <div class="rounded-none border border-black/15 bg-white p-5 shadow-2xl shadow-black/15">
                        <div class="flex items-center justify-between border-b border-black/10 pb-4">
                            <div>
                                <p class="text-xs font-semibold text-black/45">PESANAN #024</p>
                                <h2 class="mt-1 font-bold text-black">Desain Poster Acara Sekolah</h2>
                            </div>
                            <span class="border border-black px-3 py-1 text-xs font-bold text-black">Dana ditahan</span>
                        </div>
                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="bg-[#f3f3f3] p-4">
                                <p class="text-xs text-black/45">Penyedia jasa</p>
                                <p class="mt-1 font-bold text-black">Nadia A.</p>
                            </div>
                            <div class="bg-black p-4">
                                <p class="text-xs text-white/55">Harga disepakati</p>
                                <p class="mt-1 font-bold text-white">Rp75.000</p>
                            </div>
                        </div>
                        <p class="mt-5 text-xs font-semibold text-black/45">ALUR PESANAN</p>
                        <div class="mt-3 grid grid-cols-4 gap-2">
                            <span class="h-2 bg-black"></span><span class="h-2 bg-black"></span><span class="h-2 bg-black"></span><span class="h-2 bg-black/15"></span>
                        </div>
                        <div class="mt-5 border border-black/10 bg-[#f3f3f3] p-4 text-sm text-black/70">
                            Pembayaran sudah diverifikasi. Penyedia jasa dapat mulai mengerjakan pesanan.
                        </div>
                    </div>
                    <div class="absolute -right-2 -top-5 border border-black/15 bg-white p-3 shadow-lg sm:right-3">
                        <p class="text-[11px] text-black/45">Dana aman escrow</p>
                        <p class="font-bold text-black">Rp75.000</p>
                    </div>
                </div>
            </div>

            {{-- Transisi gelombang menuju section statistik --}}
            <svg class="absolute inset-x-0 bottom-0 z-10 h-16 w-full text-white sm:h-24"
                 viewBox="0 0 1440 120"
                 preserveAspectRatio="none"
                 aria-hidden="true">
                <path fill="currentColor"
                      d="M0,64 C180,116 360,116 540,78 C720,40 840,4 1020,30 C1200,56 1320,104 1440,70 L1440,120 L0,120 Z"></path>
            </svg>
        </section>

        <div id="skillhub-orbit-stats" data-service-count="{{ $featuredServices->count() }}">
            @forelse ($categories as $category)
                <span data-category-name="{{ $category->name }}" data-category-icon="{{ $category->displayIcon() }}" data-category-image="{{ $category->image ? asset('storage/' . $category->image) : '' }}"></span>
            @empty
                <span data-category-name="Skill kreatif" data-category-icon="✦" data-category-image=""></span>
            @endforelse
        </div>

        <div id="skillhub-feature-motion"></div>

        <section id="jasa">
            <div id="skillhub-featured-services" data-marketplace-url="{{ route('services.index') }}"></div>
            <script id="skillhub-featured-services-data" type="application/json">@json($featuredServiceCards, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)</script>
            <div class="hidden">
            <div class="mx-auto max-w-6xl">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-widest text-blue-600">Marketplace</p>
                        <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Jasa unggulan untukmu</h2>
                        <p class="mt-2 text-slate-500">Temukan bantuan dari teman sekolah dengan beragam keahlian.</p>
                    </div>
                    <a href="{{ route('services.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800">Lihat semua jasa →</a>
                </div>

                <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($featuredServices as $service)
                        <a href="{{ route('services.show', $service) }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                            <div class="flex items-start justify-between gap-3">
                                <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700">
                                    {{ $service->subcategory?->name ?? 'Jasa siswa' }}
                                </span>
                                <span class="text-blue-600 transition group-hover:translate-x-1">→</span>
                            </div>
                            <h3 class="mt-5 line-clamp-1 font-bold text-slate-900">{{ $service->title }}</h3>
                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">{{ $service->description }}</p>
                            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                                <span class="text-xs text-slate-500">{{ $service->seller?->name ?? 'Siswa SkillHub' }}</span>
                                <span class="font-bold text-blue-700">Rp{{ number_format($service->price, 0, ',', '.') }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white py-12 text-center">
                            <p class="font-semibold text-slate-700">Belum ada jasa unggulan.</p>
                            <p class="mt-1 text-sm text-slate-500">Jadilah yang pertama menawarkan keahlianmu.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            </div>
        </section>

        {{-- REVIEW PENGGUNA --}}
        <section id="review-static" class="hidden" aria-hidden="true">
            <div class="absolute inset-y-0 left-0 w-2 bg-rose-300"></div>
            <div class="mx-auto grid max-w-6xl gap-10 lg:grid-cols-[220px_1fr] lg:items-center">
                <div class="px-2 sm:px-5">
                    <div class="text-7xl font-serif font-bold leading-none text-slate-300">“</div>
                    <h2 class="mt-3 text-3xl font-bold leading-tight tracking-tight text-slate-900">
                        Apa kata<br>pengguna kami
                    </h2>
                    <div class="mt-8 flex items-center gap-3 text-slate-400">
                        <button type="button" class="text-2xl leading-none transition hover:text-slate-900" aria-label="Review sebelumnya">←</button>
                        <span class="h-0.5 w-7 bg-slate-900"></span>
                        <span class="h-0.5 w-24 bg-slate-300"></span>
                        <button type="button" class="text-2xl leading-none text-slate-900" aria-label="Review berikutnya">→</button>
                    </div>
                </div>

                <div class="grid gap-7 md:grid-cols-2">
                    @foreach ([
                        ['name' => 'Aulia Rahma', 'initial' => 'AR', 'time' => '1 hari lalu', 'color' => 'bg-blue-600', 'text' => 'SkillHub sangat membantu saya menemukan jasa desain untuk kegiatan sekolah. Harganya jelas, prosesnya aman, dan hasil pekerjaannya memuaskan.'],
                        ['name' => 'Rizky Pratama', 'initial' => 'RP', 'time' => '3 hari lalu', 'color' => 'bg-emerald-600', 'text' => 'Sebagai penyedia jasa, saya lebih mudah menawarkan keahlian kepada teman sekolah. Komunikasi dengan pemesan juga jadi lebih rapi dan nyaman.'],
                    ] as $review)
                        <article>
                            <div class="relative rounded-2xl bg-white px-8 py-7 shadow-[0_12px_30px_rgba(15,23,42,0.08)]">
                                <div class="absolute -bottom-4 left-7 h-0 w-0 border-r-[22px] border-t-[16px] border-r-transparent border-t-white"></div>
                                <p class="min-h-28 text-sm leading-7 text-slate-700">“{{ $review['text'] }}”</p>
                                <div class="mt-6 flex gap-0.5 text-lg tracking-tight text-emerald-500" aria-label="Rating lima dari lima">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center gap-3 pl-7">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full {{ $review['color'] }} text-xs font-bold text-white shadow-sm">
                                    {{ $review['initial'] }}
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $review['name'] }}</p>
                                    <p class="text-xs text-slate-400">{{ $review['time'] }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <div id="skillhub-review-motion"></div>

        <div id="skillhub-how-we-work"></div>

        <div id="skillhub-cta-motion" data-action-url="{{ auth()->check() ? route('services.create') : route('register') }}" data-action-label="{{ auth()->check() ? 'Ajukan jasa' : 'Buat akun gratis' }}"></div>
    </main>

    <footer class="relative overflow-hidden bg-black px-5 pb-7 pt-14 text-white">
        <div class="absolute -right-24 -top-24 h-64 w-64 rounded-full bg-blue-600/15 blur-3xl"></div>
        <div class="relative mx-auto max-w-6xl">
            <div class="grid gap-10 border-b border-white/15 pb-11 md:grid-cols-[1.5fr_1fr_1fr_1fr]">
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 text-xl font-extrabold tracking-tight transition duration-200 hover:scale-105 hover:text-blue-300">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-base">✦</span>
                        SkillHub
                    </a>
                    <p class="mt-4 max-w-xs text-sm leading-6 text-slate-400">Marketplace jasa antarsiswa untuk berkarya, berkolaborasi, dan berkembang bersama.</p>
                </div>

                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">Jelajahi</h3>
                    <div class="mt-4 flex flex-col items-start gap-3 text-sm text-slate-400">
                        <a href="#jasa" class="origin-left transition duration-200 hover:scale-110 hover:text-white">Marketplace Jasa</a>
                        <a href="#keunggulan" class="origin-left transition duration-200 hover:scale-110 hover:text-white">Keunggulan</a>
                        <a href="#review" class="origin-left transition duration-200 hover:scale-110 hover:text-white">Ulasan Pengguna</a>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">Akun</h3>
                    <div class="mt-4 flex flex-col items-start gap-3 text-sm text-slate-400">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="origin-left transition duration-200 hover:scale-110 hover:text-white">Profil {{ auth()->user()->name }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="origin-left transition duration-200 hover:scale-110 hover:text-white">Keluar</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="origin-left transition duration-200 hover:scale-110 hover:text-white">Masuk</a>
                            <a href="{{ route('register') }}" class="origin-left transition duration-200 hover:scale-110 hover:text-white">Daftar</a>
                        @endauth
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">SkillHub</h3>
                    <p class="mt-4 text-sm leading-6 text-slate-400">Tempat yang aman untuk menjual keahlian dan mencari bantuan dari sesama siswa.</p>
                    <a href="#cara-kerja" class="mt-4 inline-block origin-left text-sm font-semibold text-blue-300 transition duration-200 hover:scale-110 hover:text-white">Cara kerja kami →</a>
                </div>
            </div>

            <div class="flex flex-col gap-3 pt-7 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <p>© {{ date('Y') }} SkillHub. Dibuat untuk siswa berkarya.</p>
                <p class="transition duration-200 hover:scale-105 hover:text-slate-300">Aman • Terpercaya • Untuk siswa</p>
            </div>
        </div>
    </footer>
</body>
</html>
