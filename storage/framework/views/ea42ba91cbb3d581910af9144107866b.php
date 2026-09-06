<?php $__env->startSection('title'); ?>
    Pesanan #<?php echo e($order->id); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $statusMap = [
        'menunggu_pembayaran' => ['bg' => '#f5f5f5', 'text' => '#555555'],
        'menunggu_verifikasi' => ['bg' => '#f5f5f5', 'text' => '#555555'],
        'menunggu_konfirmasi_harga' => ['bg' => '#F59E0B', 'text' => '#fff'],
        'menunggu_konfirmasi' => ['bg' => '#3B82F6', 'text' => '#fff'],
        'dibayar' => ['bg' => '#EDE734', 'text' => '#555555'],
        'dikonfirmasi' => ['bg' => '#3B82F6', 'text' => '#fff'],
        'dikerjakan' => ['bg' => '#000', 'text' => '#fff'],
        'menunggu_persetujuan' => ['bg' => '#2C9F45', 'text' => '#fff'],
        'selesai' => ['bg' => '#2C9F45', 'text' => '#fff'],
        'dibatalkan' => ['bg' => '#E4002B', 'text' => '#fff'],
    ];
    $statusStyle = $statusMap[$order->status] ?? ['bg' => '#f5f5f5', 'text' => '#555555'];
?>

<style>
    .order-card {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 2px;
        padding: 2rem;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        font-size: 0.625rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        border-radius: 1px;
    }
