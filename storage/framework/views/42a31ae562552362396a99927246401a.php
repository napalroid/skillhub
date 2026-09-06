<?php if (isset($component)) { $__componentOriginalaf25f15ef26fc3d179956a9918eaad4d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaf25f15ef26fc3d179956a9918eaad4d = $attributes; } ?>
<?php $component = App\View\Components\Layouts\Admin::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\Admin::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <?php
        $filterLabels = [
            'all' => 'Semua',
            'masuk' => 'Uang Masuk',
            'keluar' => 'Uang Keluar',
            'pending' => 'Pending Konfirmasi',
            'expired' => 'Kadaluarsa',
        ];
        $sortOptions = [
            'latest' => 'Terbaru',
            'oldest' => 'Terlama',
            'amount_desc' => 'Jumlah Tertinggi',
            'amount_asc' => 'Jumlah Terendah',
        ];
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expiringSoon > 0): ?>
        <div class="admin-card bg-[#FFF3CD] border-[#FFE69C] p-4 mb-6" data-stagger-item>
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-[#856404] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <div>
                    <p class="font-heading font-bold text-sm text-[#856404]">Perhatian!</p>
                    <p class="text-xs text-[#856404] mt-1">Ada <?php echo e($expiringSoon); ?> transaksi yang akan kadaluarsa dalam 1 jam! Segera konfirmasi.</p>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="admin-card p-6 lg:p-7" data-stagger-item>
        <div class="flex flex-col gap-6">
            <div class="max-w-[62ch]">
                <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Riwayat Escrow</h1>
                <p class="text-sm text-[#555555] mt-2 leading-relaxed">
                    Tracking saldo escrow yang tertahan. Saat buyer membayar QRIS, saldo masuk sebagai <span class="font-bold text-black">Pending</span>.
                    Admin konfirmasi saldo masuk → status jadi <span class="font-bold text-black">Selesai</span> dan escrow bertambah.
                    Saat dana dicairkan ke seller, escrow berkurang.
                </p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-4 py-3">
                    <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Saldo Escrow</p>
                    <p class="font-heading font-bold text-lg text-black mt-1">Rp<?php echo e(number_format($currentBalance, 0, ',', '.')); ?></p>
                </div>
                <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-4 py-3">
                    <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Pending</p>
                    <p class="font-heading font-bold text-lg text-[#E4002B] mt-1">Rp<?php echo e(number_format($pendingBalance, 0, ',', '.')); ?></p>
                </div>
                <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-4 py-3">
                    <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Masuk Hari Ini</p>
                    <p class="font-heading font-bold text-lg text-[#2C9F45] mt-1">Rp<?php echo e(number_format($todayIn, 0, ',', '.')); ?></p>
                </div>
                <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-4 py-3">
                    <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Keluar Hari Ini</p>
                    <p class="font-heading font-bold text-lg text-[#555555] mt-1">Rp<?php echo e(number_format($todayOut, 0, ',', '.')); ?></p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['all' => 'Semua', 'masuk' => 'Uang Masuk', 'keluar' => 'Uang Keluar', 'pending' => 'Pending', 'expired' => 'Expired', 'proses_tahan' => 'Proses Tahan Dana', 'cair' => 'Cair']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <a href="<?php echo e(route('admin.escrow.index', ['filter' => $key, 'sort' => $sort])); ?>"
                       class="px-4 py-2 rounded-full text-[10px] font-heading font-bold uppercase tracking-wider border transition-colors <?php echo e($filter === $key ? 'bg-black border-black text-white' : 'bg-white border-[#DDDDDD] text-[#555555] hover:border-black hover:text-black'); ?>">
                        <?php echo e($label); ?> 
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($key === 'proses_tahan'): ?>
                            <span class="opacity-60">(<?php echo e($counts['pending'] ?? 0); ?>)</span>
                        <?php elseif($key === 'cair'): ?>
                            <span class="opacity-60">(<?php echo e($counts['keluar'] ?? 0); ?>)</span>
                        <?php else: ?>
                            <span class="opacity-60">(<?php echo e($counts[$key] ?? 0); ?>)</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </div>

    <div class="admin-card mt-6" data-stagger-container>
        <div class="flex flex-col gap-3 border-b border-[#DDDDDD] p-5 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-heading font-semibold text-sm text-black uppercase tracking-tight">Transaksi Escrow</h2>

            <form method="GET" action="<?php echo e(route('admin.escrow.index')); ?>" class="flex items-center gap-2">
                <input type="hidden" name="filter" value="<?php echo e($filter); ?>">
                <label for="sort" class="text-[10px] font-heading font-bold uppercase tracking-wider text-[#999999]">Urutkan</label>
                <select name="sort" id="sort" onchange="this.form.submit()"
                        class="rounded-full border border-[#DDDDDD] bg-white px-3 py-1.5 text-xs text-black focus:border-black focus:outline-none">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sortOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($value); ?>" <?php if($sort === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            </form>
        </div>

        <div class="overflow-hidden">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transactions->isNotEmpty()): ?>
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th class="w-24">Tipe</th>
                                <th class="w-32">Jumlah</th>
                                <th class="w-32">Saldo Sebelum</th>
                                <th class="w-32">Saldo Sesudah</th>
                                <th>Keterangan</th>
                                <th class="w-32">Batas Waktu</th>
                                <th class="w-28">Status</th>
                                <th class="w-[200px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php
                                    $order = $transaction->order ?? $transaction->payment?->order;
                                    $service = $order?->service;
                                    $buyer = $order?->buyer;
                                ?>
                                <tr class="row-enter">
                                    <td>
                                        <p class="text-xs text-[#555555]"><?php echo e($transaction->created_at->format('d M Y')); ?></p>
                                        <p class="text-[10px] text-[#999999]"><?php echo e($transaction->created_at->format('H:i')); ?></p>
                                    </td>
                                    <td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transaction->type === 'in'): ?>
                                            <span class="badge badge-success">Masuk</span>
                                        <?php else: ?>
                                            <span class="badge" style="background:#555;color:#fff;border-color:#555;">Keluar</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="font-heading font-bold text-sm <?php echo e($transaction->type === 'in' ? 'text-[#2C9F45]' : 'text-[#555555]'); ?>">
                                            <?php echo e($transaction->type === 'in' ? '+' : '-'); ?>Rp<?php echo e(number_format($transaction->amount, 0, ',', '.')); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-xs text-[#555555]">Rp<?php echo e(number_format($transaction->balance_before, 0, ',', '.')); ?></span>
                                    </td>
                                    <td>
                                        <span class="text-xs text-black font-medium">Rp<?php echo e(number_format($transaction->balance_after, 0, ',', '.')); ?></span>
                                    </td>
                                    <td>
                                        <p class="text-xs text-[#555555]"><?php echo e($transaction->description ?? '—'); ?></p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order): ?>
                                            <p class="text-[10px] text-[#999999] mt-1">
                                                Order #<?php echo e($order->id); ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service): ?>
                                                    · <?php echo e(\Illuminate\Support\Str::limit($service->title, 30)); ?>

                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </p>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($buyer): ?>
                                                <p class="text-[10px] text-[#999999]">Buyer: <?php echo e($buyer->name); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transaction->expires_at): ?>
                                            <p class="text-xs text-[#555555]"><?php echo e($transaction->expires_at->format('d M H:i')); ?></p>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transaction->isPending()): ?>
                                                <?php
                                                    $remaining = $transaction->timeRemaining();
                                                    $hours = floor($remaining / 3600);
                                                    $minutes = floor(($remaining % 3600) / 60);
                                                ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($remaining > 0): ?>
                                                    <p class="text-[10px] font-bold <?php echo e($remaining < 3600 ? 'text-[#E4002B]' : 'text-[#FF8C00]'); ?>">
                                                        Sisa: <?php echo e($hours); ?>j <?php echo e($minutes); ?>m
                                                    </p>
                                                <?php else: ?>
                                                    <p class="text-[10px] font-bold text-[#E4002B]">LEWAT</p>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-xs text-[#999999]">—</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transaction->status === 'pending'): ?>
                                            <span class="badge" style="background:#FF8C00;color:#fff;border-color:#FF8C00;">Pending</span>
                                        <?php elseif($transaction->status === 'completed'): ?>
                                            <span class="badge badge-success">Selesai</span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transaction->processed_at): ?>
                                                <p class="text-[9px] text-[#999999] mt-1"><?php echo e($transaction->processed_at->format('d M H:i')); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php elseif($transaction->status === 'expired'): ?>
                                            <span class="badge badge-error">Expired</span>
                                        <?php elseif($transaction->status === 'cancelled'): ?>
                                            <span class="badge badge-neutral">Batal</span>
                                        <?php else: ?>
                                            <span class="badge badge-neutral"><?php echo e($transaction->status); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transaction->isPending()): ?>
                                                <form action="<?php echo e(route('admin.escrow.confirm', $transaction)); ?>" method="POST" onsubmit="return confirm('Konfirmasi bahwa saldo sudah masuk ke rekening?')">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="w-full btn-primary text-[10px] px-4 py-2 justify-center">Konfirmasi</button>
                                                </form>
                                                <form action="<?php echo e(route('admin.escrow.reject', $transaction)); ?>" method="POST" onsubmit="return confirm('Tolak transaksi ini?')">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="w-full btn-danger text-[10px] px-2 py-1.5 justify-center">Tolak</button>
                                                </form>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order): ?>
                                                <a href="<?php echo e(route('orders.show', $order)); ?>" class="btn-ghost text-[10px] px-2 py-1.5 justify-center border border-[#DDDDDD]">Lihat Pesanan</a>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-[#DDDDDD] flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-[#999999]">
                        Menampilkan <?php echo e($transactions->firstItem()); ?>–<?php echo e($transactions->lastItem()); ?> dari <?php echo e($transactions->total()); ?> transaksi (filter: <?php echo e($filterLabels[$filter] ?? $filter); ?>)
                    </p>
                    <div class="flex items-center gap-2">
                        <?php echo e($transactions->appends(request()->query())->links('vendor.pagination.tailwind')); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                    </div>
                    <p class="text-xs font-heading font-bold uppercase tracking-wide text-[#999999]">Tidak ada transaksi pada filter ini.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaf25f15ef26fc3d179956a9918eaad4d)): ?>
<?php $attributes = $__attributesOriginalaf25f15ef26fc3d179956a9918eaad4d; ?>
<?php unset($__attributesOriginalaf25f15ef26fc3d179956a9918eaad4d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaf25f15ef26fc3d179956a9918eaad4d)): ?>
<?php $component = $__componentOriginalaf25f15ef26fc3d179956a9918eaad4d; ?>
<?php unset($__componentOriginalaf25f15ef26fc3d179956a9918eaad4d); ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/admin/escrow/index.blade.php ENDPATH**/ ?>