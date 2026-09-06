<?php $__env->startSection('title', 'Edit Jasa - SkillHub'); ?>
<?php $__env->startSection('hideNavigation', true); ?>

<?php $__env->startSection('content'); ?>
    <section class="relative isolate overflow-hidden bg-gradient-to-br from-sky-50 via-indigo-50 to-amber-50 px-4 py-12 sm:px-6 sm:py-16">
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
            <div class="absolute -left-20 top-12 h-64 w-64 rounded-full bg-blue-300/45 blur-3xl"></div>
            <div class="absolute -right-20 top-1/4 h-72 w-72 rounded-full bg-violet-300/35 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-56 w-56 rounded-full bg-amber-200/50 blur-3xl"></div>
            <div class="absolute inset-0 opacity-[0.08]" style="background-image: radial-gradient(#2563eb 1px, transparent 1px); background-size: 20px 20px;"></div>
        </div>

        <div class="mx-auto max-w-5xl">
            <div class="grid overflow-hidden rounded-[2rem] border border-white/80 bg-white/80 shadow-2xl shadow-blue-950/10 backdrop-blur-sm lg:grid-cols-[0.85fr_1.15fr]">
                <aside class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 px-7 py-10 text-white sm:px-10 lg:py-12">
                    <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full border-[18px] border-white/10"></div>
                    <div class="absolute -bottom-20 -left-16 h-52 w-52 rounded-full bg-amber-300/20"></div>

                    <div class="relative">
                        <a href="<?php echo e(route('services.my')); ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-100 transition hover:text-white">
                            <span aria-hidden="true">←</span> Kembali ke jasa saya
                        </a>

                        <span class="mt-12 inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-blue-50">Edit jasa</span>
                        <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl">Update jasamu</h1>
                        <p class="mt-3 text-lg font-medium text-blue-100">Perbarui informasi jasa</p>
                        <p class="mt-6 max-w-sm text-sm leading-7 text-blue-100/90">Ubah detail jasa sesuai kebutuhan. Jika ada perubahan signifikan, admin mungkin perlu meninjau ulang.</p>

                        <div class="mt-10 space-y-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [['number' => '01', 'text' => 'Perbarui kategori atau harga'], ['number' => '02', 'text' => 'Edit deskripsi jasa'], ['number' => '03', 'text' => 'Simpan perubahan']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-xs font-extrabold"><?php echo e($step['number']); ?></span>
                                    <span class="text-sm font-medium text-blue-50"><?php echo e($step['text']); ?></span>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                </aside>

                <div class="p-6 sm:p-10">
                    <div class="mb-8">
                        <p class="text-sm font-bold uppercase tracking-widest text-blue-600">Form edit</p>
                        <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Perbarui jasamu</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Edit detail jasa yang sudah kamu ajukan sebelumnya.</p>
                    </div>

                     <div class="mb-6 border-b border-slate-200">
                          <nav class="-mb-px flex gap-6" aria-label="Tabs">
                              <a href="#informasi" 
                                 class="tab-link whitespace-nowrap border-b-2 border-blue-600 px-1 py-4 text-sm font-bold text-blue-600"
                                 data-tab="informasi">
                                  Informasi Dasar
                              </a>
                              <a href="#booking-config" 
                                 class="tab-link whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm font-medium text-slate-500 hover:border-slate-300 hover:text-slate-700"
                                 data-tab="booking-config">
                                  Booking Config
                              </a>
                          </nav>
                     </div>

                    <div id="tab-informasi" class="tab-content">

                    <form method="POST" action="<?php echo e(route('services.update', $service->id)); ?>" enctype="multipart/form-data" class="space-y-5">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div>
                            <label for="title" class="mb-2 block text-sm font-bold text-slate-700">Nama jasa</label>
                            <input type="text" name="title" id="title" value="<?php echo e(old('title', $service->title)); ?>" required maxlength="255" placeholder="Contoh: Desain poster acara sekolah" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="category_id" class="mb-2 block text-sm font-bold text-slate-700">Kategori</label>
                                <select name="category_id" id="category_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                    <option value="">Pilih kategori</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <option value="<?php echo e($category->id); ?>" <?php if(old('category_id', $service->subcategory->category_id ?? null) == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label for="subcategory_id" class="mb-2 block text-sm font-bold text-slate-700">Subkategori</label>
                                <select name="subcategory_id" id="subcategory_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition disabled:cursor-not-allowed disabled:opacity-60 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                    <option value="">Pilih kategori dulu</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <option value="<?php echo e($subcategory->id); ?>" data-category-id="<?php echo e($subcategory->category_id); ?>" <?php if(old('subcategory_id', $service->subcategory_id) == $subcategory->id): echo 'selected'; endif; ?>><?php echo e($subcategory->name); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['subcategory_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <label for="price" class="mb-2 block text-sm font-bold text-slate-700">Harga jasa</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-sm font-bold text-slate-400">Rp</span>
                                <input type="number" name="price" id="price" value="<?php echo e(old('price', $service->price)); ?>" required min="0" inputmode="numeric" placeholder="50000" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div>
                            <label for="description" class="mb-2 block text-sm font-bold text-slate-700">Deskripsi jasa</label>
                            <textarea name="description" id="description" rows="5" required placeholder="Jelaskan apa yang akan kamu kerjakan, hasil yang didapat, dan ketentuan jasamu..." class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"><?php echo e(old('description', $service->description)); ?></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div>
                            <label for="image" class="mb-2 block text-sm font-bold text-slate-700">Gambar contoh <span class="font-medium text-slate-400">(opsional)</span></label>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->image): ?>
                                <div class="mb-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                                    <p class="text-xs font-semibold text-slate-600 mb-2">Gambar saat ini:</p>
                                    <img src="<?php echo e(asset('storage/' . $service->image)); ?>" alt="Current image" class="h-24 w-24 object-cover rounded-lg">
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <input type="file" name="image" id="image" accept="image/jpeg,image/png" class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-100 file:px-3 file:py-2 file:text-xs file:font-bold file:text-blue-700 hover:file:bg-blue-200">
                            <p class="mt-2 text-xs text-slate-400">JPG atau PNG, maksimal 2 MB. Kosongkan jika tidak ingin mengubah.</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                            <a href="<?php echo e(route('services.my')); ?>" class="rounded-xl px-4 py-3 text-center text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">Batal</a>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-600/30">
                                Simpan perubahan <span aria-hidden="true">→</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div id="tab-booking-config" class="tab-content hidden">
                    <div class="mb-6">
                        <p class="text-sm font-bold uppercase tracking-widest text-emerald-600">Konfigurasi Booking</p>
                        <h2 class="mt-2 text-xl font-extrabold tracking-tight text-slate-900">Atur Field Booking</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Tambahkan field yang perlu diisi buyer saat memesan jasa ini.</p>
                    </div>
                    
                     <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('booking.config-builder', ['service' => $service]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1920373308-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
                 </div>


             </div>
         </div>
     </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const defaultOption = subcategorySelect.querySelector('option[value=""]');

            const refreshSubcategories = () => {
                const selectedCategoryId = categorySelect.value;

                Array.from(subcategorySelect.options).forEach((option) => {
                    if (!option.value) return;
                    option.hidden = option.dataset.categoryId !== selectedCategoryId;
                });

                subcategorySelect.disabled = !selectedCategoryId;
                defaultOption.textContent = selectedCategoryId ? 'Pilih subkategori' : 'Pilih kategori dulu';

                const selectedOption = subcategorySelect.options[subcategorySelect.selectedIndex];
                if (selectedOption && selectedOption.hidden) subcategorySelect.value = '';
            };

            categorySelect.addEventListener('change', () => {
                subcategorySelect.value = '';
                refreshSubcategories();
            });

            refreshSubcategories();

            // Tab switching
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            tabLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetTab = link.dataset.tab;

                    tabLinks.forEach(l => {
                        l.classList.remove('border-blue-600', 'text-blue-600', 'font-bold');
                        l.classList.add('border-transparent', 'text-slate-500', 'font-medium');
                    });

                    link.classList.remove('border-transparent', 'text-slate-500', 'font-medium');
                    link.classList.add('border-blue-600', 'text-blue-600', 'font-bold');

                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    document.getElementById('tab-' + targetTab).classList.remove('hidden');
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('pageFooter'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/services/edit.blade.php ENDPATH**/ ?>