<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya | SkillHub</title>
    
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

        /* Mobile Menu Styles */
        .mobile-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            z-index: 50;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
        }
        
        .mobile-menu.open {
            transform: translateX(0);
        }

        /* Navigation Link Styles */
        .nav-link {
            @apply block px-6 py-4 text-lg font-bold text-gray-900 border-b border-gray-100 transition-all hover:bg-gray-50;
        }

        .nav-link:hover {
            @apply text-black;
        }

        .nav-link.active {
            @apply border-black bg-gray-50 font-extrabold;
        }

        /* Profile Dropdown Styles */
        .profile-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 40;
            display: none;
        }

        .profile-menu.open {
            display: block;
        }

        /* Profile Menu Items */
        .profile-menu-item {
            @apply block px-6 py-3 text-sm font-bold text-gray-700 border-b border-gray-100 hover:bg-gray-50;
        }

        .profile-menu-item:last-child {
            @apply border-0;
        }

        .profile-menu-item:hover {
            @apply text-black;
        }

        /* Logout Button */
        .logout-btn {
            @apply block w-full text-left px-6 py-3 text-sm font-bold text-red-600 border-b border-gray-100 hover:bg-red-50;
        }

    </style>

    @push('styles')
    <style>
        /* Mobile menu transition */
        .mobile-menu-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .mobile-menu-overlay.active {
            opacity: 1;
        }
    </style>
    @endpush