</style>

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-12">
    <div class="grid lg:grid-cols-[1fr_320px] gap-6">
        
        <div class="space-y-6">
            
             
            <div class="order-card">
                <div class="flex items-start justify-between gap-6 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex-1 min-w-0">
                        <h1 class="font-heading text-2xl font-black tracking-tight mb-3">
                            <?php echo e($order->service?->title ?? 'Pesanan #'.$order->id); ?>

                        </h1>
                        
                        <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs">
                            <span class="text-gray-500">
                                Penjual: <strong class="text-black font-bold"><?php echo e($order->service?->seller?->name ?? 'N/A'); ?></strong>
                            </span>
                            <span class="text-gray-500">
                                Pembeli: <strong class="text-black font-bold">                        <?php echo e($order->buyer?->name ?? 'N/A'); ?></strong>
                            </span>
                        </div>
                    </div>
                    
                    <span class="status-badge" style="background: <?php echo e($statusStyle['bg']); ?>; color: <?php echo e($statusStyle['text']); ?>">
                        <?php echo e(str_replace('_', ' ', $order->status)); ?>

                    </span>
                </div>
                
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Total</p>
                        <p class="font-heading text-3xl font-black tracking-tight">
                            Rp<?php echo e(number_format($order->final_price, 0, ',', '.')); ?>

                        </p>
                    </div>
                    
                    <a href="<?php echo e(route('orders.conversation', $order)); ?>"
                       class="btn-primary">
                        Hubungi <?php echo e($isBuyer ? 'Penjual' : 'Pembeli'); ?>

                    </a>
                </div>
            </div>
            
             
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->booking_data || $order->time_slot_id): ?>
                <div class="order-card">
                    <h2 class="font-heading text-sm font-black uppercase tracking-wider mb-4">Informasi Booking</h2>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->time_slot_id): ?>
                        <?php
                            $timeSlot = $order->timeSlot;
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($timeSlot): ?>
                        <div class="mb-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Waktu Booking</p>
                            <div class="flex items-center gap-2 bg-[#F0F9FF] px-4 py-3 rounded-lg border border-[#BAE6FD]">
                                <svg class="w-5 h-5 text-[#0284C7]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <div>
                                    <p class="font-bold text-[#0284C7]"><?php echo e(\Carbon\Carbon::parse($timeSlot->date)->format('l, d F Y')); ?></p>
                                    <p class="text-sm text-gray-700"><?php echo e($timeSlot->time_start); ?> - <?php echo e($timeSlot->time_end); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isSeller && $order->status !== 'dibatalkan'): ?>
                            <?php
                                $availableSlots = $order->service->timeSlots
                                    ->filter(fn($s) => $s->date >= now()->startOfDay())
                                    ->sortBy('date')
                                    ->sortBy('time_start');
                            ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($availableSlots->count() > 0): ?>
                                <form method="POST" action="<?php echo e(route('orders.reschedule', $order)); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <div class="space-y-3">
                                        <select name="time_slot_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                            <option value="">-- Pilih Slot Baru --</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($slot->id); ?>" <?php echo e($slot->id == $order->time_slot_id ? 'disabled' : ''); ?>>
                                                    <?php echo e(\Carbon\Carbon::parse($slot->date)->format('d M Y')); ?> - <?php echo e($slot->time_start); ?> - <?php echo e($slot->time_end); ?>

                                                    (<?php echo e($slot->getAvailableCountAttribute()); ?> tersisa)
                                                </option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                        <input type="text" name="notes" placeholder="Catatan perubahan (opsional)" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                        <button type="submit" class="w-full rounded-lg bg-[#F59E0B] px-4 py-2 text-sm font-bold text-white hover:bg-[#D97706]">
                                            Update Jadwal
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->booking_data): ?>
                        <div class="mt-4 border-t border-gray-100 pt-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Data Booking</p>
                            <div class="space-y-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->booking_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($value): ?>
                                        <div class="flex gap-2 text-sm">
                                            <span class="text-gray-500 font-medium uppercase"><?php echo e($key); ?></span>
                                            <span class="text-gray-900"><?php echo e($value); ?></span>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isBuyer): ?>
                <div class="order-card">
                    <h2 class="font-heading text-sm font-black uppercase tracking-wider mb-4">Status Pembayaran</h2>
                    
                    <?php
                        $paymentStatusLabel = match ($order->payment_status) {
                            'paid' => $order->payment?->isAdminConfirmed() ? 'Saldo dikonfirmasi — seller sedang mengerjakan' : 'QRIS lunas — menunggu konfirmasi saldo admin (escrow)',
                            'pending' => 'Menunggu pembayaran',
                            'expired' => 'Kedaluwarsa',
                            'failed' => 'Gagal',
                            default => ucfirst((string) $order->payment_status),
                        };

                        $orderStatusLabel = match ($order->status) {
                            'menunggu_pembayaran' => 'Menunggu Pembayaran',
                            'menunggu_konfirmasi_harga' => 'Menunggu Konfirmasi Harga',
                            'menunggu_konfirmasi' => 'Menunggu Konfirmasi Admin',
                            'dikonfirmasi' => 'Dikonfirmasi - Siap Dikerjakan',
                            'dikerjakan' => 'Proses Pengerjaan',
                            'menunggu_persetujuan' => 'Menunggu Persetujuan Buyer',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                            default => ucfirst((string) $order->status),
                        };
                    ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'selesai'): ?>
                        <div class="bg-[#2C9F45] text-white px-4 py-3 text-sm font-bold">
                            Pemesanan jasa sudah selesai, sampai bertemu di jasa selanjutnya
                        </div>
                    <?php elseif($order->payment_status === 'paid' && $order->payment?->isAdminConfirmed()): ?>
                        <div class="bg-black text-white px-4 py-3 text-sm font-bold">
                            Saldo dikonfirmasi admin. Seller telah diberi instruksi untuk segera mengerjakan pesanan jasa.
                        </div>
                    <?php elseif($order->payment_status === 'paid'): ?>
                        <div class="bg-[#E4002B] text-white px-4 py-3 text-sm font-bold mb-3">
                            Jasa terbayarkan. Saldo QRIS masuk menunggu konfirmasi admin sebelum seller mulai mengerjakan.
                        </div>
                        <p class="text-xs text-gray-500">
                            Admin akan mencocokkan dana masuk di rekening, lalu menekan <strong>Konfirmasi Saldo Masuk</strong> di Transaksi.
                        </p>
                    <?php elseif($order->status === 'menunggu_konfirmasi_harga'): ?>
                        <div class="bg-[#FEF3C7] border border-[#F59E0B] text-[#92400E] p-3">
                            <p class="text-xs font-bold">
                                ⏳ Menunggu seller menetapkan harga untuk pesanan ini. Anda akan menerima notifikasi saat harga sudah ditetapkan.
                            </p>
                        </div>
                    <?php elseif($order->status === 'menunggu_pembayaran' && $order->final_price): ?>
                        <div class="mb-4 p-4 bg-[#D1FAE5] border border-[#10B981]">
                            <p class="text-xs font-bold text-[#065F46] mb-2">Harga Telah DitETAPkan</p>
                            <p class="text-2xl font-black text-black">
                                Rp<?php echo e(number_format($order->final_price, 0, ',', '.')); ?>

                            </p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->seller_price_note): ?>
                            <p class="text-sm text-gray-700 mt-2 italic"><?php echo e($order->seller_price_note); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Bayar aman melalui QRIS Midtrans Sandbox. Setelah sukses, status menjadi Jasa Terbayarkan di admin.</p>
                        <a href="<?php echo e(route('orders.payment.show', $order)); ?>" class="btn-primary">
                            Bayar dengan QRIS
                        </a>
                    <?php elseif($order->status === 'menunggu_pembayaran'): ?>
                        <p class="text-sm text-gray-600 mb-4">Bayar aman melalui QRIS Midtrans Sandbox. Setelah sukses, status menjadi Jasa Terbayarkan di admin.</p>
                        <a href="<?php echo e(route('orders.payment.show', $order)); ?>" class="btn-primary">
                            Bayar dengan QRIS
                        </a>
                    <?php elseif($order->payment): ?>
                        <p class="text-sm text-gray-600">
                            Status pembayaran: <strong><?php echo e($paymentStatusLabel); ?></strong>
                        </p>
                    <?php else: ?>
                        <p class="text-sm text-gray-500">Menunggu buyer melakukan pembayaran.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isBuyer && $order->priceHistories->count() > 0 && $order->status !== 'menunggu_konfirmasi_harga'): ?>
                    <div class="mt-4 p-4 bg-gray-50 border border-gray-200">
                        <p class="text-xs font-black uppercase tracking-wider mb-2" style="color: #666;">History Perubahan Harga:</p>
                        <div class="space-y-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->priceHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="text-xs text-gray-600">
                                <span style="color: #999;"><?php echo e($history->created_at->format('d M H:i')); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->old_price): ?>
                                <span style="color: #999;">Rp<?php echo e(number_format($history->old_price, 0, ',', '.')); ?></span>
                                <span style="color: #999;">→</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span class="font-bold" style="color: #000;">Rp<?php echo e(number_format($history->new_price, 0, ',', '.')); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->note): ?>
                                <span style="color: #666; font-style: italic;">(<?php echo e($history->note); ?>)</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 italic">
                            Harga akhir yang digunakan untuk pembayaran: <strong class="text-black">Rp<?php echo e(number_format($order->final_price, 0, ',', '.')); ?></strong>
                        </p>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
             
            <div class="order-card">
                <h2 class="font-heading text-sm font-black uppercase tracking-wider mb-4">File Pesanan</h2>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->files->count() > 0): ?>
                    <div class="space-y-2 mb-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="flex items-center justify-between bg-gray-50 p-3 border border-gray-200">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div class="w-8 h-8 bg-black text-white flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-xs truncate"><?php echo e($file->file_path); ?></p>
                                        <p class="text-xs text-gray-500">
                                            <?php echo e(ucfirst($file->file_type)); ?> oleh <?php echo e($file->uploader->name); ?>

                                        </p>
                                    </div>
                                </div>
                                <a href="<?php echo e(Storage::url($file->file_path)); ?>" target="_blank" class="btn-outline ml-4 flex-shrink-0" style="padding: 0.5rem 1rem; font-size: 0.625rem;">
                                    Unduh
                                </a>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                 
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->messages->count() > 0 && $isSeller): ?>
                    <div class="order-card bg-[#EDE734] border border-[#d4ce2a]">
                        <h3 class="font-heading text-xs font-black uppercase tracking-wider mb-3">Pesan dari Buyer</h3>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="mb-3 pb-3 border-b border-black/20 last:border-0 last:mb-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-bold text-xs">Buyer:</span>
                                    <span class="text-xs text-gray-700"><?php echo e($msg->created_at->format('d M Y, H:i')); ?></span>
                                </div>
                                <p class="text-xs text-gray-900"><?php echo e($msg->message); ?></p>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                 
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isSeller): ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'menunggu_konfirmasi_harga'): ?>
                    <div class="order-card" style="background: #FEF3C7; border-color: #F59E0B;">
                        <h3 class="font-heading text-sm font-black uppercase tracking-wider mb-4" style="color: #B45309;">
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Set Harga untuk Pesanan Ini
                        </h3>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->booking_data): ?>
                        <div class="mb-4 p-4 bg-white border border-gray-200" style="border-radius: 2px;">
                            <p class="text-xs font-black uppercase tracking-wider mb-3" style="color: #666;">Detail Booking dari Buyer:</p>
                            <div class="space-y-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->booking_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="flex items-start gap-3">
                                    <span class="text-xs text-gray-500" style="min-width: 140px;"><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?></span>
                                    <span class="text-sm font-bold text-black"><?php echo e(is_array($value) ? implode(', ', $value) : $value); ?></span>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <form action="<?php echo e(route('orders.set-price', $order)); ?>" method="POST" id="setPriceForm">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-bold mb-2" style="color: #000;">
                                    Harga Final (Rp) <span style="color: #E4002B;">*</span>
                                </label>
                                <input type="number" 
                                       name="final_price" 
                                       id="final_price_input"
                                       required
                                       min="1000" 
                                       step="1000"
                                       value="<?php echo e($order->estimated_price ?? old('final_price')); ?>"
                                       style="width: 100%; border: 1px solid #ccc; padding: 0.75rem; font-size: 1.125rem; font-weight: bold; color: #000;">
                                <p class="mt-2 text-xs text-gray-600">
                                    Estimasi dari harga jasa: <span class="font-bold text-black">Rp<?php echo e(number_format($order->estimated_price ?? 0, 0, ',', '.')); ?></span>
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-bold mb-2" style="color: #000;">
                                    Catatan untuk Buyer (opsional)
                                </label>
                                <textarea name="seller_price_note" 
                                          rows="2"
                                          placeholder="Misal: Harga termasuk unlock hero tambahan, winrate rendah butuh effort lebih..."
                                          style="width: 100%; border: 1px solid #ccc; padding: 0.75rem; font-size: 0.875rem; color: #000; resize: vertical;"><?php echo e(old('seller_price_note')); ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn-primary w-full">
                                Set Harga & Lanjutkan
                            </button>
                        </form>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'menunggu_pembayaran' && $order->final_price): ?>
                    <div class="order-card" style="background: #D1FAE5; border-color: #10B981;">
                        <h3 class="font-heading text-sm font-black uppercase tracking-wider mb-3" style="color: #065F46;">Harga Sudah DitETAPkan</h3>
                        <div class="flex items-center gap-4 mb-4">
                            <div>
                                <p class="text-3xl font-black" style="color: #000;">
                                    Rp<?php echo e(number_format($order->final_price, 0, ',', '.')); ?>

                                </p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->seller_price_note): ?>
                                <p class="text-sm text-gray-700 mt-1"><?php echo e($order->seller_price_note); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-300">
                            <button type="button" 
                                    onclick="document.getElementById('editPriceForm').classList.toggle('hidden')"
                                    class="text-sm font-bold underline" style="color: #F59E0B;">
                                Ubah Harga
                            </button>
                            
                            <form action="<?php echo e(route('orders.set-price', $order)); ?>" method="POST" id="editPriceForm" class="hidden mt-4">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                
                                <div class="flex gap-3 items-end">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold mb-1" style="color: #666;">Harga Baru (Rp)</label>
                                        <input type="number" 
                                               name="final_price" 
                                               required
                                               min="1000" 
                                               step="1000"
                                               value="<?php echo e($order->final_price); ?>"
                                               style="width: 100%; border: 1px solid #ccc; padding: 0.5rem; color: #000;">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold mb-1" style="color: #666;">Catatan</label>
                                        <input type="text" 
                                               name="seller_price_note" 
                                               value="<?php echo e($order->seller_price_note); ?>"
                                               style="width: 100%; border: 1px solid #ccc; padding: 0.5rem; color: #000;">
                                    </div>
                                    <button type="submit" class="btn-primary" style="padding: 0.5rem 1rem; white-space: nowrap;">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->priceHistories->count() > 0): ?>
                        <div class="mt-4 pt-4 border-t border-gray-300">
                            <p class="text-xs font-black uppercase tracking-wider mb-2" style="color: #666;">History Perubahan Harga:</p>
                            <div class="space-y-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $order->priceHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="text-xs text-gray-600">
                                    <span style="color: #999;"><?php echo e($history->created_at->format('d M H:i')); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->old_price): ?>
                                    <span style="color: #999; text-decoration: line-through;">Rp<?php echo e(number_format($history->old_price, 0, ',', '.')); ?></span>
                                    <span style="color: #999;">→</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <span class="font-bold" style="color: #000;">Rp<?php echo e(number_format($history->new_price, 0, ',', '.')); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->note): ?>
                                    <span style="color: #666; font-style: italic;">(<?php echo e($history->note); ?>)</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="space-y-3 pt-4 border-t border-gray-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'menunggu_konfirmasi'): ?>
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-3 rounded">
                                <p class="text-xs font-bold">
                                    ⏳ Pembayaran sedang menunggu konfirmasi admin. Tombol "Mulai Kerjakan" akan muncul setelah admin mengkonfirmasi saldo masuk.
                                </p>
                            </div>
                        <?php elseif($order->status === 'menunggu_persetujuan'): ?>
                            <div class="bg-[#EDE734] border border-[#d4ce2a] px-4 py-3">
                                <p class="text-xs font-bold text-black">
                                    Hasil sudah dikirim. Menunggu buyer menyetujui — setelah disetujui, dana cair otomatis ke dompet 1 jam kemudian.
                                </p>
                            </div>
                        <?php elseif($order->status === 'dikonfirmasi'): ?>
                            <div class="bg-green-50 border border-green-200 text-green-800 p-3 rounded">
                                <p class="text-xs font-bold">
                                    ✓ Pembayaran sudah dikonfirmasi admin. Klik "Mulai Kerjakan" untuk memulai pengerjaan.
                                </p>
                            </div>
                        <?php elseif($order->status === 'dikerjakan'): ?>
                            <div class="bg-black text-white p-3">
                                <p class="text-xs font-bold">
                                    Sedang dikerjakan. Upload hasil di bawah untuk menyerahkan pesanan ke buyer.
                                </p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'dikonfirmasi'): ?>
                            <form method="POST" action="<?php echo e(route('orders.start-work', $order)); ?>" onsubmit="return handleStartWork(event, this)">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn-primary w-full" id="startWorkBtn">
                                    Mulai Kerjakan
                                </button>
                            </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($order->status, ['dikonfirmasi', 'dikerjakan'])): ?>
                            <form method="POST" action="<?php echo e(route('order-files.store', $order)); ?>" enctype="multipart/form-data" class="space-y-2">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="file_type" value="hasil">
                                <input type="file" name="file" accept=".pdf,.zip,.png,.jpg,.jpeg,.doc,.docx,.ppt,.pptx" required
                                       class="w-full text-xs file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-black file:text-white file:font-bold file:text-xs file:uppercase file:tracking-wider file:cursor-pointer hover:file:bg-gray-800">
                                <button type="submit" class="btn-primary w-full">
                                    Upload Hasil
                                </button>
                                <p class="text-xs text-gray-500">Format: PDF / ZIP / JPG / PNG / DOC (maks 5MB).</p>
                            </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                 
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isBuyer && $order->status === 'menunggu_persetujuan'): ?>
                    <div class="space-y-3 pt-4 border-t border-gray-200">
                        <div class="bg-[#2C9F45] text-white px-4 py-3">
                            <p class="text-xs font-bold">
                                Hasil sudah dikirim seller. Jika sudah sesuai, tekan Selesaikan Pesanan. Dana akan cair otomatis ke seller 1 jam setelah itu.
                            </p>
                        </div>
                        
                        <form method="POST" action="<?php echo e(route('order-files.approve', $order)); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn-success w-full">
                                Selesaikan Pesanan
                            </button>
                        </form>
                        
                        <button onclick="document.getElementById('revisiForm').classList.toggle('hidden')"
                                class="btn-outline w-full">
                            Minta Revisi
                        </button>
                        
                        <form id="revisiForm" method="POST" action="<?php echo e(route('order-files.revise', $order)); ?>" class="hidden space-y-2">
                            <?php echo csrf_field(); ?>
                            <input type="text" name="revision_note" placeholder="Jelaskan revisi yang diinginkan..." class="input-field text-xs" required>
                            <button type="submit" class="w-full bg-[#EDE734] text-black px-4 py-3 font-bold uppercase tracking-wider text-xs border-2 border-[#d4ce2a] hover:bg-[#d4ce2a]">
                                Kirim
                            </button>
                        </form>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'selesai'): ?>
                    <div class="pt-4 border-t border-gray-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->payment && $order->payment->status === 'released'): ?>
                            <p class="text-xs text-gray-700 font-bold">
                                Pesanan selesai & dana sudah cair ke saldo dompet seller.
                            </p>
                        <?php else: ?>
                            <p class="text-xs text-gray-700 font-bold">
                                Pesanan selesai. Dana akan cair otomatis ke seller dalam 1 jam sejak penyelesaian.
                            </p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            
             
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isBuyer && $order->status === 'selesai' && $order->service): ?>
                <div class="order-card">
                    <h2 class="font-heading text-sm font-black uppercase tracking-wider mb-4">Beri Review</h2>
                    
                    <?php
                        $existingReview = $order->service->reviews()->where('user_id', auth()->id())->first();
                    ?>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existingReview): ?>
                        <div class="bg-gray-50 p-4 border border-gray-200">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-heading text-xl font-black"><?php echo e($existingReview->rating); ?>/5</span>
                                <span class="text-xs text-gray-500"><?php echo e($existingReview->created_at->format('d M Y')); ?></span>
                            </div>
                            <p class="text-xs text-gray-800"><?php echo e($existingReview->comment); ?></p>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="<?php echo e(route('reviews.store', $order->service)); ?>" class="space-y-3">
                            <?php echo csrf_field(); ?>
                            <div>
                                <label class="label-field">Rating</label>
                                <select name="rating" class="input-field text-xs">
                                    <option value="5">⭐⭐⭐⭐⭐ Sangat Puas</option>
                                    <option value="4">⭐⭐⭐⭐ Puas</option>
                                    <option value="3">⭐⭐⭐ Cukup</option>
                                    <option value="2">⭐⭐ Kurang</option>
                                    <option value="1">⭐ Buruk</option>
                                </select>
                            </div>
                            <div>
                                <label class="label-field">Komentar (opsional)</label>
                                <textarea name="comment" rows="3" placeholder="Tulis komentar tentang pengalaman bertransaksi..." class="input-field text-xs"></textarea>
                            </div>
                            <button type="submit" class="btn-primary w-full">
                                Kirim Review
                            </button>
                        </form>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
        </div>
        
         
        <div class="space-y-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isSeller): ?>
                <div class="order-card">
                    <h2 class="font-heading text-sm font-black uppercase tracking-wider mb-4">Pembayaran & Status</h2>
                    
                    <?php
                        $paymentStatusLabel = match ($order->payment_status) {
                            'paid' => $order->payment?->isAdminConfirmed() ? 'Saldo dikonfirmasi' : 'Menunggu konfirmasi admin',
                            'pending' => 'Menunggu pembayaran',
                            'expired' => 'Kedaluwarsa',
                            'failed' => 'Gagal',
                            default => ucfirst((string) $order->payment_status),
                        };
                    ?>
                    
                    <div class="space-y-3">
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Status Pembayaran</span>
                            <span class="text-xs font-black"><?php echo e($paymentStatusLabel); ?></span>
                        </div>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->payment_status === 'paid' && $order->payment?->isAdminConfirmed()): ?>
                            <div class="bg-black text-white px-3 py-2 text-xs font-bold">
                                Saldo dikonfirmasi admin. Seller telah diberi instruksi untuk segera mengerjakan pesanan jasa.
                            </div>
                        <?php elseif($order->payment_status === 'paid'): ?>
                            <div class="bg-[#E4002B] text-white px-3 py-2 text-xs font-bold">
                                Jasa terbayarkan. Saldo QRIS masuk menunggu konfirmasi admin.
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <div class="pt-3 border-t border-gray-200 space-y-2">
                            <a href="<?php echo e(route('wallet.index')); ?>" class="flex items-center gap-2 text-xs font-bold hover:underline">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Lihat Dompet
                            </a>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->status === 'menunggu_persetujuan'): ?>
                                <p class="text-xs text-gray-500">Hasil sudah dikirim. Menunggu buyer menyetujui.</p>
                            <?php elseif($order->status === 'dikerjakan'): ?>
                                <p class="text-xs text-gray-500">Sedang dikerjakan. Upload hasil untuk menyerahkan ke buyer.</p>
                            <?php elseif($order->status === 'dibayar'): ?>
                                <p class="text-xs text-gray-500">Dana sudah di-escrow. Mulai kerjakan lalu upload hasil.</p>
                            <?php elseif($order->status === 'selesai'): ?>
                                <p class="text-xs text-gray-500">Pesanan selesai. Dana cair otomatis ke dompet 1 jam setelah penyelesaian.</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <div class="order-card border-2 border-black">
                <h3 class="font-heading text-xs font-black uppercase tracking-wider mb-2">Butuh Bantuan?</h3>
                <p class="text-xs text-gray-700 mb-3">
                    Ada masalah dengan pesanan? Hubungi counterparty atau ajukan laporan.
                </p>
                <button onclick="document.getElementById('reportForm').classList.toggle('hidden')" class="btn-danger w-full">
                    Laporkan Masalah
                </button>
            </div>
        </div>
        
    </div>
    
     
    <form id="reportForm" method="POST" action="<?php echo e(route('reports.store')); ?>" class="hidden mt-6 order-card" x-data="{ selectedRole: '', selectedCategory: '' }">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">
        <input type="hidden" name="reported_user_id" value="<?php echo e($isBuyer ? $order->service?->user_id : $order->buyer_id); ?>">
        
        <h3 class="font-heading text-sm font-black uppercase tracking-wider mb-4">Laporan Masalah</h3>
        
        <div class="space-y-4">
            <div>
                <label class="label-field">Anda melaporkan sebagai <span class="text-red-600">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center justify-center px-4 py-3 border-2 cursor-pointer transition"
                           :class="selectedRole === 'buyer' ? 'border-black bg-black text-white' : 'border-gray-300 hover:border-gray-400'">
                        <input type="radio" name="reporter_role" value="buyer" required class="sr-only" x-model="selectedRole">
                        <span class="font-bold text-xs uppercase tracking-wider">BUYER</span>
                    </label>
                    <label class="relative flex items-center justify-center px-4 py-3 border-2 cursor-pointer transition"
                           :class="selectedRole === 'seller' ? 'border-black bg-black text-white' : 'border-gray-300 hover:border-gray-400'">
                        <input type="radio" name="reporter_role" value="seller" required class="sr-only" x-model="selectedRole">
                        <span class="font-bold text-xs uppercase tracking-wider">SELLER</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="label-field">Kategori Masalah <span class="text-red-600">*</span></label>
                <select name="category" required class="input-field text-xs" x-model="selectedCategory">
                    <option value="">Pilih kategori masalah</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\Report::getCategories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($category); ?>"><?php echo e($category); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            </div>

            <div>
                <label class="label-field">Jelaskan Detail Masalah <span class="text-red-600">*</span></label>
                <textarea name="reason" rows="4" required minlength="10" maxlength="500" placeholder="Jelaskan masalah yang Anda alami secara detail (minimal 10 karakter)..." class="input-field text-xs"></textarea>
                <p class="text-xs text-gray-500 mt-1">Minimum 10 karakter, maksimum 500 karakter</p>
            </div>

            <button type="submit" class="w-full bg-[#E4002B] text-white px-4 py-3 font-bold uppercase tracking-wider text-xs border-2 border-[#E4002B] hover:bg-white hover:text-[#E4002B] transition">
                Kirim Laporan
            </button>
        </div>
    </form>
</div>

<script>
function handleStartWork(event, form) {
    const btn = document.getElementById('startWorkBtn');
    if (btn) {
        btn.disabled = true;
        btn.textContent = 'Anda akan memproses pekerjaan, buyer akan segera diberitahu.';
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    }
    return true;
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/orders/show.blade.php ENDPATH**/ ?>