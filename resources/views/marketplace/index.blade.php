@php
    $marketplaceCategories = collect([
        [
            'id' => 0,
            'name' => 'Semua Jasa',
            'url' => route('services.index'),
            'icon' => '✦',
            'iconImage' => null,
            'active' => ! request('category'),
        ],
    ])->merge($categories->map(function ($category) {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'url' => route('services.index', ['category' => $category->id]),
            'icon' => $category->icon ?: $category->displayIcon(),
            'iconImage' => $category->iconIsFile() ? asset('storage/' . $category->icon) : ($category->image ? asset('storage/' . $category->image) : null),
            'active' => (string) request('category') === (string) $category->id,
        ];
    })->values());

    $marketplaceMenuItems = [
        ['label' => 'Home', 'href' => route('home')],
        ['label' => 'Marketplace', 'href' => route('services.index')],
        ['label' => 'Chat', 'href' => route('conversations.seller-index')],
        ['label' => 'About Us', 'href' => route('home') . '#keunggulan'],
    ];

    $hasActiveFilters = request()->hasAny(['category', 'subcategory', 'search', 'sort']);

    $heroSlides = [
        [
            'title' => 'Wujudkan ide besar dengan talenta terbaik.',
            'subtitle' => 'Temukan ribuan karya dan layanan digital berkualitas langsung dari siswa berprestasi.',
            'image' => $heroImage,
            'tag' => 'Eksplorasi Jasa',
        ],
        [
            'title' => 'Jasa kreatif, koding & desain untuk kebutuhanmu.',
            'subtitle' => 'Dari pembuatan website responsif, desain grafis modern, hingga video editing profesional.',
            'image' => asset('images/skillhub-hero.png'),
            'tag' => 'Talenta Digital',
        ],
        [
            'title' => 'Solusi proyek & kebutuhan sekolah cepat dan terpercaya.',
            'subtitle' => 'Portofolio teruji dengan komunikasi langsung bersama kreator siswa yang siap membantu.',
            'image' => asset('images/skillhub-hero.jpg'),
            'tag' => 'Karya Nyata',
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eksplorasi Jasa Siswa | SkillHub</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite('resources/js/app.js')
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #ffffff;
            color: #111111;
            -webkit-font-smoothing: antialiased;
        }

        /* Snug Adidas-Inspired Product Card */
        .adidas-service-card {
            background-color: #ffffff;
            border: 1px solid transparent;
            border-radius: 0px;
            padding: 0.375rem;
            transition: border-color 0.18s cubic-bezier(0.16, 1, 0.3, 1),
                        box-shadow 0.18s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .adidas-service-card:hover {
            border-color: #000000;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            z-index: 10;
        }

        /* Category Item Snug Pill */
        .category-filter-item {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.25rem 1.125rem 0.25rem 0.25rem;
            border-radius: 9999px;
            background-color: #ffffff;
            border: 1px solid transparent;
            text-decoration: none;
            transition: border-color 0.18s ease, transform 0.18s ease;
            user-select: none;
            flex-shrink: 0;
        }

        .category-filter-item:hover {
            border-color: #000000;
            transform: translateY(-1px);
        }

        .category-filter-item.is-active {
            border-color: #000000;
            background-color: #f8fafc;
        }

        .category-icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 9999px;
            background-color: #f1f5f9;
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
        }

        .category-icon-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Custom Pagination Buttons */
        .pagination-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0 0.75rem;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            color: #0f172a;
            font-size: 0.875rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.18s ease;
        }

        .pagination-link:hover {
            border-color: #000000;
            background-color: #f8fafc;
            color: #000000;
        }

        .pagination-link.is-active {
            border-color: #000000;
            background-color: #000000;
            color: #ffffff;
        }

        .pagination-ellipsis {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            color: #94a3b8;
            font-size: 0.875rem;
            font-weight: 700;
            user-select: none;
        }
    </style>
