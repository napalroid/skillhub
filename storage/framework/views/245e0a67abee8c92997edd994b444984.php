<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'SkillHub')); ?> - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased h-full bg-gray-50 pt-16 text-gray-900">
    <?php if (isset($component)) { $__componentOriginal50a73e7d57605f77e4f417026f0ee281 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal50a73e7d57605f77e4f417026f0ee281 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.loading-screen','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('loading-screen'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal50a73e7d57605f77e4f417026f0ee281)): ?>
<?php $attributes = $__attributesOriginal50a73e7d57605f77e4f417026f0ee281; ?>
<?php unset($__attributesOriginal50a73e7d57605f77e4f417026f0ee281); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal50a73e7d57605f77e4f417026f0ee281)): ?>
<?php $component = $__componentOriginal50a73e7d57605f77e4f417026f0ee281; ?>
<?php unset($__componentOriginal50a73e7d57605f77e4f417026f0ee281); ?>
<?php endif; ?>
    <div class="min-h-screen flex flex-col">
        
        <!-- Navigation -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! (View::hasSection('hideNavigation'))): ?>
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
                 data-logout-url="<?php echo e(route('logout')); ?>"
                 data-notifications-url="<?php echo e(auth()->check() ? route('notifications.index') : ''); ?>"
                 data-notifications-read-all-url="<?php echo e(auth()->check() ? route('notifications.read-all') : ''); ?>"
                 data-dompet="<?php echo e(route('wallet.index')); ?>"
                  data-pesanan="<?php echo e(route('orders.index')); ?>"
                  data-services-my="<?php echo e(url('/jasa/saya')); ?>"
                 data-csrf-token="<?php echo e(csrf_token()); ?>"
                 data-is-admin="<?php echo e(auth()->user()?->isAdmin() ? 'true' : 'false'); ?>"
                 data-admin-dashboard="<?php echo e(auth()->user()?->isAdmin() ? route('admin.dashboard') : ''); ?>"></div>
            <script id="skillhub-account-notifications-data" type="application/json"><?php echo json_encode($accountNotifications ?? collect(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT, 512) ?></script>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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

        <!-- Page Heading -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($header)): ?>
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <?php echo e($header); ?>

                </div>
            </header>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Page Content -->
        <main class="flex-grow">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($slot)): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty(trim($slot))): ?>
                    <?php echo e($slot); ?>

                <?php else: ?>
                    <?php echo $__env->yieldContent('content'); ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
                <?php echo $__env->yieldContent('content'); ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </main>

        <!-- Footer -->
        <?php if (! empty(trim($__env->yieldContent('pageFooter')))): ?>
            <?php echo $__env->yieldContent('pageFooter'); ?>
        <?php else: ?>
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Session Status Toast (Optional Simple Implementation) -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-md shadow-lg z-50 transition-opacity duration-300">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/layouts/app.blade.php ENDPATH**/ ?>