<?php $__env->startSection('title', 'Jasa Saya - SkillHub'); ?>

<?php $__env->startSection('content'); ?>
<!--
THESIS: Seller service workspace as editorial index, refusing the four-card dashboard template
OWN-WORLD: Black/white with editorial numbering, Inter typography, asymmetric service grid with intentional gaps
STORY: Seller scans their portfolio at a glance, understands status/performance immediately, acts on any service in two clicks
FIRST VIEWPORT: Left-aligned page title with thin rule, compact stat strip (not cards), search/filter bar hanging right, service index grid below with editorial numbers
FORM: Service index grid (position 7 from grounded list), editorial numbering treatment, asymmetric spacing
FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance
-->

<div class="min-h-screen bg-white">
    <div class="mx-auto max-w-[1400px] px-6 lg:px-8 py-12">
        
        <!-- Header: thin rule + title + action -->
        <div class="border-b border-black/10 pb-8 mb-10">
            <div class="h-[1px] w-20 bg-black mb-6"></div>
            <div class="flex items-end justify-between gap-8">
                <div>
                    <h1 class="text-[42px] leading-[1.1] font-semibold tracking-[-0.02em] text-black">
                        Jasa Saya
                    </h1>
                    <p class="mt-3 text-xs text-black/60 max-w-xl font-medium">
                        Tempat kamu mengelola jasa mu - Edit label form yang mungkin kamu butuh kan dari buyer, kamu bisa buat sendiri loh
                    </p>
                </div>
                <a href="<?php echo e(route('services.create')); ?>" 
                   class="flex items-center gap-2 px-6 py-3 bg-black text-white text-sm font-medium hover:bg-black/90 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah jasa baru
                </a>
            </div>
        </div>

        <!-- Stats strip: editorial treatment, no boxes -->
        <div class="mb-12 pb-12 border-b border-black/10">
            <div class="grid grid-cols-3 gap-1">
                <div class="relative">
                    <div class="absolute -left-2 top-0 w-[2px] h-12 bg-black"></div>
                    <div class="pl-4">
                        <div class="text-[10px] font-medium tracking-[0.12em] uppercase text-black/35 mb-2">Total jasa</div>
                        <div class="text-[52px] leading-none font-light tracking-[-0.03em] text-black"><?php echo e($services->total()); ?></div>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -left-2 top-0 w-[2px] h-12 bg-emerald-600"></div>
                    <div class="pl-4">
                        <div class="text-[10px] font-medium tracking-[0.12em] uppercase text-black/35 mb-2">Disetujui</div>
                        <div class="text-[52px] leading-none font-light tracking-[-0.03em] text-emerald-600"><?php echo e($services->where('status', 'approved')->count()); ?></div>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -left-2 top-0 w-[2px] h-12 bg-black/15"></div>
                    <div class="pl-4">
                        <div class="text-[10px] font-medium tracking-[0.12em] uppercase text-black/35 mb-2">Aktif sekarang</div>
                        <div class="text-[52px] leading-none font-light tracking-[-0.03em] text-black"><?php echo e($services->total()); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter bar -->
        <form method="GET" action="<?php echo e(route('services.my')); ?>" class="mb-12">
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-[11px] font-medium tracking-[0.08em] uppercase text-black/40 mb-2">Kategori</label>
                    <select name="category" id="filterCategory" 
                            class="w-full px-4 py-2.5 bg-white border border-black/15 text-sm focus:outline-none focus:border-black transition-colors">
                        <option value="">Semua kategori</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-[11px] font-medium tracking-[0.08em] uppercase text-black/40 mb-2">Subkategori</label>
                    <select name="subcategory" id="filterSubcategory" 
                            class="w-full px-4 py-2.5 bg-white border border-black/15 text-sm focus:outline-none focus:border-black transition-colors">
                        <option value="">Semua subkategori</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($sub->id); ?>" data-category="<?php echo e($sub->category_id); ?>" <?php echo e(request('subcategory') == $sub->id ? 'selected' : ''); ?>><?php echo e($sub->name); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-black text-white text-sm font-medium hover:bg-black/90 transition-colors">
                    Terapkan
                </button>
                <a href="<?php echo e(route('services.my')); ?>" class="px-6 py-2.5 border border-black/15 text-sm font-medium hover:border-black transition-colors">
                    Reset
                </a>
            </div>
        </form>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($services->count() > 0): ?>
        <!-- Service index grid -->
        <div class="space-y-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <article class="group border-t border-black/10 pt-4">
                <div class="flex gap-4 items-center">
                    <!-- Editorial number -->
                    <div class="text-sm text-black/20 w-6 shrink-0">
                        <?php echo e(str_pad($services->firstItem() + $index, 2, '0', STR_PAD_LEFT)); ?>

                    </div>

                    <!-- Image -->
                    <div class="w-20 h-20 bg-black/5 overflow-hidden shrink-0">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->image): ?>
                        <img src="<?php echo e(asset('storage/'.$service->image)); ?>" 
                             alt="<?php echo e($service->title); ?>" 
                             class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-black/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Service info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 text-[10px] tracking-[0.06em] uppercase text-black/40 mb-1">
                            <span><?php echo e($service->subcategory->category->name ?? 'N/A'); ?></span>
                            <span class="w-0.5 h-0.5 rounded-full bg-black/20"></span>
                            <span><?php echo e($service->subcategory->name ?? '-'); ?></span>
                        </div>
                        <h2 class="text-sm font-semibold text-black mb-1 truncate">
                            <?php echo e($service->title); ?>

                        </h2>
                        <div class="text-sm font-semibold text-black">
                            Rp<?php echo e(number_format($service->price, 0, ',', '.')); ?>

                        </div>
                    </div>

                    <!-- Meta -->
                    <div class="flex items-center gap-3 text-xs text-black/50 shrink-0">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span><?php echo e($service->orders_count ?? 0); ?></span>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->reviews_count > 0): ?>
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span><?php echo e(number_format($service->reviews_avg_rating ?? 0, 1)); ?></span>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Status -->
                    <div class="shrink-0">
                        <span class="inline-flex px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-medium tracking-wide uppercase">
                            Approved
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="<?php echo e(route('services.show', $service->id)); ?>" 
                           class="px-3 py-1.5 border border-black/15 text-xs font-medium hover:border-black transition-colors">
                            Lihat
                        </a>
                        <a href="<?php echo e(route('services.slots.manage', $service)); ?>" 
                           class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium hover:bg-blue-700 transition-colors">
                            Jam
                        </a>
                        <a href="<?php echo e(route('services.edit', $service->id)); ?>" 
                           class="px-3 py-1.5 bg-black text-white text-xs font-medium hover:bg-black/90 transition-colors">
                            Edit
                        </a>
                    </div>
                </div>
            </article>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-16 pt-12 border-t border-black/10">
            <?php echo e($services->appends(request()->query())->links()); ?>

        </div>
        
        <!-- Info footer -->
        <div class="mt-8 text-center">
            <p class="text-xs text-black/40">
                Halaman ini menampilkan jasa yang telah disetujui admin. Jasa dalam status pending dapat dilihat di dashboard.
            </p>
        </div>
        <?php else: ?>
        <!-- Empty state -->
        <div class="text-center py-24 border-t border-black/10">
            <div class="w-16 h-16 rounded-full bg-black/5 flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-black/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <h3 class="text-2xl font-semibold tracking-tight text-black mb-3">
                Belum ada jasa
            </h3>
            <p class="text-[15px] text-black/60 mb-8 max-w-md mx-auto">
                Mulai tawarkan skill kamu dengan membuat jasa pertama
            </p>
            <a href="<?php echo e(route('services.create')); ?>" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white text-sm font-medium hover:bg-black/90 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat jasa pertama
            </a>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('filterCategory');
    const subcategorySelect = document.getElementById('filterSubcategory');
    const allSubOptions = subcategorySelect.querySelectorAll('option');

    function filterSubcategories() {
        const selectedCategory = categorySelect.value;
        const currentSubValue = subcategorySelect.value;
        let shouldClearSub = true;

        allSubOptions.forEach(opt => {
            const catId = opt.dataset.category;
            if (selectedCategory === '' || catId == selectedCategory || !catId) {
                opt.style.display = 'block';
                if (catId == selectedCategory && opt.value == currentSubValue) {
                    shouldClearSub = false;
                }
            } else {
                opt.style.display = 'none';
            }
        });

        if (shouldClearSub && selectedCategory !== '') {
            subcategorySelect.value = '';
        }
    }

    categorySelect.addEventListener('change', filterSubcategories);
    filterSubcategories();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/services/my-services.blade.php ENDPATH**/ ?>