</head>
<body class="min-h-screen bg-white text-[#111111] antialiased">
    
    {{-- Header Navigation Mount --}}
    <div id="skillhub-staggered-menu"
         data-home="{{ route('home') }}"
         data-marketplace="{{ route('services.index') }}"
         data-how="{{ route('home') }}#how-we-work"
         data-why-us="{{ route('home') }}#keunggulan"
         data-login="{{ route('login') }}"
         data-register="{{ route('register') }}"
         data-get-started="{{ route('home') }}#cara-kerja"
         data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
         data-user-name="{{ auth()->user()?->name ?? '' }}"
         data-profile-url="{{ route('profile.edit') }}"
         data-logout-url="{{ route('logout') }}"
         data-notifications-url="{{ auth()->check() ? route('notifications.index') : '' }}"
         data-notifications-read-all-url="{{ auth()->check() ? route('notifications.read-all') : '' }}"
         data-dompet="{{ route('wallet.index') }}"
         data-pesanan="{{ route('orders.index') }}"
         data-csrf-token="{{ csrf_token() }}"></div>
    <script id="skillhub-account-notifications-data" type="application/json">@json($accountNotifications ?? collect(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)</script>
    <script id="skillhub-staggered-menu-items-data" type="application/json">@json($marketplaceMenuItems, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)</script>

    <main x-data="carouselState()">
        
        {{-- SECTION 1: DEPTH CAROUSEL HERO (Reference Image 2 Style) --}}
        <section class="mx-auto max-w-[1440px] px-4 pt-24 sm:px-6 lg:px-8 lg:pt-28">
            <div class="relative overflow-hidden rounded-2xl bg-[#f8fafc] border border-slate-100 shadow-sm min-h-[380px] sm:min-h-[440px] lg:min-h-[480px] flex items-center"
                 @mouseenter="pause()" 
                 @mouseleave="resume()">
                
                {{-- Carousel Background Slides with Smooth 60fps Crossfade & Left Gradient Blend --}}
                <div class="absolute inset-0 z-0">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                             :class="currentSlide === index ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'">
                            
                            {{-- Background Image Positioned Right --}}
                            <div class="absolute inset-0 flex justify-end">
                                <img :src="slide.image" 
                                     :alt="slide.title" 
                                     class="h-full w-full sm:w-2/3 lg:w-3/5 object-cover object-center transform scale-100 transition-transform duration-1000 ease-out"
                                     loading="eager">
                            </div>

                            {{-- Smooth Gradient Blending into White on the Left (Matching Reference Image 2) --}}
                            <div class="absolute inset-0 bg-gradient-to-r from-[#f8fafc] via-[#f8fafc]/95 via-35% to-transparent sm:via-45%"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-[#f8fafc]/80 via-transparent to-transparent sm:hidden"></div>
                        </div>
                    </template>
                </div>

                {{-- Carousel Foreground Content --}}
                <div class="relative z-20 w-full max-w-3xl px-6 py-12 sm:px-12 lg:px-16">
                    
                    {{-- Dynamic Slide Text with Transition --}}
                    <div class="min-h-[140px] sm:min-h-[160px]">
                        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl leading-[1.15] text-balance"
                            x-text="slides[currentSlide].title">
                            Wujudkan ide besar dengan talenta terbaik.
                        </h1>
                        <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-600 sm:text-base"
                           x-text="slides[currentSlide].subtitle">
                            Temukan ribuan karya dan layanan digital berkualitas langsung dari siswa berprestasi.
                        </p>
                    </div>

                    {{-- Search Bar --}}
                    <form action="{{ route('services.index') }}" method="GET" class="mt-6 max-w-xl">
                        @foreach (request()->only(['category', 'subcategory', 'sort']) as $name => $value)
                            @if ($value)<input type="hidden" name="{{ $name }}" value="{{ $value }}">@endif
                        @endforeach
                        
                        <div class="flex items-center rounded-xl bg-white p-1.5 shadow-md border border-slate-200 focus-within:border-black focus-within:ring-1 focus-within:ring-black transition">
                            <div class="flex flex-1 items-center pl-3">
                                <svg class="h-5 w-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Cari keahlian (contoh: Desain Logo, Website, Edit Video...)" 
                                       class="w-full border-0 bg-transparent px-3 py-2.5 text-xs sm:text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-0">
                            </div>
                            
                            <button type="submit" 
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-black px-5 py-2.5 text-xs sm:text-sm font-bold text-white transition hover:bg-slate-800 active:scale-[0.98]">
                                <span>Cari</span>
                            </button>
                        </div>
                    </form>

                    {{-- Popular Quick Search Tags --}}
                    <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                        <span class="font-semibold text-slate-700">Populer:</span>
                        @foreach (['Desain Logo', 'Landing Page', 'Edit Video', 'Slide Presentasi', 'Python'] as $keyword)
                            <a href="{{ route('services.index', ['search' => $keyword]) }}" 
                               class="rounded-md bg-white/80 px-2.5 py-1 font-medium text-slate-700 border border-slate-200/80 transition hover:bg-black hover:text-white hover:border-black">
                                {{ $keyword }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Carousel Manual Arrow Controls --}}
                <button type="button" 
                        @click="prev()" 
                        class="absolute left-3 top-1/2 -translate-y-1/2 z-30 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-slate-800 shadow-md border border-slate-200/80 backdrop-blur-sm transition hover:bg-black hover:text-white hover:border-black active:scale-95"
                        aria-label="Slide sebelumnya">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                
                <button type="button" 
                        @click="next()" 
                        class="absolute right-3 top-1/2 -translate-y-1/2 z-30 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-slate-800 shadow-md border border-slate-200/80 backdrop-blur-sm transition hover:bg-black hover:text-white hover:border-black active:scale-95"
                        aria-label="Slide berikutnya">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                {{-- Carousel Indicator Dots --}}
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button type="button" 
                                @click="goTo(index)" 
                                class="h-2 rounded-full transition-all duration-300"
                                :class="currentSlide === index ? 'w-6 bg-black' : 'w-2 bg-slate-300 hover:bg-slate-400'"
                                :aria-label="'Pergi ke slide ' + (index + 1)"></button>
                    </template>
                </div>
            </div>
        </section>


        {{-- SECTION 2: POPULAR CATEGORIES (Reference Image 2 Style - Enlarged Icons, Snug Hover Border, Zero Text Glitch) --}}
        <section class="mx-auto max-w-[1440px] px-4 pt-8 pb-4 sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg sm:text-xl font-bold tracking-tight text-slate-900">
                        Popular Categories
                    </h2>
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                @if ($hasActiveFilters)
                    <a href="{{ route('services.index') }}" class="text-xs font-bold uppercase tracking-wider text-blue-600 hover:text-blue-800 underline underline-offset-4">
                        Reset Filter
                    </a>
                @endif
            </div>

            {{-- Clean Native Category Rail with Snug Hover Border and Enlarged Icons --}}
            <div class="overflow-x-auto pb-3 pt-1 scrollbar-thin">
                <div class="inline-flex items-center gap-3 sm:gap-4 min-w-full">
                    @foreach ($marketplaceCategories as $category)
                        <a href="{{ $category['url'] }}" 
                           @mouseenter="previewCategory({{ $category['id'] }})"
                           class="category-filter-item {{ $category['active'] ? 'is-active' : '' }}">
                            
                            {{-- Large Circular Icon (Significantly larger than text) --}}
                            <span class="category-icon-circle">
                                @if ($category['iconImage'])
                                    <img src="{{ $category['iconImage'] }}" alt="{{ $category['name'] }}">
                                @else
                                    <span class="text-base sm:text-lg font-bold text-slate-800">{{ $category['icon'] }}</span>
                                @endif
                            </span>

                            {{-- Clean, Non-Glitching Category Text --}}
                            <span class="text-xs sm:text-sm font-semibold text-slate-800 whitespace-nowrap group-hover:text-black">
                                {{ $category['name'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>


        {{-- ADIDAS SOLID BLACK SEPARATOR LINE (CONSTRAINED WITH SIDE SPACING) --}}
        <div class="mx-auto max-w-[1440px] px-4 sm:px-8 lg:px-12 my-6 sm:my-8">
            <div class="h-[2px] w-full bg-black"></div>
        </div>


        {{-- SECTION 3: ADIDAS-STYLE SERVICE CATALOG (Reference Image 1 Style with Snug Border) --}}
        <section id="daftar-jasa" class="bg-white py-4 sm:py-6">
            <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
                
                {{-- Toolbar / Recommendation Header --}}
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-black">
                            @if (request('search'))
                                Hasil pencarian "{{ request('search') }}"
                            @elseif ($activeCategory)
                                Kategori {{ $activeCategory->name }}
                            @else
                                Rekomendasi Jasa
                            @endif
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Menampilkan {{ $services->total() }} jasa tersedia
                        </p>
                    </div>

                    {{-- Sort & Subcategory Filter Toolbar --}}
                    <form action="{{ route('services.index') }}#daftar-jasa" method="GET" class="flex flex-wrap items-center gap-2.5">
                        @foreach (request()->only(['search', 'category']) as $name => $value)
                            @if ($value)<input type="hidden" name="{{ $name }}" value="{{ $value }}">@endif
                        @endforeach

                        {{-- Subcategory Dropdown --}}
                        <div class="relative">
                            <label for="subcategory" class="sr-only">Subkategori</label>
                            <select id="subcategory" 
                                    name="subcategory" 
                                    onchange="this.form.submit()" 
                                    class="appearance-none border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs font-semibold text-slate-800 outline-none hover:border-black focus:border-black">
                                <option value="">Semua Subkategori</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" @selected((string) request('subcategory') === (string) $subcategory->id)>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        {{-- Sort Dropdown --}}
                        <div class="relative">
                            <label for="sort" class="sr-only">Urutkan</label>
                            <select id="sort" 
                                    name="sort" 
                                    onchange="this.form.submit()" 
                                    class="appearance-none border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs font-semibold text-slate-800 outline-none hover:border-black focus:border-black">
                                <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option>
                                <option value="price_low" @selected(request('sort') === 'price_low')>Harga: Terendah</option>
                                <option value="price_high" @selected(request('sort') === 'price_high')>Harga: Tertinggi</option>
                            </select>
                            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </form>
                </div>

                {{-- ADIDAS-STYLE SHARP PRODUCT GRID WITH SNUG HOVER BORDER --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 sm:gap-5 lg:gap-6">
                    @forelse ($services as $service)
                        @php
                            $mainImage = $service->image ? asset('storage/' . $service->image) : asset('images/skillhub-hero.jpg');
                            $portfolioImage = filled($service->portfolio_images[0] ?? null) ? asset('storage/' . $service->portfolio_images[0]) : null;
                        @endphp
                        
                        <article class="adidas-service-card group flex flex-col justify-between">
                            <div>
                                {{-- Landscape Image with Dual-Image Portfolio Hover Flip --}}
                                <a href="{{ route('services.show', $service) }}" class="relative block aspect-[16/10] overflow-hidden bg-[#f1f5f9]">
                                    <img src="{{ $mainImage }}" 
                                         alt="{{ $service->title }}" 
                                         class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105 {{ $portfolioImage ? 'group-hover:opacity-0' : '' }}" 
                                         loading="lazy" 
                                         decoding="async">
                                    
                                    @if ($portfolioImage)
                                        <img src="{{ $portfolioImage }}" 
                                             alt="Portofolio {{ $service->title }}" 
                                             class="absolute inset-0 h-full w-full scale-105 object-cover opacity-0 transition-all duration-500 ease-out group-hover:scale-100 group-hover:opacity-100" 
                                             loading="lazy" 
                                             decoding="async">
                                    @endif

                                    {{-- Subcategory Tag Badge --}}
                                    <span class="absolute left-2.5 top-2.5 bg-white/90 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-black">
                                        {{ $service->subcategory?->name ?? 'Jasa' }}
                                    </span>

                                    {{-- Wishlist / Heart Icon Top Right (Adidas Style) --}}
                                    <div class="absolute right-2.5 top-2.5 text-slate-800 transition hover:text-black">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </div>
                                </a>

                                {{-- Adidas-Style Content Presentation (Price First, Title, Subcategory) --}}
                                <div class="pt-3 pb-1 px-1">
                                    {{-- Price Formatted (Bold & Prominent) --}}
                                    <div class="text-sm sm:text-base font-extrabold text-black tracking-tight">
                                        Rp{{ number_format($service->price, 0, ',', '.') }},00
                                    </div>

                                    {{-- Title --}}
                                    <h3 class="mt-1 line-clamp-2 text-xs sm:text-sm font-medium text-slate-800 hover:text-black">
                                        <a href="{{ route('services.show', $service) }}">
                                            {{ $service->title }}
                                        </a>
                                    </h3>

                                    {{-- Seller / Category Performance Label --}}
                                    <p class="mt-1 text-[11px] text-slate-500 font-normal">
                                        {{ $service->seller?->name ?? 'Siswa SkillHub' }} • {{ $service->subcategory?->name ?? 'Karya Siswa' }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    @empty
                        {{-- Empty State --}}
                        <div class="col-span-full border border-dashed border-slate-300 p-12 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <h3 class="mt-4 text-base font-bold text-black">Jasa belum ditemukan</h3>
                            <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                                Coba sesuaikan kata kunci pencarian atau ubah filter kategori Anda.
                            </p>
                            <a href="{{ route('services.index') }}" 
                               class="mt-5 inline-flex items-center border border-black bg-black px-4 py-2 text-xs font-bold text-white transition hover:bg-slate-800">
                                Lihat Semua Jasa
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- COMPREHENSIVE NUMERIC PAGINATION --}}
                @if ($services->hasPages())
                    <nav role="navigation" aria-label="Navigasi Halaman" class="mt-12 flex items-center justify-center">
                        <ul class="flex flex-wrap items-center gap-1.5">
                            
                            {{-- Previous Page Link --}}
                            @if ($services->onFirstPage())
                                <li aria-disabled="true" aria-label="Sebelumnya">
                                    <span class="pagination-link opacity-40 cursor-not-allowed" aria-hidden="true">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $services->previousPageUrl() }}#daftar-jasa" 
                                       rel="prev" 
                                       class="pagination-link" 
                                       aria-label="Halaman Sebelumnya">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements (Numbers & Ellipses) --}}
                            @foreach ($services->links()->elements as $element)
                                {{-- "Three Dots" Separator --}}
                                @if (is_string($element))
                                    <li aria-disabled="true">
                                        <span class="pagination-ellipsis">{{ $element }}</span>
                                    </li>
                                @endif

                                {{-- Array of Links --}}
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        @if ($page == $services->currentPage())
                                            <li aria-current="page">
                                                <span class="pagination-link is-active">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li>
                                                <a href="{{ $url }}#daftar-jasa" class="pagination-link" aria-label="Pergi ke halaman {{ $page }}">
                                                    {{ $page }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($services->hasMorePages())
                                <li>
                                    <a href="{{ $services->nextPageUrl() }}#daftar-jasa" 
                                       rel="next" 
                                       class="pagination-link" 
                                       aria-label="Halaman Berikutnya">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </li>
                            @else
                                <li aria-disabled="true" aria-label="Berikutnya">
                                    <span class="pagination-link opacity-40 cursor-not-allowed" aria-hidden="true">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </li>
                            @endif
                            
                        </ul>
                    </nav>
                @endif

            </div>
        </section>
    </main>

    {{-- Site Footer --}}
    <x-site-footer />

    {{-- Interactive Alpine State for Carousel & Category Switch --}}
    <script>
        function carouselState() {
            return {
                slides: @js($heroSlides),
                categoryImages: @js($categoryImages),
                currentSlide: 0,
                intervalTimer: null,
                init() {
                    this.startAutoplay();
                },
                startAutoplay() {
                    this.intervalTimer = setInterval(() => {
                        this.next();
                    }, 5000);
                },
                pause() {
                    if (this.intervalTimer) clearInterval(this.intervalTimer);
                },
                resume() {
                    this.pause();
                    this.startAutoplay();
                },
                next() {
                    this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                },
                prev() {
                    this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
                },
                goTo(index) {
                    this.currentSlide = index;
                },
                previewCategory(categoryId) {
                    if (!categoryId) return;
                    const catUrl = this.categoryImages[categoryId];
                    if (catUrl) {
                        this.slides[0].image = catUrl;
                        this.currentSlide = 0;
                    }
                }
            };
        }
    </script>
</body>
</html>
