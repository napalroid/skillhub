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

    
    <section class="mb-8 lg:mb-12 space-y-3 lg:space-y-4" data-stagger-container>
        
        <div class="stat-card stat-card-accent" data-stagger-item>
            <p class="text-[10px] lg:text-xs font-heading font-bold text-uppercase-tracked text-[#555555]">Saldo Escrow Tertahan</p>
            <p class="mt-2 lg:mt-3 font-heading font-bold text-3xl lg:text-4xl text-black tracking-tight">Rp<?php echo e(number_format($escrowBalance, 0, ',', '.')); ?></p>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
            <div class="stat-card" data-stagger-item>
                <p class="text-[10px] lg:text-xs font-heading font-bold text-uppercase-tracked text-[#999999]">Siswa Terdaftar</p>
                <p class="mt-2 lg:mt-3 font-heading font-bold text-2xl lg:text-3xl text-black"><?php echo e(number_format($totalStudents, 0, ',', '.')); ?></p>
            </div>
            <div class="stat-card" data-stagger-item>
                <p class="text-[10px] lg:text-xs font-heading font-bold text-uppercase-tracked text-[#999999]">Total Jasa</p>
                <p class="mt-2 lg:mt-3 font-heading font-bold text-2xl lg:text-3xl text-black"><?php echo e(number_format($totalServices, 0, ',', '.')); ?></p>
            </div>
            <div class="stat-card" data-stagger-item>
                <p class="text-[10px] lg:text-xs font-heading font-bold text-uppercase-tracked text-[#999999]">Menunggu Verifikasi</p>
                <p class="mt-2 lg:mt-3 font-heading font-bold text-2xl lg:text-3xl text-[#E4002B]"><?php echo e(number_format($pendingCount, 0, ',', '.')); ?></p>
            </div>
            <div class="stat-card" data-stagger-item>
                <p class="text-[10px] lg:text-xs font-heading font-bold text-uppercase-tracked text-[#999999]">Total Pesanan</p>
                <p class="mt-2 lg:mt-3 font-heading font-bold text-2xl lg:text-3xl text-black"><?php echo e(number_format($totalOrders, 0, ',', '.')); ?></p>
            </div>
        </div>
    </section>

    
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-8 lg:mb-12" data-stagger-container>
        
        <?php
            $chartMax = max(max($chartOrders), 1);
            $chartSteps = 4;
            $previousChartOffset = min($chartOffset + 1, $chartMaxOffset);
            $nextChartOffset = max($chartOffset - 1, 0);
            $periodLabels = ['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan'];
            $chartCategory = $chartCategory ?? 'all';
            $chartSubcategory = $chartSubcategory ?? 'all';
        ?>
        <div class="lg:col-span-2 admin-card overflow-hidden" data-stagger-item>
            <div class="p-4 sm:p-5 lg:p-6">
                
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
                    <div class="min-w-0">
                        <h2 class="font-heading font-bold text-base lg:text-lg text-black uppercase tracking-tight">Penjualan Transaksi</h2>
                        <p class="text-[10px] lg:text-xs leading-relaxed text-[#555555] mt-1">Visual transaksi escrow dengan periode adjustable</p>
                    </div>
                    
                    <div class="flex items-center gap-1 rounded-full border border-[#DDDDDD] bg-[#F5F5F5] p-1 w-full sm:w-auto">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $periodLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <a href="<?php echo e(route('admin.dashboard', ['period' => $period, 'offset' => 0, 'category' => $chartCategory ?? 'all', 'subcategory' => $chartSubcategory ?? 'all'])); ?>"
                               class="flex-1 sm:flex-none px-3 sm:px-3.5 py-2 text-[10px] font-heading font-bold uppercase tracking-wider rounded-full transition-colors text-center <?php echo e($chartPeriod === $period ? 'bg-black text-white shadow-sm' : 'text-[#555555] hover:text-black'); ?>">
                                <?php echo e($label); ?>

                            </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>

                
                <div class="mt-3 flex flex-col sm:flex-row gap-2 sm:items-center">
                    <span class="text-[10px] font-heading font-bold uppercase tracking-wider text-[#999999]">Filter:</span>
                    <div class="flex flex-wrap gap-2">
                        <select name="chart_category" id="chart-category" onchange="updateChartFilter()" class="input-field text-[10px] py-1.5 px-3 w-auto">
                            <option value="all" <?php echo e($chartCategory === 'all' ? 'selected' : ''); ?>>Semua Kategori</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($cat->id); ?>" <?php echo e($chartCategory == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <select name="chart_subcategory" id="chart-subcategory" onchange="updateChartFilter()" class="input-field text-[10px] py-1.5 px-3 w-auto" <?php echo e($chartCategory === 'all' ? 'disabled' : ''); ?>>
                            <option value="all" <?php echo e($chartSubcategory === 'all' ? 'selected' : ''); ?>>Semua Subkategori</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chartCategory !== 'all'): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\Subcategory::where('category_id', $chartCategory)->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <option value="<?php echo e($sub->id); ?>" <?php echo e($chartSubcategory == $sub->id ? 'selected' : ''); ?>><?php echo e($sub->name); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                </div>

                <?php $__env->startPush('scripts'); ?>
                <script>
                document.getElementById('chart-category').addEventListener('change', function() {
                    const category = this.value;
                    const subSelect = document.getElementById('chart-subcategory');
                    const period = '<?php echo e($chartPeriod); ?>';
                    
                    window.location.href = '<?php echo e(route('admin.dashboard')); ?>?period=' + period + '&offset=0&category=' + category + '&subcategory=all';
                });

                document.getElementById('chart-subcategory').addEventListener('change', function() {
                    const category = document.getElementById('chart-category').value;
                    const subcategory = this.value;
                    const period = '<?php echo e($chartPeriod); ?>';
                    
                    window.location.href = '<?php echo e(route('admin.dashboard')); ?>?period=' + period + '&offset=0&category=' + category + '&subcategory=' + subcategory;
                });
                </script>
                <?php $__env->stopPush(); ?>

                
                <div class="mt-4 lg:mt-6 grid grid-cols-1 gap-3 rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] p-3 sm:p-4">
                    <div>
                        <p class="text-[10px] font-heading font-bold uppercase tracking-wider text-[#999999]"><?php echo e($chartOffset === 0 ? ($chartPeriod === 'daily' ? 'Hari ini' : ($chartPeriod === 'weekly' ? 'Minggu ini' : 'Bulan ini')) : 'Periode terpilih'); ?></p>
                        <p class="font-heading font-bold text-xl sm:text-2xl leading-none text-black mt-2">Rp<?php echo e(number_format($chartRevenue[count($chartRevenue) - 1] ?? 0, 0, ',', '.')); ?></p>
                        <p class="text-[10px] sm:text-xs text-[#555555] mt-1"><span class="font-heading font-bold text-black"><?php echo e($chartOrders[count($chartOrders) - 1] ?? 0); ?></span> pesanan pada titik terakhir</p>
                    </div>
                    
                    
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 pt-3 border-t border-[#DDDDDD] sm:border-0 sm:pt-0">
                        <div class="flex items-center gap-2 order-2 sm:order-1">
                            <a href="<?php echo e(route('admin.dashboard', ['period' => $chartPeriod, 'offset' => $previousChartOffset, 'category' => $chartCategory ?? 'all', 'subcategory' => $chartSubcategory ?? 'all'])); ?>"
                               class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex h-9 w-9 shrink-0 items-center justify-center rounded-full border bg-white text-black transition-colors hover:border-black hover:bg-black hover:text-white', $chartOffset >= $chartMaxOffset ? 'pointer-events-none opacity-30 border-[#DDDDDD]' : 'border-black']); ?>" 
                               aria-label="Lihat periode sebelumnya">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m14.25 18-6-6 6-6"/></svg>
                            </a>
                            <a href="<?php echo e(route('admin.dashboard', ['period' => $chartPeriod, 'offset' => $nextChartOffset, 'category' => $chartCategory ?? 'all', 'subcategory' => $chartSubcategory ?? 'all'])); ?>"
                               class="<?php echo \Illuminate\Support\Arr::toCssClasses(['flex h-9 w-9 shrink-0 items-center justify-center rounded-full border bg-white text-black transition-colors hover:border-black hover:bg-black hover:text-white', $chartOffset === 0 ? 'pointer-events-none opacity-30 border-[#DDDDDD]' : 'border-black']); ?>"
                               aria-label="Lihat periode lebih baru">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m9.75 6 6 6-6 6"/></svg>
                            </a>
                        </div>
                        <div class="flex-1 rounded-full border border-[#DDDDDD] bg-white px-3 py-2 text-center order-1 sm:order-2">
                            <p class="text-[10px] font-heading font-bold uppercase tracking-wide text-black"><?php echo e($chartStart->translatedFormat('d M Y')); ?> — <?php echo e($chartEnd->translatedFormat('d M Y')); ?></p>
                        </div>
                    </div>
                </div>

                
                <div class="relative mt-4 lg:mt-6 rounded-sm border border-[#DDDDDD] bg-white p-3 sm:p-4 lg:p-5" aria-label="Grafik transaksi <?php echo e($chartPeriod); ?>">
                    
                    <div class="absolute inset-x-3 sm:inset-x-4 lg:inset-x-5 top-3 sm:top-4 lg:top-4 bottom-[48px] sm:bottom-[52px] flex flex-col justify-between pointer-events-none" aria-hidden="true">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = $chartSteps; $i >= 0; $i--): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="flex items-center gap-2 lg:gap-3">
                                <span class="hidden sm:block w-6 text-right text-[9px] font-heading font-bold text-[#999999]"><?php echo e($i === 0 ? 0 : (int) ceil(($chartMax / $chartSteps) * $i)); ?></span>
                                <div class="flex-1 border-t <?php echo e($i === 0 ? 'border-[#DDDDDD]' : 'border-dashed border-[#EAEAEA]'); ?>"></div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>

                    
                    <div class="relative z-10 mt-1 flex h-[200px] sm:h-[240px] items-end justify-between gap-1.5 sm:gap-2 px-0.5 sm:px-1 lg:px-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $chartOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $orders): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $height = $orders > 0 ? max(14, ($orders / $chartMax) * 100) : 2;
                                $isLast = $index === count($chartOrders) - 1;
                            ?>
                            <div class="group relative flex h-full min-w-0 flex-1 flex-col items-center justify-end gap-1.5 sm:gap-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($orders > 0): ?>
                                    <span class="text-[9px] sm:text-[10px] font-heading font-bold text-black <?php echo e($isLast ? 'opacity-100' : 'sm:opacity-0 sm:group-hover:opacity-100'); ?> transition-opacity"><?php echo e($orders); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                
                                <div class="absolute bottom-[26px] sm:bottom-[28px] left-1/2 z-20 hidden sm:group-hover:block w-max max-w-[180px] sm:max-w-[200px] -translate-x-1/2 rounded-sm border border-black bg-black px-2.5 sm:px-3 py-1.5 sm:py-2 text-center text-[9px] sm:text-[10px] leading-relaxed text-white shadow-lg">
                                    <strong class="font-heading"><?php echo e($chartLabels[$index]); ?></strong><br>
                                    <?php echo e($orders); ?> pesanan · Rp<?php echo e(number_format($chartRevenue[$index], 0, ',', '.')); ?>

                                </div>
                                
                                <div class="flex h-full w-full max-w-[28px] sm:max-w-[34px] lg:max-w-[40px] items-end justify-center rounded-t-sm bg-[#F5F5F5] p-1">
                                    <div class="w-full rounded-t-sm bg-black transition-all duration-200 group-hover:bg-[#E4002B]"
                                         style="height: <?php echo e($height); ?>%"
                                         aria-label="<?php echo e($chartLabels[$index]); ?>: <?php echo e($orders); ?> pesanan"></div>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>

                    
                    <div class="mt-2 sm:mt-3 grid gap-1 sm:gap-2 lg:gap-3 px-0.5 sm:px-1 lg:px-3" style="grid-template-columns: repeat(<?php echo e(count($chartLabels)); ?>, minmax(0, 1fr));">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $chartLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <span class="min-w-0 truncate text-center text-[8px] sm:text-[9px] font-heading font-bold uppercase tracking-wide text-[#999999]" title="<?php echo e($label); ?>"><?php echo e($label); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>

                
                <div class="mt-2 lg:mt-3 flex flex-wrap items-center gap-2 lg:gap-3 text-[9px] sm:text-[10px] text-[#555555]">
                    <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-black"></span> pesanan</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[#E4002B]"></span> hover = pendapatan</span>
                </div>
            </div>
        </div>

        
        <div class="admin-card" data-stagger-item>
            <div class="p-4 sm:p-5 lg:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-heading font-semibold text-sm text-black">Laporan Masuk</h2>
                    <span class="badge badge-accent text-[10px]"><?php echo e($pendingReports->count()); ?></span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingReports->isNotEmpty()): ?>
                    <div class="space-y-2.5">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pendingReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="border border-[#DDDDDD] rounded-sm p-2.5 sm:p-3 hover:border-black transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-black truncate"><?php echo e($report->order->service->title ?? 'Jasa tidak ditemukan'); ?></p>
                                        <div class="flex flex-wrap gap-x-2 gap-y-0.5 text-[10px] text-[#999999] mt-1">
                                            <span>Pelapor: <span class="text-black font-medium"><?php echo e($report->reporter->name ?? 'N/A'); ?></span></span>
                                            <span class="hidden sm:inline">•</span>
                                            <span>Dilaporkan: <span class="text-black font-medium"><?php echo e($report->reportedUser->name ?? 'N/A'); ?></span></span>
                                        </div>
                                        <p class="text-[10px] text-[#555555] mt-1.5 line-clamp-2">"<?php echo e(\Illuminate\Support\Str::limit($report->reason, 80)); ?>"</p>
                                    </div>
                                    <a href="<?php echo e(route('admin.reports.index')); ?>" class="btn-ghost text-[10px] px-3 py-1.5 shrink-0 self-start sm:self-center">Lihat →</a>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state py-6 sm:py-8">
                        <div class="empty-state-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                        </div>
                        <p class="text-xs text-[#999999]">Tidak ada laporan masuk.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6" data-stagger-container x-data="{ showCategoryModal: false }">

        
        <div class="admin-card" data-stagger-item>
            <div class="p-4 sm:p-5 lg:p-6">
                <div class="flex items-center justify-between mb-4 lg:mb-5">
                    <h2 class="font-heading font-semibold text-sm text-black">Kelola Kategori</h2>
                    <button @click="showCategoryModal = true" class="btn-primary text-[10px] px-3 py-2">
                        <span class="inline-flex items-center justify-center w-4 h-4">+</span> Tambah
                    </button>
                </div>

                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="border border-[#DDDDDD] hover:border-black transition-colors p-2.5 sm:p-3" data-stagger-item>
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                                    <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center bg-[#F5F5F5] border border-[#DDDDDD]">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->iconIsFile()): ?>
                                            <img src="<?php echo e(asset('storage/' . $category->icon)); ?>" alt="<?php echo e($category->name); ?>" class="h-4 w-4 sm:h-5 sm:w-5 object-contain">
                                        <?php elseif($category->image): ?>
                                            <img src="<?php echo e(asset('storage/' . $category->image)); ?>" alt="<?php echo e($category->name); ?>" class="h-4 w-4 sm:h-5 sm:w-5 object-contain">
                                        <?php else: ?>
                                            <?php $iconKey = $category->displayIcon(); ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($iconKey):
                                                case ('design'): ?>
                                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
                                                <?php case ('code'): ?>
                                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/></svg>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
                                                <?php case ('camera'): ?>
                                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/></svg>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
                                                <?php case ('music'): ?>
                                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z"/></svg>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
                                                <?php case ('write'): ?>
                                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487 18.549 2.799a2.121 2.121 0 1 1 3 3L19.862 7.487M16.862 4.487 6.348 14.998a2.25 2.25 0 0 0-.578.978l-1.226 4.273a.375.375 0 0 0 .464.464l4.273-1.226a2.25 2.25 0 0 0 .978-.578L18.862 7.487M16.862 4.487 18.549 2.799"/></svg>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
                                                <?php case ('learn'): ?>
                                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
                                                <?php case ('business'): ?>
                                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/></svg>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php break; ?>
                                                <?php default: ?>
                                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                                            <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <span class="font-medium text-sm text-black truncate"><?php echo e($category->name); ?></span>
                                </div>
                                <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                                    <span class="badge badge-neutral text-[9px] sm:text-[10px] px-2 py-0.5"><?php echo e($category->subcategories->count()); ?></span>
                                    <a href="<?php echo e(route('admin.categories.edit', $category)); ?>" class="btn-ghost p-1" aria-label="Edit kategori">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                    </a>
                                </div>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->subcategories->isNotEmpty()): ?>
                                <div class="mt-2 flex flex-wrap gap-1.5 pl-9 sm:pl-12">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $category->subcategories->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <span class="text-[9px] sm:text-[10px] px-2 py-0.5 rounded border border-[#DDDDDD] bg-white text-[#555555]"><?php echo e($sub->name); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->subcategories->count() > 6): ?>
                                        <span class="text-[9px] sm:text-[10px] px-2 py-0.5 text-[#999999]">+<?php echo e($category->subcategories->count() - 6); ?> lagi</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="admin-card" data-stagger-item>
            <div class="p-4 sm:p-5 lg:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-heading font-semibold text-sm text-black">Antrian Persetujuan</h2>
                    <span class="badge badge-accent text-[10px]"><?php echo e($pendingCount); ?></span>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingServices->isNotEmpty()): ?>
                    <div class="space-y-2.5" data-stagger-container>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pendingServices->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="border border-[#DDDDDD] hover:border-black transition-colors p-2.5 sm:p-3" data-stagger-item>
                                <div class="flex flex-col sm:flex-row sm:items-start gap-2.5 sm:gap-3">
                                    <span class="hidden sm:flex w-8 h-8 rounded bg-[#F5F5F5] text-black items-center justify-center text-xs font-bold shrink-0 font-heading">
                                        <?php echo e(strtoupper(substr($service->title, 0, 1))); ?>

                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-black truncate"><?php echo e($service->title); ?></p>
                                        <p class="text-[10px] text-[#555555] mt-0.5"><?php echo e($service->seller?->name ?? 'N/A'); ?> • <?php echo e($service->subcategory?->name ?? '-'); ?></p>
                                    </div>
                                    <div class="flex gap-1.5 sm:gap-1 shrink-0 self-start">
                                        <form action="<?php echo e(route('admin.services.approve', $service)); ?>" method="POST" class="flex-1 sm:flex-none">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn-success text-[10px] px-2.5 sm:px-3 py-1.5 w-full sm:w-auto">Setujui</button>
                                        </form>
                                        <form action="<?php echo e(route('admin.services.reject', $service)); ?>" method="POST" class="flex-1 sm:flex-none" x-data>
                                            <?php echo csrf_field(); ?>
                                            <button type="button" 
                                                    @click="if (confirm('Tolak jasa ini?')) $el.closest('form').submit()"
                                                    class="btn-danger text-[10px] px-2.5 sm:px-3 py-1.5 w-full sm:w-auto">Tolak</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingServices->count() > 6): ?>
                            <a href="<?php echo e(route('admin.services.pending')); ?>" class="btn-ghost text-[10px] flex items-center justify-center gap-1 w-full py-2 mt-2">
                                Lihat semua <?php echo e($pendingServices->count()); ?> pengajuan →
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state py-6 sm:py-8">
                        <div class="empty-state-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m5 13 4 4L19 7"/></svg>
                        </div>
                        <p class="text-xs text-[#999999]">Tidak ada pengajuan baru.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    
    <div x-show="showCategoryModal" x-cloak @click.outside="showCategoryModal = false"
            class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/60 p-0 sm:p-4"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
        <div @click.stop class="w-full max-w-md bg-white rounded-t-lg sm:rounded-lg border border-[#DDDDDD] p-4 sm:p-6 shadow-lg max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="flex items-center justify-between mb-4 sm:mb-5">
                <h3 class="font-heading font-semibold text-sm text-black">Tambah Kategori</h3>
                <button type="button" @click="showCategoryModal = false" class="btn-ghost p-1.5 text-xl leading-none" aria-label="Tutup modal">&times;</button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.categories.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="label-field" for="cat-name">Nama Kategori</label>
                        <input type="text" id="cat-name" name="name" required
                            class="input-field text-sm"
                            placeholder="Contoh: Desain & Grafis">
                    </div>
                    <div>
                        <label class="label-field" for="cat-subs">Subkategori (pisahkan dengan koma)</label>
                        <input type="text" id="cat-subs" name="subcategories" placeholder="Contoh: Desain Logo, Desain Poster"
                            class="input-field text-sm">
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-3 border-t border-[#DDDDDD]">
                        <button type="button" @click="showCategoryModal = false" class="btn-outline text-[10px] px-4 py-2.5">Batal</button>
                        <button type="submit" class="btn-primary text-[10px] px-4 py-2.5">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaf25f15ef26fc3d179956a9918eaad4d)): ?>
<?php $attributes = $__attributesOriginalaf25f15ef26fc3d179956a9918eaad4d; ?>
<?php unset($__attributesOriginalaf25f15ef26fc3d179956a9918eaad4d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaf25f15ef26fc3d179956a9918eaad4d)): ?>
<?php $component = $__componentOriginalaf25f15ef26fc3d179956a9918eaad4d; ?>
<?php unset($__componentOriginalaf25f15ef26fc3d179956a9918eaad4d); ?>
<?php endif; ?><?php /**PATH D:\xampp\htdocs\UKK MODE SERIUS\skillhub\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>