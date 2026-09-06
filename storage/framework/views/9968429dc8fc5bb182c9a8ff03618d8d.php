<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve(['variant' => 'login','title' => 'Masuk','subtitle' => 'Selamat datang kembali! Masuk dengan akun yang terdaftar.'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
        <div class="mb-5 flex items-start gap-3 text-sm text-green-700 bg-green-50 border border-green-200 px-4 py-3 rounded-lg">
            <svg class="h-4 w-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any() && ! $errors->has('email') && ! $errors->has('password')): ?>
        <div class="mb-5 text-sm text-red-700 bg-red-50 border border-red-200 px-4 py-3 rounded-lg">
            <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-5">
        <?php echo csrf_field(); ?>

        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Email','name' => 'email','type' => 'email','value' => ''.e(old('email')).'','placeholder' => 'nama@sekolah.sch.id','required' => true,'autofocus' => true,'autocomplete' => 'username','error' => $errors->first('email')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Email','name' => 'email','type' => 'email','value' => ''.e(old('email')).'','placeholder' => 'nama@sekolah.sch.id','required' => true,'autofocus' => true,'autocomplete' => 'username','error' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->first('email'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
            <div class="relative">
                <input
                    :type="show ? 'text' : 'password'"
                    name="password"
                    id="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                    class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 pr-11 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                >
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-0.5"
                    tabindex="-1"
                    aria-label="Tampilkan password"
                >
                    <svg x-show="!show" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg x-show="show" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1.5"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                Ingat saya
            </label>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('password.request')): ?>
                <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                    Lupa password?
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','class' => 'w-full !py-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'w-full !py-3']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            Masuk
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
    </form>

    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
        <div class="relative flex justify-center"><span class="bg-white px-3 text-xs text-slate-400">atau</span></div>
    </div>

    <p class="text-sm text-slate-500 text-center">
        Belum punya akun?
        <a href="<?php echo e(route('register')); ?>" class="text-blue-600 font-semibold hover:text-blue-700 transition-colors">Daftar gratis</a>
    </p>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/auth/login.blade.php ENDPATH**/ ?>