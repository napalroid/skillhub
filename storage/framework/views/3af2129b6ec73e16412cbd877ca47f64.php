<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Buat Pesanan — '.e($service->title).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Buat Pesanan — '.e($service->title).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/booking-calendar.jsx']); ?>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:py-14">

        <div class="mb-8">
            <a href="<?php echo e(route('services.show', $service)); ?>"
               class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-[0.16em] text-gray-400 transition hover:text-gray-700">
                <span aria-hidden="true">←</span> Kembali ke jasa
            </a>
            <div class="mt-6 flex items-center justify-between">
                <div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->time_slots_enabled): ?>
                        <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Pilih tanggal dan waktu</h1>
                        <p class="mt-2 text-sm text-gray-400">Langkah 1 dari 3</p>
                    <?php else: ?>
                        <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Buat Pesanan</h1>
                        <p class="mt-2 text-sm text-gray-400">Isi informasi booking untuk pesanan Anda</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->time_slots_enabled): ?>
            <div class="mt-4 h-1 w-full overflow-hidden rounded-full bg-gray-800">
                <div class="h-full w-1/3 bg-white"></div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <form method="POST" action="<?php echo e(route('orders.store')); ?>" id="orderForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="service_id" value="<?php echo e($service->id); ?>">
            <input type="hidden" name="time_slot_id" id="selected_slot_id">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->time_slots_enabled): ?>
            <div id="booking-calendar-root" 
                 data-service-id="<?php echo e($service->id); ?>"
                 data-available-slots="<?php echo e(json_encode($availableSlots)); ?>">
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->booking_config && isset($service->booking_config['enabled']) && $service->booking_config['enabled'] && !empty($service->booking_config['fields'])): ?>
            <div class="mt-8 rounded-xl border border-gray-800 bg-black p-6">
                <h3 class="mb-6 text-lg font-bold text-white">Informasi Booking</h3>
                <div class="grid gap-6 sm:grid-cols-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $service->booking_config['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div>
                        <label for="booking_<?php echo e($field['name']); ?>" class="mb-2 block text-sm font-bold text-white">
                            <?php echo e($field['label']); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field['required']): ?> <span class="text-red-400">*</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </label>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($field['type'] === 'textarea'): ?>
                            <textarea 
                                name="booking_data[<?php echo e($field['name']); ?>]" 
                                id="booking_<?php echo e($field['name']); ?>"
                                rows="4"
                                placeholder="<?php echo e($field['placeholder'] ?? ''); ?>"
                                <?php echo e($field['required'] ? 'required' : ''); ?>

                                class="w-full resize-y rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-gray-500 focus:ring-1 focus:ring-gray-500"><?php echo e(old('booking_data.' . $field['name'])); ?></textarea>
                        
                        <?php elseif($field['type'] === 'select'): ?>
                            <select 
                                name="booking_data[<?php echo e($field['name']); ?>]" 
                                id="booking_<?php echo e($field['name']); ?>"
                                <?php echo e($field['required'] ? 'required' : ''); ?>

                                class="w-full rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500">
                                <option value="">Pilih <?php echo e($field['label']); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $field['options'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <option value="<?php echo e($option); ?>" <?php echo e(old('booking_data.' . $field['name']) == $option ? 'selected' : ''); ?>><?php echo e($option); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        
                        <?php elseif($field['type'] === 'number'): ?>
                            <input 
                                type="number" 
                                name="booking_data[<?php echo e($field['name']); ?>]" 
                                id="booking_<?php echo e($field['name']); ?>"
                                value="<?php echo e(old('booking_data.' . $field['name'])); ?>"
                                placeholder="<?php echo e($field['placeholder'] ?? ''); ?>"
                                min="<?php echo e($field['min'] ?? ''); ?>"
                                max="<?php echo e($field['max'] ?? ''); ?>"
                                <?php echo e($field['required'] ? 'required' : ''); ?>

                                class="w-full rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-gray-500 focus:ring-1 focus:ring-gray-500">
                        
                        <?php elseif($field['type'] === 'date'): ?>
                            <input 
                                type="date" 
                                name="booking_data[<?php echo e($field['name']); ?>]" 
                                id="booking_<?php echo e($field['name']); ?>"
                                value="<?php echo e(old('booking_data.' . $field['name'])); ?>"
                                <?php echo e($field['required'] ? 'required' : ''); ?>

                                class="w-full rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500">
                        
                        <?php elseif($field['type'] === 'time'): ?>
                            <input 
                                type="time" 
                                name="booking_data[<?php echo e($field['name']); ?>]" 
                                id="booking_<?php echo e($field['name']); ?>"
                                value="<?php echo e(old('booking_data.' . $field['name'])); ?>"
                                <?php echo e($field['required'] ? 'required' : ''); ?>

                                class="w-full rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500">
                        
                        <?php else: ?>
                            <input 
                                type="text" 
                                name="booking_data[<?php echo e($field['name']); ?>]" 
                                id="booking_<?php echo e($field['name']); ?>"
                                value="<?php echo e(old('booking_data.' . $field['name'])); ?>"
                                placeholder="<?php echo e($field['placeholder'] ?? ''); ?>"
                                maxlength="<?php echo e($field['maxlength'] ?? 255); ?>"
                                <?php echo e($field['required'] ? 'required' : ''); ?>

                                class="w-full rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-gray-500 focus:ring-1 focus:ring-gray-500">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($field['help_text'])): ?>
                            <p class="mt-1 text-xs text-gray-400"><?php echo e($field['help_text']); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['booking_data.' . $field['name']];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-xs font-medium text-red-400"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="mt-8 rounded-xl border border-gray-800 bg-black p-6">
                <label for="message" class="mb-2 block text-sm font-bold text-white">Pesan untuk penyedia jasa</label>
                <textarea name="message" id="message" rows="4" maxlength="1000"
                          placeholder="Halo, saya tertarik dengan jasa ini..."
                          class="w-full resize-y rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-gray-500 focus:ring-1 focus:ring-gray-500"><?php echo e(old('message')); ?></textarea>
                <p class="mt-2 text-xs text-gray-400">Opsional. Bisa langsung bayar, atau diskusikan dulu sebelum menyetujui harga.</p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-2 text-xs font-medium text-red-400"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="fixed bottom-0 left-0 right-0 z-50 border-t border-gray-800 bg-black/95 backdrop-blur-sm">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6">
                    <div class="flex flex-col gap-1">
                        <p class="text-xs text-gray-400">Total Harga</p>
                        <p class="text-lg font-bold text-white">Rp<?php echo e(number_format($service->price, 0, ',', '.')); ?></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="<?php echo e(route('services.show', $service)); ?>"
                           class="rounded-lg border border-gray-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-gray-900">
                            Kembali
                        </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($service->time_slots_enabled): ?>
                        <button type="submit" id="submitBtn"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-700 px-6 py-3 text-sm font-bold text-gray-400 transition hover:bg-gray-600 cursor-not-allowed">
                            <span>Pilih Waktu Dulu</span>
                            <span aria-hidden="true">←</span>
                        </button>
                        <?php else: ?>
                        <button type="submit" id="submitBtn"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-bold text-black transition hover:bg-gray-100">
                            <span>Lanjut Pembayaran</span>
                            <span aria-hidden="true">→</span>
                        </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="h-24"></div>
        </form>
    </div>

    <style>
        body {
            background: #000;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timeSlotEnabled = <?php echo e($service->time_slots_enabled ? 'true' : 'false'); ?>;
            
            <?php if($service->time_slots_enabled): ?>
            const submitBtn = document.getElementById('submitBtn');
            const slotInput = document.getElementById('selected_slot_id');
            
            window.updateSubmitButton = function(slotId) {
                if (slotId) {
                    submitBtn.disabled = false;
                    submitBtn.className = 'inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-bold text-black transition hover:bg-gray-100';
                    submitBtn.innerHTML = '<span>Lanjut Pembayaran</span><span aria-hidden="true">→</span>';
                } else {
                    submitBtn.disabled = true;
                    submitBtn.className = 'inline-flex items-center justify-center gap-2 rounded-lg bg-gray-700 px-6 py-3 text-sm font-bold text-gray-400 transition hover:bg-gray-600 cursor-not-allowed';
                    submitBtn.innerHTML = '<span>Pilih Waktu Dulu</span><span aria-hidden="true">←</span>';
                }
            };
            
            slotInput.addEventListener('change', function() {
                window.updateSubmitButton(this.value);
            });
            <?php endif; ?>
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/orders/create.blade.php ENDPATH**/ ?>