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
        $initialSubcategories = $subcategories->map(function ($sub) {
            return [
                'id' => $sub->id,
                'name' => $sub->name,
                'category_id' => $sub->category_id,
                'category_name' => $sub->category->name,
                'services_count' => $sub->services_count,
            ];
        })->values()->all();

        $categoriesForSelect = $categories->map(function ($cat) {
            return ['id' => $cat->id, 'name' => $cat->name];
        })->values()->all();
    ?>

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8" data-stagger-item>
        <div>
            <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Kelola Subkategori</h1>
            <p class="text-sm text-[#555555] mt-1">Kelola subkategori di bawah masing-masing kategori</p>
        </div>
        <button @click="showCreateModal = true; editingSubcategory = null" class="btn-primary text-xs">
            <span class="inline-flex items-center justify-center w-5 h-5">+</span> Tambah Subkategori
        </button>
    </div>

    
    <div class="admin-card" data-stagger-container>
        <div class="overflow-hidden">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subcategories->isNotEmpty()): ?>
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th class="w-48">Kategori</th>
                                <th class="w-24">Jasa</th>
                                <th class="w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <tr class="row-enter" data-stagger-item>
                                    <td>
                                        <p class="font-medium text-black"><?php echo e($sub->name); ?></p>
                                    </td>
                                    <td>
                                        <span class="text-xs px-2 py-1 rounded-sm border border-[#DDDDDD] bg-white">
                                            <?php echo e($sub->category->name ?? '—'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-neutral"><?php echo e($sub->services_count); ?> jasa</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <a href="<?php echo e(route('admin.subcategories.edit', $sub)); ?>" class="btn-ghost text-xs px-2 py-1">Edit</a>
                                            <form action="<?php echo e(route('admin.subcategories.destroy', $sub)); ?>" method="POST" class="inline" onsubmit="return confirm('PERINGATAN: Akan menghapus subkategori INI DAN SEMUA JASANYA! Yakin?')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn-danger text-xs px-2 py-1">Hapus</button>
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
                        Menampilkan <?php echo e($subcategories->firstItem()); ?> - <?php echo e($subcategories->lastItem()); ?> dari <?php echo e($subcategories->total()); ?> subkategori
                    </p>
                    <div class="flex items-center gap-2">
                        <?php echo e($subcategories->appends(request()->query())->links('vendor.pagination.tailwind')); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7.25a2.25 2.25 0 0 1 2.25-2.25h7.5a2.25 2.25 0 0 1 2.25 2.25v9.5a2.25 2.25 0 0 1-2.25 2.25h-7.5A2.25 2.25 0 0 1 4 16.75z"/></svg>
                    </div>
                    <p class="text-xs text-[#999999]">Belum ada subkategori.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div x-show="showCreateModal" x-cloak @click.outside="showCreateModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div @click.stop class="w-full max-w-md bg-white rounded-md border border-[#DDDDDD] p-6 shadow-lg"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-heading font-semibold text-sm text-black" x-text="editingSubcategory ? 'Edit Subkategori' : 'Tambah Subkategori'"></h3>
                <button type="button" @click="showCreateModal = false; editingSubcategory = null" class="btn-ghost p-1" aria-label="Tutup modal">&times;</button>
            </div>
            <form method="POST" :action="editingSubcategory ? '<?php echo e(url('admin/subcategories')); ?>/' + editingSubcategory.id : '<?php echo e(route('admin.subcategories.store')); ?>'">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" x-bind:value="editingSubcategory ? 'PUT' : 'POST'">
                <div class="space-y-4">
                    <div>
                        <label class="label-field" for="sub-name">Nama Subkategori <span class="text-[#E4002B] font-normal">*</span></label>
                        <input type="text" id="sub-name" name="name" required
                            class="input-field"
                            placeholder="Contoh: Desain Logo"
                            x-model="formData.name">
                    </div>
                    <div>
                        <label class="label-field" for="sub-category">Kategori <span class="text-[#E4002B] font-normal">*</span></label>
                        <select id="sub-category" name="category_id" required class="input-field" x-model="formData.category_id">
                            <option value="">Pilih Kategori</option>
                            <template x-for="cat in categoriesForSelect" :key="cat.id">
                                <option :value="cat.id" x-text="cat.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-[#DDDDDD]">
                        <button type="button" @click="showCreateModal = false; editingSubcategory = null; formData = { name: '', category_id: '' }" class="btn-outline text-xs px-4 py-2">Batal</button>
                        <button type="submit" class="btn-primary text-xs px-4 py-2" x-text="editingSubcategory ? 'Simpan Perubahan' : 'Tambah Subkategori'"></button>
                    </div>
                </div>
            </form>
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
<?php endif; ?><?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/admin/subcategories/index.blade.php ENDPATH**/ ?>