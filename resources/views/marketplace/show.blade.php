@php
    use Illuminate\Support\Facades\Storage;

    $mediaUrl = static function (?string $path): ?string {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return asset('storage/' . ltrim($path, '/'));
    };

    $photos = collect([[
        'url' => $mediaUrl($service->image) ?? asset('images/skillhub-hero.png'),
        'label' => 'Thumbnail jasa',
    ]]);

    foreach ($portfolios as $path) {
        if ($url = $mediaUrl($path)) {
            $photos->push(['url' => $url, 'label' => 'Portofolio jasa']);
        }
    }

    while ($photos->count() < 4) {
        $photos->push(['url' => null, 'label' => 'Portofolio belum tersedia']);
    }

    $reviewData = $service->reviews->map(fn ($review) => [
        'id' => $review->id,
        'rating' => (int) $review->rating,
        'comment' => $review->comment ?? '',
        'buyer' => $review->user?->name ?? 'Anonymous',
        'date' => $review->created_at?->format('d M Y'),
        'verified' => $review->is_verified_buyer ?? false,
    ])->values();
    $reviewCount = (int) $service->reviews_count;
    $averageRating = $service->average_rating ? number_format($service->average_rating, 1) : '0.0';
    $userReview = auth()->check() ? $service->reviews->firstWhere('user_id', auth()->id()) : null;
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $service->title }} — SkillHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'DM Sans', sans-serif; }
    </style>
    @vite('resources/js/app.js')
