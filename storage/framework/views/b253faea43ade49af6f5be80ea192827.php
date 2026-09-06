<?php $__env->startSection('title', 'Ajukan Jasa - SkillHub'); ?>
<?php $__env->startSection('hideNavigation', true); ?>

<?php $__env->startSection('content'); ?>
    <div class="jasa-ajukan-page bg-bg-soft font-sans text-text antialiased">

        
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <a href="<?php echo e(route('dashboard')); ?>" class="group mt-8 inline-flex items-center gap-2 text-sm font-semibold text-black">
                <span class="transition-transform duration-200 group-hover:-translate-x-1" aria-hidden="true">←</span>
                <span class="relative">
                    Dashboard
                    <span class="absolute -bottom-0.5 left-0 h-px w-0 bg-black transition-all duration-200 ease-out group-hover:w-full"></span>
                </span>
            </a>
        </div>

        
        <header class="mx-auto max-w-6xl px-5 pt-10 pb-14 sm:px-8 lg:grid lg:grid-cols-12 lg:gap-12 lg:pt-14">
            <div class="lg:col-span-7">
                <p class="font-heading text-xs font-bold uppercase tracking-[0.28em] text-[#0051BA]">Mulai berkarya</p>
                <h1 class="mt-5 font-heading text-5xl font-extrabold leading-[0.95] tracking-[-0.04em] text-black sm:text-6xl lg:text-7xl">
                    Mau ajuin jasa yaw?
                </h1>
                <p class="mt-4 font-heading text-xl font-bold tracking-tight text-black/80 sm:text-2xl">Isi ini dulu gih!</p>
                <p class="mt-6 max-w-md text-base leading-7 text-text-secondary">
                    Ceritakan keahlianmu dengan jelas. Setelah dikirim, admin akan meninjau jasa sebelum tampil di marketplace.
                </p>
            </div>

            <div class="mt-10 lg:col-span-5 lg:mt-0">
                <p class="font-heading text-xs font-bold uppercase tracking-[0.28em] text-text-secondary">Alur pengajuan</p>
                <ol class="mt-4 border-y border-border">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [['n' => '01', 't' => 'Pilih bidang keahlianmu', 'd' => 'Tentukan kategori & subkategori jasamu.'], ['n' => '02', 't' => 'Jelaskan jasa yang kamu tawarkan', 'd' => 'Lengkapi nama, harga, dan deskripsi.'], ['n' => '03', 't' => 'Kirim untuk ditinjau admin', 'd' => 'Admin akan memverifikasi sebelum publikasi.']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li class="flex items-baseline gap-5 py-5 <?php if(!$loop->last): ?> border-b border-border <?php endif; ?>">
                            <span class="font-heading text-2xl font-extrabold leading-none text-[#0051BA]"><?php echo e($step['n']); ?></span>
                            <div>
                                <p class="font-heading text-sm font-bold uppercase tracking-wide text-black"><?php echo e($step['t']); ?></p>
                                <p class="mt-1 text-sm leading-6 text-text-secondary"><?php echo e($step['d']); ?></p>
                            </div>
                        </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ol>
            </div>
        </header>

        
        <nav class="mx-auto max-w-3xl px-5 sm:px-8" aria-label="Langkah pengajuan">
            <ol class="flex items-center gap-3 font-heading text-xs font-bold uppercase tracking-widest text-text-secondary">
                <li class="flex items-center gap-2"><span class="text-[#0051BA]">01</span><span class="hidden sm:inline">Pilih bidang</span></li>
                <li class="h-px flex-1 bg-border" aria-hidden="true"></li>
                <li class="flex items-center gap-2"><span>02</span><span class="hidden sm:inline">Jelaskan jasa</span></li>
                <li class="h-px flex-1 bg-border" aria-hidden="true"></li>
                <li class="flex items-center gap-2"><span>03</span><span class="hidden sm:inline">Kirim</span></li>
            </ol>
        </nav>

        
        <section class="mx-auto max-w-3xl px-5 pb-24 pt-12 sm:px-8">
            <div class="mb-10">
                <p class="font-heading text-xs font-bold uppercase tracking-[0.28em] text-[#0051BA]">Form pengajuan</p>
                <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-black sm:text-4xl">Kenalin jasamu ke SkillHub</h2>
                <p class="mt-3 max-w-lg text-base leading-7 text-text-secondary">Lengkapi detail berikut agar admin dapat meninjaunya dengan mudah.</p>
            </div>

            <form method="POST" action="<?php echo e(route('services.store')); ?>" enctype="multipart/form-data" autocomplete="off" class="space-y-12" x-data="{ submitting: false }" @submit="submitting = true">

                <?php echo csrf_field(); ?>

                
                <section class="grid gap-6 border-t border-border pt-8 lg:grid-cols-[200px_1fr]">
                    <div>
                        <span class="font-heading text-sm font-extrabold tracking-widest text-[#0051BA]">01</span>
                        <h3 class="mt-1 font-heading text-lg font-bold tracking-tight text-black">Kenalin jasamu</h3>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <label for="title" class="label-field">Nama jasa</label>
                            <input type="text" name="title" id="title" value="<?php echo e(old('title')); ?>" required maxlength="255" placeholder="Contoh: Desain poster acara sekolah" class="input-field <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-[#0051BA] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-[#0051BA]"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="category_id" class="label-field">Kategori</label>
                                <select name="category_id" id="category_id" required class="input-field <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-[#0051BA] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Pilih kategori</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <option value="<?php echo e($category->id); ?>" <?php if(old('category_id') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-[#0051BA]"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label for="subcategory_id" class="label-field">Subkategori</label>
                                <select name="subcategory_id" id="subcategory_id" required disabled class="input-field <?php $__errorArgs = ['subcategory_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-[#0051BA] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Pilih kategori dulu</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <option value="<?php echo e($subcategory->id); ?>" data-category-id="<?php echo e($subcategory->category_id); ?>" <?php if(old('subcategory_id') == $subcategory->id): echo 'selected'; endif; ?>><?php echo e($subcategory->name); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['subcategory_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-[#0051BA]"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="mt-4 p-4 bg-[#FFF9E6] rounded">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-black mb-1">Tidak menemukan kategori yang sesuai?</p>
                                    <p class="text-xs text-[#555555]">Request kategori atau subkategori baru ke admin</p>
                                </div>
                                <button type="button" 
                                        @click="$dispatch('open-category-request-modal')"
                                        class="shrink-0 px-3 py-1.5 text-xs font-bold uppercase tracking-wide bg-[#0051BA] text-white hover:bg-[#003d8f] transition">
                                    Request
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                
                <section class="grid gap-6 border-t border-border pt-8 lg:grid-cols-[200px_1fr]">
                    <div>
                        <span class="font-heading text-sm font-extrabold tracking-widest text-[#0051BA]">02</span>
                        <h3 class="mt-1 font-heading text-lg font-bold tracking-tight text-black">Tentukan nilainya</h3>
                    </div>
                    <div>
                        <label for="price" class="label-field">Harga jasa</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center font-heading text-sm font-bold text-text-secondary">Rp</span>
                            <input type="number" name="price" id="price" value="<?php echo e(old('price')); ?>" required min="0" inputmode="numeric" placeholder="50000" class="input-field pl-12 tabular-nums <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-[#0051BA] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-[#0051BA]"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </section>

                
                <section class="grid gap-6 border-t border-border pt-8 lg:grid-cols-[200px_1fr]">
                    <div>
                        <span class="font-heading text-sm font-extrabold tracking-widest text-[#0051BA]">03</span>
                        <h3 class="mt-1 font-heading text-lg font-bold tracking-tight text-black">Ceritakan jasamu</h3>
                    </div>
                    <div>
                        <label for="description" class="label-field">Deskripsi jasa</label>
                        <textarea name="description" id="description" rows="6" required placeholder="Jelaskan apa yang akan kamu kerjakan, hasil yang didapat, dan ketentuan jasamu..." class="input-field resize-y leading-7 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-[#0051BA] <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description')); ?></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-[#0051BA]"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </section>

                
                <section class="grid gap-6 border-t border-border pt-8 lg:grid-cols-[200px_1fr]">
                    <div>
                        <span class="font-heading text-sm font-extrabold tracking-widest text-[#0051BA]">04</span>
                        <h3 class="mt-1 font-heading text-lg font-bold tracking-tight text-black">Tampilkan karyamu</h3>
                    </div>
                    <div class="space-y-8">

                        
                        <div x-data="{ img: '' }">
                            <label for="image" class="label-field">Gambar contoh <span class="font-medium normal-case tracking-normal text-text-secondary">(opsional)</span></label>
                            <label for="image" class="group flex cursor-pointer flex-col items-center justify-center border border-dashed border-border bg-white px-6 py-10 text-center transition duration-200 hover:border-[#0051BA]">
                                <span class="flex h-12 w-12 items-center justify-center rounded-full border border-border text-2xl leading-none text-black transition duration-200 group-hover:border-[#0051BA] group-hover:text-[#0051BA]" aria-hidden="true">+</span>
                                <span class="mt-4 font-heading text-sm font-bold uppercase tracking-wide text-black">Tambahkan gambar contoh</span>
                                <span class="mt-1 text-xs text-text-secondary" x-text="img || 'JPG / PNG · Maks. 2 MB'"></span>
                            </label>
                            <input id="image" name="image" type="file" accept="image/jpeg,image/png" class="sr-only" @change="img = $event.target.files[0]?.name || ''">
                            <p class="mt-2 text-xs text-text-secondary">JPG atau PNG, maksimal 2 MB.</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-[#0051BA]"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        
                        <div
                            x-data="{
                                files: [],
                                dragActive: false,
                                setFiles(list) {
                                    this.files.forEach(item => URL.revokeObjectURL(item.preview));
                                    this.files = Array.from(list).slice(0, 3).map(file => ({ file, preview: URL.createObjectURL(file) }));
                                    const transfer = new DataTransfer();
                                    this.files.forEach(item => transfer.items.add(item.file));
                                    this.$refs.portfolioInput.files = transfer.files;
                                },
                                removeFile(index) {
                                    URL.revokeObjectURL(this.files[index].preview);
                                    this.files.splice(index, 1);
                                    const transfer = new DataTransfer();
                                    this.files.forEach(item => transfer.items.add(item.file));
                                    this.$refs.portfolioInput.files = transfer.files;
                                }
                            }"
                            @dragover.prevent="dragActive = true"
                            @dragleave.prevent="dragActive = false"
                            @drop.prevent="dragActive = false; setFiles($event.dataTransfer.files)"
                        >
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <label for="portfolio_images" class="label-field mb-0">Portofolio <span class="font-medium normal-case tracking-normal text-text-secondary">(maks. 3 foto)</span></label>
                                <span class="font-heading text-xs font-bold uppercase tracking-widest text-[#0051BA]" x-text="files.length + ' / 3 foto'"></span>
                            </div>
                            <label for="portfolio_images" class="group flex cursor-pointer flex-col items-center justify-center border border-dashed px-5 py-8 text-center transition duration-200" :class="dragActive ? 'border-[#0051BA] bg-[#0051BA]/5' : 'border-border bg-white hover:border-[#0051BA]'">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full border border-border text-xl text-black transition duration-200 group-hover:border-[#0051BA]" aria-hidden="true">↑</span>
                                <span class="mt-3 font-heading text-sm font-bold uppercase tracking-wide text-black">Tarik foto ke sini atau pilih dari perangkat</span>
                                <span class="mt-1 text-xs text-text-secondary">JPG, PNG, atau WEBP · maksimal 2 MB per foto</span>
                                <input x-ref="portfolioInput" @change="setFiles($event.target.files)" type="file" name="portfolio_images[]" id="portfolio_images" accept="image/jpeg,image/png,image/webp" multiple class="sr-only">
                            </label>
                            <div x-show="files.length" x-cloak class="mt-3 grid grid-cols-3 gap-3">
                                <template x-for="(item, index) in files" :key="item.preview">
                                    <div class="group relative aspect-square overflow-hidden border border-border bg-bg-muted">
                                        <img :src="item.preview" alt="Pratinjau portofolio" class="h-full w-full object-cover">
                                        <button type="button" @click="removeFile(index)" class="absolute right-1.5 top-1.5 flex h-7 w-7 items-center justify-center rounded-full bg-black/75 text-sm font-bold text-white opacity-0 transition group-hover:opacity-100 focus:opacity-100" aria-label="Hapus foto">×</button>
                                    </div>
                                </template>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['portfolio_images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-[#0051BA]"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['portfolio_images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-[#0051BA]"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                    </div>
                </section>

                
                <div class="flex flex-col-reverse gap-3 border-t border-border pt-8 sm:flex-row sm:items-center sm:justify-between">
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn-ghost justify-center px-4 py-3 sm:justify-start">Nanti dulu</a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 bg-[#0051BA] text-white font-heading font-bold text-xs uppercase tracking-wider px-6 py-3 border-2 border-[#0051BA] transition-all duration-150 hover:bg-white hover:text-[#0051BA] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed" :disabled="submitting">
                        <span x-show="!submitting">Kirim untuk ditinjau <span aria-hidden="true">→</span></span>
                        <span x-show="submitting" x-cloak>Mengirim…</span>
                    </button>
                </div>
            </form>
        </section>

        
        <div x-data="{ showModal: false }" 
             @open-category-request-modal.window="showModal = true"
             @keydown.escape.window="showModal = false"
             x-show="showModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            
            
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="showModal = false"></div>
            
            
            <div class="flex min-h-screen items-center justify-center p-4">
                <div @click.away="showModal = false" 
                     class="relative w-full max-w-lg bg-white shadow-xl transform transition-all">
                    
                    
                    <div class="border-b border-[#DDDDDD] px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-heading text-lg font-bold text-black">Request Kategori/Subkategori</h3>
                            <button type="button" @click="showModal = false" class="text-2xl leading-none text-[#999999] hover:text-black transition">&times;</button>
                        </div>
                    </div>

                    
                    <form method="POST" action="<?php echo e(route('category-request.store')); ?>" class="p-6 space-y-4">
                        <?php echo csrf_field(); ?>

                        
                        <div x-data="{ requestType: '' }">
                            <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Tipe Request <span class="text-[#E4002B]">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <label class="relative flex items-center px-3 py-2.5 border cursor-pointer transition text-xs"
                                       :class="requestType === 'category_request' ? 'border-[#0051BA] bg-[#0051BA] text-white' : 'border-[#DDDDDD] hover:border-[#0051BA]'">
                                    <input type="radio" name="request_type" value="category_request" x-model="requestType" required class="sr-only">
                                    <span class="font-bold uppercase">Kategori Baru</span>
                                </label>
                                <label class="relative flex items-center px-3 py-2.5 border cursor-pointer transition text-xs"
                                       :class="requestType === 'subcategory_request' ? 'border-[#0051BA] bg-[#0051BA] text-white' : 'border-[#DDDDDD] hover:border-[#0051BA]'">
                                    <input type="radio" name="request_type" value="subcategory_request" x-model="requestType" required class="sr-only">
                                    <span class="font-bold uppercase">Subkategori Baru</span>
                                </label>
                            </div>

                            
                            <div x-show="requestType === 'category_request'" x-collapse class="mt-4 space-y-3">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Nama Kategori <span class="text-[#E4002B]">*</span></label>
                                    <input type="text" 
                                           name="requested_category_name" 
                                           :required="requestType === 'category_request'"
                                           class="w-full px-3 py-2 border border-[#DDDDDD] focus:border-[#0051BA] focus:outline-none transition text-sm"
                                           placeholder="Contoh: Fotografi & Videografi">
                                </div>
                            </div>

                            
                            <div x-show="requestType === 'subcategory_request'" x-collapse class="mt-4 space-y-3">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Pilih Kategori <span class="text-[#E4002B]">*</span></label>
                                    <select name="existing_category_id" 
                                            :required="requestType === 'subcategory_request'"
                                            class="w-full px-3 py-2 border border-[#DDDDDD] focus:border-[#0051BA] focus:outline-none transition text-sm">
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Nama Subkategori <span class="text-[#E4002B]">*</span></label>
                                    <input type="text" 
                                           name="requested_subcategory_name" 
                                           :required="requestType === 'subcategory_request'"
                                           class="w-full px-3 py-2 border border-[#DDDDDD] focus:border-[#0051BA] focus:outline-none transition text-sm"
                                           placeholder="Contoh: Wedding Photography">
                                </div>
                            </div>

                            
                            <div class="mt-4">
                                <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Alasan Request <span class="text-[#E4002B]">*</span></label>
                                <textarea name="reason_for_request" 
                                          rows="3" 
                                          required
                                          minlength="10"
                                          maxlength="1000"
                                          class="w-full px-3 py-2 border border-[#DDDDDD] focus:border-[#0051BA] focus:outline-none transition text-sm resize-y"
                                          placeholder="Jelaskan mengapa kategori/subkategori ini perlu ditambahkan..."></textarea>
                                <p class="text-xs text-[#999999] mt-1">Min 10 karakter, max 1000 karakter</p>
                            </div>

                            
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" 
                                        @click="showModal = false"
                                        class="px-4 py-2 text-xs font-bold uppercase border border-[#DDDDDD] hover:border-[#555555] transition">
                                    Batal
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 text-xs font-bold uppercase bg-[#0051BA] text-white hover:bg-black transition">
                                    Kirim Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/services/create.blade.php ENDPATH**/ ?>