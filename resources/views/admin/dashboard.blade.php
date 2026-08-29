<x-layouts.admin>
    {{-- STAT CARDS --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-16" data-stagger-container>
        <div class="stat-card" data-stagger-item>
            <p class="text-xs font-heading font-bold text-uppercase-tracked text-[#999999]">Siswa Terdaftar</p>
            <p class="mt-3 font-heading font-bold text-3xl text-black">{{ number_format($totalStudents, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card" data-stagger-item>
            <p class="text-xs font-heading font-bold text-uppercase-tracked text-[#999999]">Total Jasa</p>
            <p class="mt-3 font-heading font-bold text-3xl text-black">{{ number_format($totalServices, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card" data-stagger-item>
            <p class="text-xs font-heading font-bold text-uppercase-tracked text-[#999999]">Menunggu Verifikasi</p>
            <p class="mt-3 font-heading font-bold text-3xl text-[#EDE734]">{{ number_format($pendingCount, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card" data-stagger-item>
            <p class="text-xs font-heading font-bold text-uppercase-tracked text-[#999999]">Total Pesanan</p>
            <p class="mt-3 font-heading font-bold text-3xl text-black">{{ number_format($totalOrders, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card stat-card-accent" data-stagger-item>
            <p class="text-xs font-heading font-bold text-uppercase-tracked text-[#999999]">Saldo Escrow Tertahan</p>
            <p class="mt-3 font-heading font-bold text-3xl text-[#E4002B]">Rp{{ number_format($escrowBalance, 0, ',', '.') }}</p>
        </div>
    </section>

    {{-- CHART + REPORTS --}}
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-16" data-stagger-container>
        {{-- Chart: Penjualan --}}
        @php
            $chartMax = max(max($chartOrders), 1);
            $chartSteps = 4;
            $previousChartOffset = min($chartOffset + 1, $chartMaxOffset);
            $nextChartOffset = max($chartOffset - 1, 0);
            $periodLabels = ['daily' => 'Harian', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan'];
        @endphp
        <div class="lg:col-span-2 admin-card overflow-hidden" data-stagger-item>
            <div class="p-6 lg:p-7">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="font-heading font-bold text-lg text-black uppercase tracking-tight">Penjualan Transaksi</h2>
                        <p class="text-xs leading-relaxed text-[#555555] mt-1 max-w-[42ch]">Visual escrow yang bersih. Batang ramping dengan jeda napas, siap untuk slide presentasi UKK.</p>
                    </div>
                    <div class="flex items-center gap-1 rounded-full border border-[#DDDDDD] bg-[#F5F5F5] p-1">
                        @foreach ($periodLabels as $period => $label)
                            <a href="{{ route('admin.dashboard', ['period' => $period, 'offset' => 0]) }}"
                               class="px-3.5 py-2 text-[10px] font-heading font-bold uppercase tracking-wider rounded-full transition-colors {{ $chartPeriod === $period ? 'bg-black text-white shadow-sm' : 'text-[#555555] hover:text-black' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3 rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] p-4 sm:grid-cols-[1.4fr_auto] sm:items-end">
                    <div>
                        <p class="text-[10px] font-heading font-bold uppercase tracking-wider text-[#999999]">{{ $chartOffset === 0 ? ($chartPeriod === 'daily' ? 'Hari ini' : ($chartPeriod === 'weekly' ? 'Minggu ini' : 'Bulan ini')) : 'Periode terpilih' }}</p>
                        <p class="font-heading font-bold text-2xl leading-none text-black mt-2">Rp{{ number_format($chartRevenue[count($chartRevenue) - 1] ?? 0, 0, ',', '.') }}</p>
                        <p class="text-xs text-[#555555] mt-1"><span class="font-heading font-bold text-black">{{ $chartOrders[count($chartOrders) - 1] ?? 0 }}</span> pesanan pada titik terakhir</p>
                    </div>
                    <div class="flex items-center gap-2 justify-between sm:justify-end">
                        <a href="{{ route('admin.dashboard', ['period' => $chartPeriod, 'offset' => $previousChartOffset]) }}"
                           @class(['flex h-9 w-9 items-center justify-center rounded-full border bg-white text-black transition-colors hover:border-black hover:bg-black hover:text-white', $chartOffset >= $chartMaxOffset ? 'pointer-events-none opacity-30 border-[#DDDDDD]' : 'border-black']) 
                           aria-label="Lihat periode sebelumnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m14.25 18-6-6 6-6"/></svg>
                        </a>
                        <div class="min-w-[148px] rounded-full border border-[#DDDDDD] bg-white px-3 py-2 text-center">
                            <p class="text-[10px] font-heading font-bold uppercase tracking-wide text-black">{{ $chartStart->translatedFormat('d M Y') }} — {{ $chartEnd->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-[#999999]">geser hingga 3 tahun ke belakang</p>
                        </div>
                        <a href="{{ route('admin.dashboard', ['period' => $chartPeriod, 'offset' => $nextChartOffset]) }}"
                           @class(['flex h-9 w-9 items-center justify-center rounded-full border bg-white text-black transition-colors hover:border-black hover:bg-black hover:text-white', $chartOffset === 0 ? 'pointer-events-none opacity-30 border-[#DDDDDD]' : 'border-black'])
                           aria-label="Lihat periode lebih baru">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="m9.75 6 6 6-6 6"/></svg>
                        </a>
                    </div>
                </div>

                <div class="relative mt-6 rounded-sm border border-[#DDDDDD] bg-white p-4 sm:p-5" aria-label="Grafik transaksi {{ $chartPeriod }}">
                    <div class="absolute inset-x-4 top-4 bottom-[52px] flex flex-col justify-between pointer-events-none sm:inset-x-5" aria-hidden="true">
                        @for ($i = $chartSteps; $i >= 0; $i--)
                            <div class="flex items-center gap-3">
                                <span class="hidden w-6 text-right text-[9px] font-heading font-bold text-[#999999] sm:block">{{ $i === 0 ? 0 : (int) ceil(($chartMax / $chartSteps) * $i) }}</span>
                                <div class="flex-1 border-t {{ $i === 0 ? 'border-[#DDDDDD]' : 'border-dashed border-[#EAEAEA]' }}"></div>
                            </div>
                        @endfor
                    </div>

                    <div class="relative z-10 mt-1 flex h-[240px] items-end justify-between gap-2 px-1 sm:gap-3 sm:px-3">
                        @foreach ($chartOrders as $index => $orders)
                            @php
                                $height = $orders > 0 ? max(14, ($orders / $chartMax) * 100) : 2;
                                $isLast = $index === count($chartOrders) - 1;
                            @endphp
                            <div class="group relative flex h-full min-w-0 flex-1 flex-col items-center justify-end gap-2">
                                @if ($orders > 0)
                                    <span class="hidden text-[10px] font-heading font-bold text-black sm:block {{ $isLast ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }} transition-opacity">{{ $orders }}</span>
                                @endif
                                <div class="absolute bottom-[28px] left-1/2 z-20 hidden w-max max-w-[200px] -translate-x-1/2 rounded-sm border border-black bg-black px-3 py-2 text-center text-[10px] leading-relaxed text-white shadow-lg group-hover:block">
                                    <strong class="font-heading">{{ $chartLabels[$index] }}</strong><br>
                                    {{ $orders }} pesanan · Rp{{ number_format($chartRevenue[$index], 0, ',', '.') }}
                                </div>
                                <div class="flex h-full w-full max-w-[34px] items-end justify-center rounded-t-sm bg-[#F5F5F5] p-1 sm:max-w-[40px]">
                                    <div class="w-full rounded-t-sm bg-black transition-all duration-200 group-hover:bg-[#E4002B]"
                                         style="height: {{ $height }}%"
                                         aria-label="{{ $chartLabels[$index] }}: {{ $orders }} pesanan"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3 grid gap-2 px-1 sm:gap-3 sm:px-3" style="grid-template-columns: repeat({{ count($chartLabels) }}, minmax(0, 1fr));">
                        @foreach ($chartLabels as $label)
                            <span class="min-w-0 truncate text-center text-[9px] font-heading font-bold uppercase tracking-wide text-[#999999]" title="{{ $label }}">{{ $label }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap items-center gap-3 text-[10px] text-[#555555]">
                    <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-black"></span> pesanan</span>
                    <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-[#E4002B]"></span> hover = pendapatan</span>
                    <span class="ml-auto text-[#999999]">scroll periode hingga 3 tahun • data server-rendered, anti-hilang</span>
                </div>
            </div>
        </div>

        {{-- Report Alerts --}}
        <div class="admin-card" data-stagger-item>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-heading font-semibold text-sm text-black">Laporan Masuk</h2>
                    <span class="badge badge-accent">{{ $pendingReports->count() }}</span>
                </div>
                @if ($pendingReports->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($pendingReports as $report)
                            <div class="border border-[#DDDDDD] rounded-sm p-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-black truncate">{{ $report->order->service->title ?? 'Jasa tidak ditemukan' }}</p>
                                        <p class="text-[10px] text-[#999999] mt-1">Pelapor: {{ $report->reporter->name ?? 'N/A' }}</p>
                                        <p class="text-[10px] text-[#999999]">Dilaporkan: {{ $report->reportedUser->name ?? 'N/A' }}</p>
                                        <p class="text-[10px] text-[#999999] mt-1 line-clamp-2">"{{ \Illuminate\Support\Str::limit($report->reason, 80) }}"</p>
                                    </div>
                                    <a href="{{ route('admin.reports.index') }}" class="btn-ghost text-xs shrink-0">Lihat &rarr;</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state py-8">
                        <div class="empty-state-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                        </div>
                        <p class="text-xs text-[#999999]">Tidak ada laporan masuk.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- KATEGORI MANAGEMENT + APPROVAL QUEUE --}}
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6" data-stagger-container>

        {{-- Category Management --}}
        <div class="admin-card" data-stagger-item>
            <div class="p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-heading font-semibold text-sm text-black">Kelola Kategori</h2>
                    <button @click="showCategoryModal = true" class="btn-primary text-xs">
                        <span class="inline-flex items-center justify-center w-5 h-5">+</span> Tambah
                    </button>
                </div>

                <div class="space-y-2">
                    @foreach ($categories as $category)
                        <div class="admin-card p-3" data-stagger-item>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center bg-[#F5F5F5] border border-[#DDDDDD]">
                                        @if ($category->iconIsFile())
                                            <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }}" class="h-5 w-5 object-contain">
                                        @elseif ($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-5 w-5 object-contain">
                                        @else
                                            @php $iconKey = $category->displayIcon(); @endphp
                                            @switch($iconKey)
                                                @case('design')
                                                    <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                                                    @break
                                                @case('code')
                                                    <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/></svg>
                                                    @break
                                                @case('camera')
                                                    <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/></svg>
                                                    @break
                                                @case('music')
                                                    <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z"/></svg>
                                                    @break
                                                @case('write')
                                                    <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487 18.549 2.799a2.121 2.121 0 1 1 3 3L19.862 7.487M16.862 4.487 6.348 14.998a2.25 2.25 0 0 0-.578.978l-1.226 4.273a.375.375 0 0 0 .464.464l4.273-1.226a2.25 2.25 0 0 0 .978-.578L18.862 7.487M16.862 4.487 18.549 2.799"/></svg>
                                                    @break
                                                @case('learn')
                                                    <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                                                    @break
                                                @case('business')
                                                    <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/></svg>
                                                    @break
                                                @default
                                                    <svg class="h-5 w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                                            @endswitch
                                        @endif
                                    </div>
                                    <span class="font-medium text-black">{{ $category->name }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-neutral text-[10px]">{{ $category->subcategories->count() }} sub</span>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn-ghost p-1" aria-label="Edit kategori">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                    </a>
                                </div>
                            </div>
                            @if ($category->subcategories->isNotEmpty())
                                <div class="mt-2 flex flex-wrap gap-1.5 pl-12">
                                    @foreach ($category->subcategories as $sub)
                                        <span class="text-[10px] px-2 py-1 rounded border border-[#DDDDDD] bg-white">{{ $sub->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Pending Approval Queue --}}
        <div class="admin-card" data-stagger-item>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-heading font-semibold text-sm text-black">Antrian Persetujuan</h2>
                    <span class="badge badge-accent">{{ $pendingCount }} pengajuan</span>
                </div>

                @if ($pendingServices->isNotEmpty())
                    <div class="space-y-3" data-stagger-container>
                        @foreach ($pendingServices->take(6) as $service)
                            <div class="admin-card p-3" data-stagger-item>
                                <div class="flex items-start gap-3">
                                    <span class="w-8 h-8 rounded bg-[#F5F5F5] text-black flex items-center justify-center text-xs font-bold shrink-0 font-heading">
                                        {{ strtoupper(substr($service->title, 0, 1)) }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-black truncate">{{ $service->title }}</p>
                                        <p class="text-[10px] text-[#555555] mt-0.5">{{ $service->seller?->name ?? 'N/A' }} &bull; {{ $service->subcategory?->name ?? '-' }}</p>
                                    </div>
                                    <div class="flex gap-1 shrink-0">
                                        <form action="{{ route('admin.services.reject', $service) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Tolak jasa ini?')" class="btn-danger text-xs px-3 py-1.5">Tolak</button>
                                        </form>
                                        <form action="{{ route('admin.services.approve', $service) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-primary text-xs px-3 py-1.5">Setujui</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if ($pendingServices->count() > 6)
                            <a href="{{ route('admin.services.pending') }}" class="btn-ghost text-xs flex items-center justify-center gap-1 w-full py-2">
                                Lihat semua {{ $pendingServices->count() }} pengajuan &rarr;
                            </a>
                        @endif
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m5 13 4 4L19 7"/></svg>
                        </div>
                        <p class="text-xs text-[#999999]">Tidak ada pengajuan baru.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- CATEGORY MODAL --}}
    <div x-show="showCategoryModal" x-cloak @click.outside="showCategoryModal = false"
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
                <h3 class="font-heading font-semibold text-sm text-black">Tambah Kategori</h3>
                <button type="button" @click="showCategoryModal = false" class="btn-ghost p-1" aria-label="Tutup modal">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="label-field" for="cat-name">Nama Kategori</label>
                        <input type="text" id="cat-name" name="name" required
                            class="input-field"
                            placeholder="Contoh: Desain & Grafis">
                    </div>
                    <div>
                        <label class="label-field" for="cat-subs">Subkategori (pisahkan dengan koma)</label>
                        <input type="text" id="cat-subs" name="subcategories" placeholder="Contoh: Desain Logo, Desain Poster"
                            class="input-field">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-[#DDDDDD]">
                        <button type="button" @click="showCategoryModal = false" class="btn-outline text-xs px-4 py-2">Batal</button>
                        <button type="submit" class="btn-primary text-xs px-4 py-2">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>