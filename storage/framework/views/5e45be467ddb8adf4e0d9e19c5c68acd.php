<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($title ?? 'SkillHub - Marketplace Jasa Sekolah'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS via CDN (cepat, no build) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Style tambahan -->
    <style>
        body {
            background-color: #f8fafc;
        }
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
        .shadow-card {
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        .shadow-card:hover {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
    </style>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col pt-16">

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
         data-is-admin="<?php echo e(auth()->user()?->isAdmin() ? 'true' : 'false'); ?>"
         data-admin-dashboard="<?php echo e(auth()->user()?->isAdmin() ? route('admin.dashboard') : ''); ?>"></div>
    <script id="skillhub-account-notifications-data" type="application/json"><?php echo json_encode($accountNotifications ?? collect(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT, 512) ?></script>

    <!-- ====== MAIN CONTENT ====== -->
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-6">
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
        <!-- Flash Message -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 rounded-lg shadow-sm mb-6 flex items-center justify-between">
                <span><?php echo e(session('success')); ?></span>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 rounded-lg shadow-sm mb-6 flex items-center justify-between">
                <span><?php echo e(session('error')); ?></span>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php echo e($slot); ?>


        <!-- Konten halaman berbasis layout tetap didukung. -->
        <?php echo $__env->yieldContent('content'); ?>
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

    <!-- ====== SCRIPTS ====== -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/components/layouts/app.blade.php ENDPATH**/ ?>