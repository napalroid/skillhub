@php
    $marketplaceCategories = collect([
        [
            'id' => 0,
            'name' => 'Semua Jasa',
            'url' => route('services.index'),
            'icon' => null,
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
            'image' => asset('storage/marketplace-image/SKILLHUB_1920x1080.png'),
            'tag' => 'Eksplorasi Jasa',
        ],
        [
            'title' => 'Jasa kreatif, koding & desain untuk kebutuhanmu.',
            'subtitle' => 'Dari pembuatan website responsif, desain grafis modern, hingga video editing profesional.',
            'image' => asset('storage/marketplace-image/skillhubsitemap.png'),
            'tag' => 'Talenta Digital',
        ],
        [
            'title' => 'Solusi proyek & kebutuhan sekolah cepat dan terpercaya.',
            'subtitle' => 'Portofolio teruji dengan komunikasi langsung bersama kreator siswa yang siap membantu.',
            'image' => asset('storage/marketplace-image/ITSKILLHUB.png'),
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

        /* Hero Background Container */
        .hero-bg-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .hero-bg-item {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            transition: opacity 1s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 1s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .hero-bg-item img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 8s linear;
        }

        .hero-bg-item:hover img {
            transform: scale(1.1);
        }

        /* Service Card - No Background, Hover State Only */
        .service-card {
            display: block;
            border: 1px solid transparent;
            padding: 0.5rem;
            transition: border-color 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .service-card:hover {
            border-color: #000000;
            transform: translateY(-2px);
        }

        .service-card-image {
            position: relative;
            overflow: hidden;
            background-color: #f1f5f9;
        }

        .service-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .service-card:hover .service-card-image img {
            transform: scale(1.03);
        }

        .service-card-title {
            transition: color 0.2s ease;
        }

        .service-card:hover .service-card-title {
            color: #000000;
        }

        /* Category Filter - Clean Editorial */
        .category-item {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1.25rem;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            user-select: none;
            flex-shrink: 0;
        }

        .category-item:hover {
            border-color: #000000;
            background-color: #fafafa;
        }

        .category-item.active {
            border-color: #000000;
            background-color: #000000;
            color: #ffffff;
        }

        .category-item.active .category-icon {
            filter: invert(1);
        }

        .category-icon {
            width: 1.5rem;
            height: 1.5rem;
            flex-shrink: 0;
        }

        /* Pagination */
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
            font-weight: 600;
            text-decoration: none;
            transition: all 0.18s ease;
        }

        .pagination-link:hover {
            border-color: #000000;
            background-color: #fafafa;
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
            font-weight: 600;
            user-select: none;
        }

        /* FAQ Accordion */
        .faq-item {
            border-bottom: 1px solid #e2e8f0;
            transition: border-color 0.2s ease;
        }

        .faq-item:hover {
            border-color: #000000;
        }

        .faq-toggle {
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .faq-toggle .faq-icon {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .faq-text {
            margin: 0;
            line-height: 1.7;
        }

        @media (max-width: 639px) {
            .faq-text {
                font-size: 0.875rem;
            }
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
         data-user-id="{{ auth()->id() ?? '' }}"
         data-user-name="{{ auth()->user()?->name ?? '' }}"
         data-avatar-url="{{ auth()->user()?->avatar_url ?? '' }}"
         data-profile-url="{{ route('profile.edit') }}"
         data-logout-url="{{ route('logout') }}"
         data-notifications-url="{{ auth()->check() ? route('notifications.index') : '' }}"
         data-notifications-read-all-url="{{ auth()->check() ? route('notifications.read-all') : '' }}"
         data-dompet="{{ route('wallet.index') }}"
         data-pesanan="{{ route('orders.index') }}"
         data-csrf-token="{{ csrf_token() }}"
         data-is-admin="{{ auth()->check() && auth()->user()->isAdmin() ? 'true' : 'false' }}"
         data-admin-dashboard="{{ auth()->check() && auth()->user()->isAdmin() ? route('admin.dashboard') : '' }}"></div>
    <script id="skillhub-account-notifications-data" type="application/json">@json($accountNotifications ?? collect(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)</script>
    <script id="skillhub-staggered-menu-items-data" type="application/json">@json($marketplaceMenuItems, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)</script>

    <main x-data="heroState()">
        
        {{-- SECTION 1: HERO - IMAGE AS BACKGROUND FOR TEXT --}}
        <section class="relative min-h-[600px] sm:min-h-[700px] lg:min-h-[800px] overflow-hidden">
            
            {{-- Background Image Slides --}}
            <div class="absolute inset-0 z-0">
                <template x-for="(slide, index) in slides" :key="index">
                    <div class="absolute inset-0 transition-all duration-1000 ease-out"
                         :class="currentSlide === index ? 'opacity-100 scale-100' : 'opacity-0 scale-105'">
                        <img :src="slide.image" 
                             :alt="slide.title" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
                    </div>
                </template>
            </div>

            {{-- Content OVER the background image --}}
            <div class="relative z-10 mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8 h-full min-h-[600px] sm:min-h-[700px] lg:min-h-[800px] flex items-center">
                <div class="w-full lg:w-1/2 space-y-6 sm:space-y-8 py-20 sm:py-24 lg:py-32">
                    
                    {{-- Dynamic Title --}}
                    <div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold tracking-tight text-white leading-[1.1] drop-shadow-lg transition-opacity duration-700"
                            x-text="slides[currentSlide].title">
                            Wujudkan ide besar dengan talenta terbaik.
                        </h1>
                    </div>

                    {{-- Dynamic Subtitle --}}
                    <div>
                        <p class="text-base sm:text-lg lg:text-xl leading-relaxed text-white/90 max-w-xl drop-shadow transition-opacity duration-700"
                           x-text="slides[currentSlide].subtitle">
                            Temukan ribuan karya dan layanan digital berkualitas langsung dari siswa berprestasi.
                        </p>
                    </div>

                    {{-- Search Bar --}}
                    <form action="{{ route('services.index') }}" method="GET" class="max-w-xl">
                        @foreach (request()->only(['category', 'subcategory', 'sort']) as $name => $value)
                            @if ($value)<input type="hidden" name="{{ $name }}" value="{{ $value }}">@endif
                        @endforeach
                        
                        <div class="flex items-center bg-white/95 backdrop-blur-sm border-2 border-white/50 focus-within:border-white transition shadow-2xl">
                            <div class="flex flex-1 items-center pl-4">
                                <svg class="h-5 w-5 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Cari jasa yang Anda butuhkan..." 
                                       class="w-full border-0 bg-transparent px-3 py-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-0">
                            </div>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3.5 text-sm font-bold text-white bg-black transition hover:bg-slate-800 active:scale-[0.98]">
                                Cari
                            </button>
                        </div>
                    </form>

                    {{-- Popular Tags --}}
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="font-bold text-white uppercase tracking-wide drop-shadow">Populer:</span>
                        @foreach (['Desain Logo', 'Landing Page', 'Edit Video', 'Python'] as $keyword)
                            <a href="{{ route('services.index', ['search' => $keyword]) }}" 
                               class="px-3 py-1.5 font-semibold text-white border border-white/50 bg-white/10 backdrop-blur-sm transition hover:bg-white hover:text-black">
                                {{ $keyword }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Navigation Controls --}}
            <div class="absolute bottom-8 left-4 sm:left-8 right-4 sm:right-8 flex items-center justify-between z-20">
                <button type="button" 
                        @click="prev()" 
                        class="flex h-12 w-12 items-center justify-center bg-white/90 backdrop-blur-sm text-black border border-white transition hover:bg-black hover:text-white hover:border-black active:scale-95 shadow-xl"
                        aria-label="Gambar sebelumnya">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <div class="flex items-center gap-2">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button type="button" 
                                @click="goTo(index)" 
                                class="h-2 transition-all duration-300"
                                :class="currentSlide === index ? 'w-8 bg-white' : 'w-2 bg-white/50 hover:bg-white/70'"
                                :aria-label="'Pergi ke slide ' + (index + 1)"></button>
                    </template>
                </div>
                
                <button type="button" 
                        @click="next()" 
                        class="flex h-12 w-12 items-center justify-center bg-white/90 backdrop-blur-sm text-black border border-white transition hover:bg-black hover:text-white hover:border-black active:scale-95 shadow-xl"
                        aria-label="Gambar berikutnya">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </section>

        {{-- SECTION 2: CATEGORY NAVIGATION --}}
        <section class="mx-auto max-w-[1440px] px-4 pt-12 pb-6 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-black uppercase">
                    Kategori
                </h2>
                @if ($hasActiveFilters)
                    <a href="{{ route('services.index') }}" class="text-xs font-bold uppercase tracking-wider text-slate-600 hover:text-black underline underline-offset-4 transition">
                        Reset Filter
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto pb-3 -mx-4 px-4">
                <div class="inline-flex items-center gap-3 min-w-full">
                    @foreach ($marketplaceCategories as $category)
                        <a href="{{ $category['url'] }}" 
                           class="category-item {{ $category['active'] ? 'active' : '' }}">
                            
                            @if ($category['iconImage'])
                                <img src="{{ $category['iconImage'] }}" 
                                     alt="{{ $category['name'] }}" 
                                     class="category-icon object-cover">
                            @elseif ($category['id'] === 0)
                                <svg class="category-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            @else
                                <svg class="category-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    @switch($category['icon'])
                                        @case('design')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.39m3.421 3.415a15.995 15.995 0 004.764-4.764l3.426-5.14a.75.75 0 00-.944-.944l-5.14 3.426a16 16 0 00-4.764 4.765m4.658 4.657l-4.658-4.657m0 0a3 3 0 10-4.243-4.243"/>
                                        @break

                                        @case('code')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/>
                                        @break

                                        @case('camera')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/>
                                        @break

                                        @case('music')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-.99-3.467l2.31-.66a2.25 2.25 0 001.632-2.163zm0 0V4.5l-9 3v10.5"/>
                                        @break

                                        @case('write')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                        @break

                                        @case('learn')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                                        @break

                                        @case('business')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
                                        @break

                                        @default
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                    @endswitch
                                </svg>
                            @endif

                            <span class="text-sm font-bold whitespace-nowrap">
                                {{ $category['name'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- SEPARATOR --}}
        <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8 my-8">
            <div class="h-px w-full bg-black"></div>
        </div>

        {{-- SECTION 3: SERVICE LISTING --}}
        <section id="daftar-jasa" class="bg-white py-6 sm:py-8">
            <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
                
                {{-- Header & Controls --}}
                <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between border-b border-slate-200 pb-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-black uppercase">
                            @if (request('search'))
                                Hasil: "{{ request('search') }}"
                            @elseif ($activeCategory)
                                {{ $activeCategory->name }}
                            @else
                                Semua Jasa
                            @endif
                        </h2>
                        <p class="text-sm text-slate-500 mt-1 font-medium">
                            {{ $services->total() }} jasa tersedia
                        </p>
                    </div>

                    {{-- Filter Toolbar --}}
                    <form action="{{ route('services.index') }}#daftar-jasa" method="GET" class="flex flex-wrap items-center gap-2">
                        @foreach (request()->only(['search', 'category']) as $name => $value)
                            @if ($value)<input type="hidden" name="{{ $name }}" value="{{ $value }}">@endif
                        @endforeach

                        {{-- Subcategory --}}
                        <div class="relative">
                            <label for="subcategory" class="sr-only">Subkategori</label>
                            <select id="subcategory" 
                                    name="subcategory" 
                                    onchange="this.form.submit()" 
                                    class="appearance-none border border-slate-300 bg-white py-2.5 pl-3 pr-9 text-xs font-bold uppercase tracking-wide text-black outline-none hover:border-black focus:border-black transition">
                                <option value="">Semua Subkategori</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" @selected((string) request('subcategory') === (string) $subcategory->id)>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 h-4 w-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        {{-- Sort --}}
                        <div class="relative">
                            <label for="sort" class="sr-only">Urutkan</label>
                            <select id="sort" 
                                    name="sort" 
                                    onchange="this.form.submit()" 
                                    class="appearance-none border border-slate-300 bg-white py-2.5 pl-3 pr-9 text-xs font-bold uppercase tracking-wide text-black outline-none hover:border-black focus:border-black transition">
                                <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option>
                                <option value="price_low" @selected(request('sort') === 'price_low')>Harga: Rendah</option>
                                <option value="price_high" @selected(request('sort') === 'price_high')>Harga: Tinggi</option>
                            </select>
                            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 h-4 w-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </form>
                </div>

                {{-- SERVICE GRID --}}
                <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @forelse ($services as $service)
                        @php
                            $mainImage = $service->image ? asset('storage/' . $service->image) : asset('images/skillhub-hero.png');
                            $portfolioImage = filled($service->portfolio_images[0] ?? null) ? asset('storage/' . $service->portfolio_images[0]) : null;
                        @endphp
                        
                        <article class="service-card group">
                            {{-- Image --}}
                            <a href="{{ route('services.show', $service) }}" class="service-card-image block aspect-[16/9] mb-3">
                                <img src="{{ $mainImage }}" 
                                     alt="{{ $service->title }}" 
                                     loading="lazy" 
                                     decoding="async">
                                
                                @if ($portfolioImage)
                                    <img src="{{ $portfolioImage }}" 
                                         alt="Portofolio {{ $service->title }}" 
                                         class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                         loading="lazy" 
                                         decoding="async">
                                @endif
                            </a>

                            {{-- Content --}}
                            <div class="space-y-1">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                    {{ $service->subcategory?->name ?? 'Jasa' }}
                                </p>

                                <h3 class="service-card-title text-sm font-bold text-slate-800 line-clamp-2 leading-snug">
                                    <a href="{{ route('services.show', $service) }}">
                                        {{ $service->title }}
                                    </a>
                                </h3>

                                <p class="text-xs text-slate-500">
                                    {{ $service->seller?->name ?? 'SkillHub' }}
                                </p>

                                <div class="text-base font-extrabold text-black pt-1">
                                    Rp{{ number_format($service->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </article>
                    @empty
                        {{-- Empty State --}}
                        <div class="col-span-full border-2 border-dashed border-slate-300 p-16 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <h3 class="mt-4 text-lg font-bold text-black">Tidak ada jasa ditemukan</h3>
                            <p class="mt-2 text-sm text-slate-600 max-w-sm mx-auto">
                                Coba ubah kata kunci pencarian atau filter kategori.
                            </p>
                            <a href="{{ route('services.index') }}" 
                               class="mt-6 inline-flex items-center border-2 border-black bg-black px-6 py-3 text-xs font-bold uppercase tracking-wide text-white transition hover:bg-white hover:text-black">
                                Lihat Semua Jasa
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                @if ($services->hasPages())
                    <nav role="navigation" aria-label="Navigasi Halaman" class="mt-16 flex items-center justify-center">
                        <ul class="flex flex-wrap items-center gap-1">
                            
                            {{-- Previous --}}
                            @if ($services->onFirstPage())
                                <li aria-disabled="true">
                                    <span class="pagination-link opacity-40 cursor-not-allowed">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $services->previousPageUrl() }}#daftar-jasa" 
                                       rel="prev" 
                                       class="pagination-link">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </a>
                                </li>
                            @endif

                            {{-- Page Numbers --}}
                            @foreach ($services->links()->elements as $element)
                                @if (is_string($element))
                                    <li aria-disabled="true">
                                        <span class="pagination-ellipsis">{{ $element }}</span>
                                    </li>
                                @endif

                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        @if ($page == $services->currentPage())
                                            <li aria-current="page">
                                                <span class="pagination-link is-active">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li>
                                                <a href="{{ $url }}#daftar-jasa" class="pagination-link">
                                                    {{ $page }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            {{-- Next --}}
                            @if ($services->hasMorePages())
                                <li>
                                    <a href="{{ $services->nextPageUrl() }}#daftar-jasa" 
                                       rel="next" 
                                       class="pagination-link">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </li>
                            @else
                                <li aria-disabled="true">
                                    <span class="pagination-link opacity-40 cursor-not-allowed">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </li>
                            @endif
                            
                        </ul>
                    </nav>
                @endif

            </div>
        </section>

        {{-- FAQ SECTION - EDITORIAL DESIGN --}}
        <section class="mx-auto max-w-[1440px] px-4 py-16 sm:px-6 lg:px-8 border-t border-slate-200 mt-16">
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-black uppercase mb-12">Pertanyaan Umum</h2>
            
            <div class="space-y-0">
                @foreach ([
                    ['question' => 'Bagaimana cara kerja SkillHub?', 'answer' => 'SkillHub adalah marketplace jasa untuk lingkungan sekolah. Anda dapat memilih jasa yang dibutuhkan, memesan langsung, dan pembayaran akan diholds oleh sistem escrow hingga pekerjaan selesai.'],
                    ['question' => 'Bagaimana cara memilih jasa yang tepat?', 'answer' => 'Perhatikan rating, jumlah pesanan, portfolio penyedia jasa, dan deskripsi layanan yang diberikan. Anda juga bisa memfilter berdasarkan kategori dan subkategori yang relevan.'],
                    ['question' => 'Bagaimana cara memesan jasa?', 'answer' => 'Klik pada jasa yang Anda pilih, lalu ikuti proses pemesanan. Pembayaran akan di-held oleh sistem escrow SkillHub hingga pekerjaan selesai.'],
                    ['question' => 'Bagaimana cara melakukan pembayaran?', 'answer' => 'Pembayaran dilakukan melalui Midtrans dengan berbagai metode (QRIS, Transfer Bank, E-Wallet). Dana akan diholds oleh sistem escrow hingga pekerjaan selesai dan diverifikasi.'],
                    ['question' => 'Bagaimana jika saya membutuhkan jasa yang tidak tersedia?', 'answer' => 'Kami menerima pengajuan jasa baru. Anda bisa mengajukan jasa yang dibutuhkan melalui halaman "Ajukan Jasa" setelah login.'],
                    ['question' => 'Bagaimana cara melihat reputasi penyedia jasa?', 'answer' => 'Di halaman detail jasa, Anda dapat melihat rating rata-rata, jumlah pesanan yang selesai, dan portofolio penyedia jasa.']
                ] as $faq)
                    <div class="faq-item">
                        <button type="button" class="faq-toggle flex w-full items-center justify-between py-6 text-left text-base font-bold text-black hover:text-black focus:outline-none transition-colors">
                            <span>{{ $faq['question'] }}</span>
                            <svg class="faq-icon h-5 w-5 text-slate-400 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="faq-content">
                            <p class="faq-text text-sm text-slate-600 leading-relaxed pb-6">{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- SEPARATOR --}}
        <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8 my-12">
            <div class="h-px w-full bg-black"></div>
        </div>

    </main>
    <x-site-footer />

    {{-- Hero State & FAQ Logic --}}
    <script>
        function heroState() {
            return {
                slides: @js($heroSlides),
                currentSlide: 0,
                intervalTimer: null,
                init() {
                    this.startAutoplay();
                },
                startAutoplay() {
                    this.intervalTimer = setInterval(() => {
                        this.next();
                    }, 7000);
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
                }
            };
        }

        // FAQ Accordion
        document.addEventListener('DOMContentLoaded', function () {
            const faqItems = document.querySelectorAll('.faq-toggle');
            faqItems.forEach(function (faqToggle) {
                faqToggle.addEventListener('click', function () {
                    const faqItem = this.closest('.faq-item');
                    const faqContent = faqItem.querySelector('.faq-content');
                    const faqText = faqItem.querySelector('.faq-text');
                    
                    faqItem.classList.toggle('active');
                    
                    if (faqItem.classList.contains('active')) {
                        faqContent.style.maxHeight = faqText.scrollHeight + 'px';
                    } else {
                        faqContent.style.maxHeight = '0';
                    }
                });
            });
        });
    </script>
</body>
</html>