</head>
<body class="min-h-screen bg-white pt-16 text-[#111111] antialiased">
    <!-- ====== NAVBAR ====== -->
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

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- SUMMARY SECTION --}}
        <div class="grid grid-cols-2 gap-4 mb-10 md:grid-cols-4">
            <a href="{{ route('orders.index', ['status' => 'all', 'role' => $role]) }}"
               class="group border border-[#080808] bg-white p-6 transition-colors hover:bg-black hover:text-white sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-black/40 transition-colors group-hover:text-white/60">Total Pesanan</p>
                <p class="mt-3 text-4xl font-black tracking-tighter sm:text-5xl">{{ number_format($totalOrders) }}</p>
            </a>
            <a href="{{ route('orders.index', ['status' => 'completed', 'role' => $role]) }}"
               class="group border border-[#080808] bg-white p-6 transition-colors hover:bg-black hover:text-white sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-black/40 transition-colors group-hover:text-white/60">Selesai</p>
                <p class="mt-3 text-4xl font-black tracking-tighter sm:text-5xl">{{ number_format($completedCount) }}</p>
            </a>
            <a href="{{ route('orders.index', ['status' => 'in_progress', 'role' => $role]) }}"
               class="group border border-[#080808] bg-white p-6 transition-colors hover:bg-black hover:text-white sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-black/40 transition-colors group-hover:text-white/60">Berlangsung</p>
                <p class="mt-3 text-4xl font-black tracking-tighter sm:text-5xl">{{ number_format($inProgressCount) }}</p>
            </a>
            <a href="{{ route('orders.index', ['status' => 'pending', 'role' => $role]) }}"
               class="group border border-[#080808] bg-white p-6 transition-colors hover:bg-black hover:text-white sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-black/40 transition-colors group-hover:text-white/60">Menunggu</p>
                <p class="mt-3 text-4xl font-black tracking-tighter sm:text-5xl">{{ number_format($pendingCount) }}</p>
            </a>
        </div>

        {{-- FILTER SECTION --}}
        <div class="mb-8">
            <div class="mb-4 flex flex-wrap items-center gap-3">
                <span class="text-[11px] font-bold uppercase tracking-[.14em] text-black/45">Tampilkan sebagai</span>
                <div class="flex border border-[#080808]">
                    @foreach (['all' => 'Semua', 'buyer' => 'Pembeli', 'seller' => 'Penjual'] as $key => $label)
                        <a href="{{ route('orders.index', ['role' => $key, 'status' => $statusFilter]) }}"
                           class="px-4 py-2 text-xs font-bold uppercase tracking-[.06em] transition
                                  {{ $role === $key ? 'bg-black text-white' : 'bg-white text-black hover:bg-black hover:text-white' }}
                                  {{ $key !== 'all' ? 'border-l border-[#080808]' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-wrap gap-2 md:gap-3">
                @php
                    $filterLabels = [
                        'all' => 'Semua',
                        'pending' => 'Menunggu',
                        'processing' => 'Diproses',
                        'in_progress' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ];
                @endphp
                @foreach ($filterLabels as $key => $label)
                    <a href="{{ route('orders.index', ['status' => $key, 'role' => $role]) }}"
                       class="px-5 py-2.5 text-sm font-bold border border-[#080808] transition-all focus:outline-none focus:ring-2 focus:ring-black/20 {{ $statusFilter === $key ? 'bg-black text-white' : 'bg-white text-black hover:bg-black hover:text-white' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ORDER LIST SECTION --}}
        <div class="bg-white border border-[#e5e5e5] overflow-hidden min-h-[400px]">
            @if ($orders->count() > 0)
                <div class="divide-y divide-[#e5e5e5]">
                    @foreach ($orders as $order)
                        @php
                            $isBuyer = $order->buyer_id === auth()->id();
                            $isSeller = $order->is_seller ?? false;
                            $counterparty = $isBuyer ? $order->service->seller : $order->buyer;
                            $counterpartyName = $counterparty?->name ?? '-';
                            
                            // Status mapping for display
                            $statusMapping = [
                                'menunggu_pembayaran' => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-amber-100 text-amber-800'],
                                'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-orange-100 text-orange-800'],
                                'dibayar' => ['label' => 'Dibayar', 'class' => 'bg-blue-100 text-blue-800'],
                                'dikerjakan' => ['label' => 'Sedang Dikerjakan', 'class' => 'bg-blue-100 text-blue-800'],
                                'menunggu_persetujuan' => ['label' => 'Menunggu Persetujuan', 'class' => 'bg-purple-100 text-purple-800'],
                                'selesai' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-800'],
                                'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'bg-gray-200 text-gray-800'],
                            ];
                            $statusInfo = $statusMapping[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-800'];
                        @endphp
                        
                        <div class="p-6 sm:p-8 hover:bg-gray-50 transition-colors group">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                                {{-- Left side: Order info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap mb-2">
                                        <p class="font-black text-gray-900 text-lg sm:text-xl truncate">
                                            {{ $order->service->title }}
                                        </p>
                                        <span class="text-xs font-bold uppercase tracking-wider px-2 py-0.5 rounded-none {{ $isBuyer ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $isBuyer ? 'Sebagai Pembeli' : 'Sebagai Penjual' }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mt-2">
                                        <span class="font-medium">
                                            {{ $isBuyer ? 'Penjual' : 'Pembeli' }}: {{ $counterpartyName }}
                                        </span>
                                        <span class="text-gray-400">•</span>
                                        <span class="font-medium">Pesanan #{{ sprintf('%05d', $order->id) }}</span>
                                        <span class="text-gray-400">•</span>
                                        <span class="font-medium text-gray-900">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</span>
                                    </div>

                                    <span class="inline-block mt-3 text-xs px-3 py-1 rounded-none {{ $statusInfo['class'] }}">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                </div>

                                {{-- Right side: Price and Action --}}
                                <div class="flex flex-col md:items-end md:justify-center gap-3 min-w-[180px]">
                                    <p class="text-2xl sm:text-3xl font-black text-black">
                                        Rp{{ number_format($order->final_price, 0, ',', '.') }}
                                    </p>

                                    @php
                                        $actions = [];
                                        
                                        // Buyer actions
                                        if ($isBuyer) {
                                            if ($order->status === 'menunggu_pembayaran') {
                                                $actions[] = [
                                                    'label' => 'Bayar Sekarang',
                                                    'url' => route('orders.payment.show', $order),
                                                    'primary' => true
                                                ];
                                            } elseif ($order->status === 'menunggu_persetujuan') {
                                                $actions[] = [
                                                    'label' => 'Selesaikan',
                                                    'form' => true,
                                                    'action' => route('order-files.approve', $order),
                                                    'confirm' => 'Yakin hasil sudah sesuai? Pesanan akan selesai & dana cair otomatis 1 jam kemudian.'
                                                ];
                                            }
                                        }
                                        
                                        // Seller actions
                                        if ($isSeller) {
                                            if ($order->status === 'dibayar') {
                                                $actions[] = [
                                                    'label' => 'Mulai Kerjakan',
                                                    'form' => true,
                                                    'action' => route('orders.start-work', $order)
                                                ];
                                            } elseif (in_array($order->status, ['dibayar', 'dikerjakan'])) {
                                                $actions[] = [
                                                    'label' => 'Upload Hasil',
                                                    'href' => route('orders.show', $order) . '#upload-results'
                                                ];
                                            } elseif ($order->status === 'menunggu_persetujuan') {
                                                $actions[] = [
                                                    'label' => 'Hasil Dikirim',
                                                    'disabled' => true
                                                ];
                                            }
                                        }

                                        // Common actions
                                        $actions[] = [
                                            'label' => 'Detail',
                                            'href' => route('orders.show', $order)
                                        ];

                                        // Review CTA for completed orders by buyer
                                        if ($isBuyer && $order->status === 'selesai' && ! $order->review) {
                                            $actions[] = [
                                                'label' => 'Beri Ulasan',
                                                'href' => route('orders.show', $order) . '#review-section'
                                            ];
                                        }

                                        // Remove duplicate "Detail" action if already present
                                        $hasDetail = false;
                                        $finalActions = [];
                                        foreach ($actions as $action) {
                                            if ($action['label'] === 'Detail' && $hasDetail) {
                                                continue;
                                            }
                                            if ($action['label'] === 'Detail') {
                                                $hasDetail = true;
                                            }
                                            $finalActions[] = $action;
                                        }
                                        $actions = $finalActions;
                                    @endphp

                                    <div class="flex flex-col sm:flex-row gap-2">
                                        @foreach ($actions as $action)
                                            @if ($action['form'] ?? false)
                                                <form method="POST" action="{{ $action['action'] }}" 
                                                      onsubmit="return confirm('{{ $action['confirm'] ?? '' }}')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="{{ $action['primary'] ?? false ? 'bg-black text-white' : 'bg-white text-black border border-[#080808]' }} 
                                                               px-4 py-2 text-sm font-bold transition active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                                                        {{ $action['label'] }}
                                                    </button>
                                                </form>
                                            @elseif($action['disabled'] ?? false)
                                                <button disabled
                                                        class="bg-gray-100 text-gray-400 px-4 py-2 text-sm font-bold cursor-not-allowed">
                                                    {{ $action['label'] }}
                                                </button>
                                            @else
                                                <a href="{{ $action['href'] }}"
                                                   class="{{ $action['primary'] ?? false ? 'bg-black text-white' : 'bg-white text-black border border-[#080808]' }} 
                                                          px-4 py-2 text-sm font-bold transition hover:bg-black hover:text-white">
                                                    {{ $action['label'] }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="p-6 sm:p-8 border-t border-[#e5e5e5] bg-gray-50">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="p-12 sm:p-20 text-center">
                    <div class="max-w-md mx-auto">
                        <p class="text-2xl font-bold text-gray-500 mb-4">
                            Belum ada pesanan
                        </p>
                        @if ($statusFilter === 'all')
                            <p class="text-gray-400 mb-6">
                                Pesanan kamu akan muncul di sini setelah melakukan transaksi jasa.
                            </p>
                            <a href="{{ route('services.index') }}"
                               class="inline-block px-6 py-3 bg-black hover:bg-gray-800 text-white text-base font-bold uppercase tracking-wide transition">
                                Jelajahi Jasa
                            </a>
                        @else
                            <p class="text-gray-400 mb-6">
                                Belum ada pesanan dengan status "{{ $filterLabels[$statusFilter] }}".
                            </p>
                            <a href="{{ route('orders.index') }}"
                               class="inline-block px-6 py-3 bg-transparent hover:bg-gray-50 text-black text-base font-bold uppercase tracking-wide border-2 border-black transition">
                                Lihat Semua Pesanan
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- RESPONSIVE PAGINATION (bottom) --}}
        <div class="mt-6 md:hidden">
            {{ $orders->links() }}
        </div>
    </main>

    <!-- ====== FOOTER ====== -->
    <x-site-footer />

    <!-- ====== JAVASCRIPT ====== -->
    @stack('scripts')
</body>
</html>
