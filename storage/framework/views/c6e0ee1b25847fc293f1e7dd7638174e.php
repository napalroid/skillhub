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
        $services = $pendingServices;
    ?>

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8" data-stagger-item>
        <div>
            <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Antrian Persetujuan Jasa</h1>
            <p class="text-sm text-[#555555] mt-1">Review dan setujui/tolak jasa yang diajukan penjual</p>
        </div>
        <a href="<?php echo e(route('admin.services.index')); ?>" class="btn-outline text-xs">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 19.5 3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Semua Jasa
        </a>
    </div>

    
    <div class="admin-card" data-stagger-container>
        <div class="overflow-hidden">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($services->isNotEmpty()): ?>
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="w-24">Gambar</th>
                                <th>Jasa</th>
                                <th class="w-36">Kategori</th>
                                <th class="w-32">Penjual</th>
                                <th class="w-28">Harga</th>
                                <th class="w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php
                                    $mainImage = $service->image ? asset('storage/' . $service->image) : asset('images/skillhub-hero.webp');
                                ?>
                                <tr class="row-enter" data-stagger-item>
                                    <td>
                                        <img src="<?php echo e($mainImage); ?>" alt="<?php echo e($service->title); ?>"
                                             class="w-20 h-14 object-cover rounded-sm border border-[#DDDDDD]">
                                    </td>
                                    <td>
                                        <p class="font-medium text-black truncate max-w-xs"><?php echo e($service->title); ?></p>
                                        <p class="text-[10px] text-[#555555] mt-0.5 line-clamp-1"><?php echo e($service->description); ?></p>
                                    </td>
                                    <td>
                                        <span class="text-xs px-2 py-1 rounded-sm border border-[#DDDDDD] bg-white">
                                            <?php echo e($service->subcategory?->name ?? '-'); ?>

                                        </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->subcategory?->category): ?>
                                            <p class="text-[10px] text-[#555555] mt-0.5"><?php echo e($service->subcategory->category->name); ?></p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td>
                                        <p class="text-xs font-medium text-black"><?php echo e($service->seller?->name ?? 'N/A'); ?></p>
                                        <p class="text-[10px] text-[#555555] mt-0.5"><?php echo e($service->seller?->email ?? ''); ?></p>
                                    </td>
                                    <td>
                                        <span class="font-heading font-semibold text-sm text-black">
                                            Rp<?php echo e(number_format($service->price, 0, ',', '.')); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <a href="<?php echo e(route('admin.services.preview', $service)); ?>"
                                               class="btn-ghost p-1.5 text-[10px]" aria-label="Lihat detail jasa">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <form action="<?php echo e(route('admin.services.approve', $service)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn-success text-[10px] px-3 py-1.5">Setujui</button>
                                            </form>
                                            <form action="<?php echo e(route('admin.services.reject', $service)); ?>" method="POST" class="inline" x-data>
                                                <?php echo csrf_field(); ?>
                                                <button type="button" 
                                                        @click="if (confirm('Tolak jasa ini?')) $el.closest('form').submit()"
                                                        class="btn-danger text-[10px] px-3 py-1.5">Tolak</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="p-4 border-t border-[#DDDDDD] flex items-center justify-between">
                    <p class="text-xs text-[#999999]">
                        Menampilkan <?php echo e($services->firstItem()); ?> - <?php echo e($services->lastItem()); ?> dari <?php echo e($services->total()); ?> jasa
                    </p>
                    <div class="flex items-center gap-2">
                        <?php echo e($services->appends(request()->query())->links('vendor.pagination.tailwind')); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m5 13 4 4L19 7"/></svg>
                    </div>
                    <p class="text-xs text-[#999999]">Tidak ada pengajuan baru.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <?php $__env->startSection('scripts'); ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        if (!prefersReduced && window.gsap) {
            gsap.from('.row-enter', {
                opacity: 0, y: 12, duration: 0.4, ease: 'power2.out',
                stagger: 0.03, delay: 0.1
            });
        }
    });
    </script>
    <?php $__env->stopSection(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaf25f15ef26fc3d179956a9918eaad4d)): ?>
<?php $attributes = $__attributesOriginalaf25f15ef26fc3d179956a9918eaad4d; ?>
<?php unset($__attributesOriginalaf25f15ef26fc3d179956a9918eaad4d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaf25f15ef26fc3d179956a9918eaad4d)): ?>
<?php $component = $__componentOriginalaf25f15ef26fc3d179956a9918eaad4d; ?>
<?php unset($__componentOriginalaf25f15ef26fc3d179956a9918eaad4d); ?>
<?php endif; ?><?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/admin/services/pending.blade.php ENDPATH**/ ?>