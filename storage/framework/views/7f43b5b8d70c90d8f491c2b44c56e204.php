<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya | SkillHub</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>
    
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

    <?php $__env->startPush('styles'); ?>
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
    <?php $__env->stopPush(); ?>
</head>
<body class="min-h-screen bg-white pt-16 text-[#111111] antialiased">
    <!-- ====== NAVBAR ====== -->
    <div id="skillhub-staggered-menu"
         data-home="<?php echo e(route('home')); ?>"
         data-marketplace="<?php echo e(route('services.index')); ?>"
         data-chat="<?php echo e(route('conversations.seller-index')); ?>"
         data-login="<?php echo e(route('login')); ?>"
         data-register="<?php echo e(route('register')); ?>"
         data-authenticated="<?php echo e(auth()->check() ? 'true' : 'false'); ?>"
         data-user-id="<?php echo e(auth()->id() ?? ''); ?>"
         data-user-name="<?php echo e(auth()->user()?->name ?? ''); ?>"
         data-avatar-url="<?php echo e(auth()->user()?->avatar_url ?? ''); ?>"
          data-profile-url="<?php echo e(route('profile.edit')); ?>"
          data-services-my="<?php echo e(auth()->check() ? route('services.my') : '#'); ?>"
          data-logout-url="<?php echo e(route('logout')); ?>"
          data-notifications-url="<?php echo e(auth()->check() ? route('notifications.index') : ''); ?>"
          data-notifications-read-all-url="<?php echo e(auth()->check() ? route('notifications.read-all') : ''); ?>"
           data-dompet="<?php echo e(route('wallet.index')); ?>"
           data-pesanan="<?php echo e(route('orders.index')); ?>"
           data-csrf-token="<?php echo e(csrf_token()); ?>"
          data-is-admin="<?php echo e(auth()->check() && auth()->user()->isAdmin() ? 'true' : 'false'); ?>"
          data-admin-dashboard="<?php echo e(auth()->check() && auth()->user()->isAdmin() ? route('admin.dashboard') : ''); ?>"></div>
    <script id="skillhub-account-notifications-data" type="application/json"><?php echo json_encode($accountNotifications ?? collect(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT, 512) ?></script>

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        
        <div class="mb-10">
            <p class="text-[11px] font-bold uppercase tracking-[.16em] text-black/45 mb-2">Transaksi</p>
            <div class="w-10 h-px bg-black mb-4"></div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-black leading-[1.1] uppercase mb-4">
                Pesanan
            </h1>
            <p class="text-base sm:text-lg text-slate-600 max-w-xl">
                Lacak semua pesanan jasa di satu tempat. Cek status, kelola progres, dan selesaikan transaksi dengan mudah.
            </p>
        </div>

        
        <div class="grid grid-cols-2 gap-4 mb-10 md:grid-cols-4">
            <a href="<?php echo e(route('orders.index', ['status' => 'all', 'role' => $role])); ?>"
               class="group border border-[#080808] bg-white p-6 transition-colors hover:bg-black hover:text-white sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-black/40 transition-colors group-hover:text-white/60">Total Pesanan</p>
                <p class="mt-3 text-4xl font-black tracking-tighter sm:text-5xl"><?php echo e(number_format($totalOrders)); ?></p>
            </a>
            <a href="<?php echo e(route('orders.index', ['status' => 'completed', 'role' => $role])); ?>"
               class="group border border-[#080808] bg-white p-6 transition-colors hover:bg-black hover:text-white sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-black/40 transition-colors group-hover:text-white/60">Selesai</p>
                <p class="mt-3 text-4xl font-black tracking-tighter sm:text-5xl"><?php echo e(number_format($completedCount)); ?></p>
            </a>
            <a href="<?php echo e(route('orders.index', ['status' => 'in_progress', 'role' => $role])); ?>"
               class="group border border-[#080808] bg-white p-6 transition-colors hover:bg-black hover:text-white sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-black/40 transition-colors group-hover:text-white/60">Berlangsung</p>
                <p class="mt-3 text-4xl font-black tracking-tighter sm:text-5xl"><?php echo e(number_format($inProgressCount)); ?></p>
            </a>
            <a href="<?php echo e(route('orders.index', ['status' => 'pending', 'role' => $role])); ?>"
               class="group border border-[#080808] bg-white p-6 transition-colors hover:bg-black hover:text-white sm:p-8">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-black/40 transition-colors group-hover:text-white/60">Menunggu</p>
                <p class="mt-3 text-4xl font-black tracking-tighter sm:text-5xl"><?php echo e(number_format($pendingCount)); ?></p>
            </a>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            
            
            <div class="lg:col-span-8">
                
                <div class="mb-6">
                    <div class="flex flex-wrap gap-2 md:gap-3">
                        <?php
                            $filterLabels = [
                                'all' => 'Semua',
                                'pending' => 'Menunggu',
                                'processing' => 'Diproses',
                                'in_progress' => 'Berlangsung',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ];
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $filterLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <a href="<?php echo e(route('orders.index', ['status' => $key, 'role' => $role])); ?>"
                               class="px-5 py-2.5 text-sm font-bold border border-[#080808] transition-all focus:outline-none focus:ring-2 focus:ring-black/20 <?php echo e($statusFilter === $key ? 'bg-black text-white' : 'bg-white text-black hover:bg-black hover:text-white'); ?>">
                                <?php echo e($label); ?>

                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>

                
                <div class="border-t border-black">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($orders->count() > 0): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $isBuyer = $order->buyer_id === auth()->id();
                                $isSeller = $order->is_seller ?? false;
                                $counterparty = $isBuyer ? $order->service->seller : $order->buyer;
                                $counterpartyName = $counterparty?->name ?? '-';
                                
                                $statusMapping = [
                                    'menunggu_pembayaran' => ['label' => 'Menunggu Pembayaran', 'class' => 'border-dashed'],
                                    'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => 'border-dashed'],
                                    'dibayar' => ['label' => 'Dibayar', 'class' => ''],
                                    'dikerjakan' => ['label' => 'Dikerjakan', 'class' => ''],
                                    'menunggu_persetujuan' => ['label' => 'Menunggu Persetujuan', 'class' => ''],
                                    'selesai' => ['label' => 'Selesai', 'class' => ''],
                                    'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'bg-black text-white'],
                                ];
                                $statusInfo = $statusMapping[$order->status] ?? ['label' => $order->status, 'class' => ''];
                            ?>
                            
                            <div class="py-5 border-b border-slate-200 hover:bg-slate-50 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            <span class="text-xs font-bold uppercase tracking-wider <?php echo e($isBuyer ? 'text-blue-600' : 'text-green-600'); ?>">
                                                <?php echo e($isBuyer ? 'Sebagai Pembeli' : 'Sebagai Penjual'); ?>

                                            </span>
                                        </div>
                                        <p class="font-bold text-black text-base truncate">
                                            <?php echo e($order->service->title); ?>

                                        </p>
                                        <p class="text-xs text-slate-500 mt-1">
                                            <?php echo e($isBuyer ? 'Penjual' : 'Pembeli'); ?>: <?php echo e($counterpartyName); ?> · #<?php echo e(sprintf('%05d', $order->id)); ?> · <?php echo e($order->created_at->format('d M Y, H:i')); ?>

                                        </p>
                                    </div>
                                    
                                    <div class="flex items-center gap-4 sm:gap-6">
                                        <div class="text-right">
                                            <p class="text-lg font-extrabold text-black">
                                                Rp<?php echo e(number_format($order->final_price, 0, ',', '.')); ?>

                                            </p>
                                            <span class="inline-block mt-1 text-[10px] px-2 py-0.5 border border-black <?php echo e($statusInfo['class']); ?>">
                                                <?php echo e($statusInfo['label']); ?>

                                            </span>
                                        </div>
                                        
                                        <a href="<?php echo e(route('orders.show', $order)); ?>"
                                           class="text-sm font-bold text-black hover:underline whitespace-nowrap">
                                            Detail →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                        
                        <div class="pt-6">
                            <?php echo e($orders->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="py-12 text-center">
                            <p class="text-lg font-bold text-black mb-2">Belum ada pesanan</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($statusFilter === 'all'): ?>
                                <p class="text-sm text-slate-500 mb-6">Pesanan kamu akan muncul di sini setelah melakukan transaksi jasa.</p>
                                <a href="<?php echo e(route('services.index')); ?>"
                                   class="inline-block px-6 py-3 bg-black hover:bg-slate-800 text-white text-sm font-bold uppercase tracking-wide transition">
                                    Jelajahi Jasa
                                </a>
                            <?php else: ?>
                                <p class="text-sm text-slate-500 mb-6">Belum ada pesanan dengan status "<?php echo e($filterLabels[$statusFilter]); ?>".</p>
                                <a href="<?php echo e(route('orders.index')); ?>"
                                   class="inline-block px-6 py-3 border-2 border-black hover:bg-black hover:text-white text-black text-sm font-bold uppercase tracking-wide transition">
                                    Lihat Semua
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="lg:col-span-4">
                
                <div class="mb-8">
                    <h2 class="text-xs font-bold uppercase tracking-[.14em] text-black/60 mb-4">Tampilkan sebagai</h2>
                    <div class="flex border border-[#080808]">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['all' => 'Semua', 'buyer' => 'Pembeli', 'seller' => 'Penjual']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <a href="<?php echo e(route('orders.index', ['role' => $key, 'status' => $statusFilter])); ?>"
                               class="flex-1 text-center px-3 py-2.5 text-xs font-bold uppercase tracking-[.06em] transition
                                      <?php echo e($role === $key ? 'bg-black text-white' : 'bg-white text-black hover:bg-black hover:text-white'); ?>

                                      <?php echo e($key !== 'all' ? 'border-l border-[#080808]' : ''); ?>">
                                <?php echo e($label); ?>

                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>

                
                <div class="border-t border-slate-200 pt-6">
                    <h2 class="text-xs font-bold uppercase tracking-[.14em] text-black/60 mb-4">Tips</h2>
                    <p class="text-sm text-slate-600 leading-relaxed mb-4">
                        Sebagai pembeli, kamu bisa membayar, mengunggah brief, dan menyetujui hasil jasa.
                    </p>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Sebagai penjual, kerjakan pesanan, upload hasil, dan terima pembayaran otomatis setelah disetujui.
                    </p>
                </div>

                
                <div class="border-t border-slate-200 pt-6 mt-6">
                    <h2 class="text-xs font-bold uppercase tracking-[.14em] text-black/60 mb-4">Ringkasan</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Total transaksi</span>
                            <span class="font-bold"><?php echo e(number_format($totalOrders)); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Selesai</span>
                            <span class="font-bold text-green-600"><?php echo e(number_format($completedCount)); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Berlangsung</span>
                            <span class="font-bold text-blue-600"><?php echo e(number_format($inProgressCount)); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Menunggu</span>
                            <span class="font-bold text-amber-600"><?php echo e(number_format($pendingCount)); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ====== FOOTER ====== -->
    <?php if (isset($component)) { $__componentOriginal222c87a019257fb1d70ae0ff46ab02e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal222c87a019257fb1d70ae0ff46ab02e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal222c87a019257fb1d70ae0ff46ab02e1)): ?>
<?php $attributes = $__attributesOriginal222c87a019257fb1d70ae0ff46ab02e1; ?>
<?php unset($__attributesOriginal222c87a019257fb1d70ae0ff46ab02e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal222c87a019257fb1d70ae0ff46ab02e1)): ?>
<?php $component = $__componentOriginal222c87a019257fb1d70ae0ff46ab02e1; ?>
<?php unset($__componentOriginal222c87a019257fb1d70ae0ff46ab02e1); ?>
<?php endif; ?>

    <!-- ====== JAVASCRIPT ====== -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/orders/index.blade.php ENDPATH**/ ?>