<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jasa — SkillHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes heroZoom { from { transform: scale(1); } to { transform: scale(1.15); } }
        .hero-zoom { animation: heroZoom 20s ease-in-out infinite alternate; }
        .hero-visible { opacity: .7; }
        .hero-hidden { opacity: 0; }
        .hero-img { transition: opacity .5s ease-in-out; }        @keyframes heroIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .hero-in { animation: heroIn .8s ease-out both; }
        @keyframes chipPop { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .chip-pop { animation: chipPop .5s ease-out both; }
        .chip-zoom { transition: transform .3s ease, box-shadow .3s ease; will-change: transform; }
        .chip-zoom:hover { transform: translateY(-4px) scale(1.1); }
    </style>
</head>
<body class="min-h-screen bg-[#fbfcff] text-slate-900">
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-18 max-w-7xl items-center justify-between gap-5 px-5 py-3">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5 text-xl font-extrabold tracking-tight">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-lg text-white">✦</span> SkillHub
            </a>
            <form action="{{ route('services.index') }}" method="GET" class="hidden max-w-md flex-1 md:flex">
                @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                @if(request('subcategory'))<input type="hidden" name="subcategory" value="{{ request('subcategory') }}">@endif
                <div class="flex w-full rounded-xl border border-slate-200 bg-slate-50 p-1 shadow-sm focus-within:border-blue-300 focus-within:ring-4 focus-within:ring-blue-50">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari jasa atau keahlian..." class="min-w-0 flex-1 bg-transparent px-3 text-sm outline-none placeholder:text-slate-400">
                    <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Cari</button>
                </div>
            </form>
            <nav class="flex shrink-0 items-center gap-2 text-sm font-semibold">
                @auth
                    <a href="{{ route('conversations.seller-index') }}" class="flex items-center gap-2 rounded-xl px-2 py-1.5 text-slate-700 transition hover:bg-slate-100" title="Pesan masuk saya">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg></span>
                        <span class="hidden sm:inline">Chat</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-xl px-2 py-1.5 text-slate-700 transition hover:bg-slate-100" title="Profil saya">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-xs font-extrabold text-white">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="hidden max-w-32 truncate sm:inline">{{ auth()->user()->name }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-2 rounded-xl px-2 py-1.5 text-slate-700 transition hover:bg-slate-100" title="Masuk ke akun">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6m6-12 3 3m0 0-3 3m3-3H9"/></svg></span>
                        <span class="hidden sm:inline">Masuk</span>
                    </a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-white shadow-sm shadow-blue-600/25 hover:bg-blue-700">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-5 py-7 sm:py-9" x-data="heroState()">
        <section class="hero-in relative z-0 min-h-[250px] overflow-hidden rounded-[28px] bg-slate-900 px-7 py-10 shadow-xl shadow-blue-950/10 sm:min-h-[300px] sm:px-12 sm:py-14">
            <div class="absolute inset-0 -z-20 overflow-hidden">
                <img src="{{ $heroImage }}" alt="{{ $activeCategory?->name ?? 'Kolaborasi siswa SkillHub' }}" :src="heroImage" :alt="heroLabel" class="hero-zoom hero-img hero-visible h-full w-full object-cover object-center" :class="fade && 'hero-hidden'">
            </div>
            <div class="absolute inset-0 -z-10 bg-gradient-to-r from-slate-950 via-slate-900/75 to-slate-900/10"></div>
            <div class="max-w-xl text-white">
                <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-blue-100 backdrop-blur">Marketplace jasa siswa</span>
                <h1 class="mt-5 text-3xl font-extrabold leading-tight sm:text-5xl">Temukan bantuan, wujudkan ide bersama.</h1>
                <p class="mt-4 max-w-lg text-sm leading-7 text-slate-200 sm:text-base">Jelajahi jasa kreatif, teknologi, hingga pendampingan dari teman sekolah yang siap membantu kebutuhanmu.</p>
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="mt-7 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-blue-700 shadow-lg transition hover:bg-blue-50">Kembali ke dashboard <span aria-hidden="true">→</span></a>
            </div>
        </section>

        <section class="relative z-30 mt-8 isolate">
            <div class="mb-4 flex items-center justify-between gap-4">
                <div><p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-600">Jelajahi berdasarkan</p><h2 class="mt-1 text-xl font-extrabold">Kategori utama</h2></div>
                @if(request()->hasAny(['category', 'subcategory', 'search']))<a href="{{ route('services.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Reset filter</a>@endif
            </div>
            <div class="-mx-3 flex gap-3 overflow-x-auto px-3 py-4">
                <a href="{{ route('services.index') }}" @mouseenter="preview(0)" @mouseleave="resetHero()" class="chip-pop chip-zoom relative z-10 flex shrink-0 items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-bold shadow-md hover:z-30 {{ !request('category') ? 'border-slate-950 bg-slate-950 text-white shadow-slate-300' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-400' }}"><span class="flex h-10 w-10 items-center justify-center rounded-xl text-xl {{ !request('category') ? 'bg-white/15' : 'bg-slate-100 text-slate-700' }}">✦</span> Semua</a>
                @foreach($categories as $category)
                    <a href="{{ route('services.index', ['category' => $category->id]) }}" data-hero-category="{{ $category->id }}" data-name="{{ $category->name }}" @mouseenter="preview({{ $category->id }})" @mouseleave="resetHero()" class="chip-pop chip-zoom relative z-10 flex shrink-0 items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-bold shadow-md hover:z-30 {{ (string) request('category') === (string) $category->id ? 'border-slate-950 bg-slate-950 text-white shadow-slate-300' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-400' }}" style="animation-delay: {{ $loop->iteration * 0.05 }}s">
                        @if($category->iconIsFile())
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-xl {{ (string) request('category') === (string) $category->id ? 'bg-white/15' : 'bg-slate-100' }}"><img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }}" class="h-full w-full object-cover"></span>
                        @elseif($category->icon)
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-xl {{ (string) request('category') === (string) $category->id ? 'bg-white/15' : 'bg-slate-100' }}">{{ $category->icon }}</span>
                        @else
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-xl {{ (string) request('category') === (string) $category->id ? 'bg-white/15' : 'bg-slate-100' }}">{{ $category->displayIcon() }}</span>
                        @endif
                        <span>{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section id="daftar-jasa" class="relative z-0 mt-7">
            <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div><p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-600">Pilihan terbaru</p><h2 class="mt-1 text-2xl font-extrabold">Daftar jasa</h2><p class="mt-1 text-sm text-slate-500">{{ $services->total() }} jasa tersedia untuk kamu jelajahi.</p></div>
                <form action="{{ route('services.index') }}#daftar-jasa" method="GET" class="flex flex-wrap items-center gap-2">
                    @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                    @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                    <label for="subcategory" class="text-sm font-semibold text-slate-600">Sort By:</label>
                    <select id="subcategory" name="subcategory" onchange="this.form.submit()" class="rounded-xl border-slate-200 bg-white py-2.5 pl-3 pr-9 text-sm font-semibold text-slate-700 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"><option value="">Semua subkategori</option>@foreach($subcategories as $subcategory)<option value="{{ $subcategory->id }}" @selected((string) request('subcategory') === (string) $subcategory->id)>{{ $subcategory->name }} · {{ $subcategory->category->name }}</option>@endforeach</select>
                    <select name="sort" onchange="this.form.submit()" class="rounded-xl border-slate-200 bg-white py-2.5 pl-3 pr-9 text-sm font-semibold text-slate-700 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"><option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option><option value="price_low" @selected(request('sort') === 'price_low')>Harga terendah</option><option value="price_high" @selected(request('sort') === 'price_high')>Harga tertinggi</option></select>
                </form>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($services as $service)
                    <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70">
                        <a href="{{ route('services.show', $service) }}" class="block">
                            <div class="relative h-44 overflow-hidden bg-slate-100">
                                @if($service->image)<img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">@else<img src="{{ asset('images/skillhub-hero.png') }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">@endif
                                <span class="absolute left-3 top-3 rounded-full bg-white/95 px-2.5 py-1 text-[11px] font-bold text-blue-700 shadow-sm">{{ $service->subcategory?->name ?? 'Jasa' }}</span>
                                <span class="absolute bottom-3 right-3 rounded-full bg-slate-950/80 px-2.5 py-1 text-xs font-bold text-white">Rp{{ number_format($service->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="p-4"><p class="text-xs font-semibold text-slate-400">{{ $service->seller?->name ?? 'Siswa SkillHub' }}</p><h3 class="mt-1 line-clamp-1 text-base font-extrabold text-slate-900 group-hover:text-blue-700">{{ $service->title }}</h3><p class="mt-2 line-clamp-2 min-h-10 text-xs leading-5 text-slate-500">{{ $service->description }}</p><div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs font-semibold"><span class="text-amber-500">★ Jasa terverifikasi</span><span class="text-blue-600">Lihat detail →</span></div></div>
                        </a>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"><div class="text-4xl">🔎</div><h3 class="mt-4 text-lg font-bold text-slate-800">Jasa belum ditemukan</h3><p class="mt-2 text-sm text-slate-500">Coba ubah kata kunci atau pilihan kategori kamu.</p><a href="{{ route('services.index') }}" class="mt-5 inline-flex rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-700">Lihat semua jasa</a></div>
                @endforelse
            </div>
            @if($services->hasPages())<div class="mt-8">{{ $services->links() }}</div>@endif
        </section>
    </main>

    <footer class="relative mt-16 overflow-hidden bg-black px-5 pb-7 pt-14 text-white">
        <div class="absolute -right-24 -top-24 h-64 w-64 rounded-full bg-blue-600/15 blur-3xl"></div>
        <div class="relative mx-auto max-w-6xl">
            <div class="grid gap-10 border-b border-white/15 pb-11 md:grid-cols-[1.5fr_1fr_1fr_1fr]">
                <div><a href="{{ route('home') }}" class="inline-flex origin-left items-center gap-2.5 text-xl font-extrabold tracking-tight transition duration-200 hover:scale-105 hover:text-blue-300"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-base">✦</span>SkillHub</a><p class="mt-4 max-w-xs text-sm leading-6 text-slate-400">Marketplace jasa antarsiswa untuk berkarya, berkolaborasi, dan berkembang bersama.</p></div>
                <div><h3 class="text-sm font-bold uppercase tracking-wider">Jelajahi</h3><div class="mt-4 flex flex-col items-start gap-3 text-sm text-slate-400"><a href="{{ route('services.index') }}" class="origin-left transition duration-200 hover:scale-110 hover:text-white">Marketplace Jasa</a><a href="{{ route('home') }}#keunggulan" class="origin-left transition duration-200 hover:scale-110 hover:text-white">Keunggulan</a><a href="{{ route('home') }}#review" class="origin-left transition duration-200 hover:scale-110 hover:text-white">Ulasan Pengguna</a></div></div>
                <div><h3 class="text-sm font-bold uppercase tracking-wider">Akun</h3><div class="mt-4 flex flex-col items-start gap-3 text-sm text-slate-400"><a href="{{ route('login') }}" class="origin-left transition duration-200 hover:scale-110 hover:text-white">Masuk</a><a href="{{ route('register') }}" class="origin-left transition duration-200 hover:scale-110 hover:text-white">Daftar</a></div></div>
                <div><h3 class="text-sm font-bold uppercase tracking-wider">SkillHub</h3><p class="mt-4 text-sm leading-6 text-slate-400">Tempat yang aman untuk menjual keahlian dan mencari bantuan dari sesama siswa.</p><a href="{{ route('home') }}#cara-kerja" class="mt-4 inline-block origin-left text-sm font-semibold text-blue-300 transition duration-200 hover:scale-110 hover:text-white">Cara kerja kami →</a></div>
            </div>
            <div class="flex flex-col gap-3 pt-7 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between"><p>© {{ date('Y') }} SkillHub. Dibuat untuk siswa berkarya.</p><p>Aman • Terpercaya • Untuk siswa</p></div>
        </div>
    </footer>
    <script>
        function heroState() {
            return {
                images: @js($categoryImages),
                defaultImage: @js(asset('images/skillhub-hero.png')),
                activeId: @js($activeCategory?->id ?? null),
                heroImage: @js($heroImage),
                heroLabel: @js($activeCategory?->name ?? 'Kolaborasi siswa SkillHub'),
                fade: false,
                _timer: null,
                _set(url, label) {
                    if (!url || url === this.heroImage) return;
                    clearTimeout(this._timer);
                    this.fade = true;
                    this._timer = setTimeout(() => {
                        this.heroImage = url;
                        this.heroLabel = label;
                        this.fade = false;
                    }, 260);
                },
                preview(id) {
                    if (!id) {
                        this._set(this.defaultImage, 'Kolaborasi siswa SkillHub');
                        return;
                    }
                    const url = this.images[id] ?? null;
                    if (!url) return;
                    this._set(url, this._name(id));
                },
                resetHero() {
                    if (this.activeId) {
                        const url = this.images[this.activeId] ?? this.defaultImage;
                        this._set(url, this._name(this.activeId));
                    } else {
                        this._set(this.defaultImage, 'Kolaborasi siswa SkillHub');
                    }
                },
                _name(id) {
                    const el = document.querySelector(`[data-hero-category="${id}"]`);
                    return el ? el.dataset.name : 'Kategori pilihan';
                }
            };
        }
    </script>
</body>
</html>
