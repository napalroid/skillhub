<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>SkillHub - Marketplace Jasa Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'DM Sans', sans-serif; }
        
        .site-footer .footer-heading {
            @apply text-base font-bold uppercase tracking-widest text-white mb-6;
        }
        
        .site-footer .footer-list {
            @apply space-y-3;
        }
        
        .site-footer .footer-list a {
            @apply text-sm text-white/60 transition-all duration-250 cubic-bezier(0.4, 0, 0.2, 1) relative group;
        }
        
        .site-footer .footer-list a::after {
            @apply absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-white transition-transform duration-250 cubic-bezier(0.4, 0, 0.2, 1);
            content: "";
        }
        
        .site-footer .footer-list a:hover {
            @apply text-white;
        }
        
        .site-footer .footer-list a:hover::after {
            @apply scale-x-100 origin-left;
        }
        
        .site-footer .footer-newsletter-input {
            @apply flex-1 min-w-0 bg-black/50 border border-white/20 px-4 py-3 text-sm text-white placeholder-white/40 focus:border-white/60 focus:outline-none transition-colors duration-250;
        }
        
        .site-footer .footer-newsletter-btn {
            @apply flex items-center justify-center px-4 py-3 bg-white/10 hover:bg-white/20 transition-all duration-250;
        }
        
        .site-footer .footer-newsletter-btn:hover svg {
            @apply translate-x-1;
        }
        
        .site-footer .footer-social {
            @apply text-white/60 transition-all duration-250 hover:text-white hover:scale-1.08 hover:-translate-y-0.5 hover:opacity-100;
        }
        
        .site-footer .footer-mini {
            @apply text-xs text-white/40 transition-all duration-250 hover:text-white/80;
        }
        
        .site-footer .footer-top {
            @apply text-xs text-white/50 hover:text-white transition-all duration-250 hover:scale-105;
            background: none;
            border: none;
            cursor: pointer;
        }
        
        .site-footer .js-anim [data-reveal] {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .site-footer .js-anim [data-reveal].is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .footer-scarface {
            background: #000;
        }
        
        .footer-scarface img {
            transition: transform 0.3s ease;
        }
        
        .footer-scarface:hover img {
            transform: scale(1.02);
        }
    </style>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js', 'resources/js/routes/welcome.js']); ?>
</head>

<body id="top" class="overflow-x-hidden bg-white text-[#171717]">
     <div id="skillhub-staggered-menu"
          data-home="#top"
          data-marketplace="#jasa"
          data-how="#how-we-work"
          data-why-us="#keunggulan"
          data-login="<?php echo e(route('login')); ?>"
          data-register="<?php echo e(route('register')); ?>"
          data-get-started="#cara-kerja"
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
    <script id="skillhub-account-notifications-data" type="application/json"><?php echo json_encode($accountNotifications ?? collect(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT, 512) ?></script>

    <?php if (isset($component)) { $__componentOriginal5136c243f45768d74c011ba38361526a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5136c243f45768d74c011ba38361526a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification-toast','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification-toast'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5136c243f45768d74c011ba38361526a)): ?>
<?php $attributes = $__attributesOriginal5136c243f45768d74c011ba38361526a; ?>
<?php unset($__attributesOriginal5136c243f45768d74c011ba38361526a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5136c243f45768d74c011ba38361526a)): ?>
<?php $component = $__componentOriginal5136c243f45768d74c011ba38361526a; ?>
<?php unset($__componentOriginal5136c243f45768d74c011ba38361526a); ?>
<?php endif; ?>

    <main>
        <section class="relative isolate overflow-hidden px-5 pb-28 pt-36 sm:pb-36 sm:pt-44">
            <div class="absolute inset-0 -z-10">
                <img src="<?php echo e(asset('images/skillhub-hero.png')); ?>"
                     alt="Ilustrasi SkillHub"
                     class="h-full w-full object-cover object-[65%_center]">
                <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/75 via-45% to-white/10"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-white/60 via-transparent to-white/10"></div>
            </div>

            <div class="mx-auto grid max-w-6xl items-center gap-12 lg:grid-cols-2">
                <div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                        <div id="greeting-decrypted" 
                             data-text="<?php echo e(auth()->user()->name); ?>"
                             class="text-lg font-bold text-black">
                        </div>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-2 rounded-full border border-black bg-white/90 px-4 py-2 text-xs font-bold text-black">
                            <span class="h-2 w-2 rounded-full bg-black"></span>
                            Marketplace jasa khusus siswa
                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <h1 class="mt-6 max-w-2xl text-4xl font-bold leading-[.9] tracking-[-.07em] text-black sm:text-5xl lg:text-7xl">
                        Ubah keahlianmu menjadi <span class="text-black/45">peluang.</span>
                    </h1>

                    <p class="mt-6 max-w-xl text-base leading-8 text-black/70 sm:text-lg">
                        SkillHub mempertemukan siswa yang memiliki keahlian dengan teman sekolah yang membutuhkan bantuan. Transaksi aman, komunikasi mudah, dan semua dalam satu platform.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="<?php echo e(route('services.index')); ?>" class="inline-flex items-center gap-2 bg-black px-5 py-3 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-black/75">
                            Jelajahi Jasa <span aria-hidden="true">→</span>
                        </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('services.create')); ?>" class="inline-flex items-center gap-2 border border-black bg-white px-5 py-3 text-sm font-bold text-black transition hover:-translate-y-0.5 hover:bg-black hover:text-white">
                                Ajukan Jasa
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center gap-2 border border-black bg-white px-5 py-3 text-sm font-bold text-black transition hover:-translate-y-0.5 hover:bg-black hover:text-white">
                                Mulai Bergabung
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-x-6 gap-y-3 text-sm text-black/65">
                        <span>✓ Transaksi escrow</span>
                        <span>✓ Siswa terverifikasi</span>
                        <span>✓ Review terpercaya</span>
                    </div>
                </div>

                <div class="relative mx-auto w-full max-w-lg">
                    <div class="rounded-none border border-black/15 bg-white p-5 shadow-2xl shadow-black/15">
                        <div class="flex items-center justify-between border-b border-black/10 pb-4">
                            <div>
                                <p class="text-xs font-semibold text-black/45">PESANAN #024</p>
                                <h2 class="mt-1 font-bold text-black">Desain Poster Acara Sekolah</h2>
                            </div>
                            <span class="border border-black px-3 py-1 text-xs font-bold text-black">Dana ditahan</span>
                        </div>
                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="bg-[#f3f3f3] p-4">
                                <p class="text-xs text-black/45">Penyedia jasa</p>
                                <p class="mt-1 font-bold text-black">Nadia A.</p>
                            </div>
                            <div class="bg-black p-4">
                                <p class="text-xs text-white/55">Harga disepakati</p>
                                <p class="mt-1 font-bold text-white">Rp75.000</p>
                            </div>
                        </div>
                        <p class="mt-5 text-xs font-semibold text-black/45">ALUR PESANAN</p>
                        <div class="mt-3 grid grid-cols-4 gap-2">
                            <span class="h-2 bg-black"></span><span class="h-2 bg-black"></span><span class="h-2 bg-black"></span><span class="h-2 bg-black/15"></span>
                        </div>
                        <div class="mt-5 border border-black/10 bg-[#f3f3f3] p-4 text-sm text-black/70">
                            Pembayaran sudah diverifikasi. Penyedia jasa dapat mulai mengerjakan pesanan.
                        </div>
                    </div>
                    <div class="absolute -right-2 -top-5 border border-black/15 bg-white p-3 shadow-lg sm:right-3">
                        <p class="text-[11px] text-black/45">Dana aman escrow</p>
                        <p class="font-bold text-black">Rp75.000</p>
                    </div>
                </div>
            </div>

            
            <svg class="absolute inset-x-0 bottom-0 z-10 h-16 w-full text-white sm:h-24"
                 viewBox="0 0 1440 120"
                 preserveAspectRatio="none"
                 aria-hidden="true">
                <path fill="currentColor"
                      d="M0,64 C180,116 360,116 540,78 C720,40 840,4 1020,30 C1200,56 1320,104 1440,70 L1440,120 L0,120 Z"></path>
            </svg>
        </section>

        <div id="skillhub-orbit-stats" data-service-count="<?php echo e($featuredServices->count()); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <span data-category-name="<?php echo e($category->name); ?>" data-category-icon="<?php echo e($category->displayIcon()); ?>" data-category-image="<?php echo e($category->image ? asset('storage/' . $category->image) : ''); ?>"></span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <span data-category-name="Skill kreatif" data-category-icon="✦" data-category-image=""></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div id="skillhub-feature-motion"></div>

        <section id="jasa">
            <div id="skillhub-featured-services" data-marketplace-url="<?php echo e(route('services.index')); ?>"></div>
            <script id="skillhub-featured-services-data" type="application/json"><?php echo json_encode($featuredServiceCards, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT, 512) ?></script>
            <div class="hidden">
            <div class="mx-auto max-w-6xl">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-widest text-blue-600">Marketplace</p>
                        <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Jasa unggulan untukmu</h2>
                        <p class="mt-2 text-slate-500">Temukan bantuan dari teman sekolah dengan beragam keahlian.</p>
                    </div>
                    <a href="<?php echo e(route('services.index')); ?>" class="text-sm font-bold text-blue-600 hover:text-blue-800">Lihat semua jasa →</a>
                </div>

                <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $featuredServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <a href="<?php echo e(route('services.show', $service)); ?>" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                            <div class="flex items-start justify-between gap-3">
                                <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700">
                                    <?php echo e($service->subcategory?->name ?? 'Jasa siswa'); ?>

                                </span>
                                <span class="text-blue-600 transition group-hover:translate-x-1">→</span>
                            </div>
                            <h3 class="mt-5 line-clamp-1 font-bold text-slate-900"><?php echo e($service->title); ?></h3>
                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500"><?php echo e($service->description); ?></p>
                            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                                <span class="text-xs text-slate-500"><?php echo e($service->seller?->name ?? 'Siswa SkillHub'); ?></span>
                                <span class="font-bold text-blue-700">Rp<?php echo e(number_format($service->price, 0, ',', '.')); ?></span>
                            </div>
                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white py-12 text-center">
                            <p class="font-semibold text-slate-700">Belum ada jasa unggulan.</p>
                            <p class="mt-1 text-sm text-slate-500">Jadilah yang pertama menawarkan keahlianmu.</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            </div>
        </section>

        
        <section style="width:100vw; margin-left:calc(50% - 50vw);">
            <div class="grid grid-cols-1 sm:grid-cols-3">
                <div class="relative block aspect-[3/4] overflow-hidden bg-black">
                    <img src="<?php echo e(asset('storage/marketplace-image/EDGARDAVIDSKILLHUB.webp')); ?>"
                         alt="SkillHub"
                         loading="lazy"
                         class="h-full w-full object-cover object-left">
                    <div class="absolute bottom-6 left-4 sm:bottom-12 sm:left-1/3 flex flex-col gap-1.5 sm:gap-2">
                        <div class="bg-white px-2 py-0.5 w-fit">
                            <h3 class="font-display text-sm sm:text-2xl lg:text-3xl font-black uppercase leading-none text-black" style="font-family:'Archivo',sans-serif;letter-spacing:.12em">SKILLHUB</h3>
                        </div>
                        <div class="bg-white px-2 py-0.5 w-fit max-w-[220px] sm:max-w-none">
                            <p class="text-[10px] sm:text-sm text-black leading-tight sm:leading-relaxed">
                                Tempat anda menemukan jasa dari teman
                            </p>
                        </div>
                        <div class="bg-white px-2 py-0.5 w-fit max-w-[220px] sm:max-w-none">
                            <p class="text-[10px] sm:text-sm text-black leading-tight sm:leading-relaxed">
                                sekolah dengan beragam keahlian.
                            </p>
                        </div>
                        <a href="<?php echo e(route('services.index')); ?>" class="inline-flex items-center gap-1.5 sm:gap-2 bg-black px-3 py-1.5 sm:px-6 sm:py-3 text-[10px] sm:text-sm font-bold text-white uppercase tracking-wider transition hover:bg-black/80 w-fit mt-1 sm:mt-0">
                            Beli sekarang <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
                <div class="relative block aspect-[3/4] overflow-hidden bg-black">
                    <img src="<?php echo e(asset('storage/marketplace-image/ZINEDINEZIDANESKILLHUB.webp')); ?>"
                         alt="SkillHub"
                         loading="lazy"
                         class="h-full w-full object-cover object-center">
                </div>
                <div class="relative block aspect-[3/4] overflow-hidden bg-black">
                    <img src="<?php echo e(asset('storage/marketplace-image/messiskillhub2.webp')); ?>"
                         alt="SkillHub"
                         loading="lazy"
                         class="h-full w-full object-cover object-right">
                </div>
            </div>
        </section>

        
        <section id="review-static" class="hidden" aria-hidden="true">
            <div class="absolute inset-y-0 left-0 w-2 bg-rose-300"></div>
            <div class="mx-auto grid max-w-6xl gap-10 lg:grid-cols-[220px_1fr] lg:items-center">
                <div class="px-2 sm:px-5">
                    <div class="text-7xl font-serif font-bold leading-none text-slate-300">“</div>
                    <h2 class="mt-3 text-3xl font-bold leading-tight tracking-tight text-slate-900">
                        Apa kata<br>pengguna kami
                    </h2>
                    <div class="mt-8 flex items-center gap-3 text-slate-400">
                        <button type="button" class="text-2xl leading-none transition hover:text-slate-900" aria-label="Review sebelumnya">←</button>
                        <span class="h-0.5 w-7 bg-slate-900"></span>
                        <span class="h-0.5 w-24 bg-slate-300"></span>
                        <button type="button" class="text-2xl leading-none text-slate-900" aria-label="Review berikutnya">→</button>
                    </div>
                </div>

                <div class="grid gap-7 md:grid-cols-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                        ['name' => 'Aulia Rahma', 'initial' => 'AR', 'time' => '1 hari lalu', 'color' => 'bg-blue-600', 'text' => 'SkillHub sangat membantu saya menemukan jasa desain untuk kegiatan sekolah. Harganya jelas, prosesnya aman, dan hasil pekerjaannya memuaskan.'],
                        ['name' => 'Rizky Pratama', 'initial' => 'RP', 'time' => '3 hari lalu', 'color' => 'bg-emerald-600', 'text' => 'Sebagai penyedia jasa, saya lebih mudah menawarkan keahlian kepada teman sekolah. Komunikasi dengan pemesan juga jadi lebih rapi dan nyaman.'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <article>
                            <div class="relative rounded-2xl bg-white px-8 py-7 shadow-[0_12px_30px_rgba(15,23,42,0.08)]">
                                <div class="absolute -bottom-4 left-7 h-0 w-0 border-r-[22px] border-t-[16px] border-r-transparent border-t-white"></div>
                                <p class="min-h-28 text-sm leading-7 text-slate-700">“<?php echo e($review['text']); ?>”</p>
                                <div class="mt-6 flex gap-0.5 text-lg tracking-tight text-emerald-500" aria-label="Rating lima dari lima">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center gap-3 pl-7">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full <?php echo e($review['color']); ?> text-xs font-bold text-white shadow-sm">
                                    <?php echo e($review['initial']); ?>

                                </span>
                                <div>
                                    <p class="text-sm font-bold text-slate-800"><?php echo e($review['name']); ?></p>
                                    <p class="text-xs text-slate-400"><?php echo e($review['time']); ?></p>
                                </div>
                            </div>
                        </article>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </section>

        <div id="skillhub-review-motion"></div>

        <div id="skillhub-how-we-work"></div>

        <div id="skillhub-cta-motion" data-action-url="<?php echo e(auth()->check() ? route('services.create') : route('register')); ?>" data-action-label="<?php echo e(auth()->check() ? 'Ajukan jasa' : 'Buat akun gratis'); ?>"></div>
    </main>

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
</body>
</html><?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/welcome.blade.php ENDPATH**/ ?>