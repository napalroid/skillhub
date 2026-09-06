<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Kelola Jam Booking — '.e($service->title).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Kelola Jam Booking — '.e($service->title).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/booking-calendar.jsx']); ?>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:py-14">

        <div class="mb-8">
            <a href="<?php echo e(route('services.my')); ?>"
               class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-[0.16em] text-gray-400 transition hover:text-gray-700">
                <span aria-hidden="true">←</span> Kembali ke jasa saya
            </a>
            <div class="mt-6">
                <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Kelola Booking</h1>
                <p class="mt-2 text-sm text-gray-400"><?php echo e($service->title); ?> - Klik tanggal untuk lihat pesanan, klik jam untuk filter</p>
            </div>
        </div>

        
        <div id="booking-calendar-root" 
             data-service-id="<?php echo e($service->id); ?>"
             data-available-slots="<?php echo e(json_encode(\App\Models\ServiceTimeSlot::where('service_id', $service->id)->whereDate('date', '>=', now()->format('Y-m-d'))->orderBy('date')->orderBy('time_start')->get()->map(fn($s) => [
                 'id' => $s->id,
                 'date' => $s->date->format('Y-m-d'),
                 'time_start' => $s->time_start,
                 'time_end' => $s->time_end,
                 'max_bookings' => $s->max_bookings,
                 'platform_bookings' => $s->orders()->whereNotIn('status', ['menunggu_pembayaran', 'dibatalkan'])->count(),

                 'available' => $s->getAvailableCountAttribute(),
             ]))); ?>"
             data-is-seller="true">
        </div>

        
        <div x-data="orderManager()" x-init="init()" class="mt-8 space-y-6">
            
            <div x-show="selectedDate" class="rounded-xl border border-gray-800 bg-black p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">
                            Pesanan pada <span x-text="formatDate(selectedDate)"></span>
                            <span x-show="selectedSlot" x-text="` jam ${selectedSlot?.time_start} - ${selectedSlot?.time_end}`"></span>
                        </h3>
                        <p class="mt-1 text-sm text-gray-400" x-show="!selectedSlot">Klik jam untuk filter berdasarkan waktu</p>
                    </div>
                    <button @click="clearFilter()" class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-bold text-white hover:bg-gray-700">
                        Reset Filter
                    </button>
                </div>
            </div>

            
            <div x-show="loading" class="rounded-xl border border-gray-800 bg-black p-8 text-center">
                <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-white border-t-transparent"></div>
                <p class="mt-3 text-sm text-gray-400">Memuat data...</p>
            </div>

            
            <div x-show="!loading && orders.length > 0" class="space-y-3">
                <template x-for="order in orders" :key="order.id">
                    <div class="rounded-xl border border-gray-800 bg-black p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                     <p class="text-lg font-bold text-white" x-text="order.buyer_name"></p>
                                     <span x-show="order.status !== 'menunggu_pembayaran' && order.status !== 'dibatalkan'" class="rounded-full px-3 py-1 text-xs font-bold uppercase"
                                           :class="{
                                               'bg-blue-900/50 text-blue-300': order.status === 'menunggu_konfirmasi',
                                               'bg-green-900/50 text-green-300': order.status === 'dikonfirmasi',
                                               'bg-yellow-900/50 text-yellow-300': order.status === 'dikerjakan',
                                               'bg-gray-900/50 text-gray-300': order.status === 'selesai'
                                           }"
                                           x-text="order.status_label"></span>
                                </div>
                                
                                <div class="mt-3 space-y-1 text-sm text-gray-400">
                                    <p>⏰ <span x-text="`${order.slot_time_start} - ${order.slot_time_end}`"></span></p>
                                    <p>💰 <span x-text="order.price_formatted"></span></p>
                                    <p x-show="order.message" class="text-gray-300">💬 <span x-text="order.message"></span></p>
                                </div>
                            </div>

                             <div class="flex items-center gap-2">
                                 <a :href="`/pesanan/${order.id}`" 
                                    class="rounded-lg bg-white px-4 py-2 text-sm font-bold text-black hover:bg-gray-200">
                                     Lihat Detail
                                 </a>
                             </div>
                        </div>
                    </div>
                </template>
            </div>

            
            <div x-show="!loading && orders.length === 0 && selectedDate" 
                 class="rounded-xl border border-dashed border-gray-700 bg-gray-900/50 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="mt-4 text-sm text-gray-400">Belum ada pesanan</p>
            </div>
        </div>
    </div>

    <style>
        body { background: #000; }
    </style>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
         class="fixed bottom-4 right-4 rounded-lg bg-green-600 px-6 py-3 text-white shadow-lg z-50">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <script>
        function orderManager() {
            return {
                selectedDate: null,
                selectedSlot: null,
                orders: [],
                loading: false,

                init() {
                    this.slots = [];
                    
                    // Listen to calendar date selection
                    window.addEventListener('date-selected', (e) => {
                        this.selectedDate = e.detail.date;
                        this.selectedSlot = null;
                        this.loadOrders();
                    });

                    // Listen to calendar slot selection
                    window.addEventListener('slot-selected', (e) => {
                        this.selectedDate = e.detail.date;
                        this.selectedSlot = e.detail.slot;
                        this.loadOrders();
                    });
                },

                async loadOrders() {
                    if (!this.selectedDate) return;

                    this.loading = true;
                    const params = new URLSearchParams({
                        date: this.selectedDate,
                        slot_id: this.selectedSlot?.id || ''
                    });

                    try {
                        const response = await fetch(`/jasa/<?php echo e($service->id); ?>/jam-tersedia/orders?${params}`);
                        const data = await response.json();
                        console.log('Orders response:', data);
                        if (data.orders && data.orders.length > 0) {
                            console.log('First order details:', JSON.stringify(data.orders[0], null, 2));
                        }
                        this.orders = data.orders || [];
                    } catch (error) {
                        console.error('Error:', error);
                        this.orders = [];
                    } finally {
                        this.loading = false;
                    }
                },

                formatDate(date) {
                    return new Date(date).toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    });
                },

                clearFilter() {
                    this.selectedDate = null;
                    this.selectedSlot = null;
                    this.orders = [];
                }
            }
        }
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
<?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/services/manage-slots.blade.php ENDPATH**/ ?>