</head>
<body class="bg-[#f2f2f1] text-[#171717] antialiased pt-16">
    <div id="skillhub-staggered-menu"
         data-home="{{ route('home') }}"
         data-marketplace="{{ route('services.index') }}"
         data-chat="{{ route('conversations.seller-index') }}"
         data-login="{{ route('login') }}"
         data-register="{{ route('register') }}"
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
         data-csrf-token="{{ csrf_token() }}"></div>
    <script id="skillhub-account-notifications-data" type="application/json">@json($accountNotifications ?? collect(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT)</script>
    <div x-data="productPage(@js($photos->values()), @js($reviewData), @js(session('success') ?? session('error')))" @keydown.escape.window="closeAll()" class="min-h-screen">
        <header class="border-b border-black/10 bg-[#f2f2f1]">
            <div class="mx-auto flex h-14 max-w-[1440px] items-center justify-between px-5 sm:px-8">
                <a href="{{ route('services.index') }}" class="inline-flex items-center gap-1.5 text-[11px] font-semibold tracking-tight text-black/75 transition hover:text-black">
                    <i data-lucide="chevron-left" class="h-3.5 w-3.5"></i> Home / Products
                </a>
                <div class="flex items-center gap-2">
                    <button type="button" @click="sidebarOpen = true" class="flex h-8 w-8 items-center justify-center rounded-full border border-black/15 bg-white lg:hidden" aria-label="Buka menu"><i data-lucide="menu" class="h-4 w-4"></i></button>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-[1440px] px-5 py-9 sm:px-8 lg:py-12">
            <div class="grid gap-8 lg:grid-cols-[1.45fr_.85fr] lg:gap-12">
                <section class="min-w-0">
                    <div class="group relative aspect-[16/10] overflow-hidden rounded-[18px] bg-[#dededc]">
                        <template x-for="(photo, index) in photos" :key="index">
                            <img x-show="activePhoto === index" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-[1.02]" x-transition:enter-end="opacity-100 scale-100" :src="photo.url" :alt="photo.label" class="absolute inset-0 h-full w-full object-contain" x-on:error="$el.classList.add('hidden')">
                        </template>
                        <button type="button" @click="previousPhoto" class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-white/90 p-2 opacity-0 shadow transition hover:scale-105 group-hover:opacity-100" aria-label="Foto sebelumnya"><i data-lucide="chevron-left" class="h-4 w-4"></i></button>
                        <button type="button" @click="nextPhoto" class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-white/90 p-2 opacity-0 shadow transition hover:scale-105 group-hover:opacity-100" aria-label="Foto berikutnya"><i data-lucide="chevron-right" class="h-4 w-4"></i></button>
                    </div>

                    <div class="mt-4 rounded-[18px] bg-white p-3">
                        <div class="grid grid-cols-3 gap-3">
                            <template x-for="(photo, index) in photos.slice(1, 4)" :key="index">
                                <button type="button" @click="activePhoto = index + 1" class="relative aspect-[1.08/1] overflow-hidden rounded-xl bg-[#dededc] ring-offset-2 transition" :class="activePhoto === index + 1 ? 'ring-2 ring-black' : 'opacity-75 hover:opacity-100'" :title="photo.label">
                                    <img x-show="photo.url" :src="photo.url" :alt="photo.label" class="h-full w-full object-cover" x-on:error="$el.classList.add('hidden')">
                                    <span x-show="!photo.url" class="flex h-full items-center justify-center text-center text-[10px] font-semibold text-black/35">Belum ada<br>portofolio</span>
                                </button>
                            </template>
                        </div>
                    </div>
                </section>

                <section class="pt-1">
                    <p class="text-xs font-semibold uppercase tracking-[.16em] text-black/45">{{ $service->subcategory?->name ?? 'Jasa' }}</p>
                    <h1 class="mt-2 text-3xl font-semibold tracking-[-.06em] sm:text-[34px]">{{ $service->title }}</h1>
                    <p class="mt-1.5 text-sm font-semibold">Rp{{ number_format($service->price, 0, ',', '.') }}</p>

                    <div class="mt-5 overflow-hidden rounded-[15px] border border-black/15 bg-[#f7f7f6]">
                        <button type="button" @click="descOpen = !descOpen" class="flex w-full items-center justify-between px-4 py-3 text-left text-xs font-bold">
                            Deskripsi <i data-lucide="chevron-up" class="h-4 w-4 transition-transform duration-300" :class="descOpen ? '' : 'rotate-180'"></i>
                        </button>
                        <div x-show="descOpen" x-transition class="px-4 pb-4 text-xs leading-[1.55] text-black/60">{{ $service->description }}</div>
                    </div>

                    <div class="mt-5">
                        <p class="text-[11px] font-bold uppercase tracking-[.12em] text-black/55">Tentang jasa</p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            <span class="rounded-full border border-black/15 px-3 py-1 text-[10px] font-medium">{{ $service->subcategory?->category?->name ?? 'SkillHub' }}</span>
                            <span class="rounded-full border border-black/15 px-3 py-1 text-[10px] font-medium">{{ $service->subcategory?->name ?? 'Jasa siswa' }}</span>
                            <span class="rounded-full border border-black/15 px-3 py-1 text-[10px] font-medium">{{ $service->orders_count }} kali terjual</span>
                            <span class="rounded-full border border-black/15 px-3 py-1 text-[10px] font-medium">{{ $reviewCount }} review</span>
                        </div>
                    </div>

                    <div class="mt-5 flex items-center gap-2">
                        <span class="text-sm font-bold">{{ $service->seller?->name ?? 'Siswa SkillHub' }}</span>
                        <span class="h-1 w-1 rounded-full bg-black/30"></span>
                        <span class="text-xs text-black/50">Penyedia jasa</span>
                    </div>

                    @auth
                        @if (auth()->id() !== $service->user_id)
                            <div class="mt-6 grid grid-cols-[1fr_1fr] gap-3">
                                <form method="POST" action="{{ route('conversations.start', $service) }}">@csrf<button type="submit" class="w-full rounded-full border border-black bg-transparent px-4 py-3 text-xs font-bold transition hover:bg-white" title="Buka ruang chat untuk mendiskusikan harga">Diskusikan harga?</button></form>
                                <form method="POST" action="{{ route('orders.store') }}" x-data="{ sending: false }" @submit="sending = true">@csrf<input type="hidden" name="service_id" value="{{ $service->id }}"><button :disabled="sending" class="flex w-full items-center justify-center gap-2 rounded-full bg-[#171717] px-4 py-3 text-xs font-bold text-white transition hover:bg-black disabled:opacity-70"><i x-show="sending" x-cloak data-lucide="loader-circle" class="h-3.5 w-3.5 animate-spin"></i><span x-text="sending ? 'Memproses...' : 'Pesan sekarang'"></span></button></form>
                            </div>
                        @else
                            <div class="mt-6 grid grid-cols-[1fr_1fr] gap-3">
                                <a href="{{ route('conversations.seller-index') }}" class="flex w-full items-center justify-center gap-2 rounded-full border border-black bg-transparent px-4 py-3 text-xs font-bold transition hover:bg-white" title="Buka percakapan dengan pembeli">
                                    <i data-lucide="message-circle" class="h-3.5 w-3.5"></i> Buka chat
                                </a>
                                <div class="flex items-center justify-center rounded-full bg-black/5 px-4 py-3 text-center text-xs font-bold text-black/50">Ini jasa milikmu</div>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="mt-6 block rounded-full bg-[#171717] px-4 py-3 text-center text-xs font-bold text-white">Masuk untuk memesan</a>
                    @endauth

                    <div class="mt-8 overflow-hidden rounded-[15px] border border-black/15 bg-[#f7f7f6]">
                        <button type="button" @click="workflowOpen = !workflowOpen" class="flex w-full items-center justify-between px-4 py-3 text-left text-xs font-bold">
                            Cara kerja SkillHub <i data-lucide="chevron-up" class="h-4 w-4 transition-transform duration-300" :class="workflowOpen ? '' : 'rotate-180'"></i>
                        </button>
                        <div x-show="workflowOpen" x-transition class="px-4 pb-4">
                            <div class="space-y-3 text-[11px]">
                                <div class="flex gap-3">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-black text-[10px] font-bold text-white">1</span>
                                    <div>
                                        <p class="font-bold text-black">Pesan & bayar</p>
                                        <p class="text-black/60">Dana ditahan aman di escrow.</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-black text-[10px] font-bold text-white">2</span>
                                    <div>
                                        <p class="font-bold text-black">Diskusikan</p>
                                        <p class="text-black/60">Sepakati detail dengan seller.</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-black text-[10px] font-bold text-white">3</span>
                                    <div>
                                        <p class="font-bold text-black">Terima hasil</p>
                                        <p class="text-black/60">Seller mengirim hasil jasa.</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-black text-[10px] font-bold text-white">4</span>
                                    <div>
                                        <p class="font-bold text-black">Beri review</p>
                                        <p class="text-black/60">Dana cair setelah disetujui.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <section class="mt-12 grid gap-8 border-t border-black/15 pt-9 lg:grid-cols-[1.45fr_.85fr]">
                @auth
                    @php
                        $userReview = $service->reviews->firstWhere('user_id', auth()->id());
                    @endphp
                    
                    @if($userReview)
                        <div class="col-span-full">
                            <div class="rounded-xl bg-green-50 border border-green-200 p-4">
                                <p class="text-sm font-semibold text-green-800">✓ Anda sudah memberi review</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="font-bold">{{ $userReview->rating }}/5</span>
                                    @if($userReview->is_verified_buyer)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Verified Buyer</span>
                                    @endif
                                </div>
                                @if($userReview->comment)
                                    <p class="mt-2 text-sm text-gray-700">{{ $userReview->comment }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="col-span-full">
                            <form method="POST" action="{{ route('reviews.store', $service) }}" class="rounded-xl bg-gray-50 border border-gray-200 p-4" x-data="{ rating: 0, hoverRating: 0 }">
                                @csrf
                                <h3 class="font-bold mb-3">Beri Review</h3>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Rating</label>
                                    <div class="flex items-center gap-1">
                                        <template x-for="star in 5" :key="star">
                                            <button type="button" @click="rating = star" @mouseenter="hoverRating = star" @mouseleave="hoverRating = 0" class="group p-1 transition-transform duration-150 hover:scale-110">
                                                <svg class="h-7 w-7 transition-all duration-200" :class="(hoverRating || rating) >= star ? 'text-amber-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        </template>
                                        <span class="ml-2 text-sm font-medium text-gray-600" x-text="rating ? rating + '/5' : 'Pilih rating'"></span>
                                    </div>
                                    <input type="hidden" name="rating" :value="rating" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="block text-sm font-medium mb-1">Komentar (opsional)</label>
                                    <textarea name="comment" rows="3" maxlength="500" 
                                        class="w-full rounded border-gray-300 px-3 py-2 text-sm transition-all duration-200 focus:border-black focus:ring-1 focus:ring-black" 
                                        placeholder="Ceritakan pengalaman Anda..."></textarea>
                                </div>
                                
                                <button type="submit" class="w-full bg-black text-white px-4 py-2.5 rounded font-medium hover:bg-gray-800 transition-all duration-200 hover:shadow-lg">
                                    Kirim Review
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="col-span-full">
                        <div class="rounded-xl bg-blue-50 border border-blue-200 p-4 text-center">
                            <p class="text-sm text-blue-800">
                                <a href="{{ route('login') }}" class="font-semibold underline">Login</a> untuk memberi review
                            </p>
                        </div>
                    </div>
                @endauth
                
                <div class="lg:col-span-1">
                    <h2 class="text-xl font-semibold tracking-[-.04em]">Rating & Reviews</h2>
                    <div class="mt-4 grid grid-cols-[auto_1fr] items-center gap-x-7">
                        <div><p class="text-6xl font-semibold tracking-[-.1em]">{{ $averageRating }}</p><p class="mt-1 text-[10px] text-black/45">({{ $reviewCount }} review)</p></div>
                        <div class="space-y-1.5">
                            <template x-for="rating in [5,4,3,2,1]" :key="rating"><div class="grid grid-cols-[14px_1fr] items-center gap-2 text-[10px]"><span><i data-lucide="star" class="mr-0.5 inline h-3 w-3 fill-amber-400 text-amber-400"></i><span x-text="rating"></span></span><span class="h-1 rounded-full bg-black/10"><span class="block h-1 rounded-full bg-black transition-all duration-500" :style="'width:' + percentage(rating) + '%' "></span></span></div></template>
                        </div>
                    </div>
                </div>
                <div x-data="reviewPanel(@js($reviewData))" class="lg:col-span-1 border-t border-black/15 pt-6 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold tracking-[-.04em]">Review jasa</h2>
                        <div class="flex gap-1 flex-wrap">
                            <button @click="filter = 0" :class="filter === 0 ? 'bg-black text-white' : 'bg-white'" class="rounded-full border border-black/15 px-2.5 py-1 text-[10px] font-bold transition hover:bg-gray-100">Semua</button>
                            <button @click="filter = 5" :class="filter === 5 ? 'bg-black text-white' : 'bg-white'" class="rounded-full border border-black/15 px-2.5 py-1 text-[10px] font-bold transition hover:bg-gray-100">5★</button>
                            <button @click="filter = 4" :class="filter === 4 ? 'bg-black text-white' : 'bg-white'" class="rounded-full border border-black/15 px-2.5 py-1 text-[10px] font-bold transition hover:bg-gray-100">4★</button>
                            <button @click="filter = 3" :class="filter === 3 ? 'bg-black text-white' : 'bg-white'" class="rounded-full border border-black/15 px-2.5 py-1 text-[10px] font-bold transition hover:bg-gray-100">3★</button>
                            <button @click="filter = 2" :class="filter === 2 ? 'bg-black text-white' : 'bg-white'" class="rounded-full border border-black/15 px-2.5 py-1 text-[10px] font-bold transition hover:bg-gray-100">2★</button>
                            <button @click="filter = 1" :class="filter === 1 ? 'bg-black text-white' : 'bg-white'" class="rounded-full border border-black/15 px-2.5 py-1 text-[10px] font-bold transition hover:bg-gray-100">1★</button>
                        </div>
                    </div>
                    <div class="mt-4 max-h-44 space-y-3 overflow-y-auto pr-1">
                        <template x-for="review in visible" :key="review.id"><article class="rounded-xl bg-white p-3" x-transition><div class="flex items-center justify-between"><p class="text-xs font-bold" x-text="review.buyer"></p><span class="text-xs font-bold" x-text="'★'.repeat(review.rating)"></span></div><p x-show="review.comment" x-text="review.comment" class="mt-1 text-xs leading-5 text-black/55"></p><div class="mt-1" x-show="review.verified"><span class="text-[9px] bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded">✓ Verified Buyer</span></div></article></template>
                        <p x-show="visible.length === 0" class="rounded-xl bg-white p-4 text-xs text-black/45">Belum ada review untuk filter ini.</p>
                    </div>
                </div>
            </section>
        </main>

        <div x-show="modalOpen" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-5" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-black/45 backdrop-blur-sm" @click="modalOpen = false"></div>
            <div x-show="modalOpen" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-md rounded-[20px] bg-white p-6 shadow-2xl">
                <button type="button" @click="modalOpen = false" class="absolute right-4 top-4 text-black/40 hover:text-black"><i data-lucide="x" class="h-5 w-5"></i></button>
                <h2 class="text-xl font-semibold tracking-[-.04em]">Diskusikan harga?</h2>
                <p class="mt-2 text-xs leading-5 text-black/55">Pesanmu akan diteruskan ke {{ $service->seller?->name ?? 'penyedia jasa' }} dalam ruang pesanan.</p>
                <form method="POST" action="{{ route('orders.store') }}" class="mt-5" x-data="{ sending: false }" @submit="sending = true">@csrf<input type="hidden" name="service_id" value="{{ $service->id }}"><textarea name="message" required maxlength="1000" placeholder="Halo, saya ingin mendiskusikan harga jasa ini..." class="h-28 w-full resize-none rounded-xl border border-black/15 bg-[#f6f6f5] p-3 text-xs outline-none focus:border-black"></textarea><button :disabled="sending" class="mt-3 flex w-full items-center justify-center gap-2 rounded-full bg-black px-4 py-3 text-xs font-bold text-white disabled:opacity-70"><i x-show="sending" x-cloak data-lucide="loader-circle" class="h-3.5 w-3.5 animate-spin"></i><span x-text="sending ? 'Mengirim...' : 'Kirim pesan'"></span></button></form>
            </div>
        </div>

        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
            <div class="absolute inset-0 bg-black/40" @click="sidebarOpen = false"></div>
            <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" class="relative h-full w-72 bg-white p-5">
                <div class="flex items-center justify-between">
                    <span class="text-lg font-bold">SkillHub</span>
                    <button type="button" @click="sidebarOpen = false" aria-label="Tutup menu"><i data-lucide="x" class="h-5 w-5"></i></button>
                </div>
                <nav class="mt-7 space-y-1 text-sm font-semibold">
                    <a href="{{ route('home') }}" class="block rounded-xl px-3 py-3 hover:bg-black/5">Beranda</a>
                    <a href="{{ route('services.index') }}" class="block rounded-xl px-3 py-3 hover:bg-black/5">Marketplace</a>
                    @auth
                        <a href="{{ route('orders.index') }}" class="block rounded-xl px-3 py-3 hover:bg-black/5">Pesanan</a>
                    @endauth
                </nav>
            </aside>
        </div>

        <div x-show="toast" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-3 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="fixed bottom-5 right-5 z-[60] max-w-sm rounded-xl border border-black/10 bg-white px-4 py-3 text-xs font-semibold shadow-xl" x-text="toast"></div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('productPage', (photos, reviews, flash) => ({
                photos, reviews, activePhoto: 0, modalOpen: false, sidebarOpen: false, profileOpen: false, descOpen: true, workflowOpen: true, toast: flash,
                init() { lucide.createIcons(); if (this.toast) setTimeout(() => this.toast = null, 4500); },
                nextPhoto() { this.activePhoto = (this.activePhoto + 1) % this.photos.length; },
                previousPhoto() { this.activePhoto = (this.activePhoto + this.photos.length - 1) % this.photos.length; },
                closeAll() { this.modalOpen = false; this.sidebarOpen = false; this.profileOpen = false; },
            }));
            Alpine.data('reviewPanel', (reviews) => ({
                reviews, filter: 0,
                get visible() { return this.filter ? this.reviews.filter((review) => review.rating === this.filter) : this.reviews; },
                percentage(rating) { return this.reviews.length ? (this.reviews.filter((review) => review.rating === rating).length / this.reviews.length) * 100 : 0; },
            }));
        });
    </script>
</body>
</html>