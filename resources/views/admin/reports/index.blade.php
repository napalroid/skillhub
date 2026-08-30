<x-layouts.admin>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    @php
        // Pie chart colors dengan 50% opacity
        $pieColors = ['#00000080', '#55555580', '#99999980', '#C4002580', '#22222280'];
        $totalPie = $categoryStats->sum('count');
        $maxCount = $categoryStats->max('count') ?: 1;
    @endphp

    {{-- PAGE HEADER --}}
    <div class="mb-8" data-stagger-item>
        <h1 class="font-heading font-bold text-3xl lg:text-4xl text-black uppercase tracking-tight">Laporan</h1>
        <p class="text-sm text-[#555555] mt-2">Kelola laporan penyalahgunaan dan masalah pengguna</p>
    </div>

    {{-- STATISTICS SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-0 border border-[#DDDDDD] mb-8">
        <div class="p-6 sm:border-r border-b sm:border-b-0 border-[#DDDDDD]">
            <p class="text-xs font-heading font-bold uppercase tracking-wider text-[#999999]">Open</p>
            <p class="font-heading font-bold text-4xl text-black mt-2">{{ $stats['open'] }}</p>
        </div>
        <div class="p-6 sm:border-r border-b sm:border-b-0 border-[#DDDDDD]">
            <p class="text-xs font-heading font-bold uppercase tracking-wider text-[#999999]">Reviewed</p>
            <p class="font-heading font-bold text-4xl text-black mt-2">{{ $stats['reviewed'] }}</p>
        </div>
        <div class="p-6">
            <p class="text-xs font-heading font-bold uppercase tracking-wider text-[#999999]">Closed</p>
            <p class="font-heading font-bold text-4xl text-black mt-2">{{ $stats['closed'] }}</p>
        </div>
    </div>

    {{-- CHARTS SECTION --}}
    <div class="mb-8">
        {{-- PIE CHART - Top Reasons --}}
        <div class="border border-[#DDDDDD] p-6">
            <h2 class="font-heading font-bold text-sm uppercase tracking-wider text-black mb-6">Alasan Laporan Terbanyak</h2>
            @if($categoryStats->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-center">
                    {{-- Pie Chart Container --}}
                    <div class="lg:col-span-3">
                        <canvas id="reasonPieChart" width="320" height="320"></canvas>
                    </div>
                    
                    {{-- Legend --}}
                    <div class="lg:col-span-2 space-y-3">
                        @foreach($categoryStats->take(5) as $index => $cat)
                            @php
                                $totalCount = $categoryStats->sum('count');
                                $percentage = $totalCount > 0 ? ($cat->count / $totalCount) * 100 : 0;
                            @endphp
                            <div class="flex items-center gap-3 border-b border-[#EEEEEE] pb-2">
                                <span class="w-3 h-3 shrink-0 rounded-sm" style="background-color: {{ ['#1E3A8A', '#DC2626', '#059669', '#D97706', '#7C3AED'][$index] ?? '#1E3A8A' }};"></span>
                                <span class="font-heading font-bold text-xs text-black truncate flex-1">{{ $cat->category }}</span>
                                <div class="text-right shrink-0">
                                    <span class="font-heading font-bold text-sm text-black block leading-tight">{{ $cat->count }}</span>
                                    <span class="text-[10px] text-[#999999] leading-tight">{{ number_format($percentage, 1) }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-sm text-[#999999] text-center py-12">Belum ada data</p>
            @endif
        </div>
    </div>

    {{-- TOP 5 REASONS TABLE --}}
    @if($categoryStats->count() > 0)
    <div class="mb-8">
        <h2 class="font-heading font-bold text-sm uppercase tracking-wider text-black mb-4">Top 5 Alasan Laporan</h2>
        <div class="border-t border-[#DDDDDD]">
            @foreach($categoryStats->take(5) as $index => $cat)
                <div class="flex items-center gap-4 py-4 border-b border-[#DDDDDD]">
                    <span class="font-heading font-bold text-2xl text-[#DDDDDD] w-8">{{ $index + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="font-heading font-bold text-sm text-black">{{ $cat->category }}</p>
                        <div class="mt-2 h-1 bg-[#F5F5F5] rounded-sm overflow-hidden">
                            <div class="h-full" style="width: {{ ($maxCount > 0 ? ($cat->count / $maxCount) * 100 : 0) }}%; background-color: {{ ['#1E3A8A', '#DC2626', '#059669', '#D97706', '#7C3AED'][$index] ?? '#1E3A8A' }};"></div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-heading font-bold text-lg text-black">{{ $cat->count }}</p>
                        <p class="text-xs text-[#999999]">{{ $stats['total'] > 0 ? number_format(($cat->count / $stats['total']) * 100, 1) : 0 }}%</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- FILTER TABS --}}
    <div class="border-b border-[#DDDDDD] mb-6 overflow-x-auto" x-data="{ activeTab: '{{ $status }}' }">
        <div class="flex gap-0 min-w-max">
            <a href="{{ route('admin.reports.index', ['status' => 'all']) }}" 
               class="px-4 lg:px-5 py-3 text-xs font-heading font-bold uppercase tracking-wider transition border-b-2 whitespace-nowrap"
               :class="'{{ $status }}' === 'all' ? 'border-black text-black' : 'border-transparent text-[#999999] hover:text-black'">
                Semua
            </a>
            <a href="{{ route('admin.reports.index', ['status' => 'open']) }}" 
               class="px-4 lg:px-5 py-3 text-xs font-heading font-bold uppercase tracking-wider transition border-b-2 whitespace-nowrap"
               :class="'{{ $status }}' === 'open' ? 'border-black text-black' : 'border-transparent text-[#999999] hover:text-black'">
                Open
            </a>
            <a href="{{ route('admin.reports.index', ['status' => 'reviewed']) }}" 
               class="px-4 lg:px-5 py-3 text-xs font-heading font-bold uppercase tracking-wider transition border-b-2 whitespace-nowrap"
               :class="'{{ $status }}' === 'reviewed' ? 'border-black text-black' : 'border-transparent text-[#999999] hover:text-black'">
                Reviewed
            </a>
            <a href="{{ route('admin.reports.index', ['status' => 'closed']) }}" 
               class="px-4 lg:px-5 py-3 text-xs font-heading font-bold uppercase tracking-wider transition border-b-2 whitespace-nowrap"
               :class="'{{ $status }}' === 'closed' ? 'border-black text-black' : 'border-transparent text-[#999999] hover:text-black'">
                Closed
            </a>
        </div>
    </div>

    {{-- REPORTS LIST --}}
    <div class="border-t border-[#DDDDDD]">
        @if($reports->count() > 0)
            @foreach($reports as $report)
                <div class="border-b border-[#DDDDDD] py-5 hover:bg-[#F5F5F5] transition cursor-pointer"
                     onclick="openReportModal({{ $report->id }})">
                    {{-- DESKTOP VIEW --}}
                    <div class="hidden lg:flex items-start gap-4">
                        {{-- REPORTER --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="font-heading font-bold text-sm text-black">{{ $report->reporter->name ?? 'N/A' }}</p>
                                <span class="text-xs px-2 py-0.5 border border-[#DDDDDD] uppercase font-bold">{{ $report->reporter_role }}</span>
                            </div>
                            <p class="text-xs text-[#999999]">melaporkan</p>
                        </div>

                        {{-- CATEGORY --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-heading font-bold text-sm text-black">{{ $report->category }}</p>
                            <p class="text-xs text-[#555555] truncate">{{ $report->reason }}</p>
                        </div>

                        {{-- REPORTED USER --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-[#999999] uppercase font-bold tracking-wider">Dilaporkan</p>
                            <p class="font-medium text-sm text-black mt-1">{{ $report->reportedUser->name ?? 'N/A' }}</p>
                        </div>

                        {{-- DATE --}}
                        <div class="text-right shrink-0">
                            <p class="text-xs text-[#999999]">{{ $report->created_at->format('d M Y') }}</p>
                            <span class="inline-block mt-1 text-xs px-2 py-0.5 uppercase font-bold border
                                @if($report->status === 'open') border-[#E4002B] text-[#E4002B]
                                @elseif($report->status === 'reviewed') border-[#0051BA] text-[#0051BA]
                                @else border-[#999999] text-[#999999]
                                @endif">
                                {{ $report->status }}
                            </span>
                        </div>
                    </div>

                    {{-- MOBILE VIEW --}}
                    <div class="lg:hidden space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <p class="font-heading font-bold text-sm text-black">{{ $report->reporter->name ?? 'N/A' }}</p>
                                    <span class="text-[10px] px-2 py-0.5 border border-[#DDDDDD] uppercase font-bold">{{ $report->reporter_role }}</span>
                                </div>
                                <p class="text-xs text-[#999999]">melaporkan</p>
                            </div>
                            <span class="inline-block text-[10px] px-2 py-0.5 uppercase font-bold border shrink-0
                                @if($report->status === 'open') border-[#E4002B] text-[#E4002B]
                                @elseif($report->status === 'reviewed') border-[#0051BA] text-[#0051BA]
                                @else border-[#999999] text-[#999999]
                                @endif">
                                {{ $report->status }}
                            </span>
                        </div>

                        <div class="border-t border-[#EEEEEE] pt-3">
                            <p class="font-heading font-bold text-sm text-black mb-1">{{ $report->category }}</p>
                            <p class="text-xs text-[#555555] line-clamp-2">{{ $report->reason }}</p>
                        </div>

                        <div class="flex items-center justify-between border-t border-[#EEEEEE] pt-3">
                            <div>
                                <p class="text-[10px] text-[#999999] uppercase font-bold tracking-wider">Dilaporkan</p>
                                <p class="font-medium text-sm text-black mt-0.5">{{ $report->reportedUser->name ?? 'N/A' }}</p>
                            </div>
                            <p class="text-xs text-[#999999]">{{ $report->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-6">
                {{ $reports->appends(['status' => $status])->links('vendor.pagination.tailwind') }}
            </div>
        @else
            <div class="py-16 text-center">
                <p class="font-heading font-bold text-lg text-black">Belum ada laporan</p>
                <p class="text-sm text-[#999999] mt-2">Tidak terdapat laporan pada filter yang dipilih</p>
            </div>
        @endif
    </div>

    {{-- REPORT DETAIL MODAL --}}
    <div id="reportModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white border border-[#DDDDDD] w-full max-w-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="px-6 py-4 border-b border-[#DDDDDD] flex items-center justify-between sticky top-0 bg-white">
                <h3 class="font-heading font-bold text-base uppercase tracking-tight">Detail Laporan</h3>
                <button onclick="closeReportModal()" class="p-2 hover:bg-[#F5F5F5]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="reportModalContent" class="p-6">
                {{-- Content loaded dynamically --}}
            </div>
        </div>
    </div>

    @php
        $pieData = $categoryStats->take(5);
    @endphp
    
    @if($pieData->count() > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pieCtx = document.getElementById('reasonPieChart');
            if (!pieCtx || typeof Chart === 'undefined') return;
            
            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: @json($pieData->pluck('category')),
                    datasets: [{
                        data: @json($pieData->pluck('count')),
                        backgroundColor: ['#1E3A8A', '#DC2626', '#059669', '#D97706', '#7C3AED'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#000',
                            titleFont: { size: 11, weight: 'bold' },
                            bodyFont: { size: 11 },
                            padding: 8,
                            cornerRadius: 0,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + percentage + '%';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endif


    <script>
        const reportsData = @json($reports->items());
        
        function openReportModal(reportId) {
            const report = reportsData.find(r => r.id === reportId);
            if (!report) return;
            
            const content = `
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-heading font-bold uppercase tracking-wider text-[#999999]">Pelapor</p>
                            <p class="font-heading font-bold text-base text-black mt-1">${report.reporter.name}</p>
                            <p class="text-xs text-[#555555]">${report.reporter.email}</p>
                            <span class="inline-block mt-2 text-xs px-2 py-0.5 border border-[#DDDDDD] uppercase font-bold">${report.reporter_role}</span>
                        </div>
                        <div>
                            <p class="text-xs font-heading font-bold uppercase tracking-wider text-[#999999]">Dilaporkan</p>
                            <p class="font-heading font-bold text-base text-black mt-1">${report.reported_user.name}</p>
                            <p class="text-xs text-[#555555]">${report.reported_user.email}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-heading font-bold uppercase tracking-wider text-[#999999]">Kategori</p>
                        <p class="font-heading font-bold text-lg text-black mt-1">${report.category}</p>
                    </div>

                    <div>
                        <p class="text-xs font-heading font-bold uppercase tracking-wider text-[#999999]">Detail Laporan</p>
                        <p class="text-sm text-[#111111] mt-2 leading-relaxed">${report.reason}</p>
                    </div>

                    ${report.order ? `
                    <div>
                        <p class="text-xs font-heading font-bold uppercase tracking-wider text-[#999999]">Order Terkait</p>
                        <a href="/pesanan/${report.order.id}" class="text-sm text-black underline mt-1">Order #${report.order.id}</a>
                    </div>
                    ` : ''}

                    <div class="border-t border-[#DDDDDD] pt-6">
                        <p class="text-xs text-[#999999]">Dilaporkan pada ${new Date(report.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
                    </div>

                    <form action="/admin/reports/${report.id}/resolve" method="POST" class="space-y-4 border-t border-[#DDDDDD] pt-6">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label class="block text-xs font-heading font-bold uppercase tracking-wider text-[#555555] mb-2">Catatan Admin (opsional)</label>
                            <textarea name="admin_notes" rows="3" maxlength="1000" placeholder="Berikan catatan atau penjelasan keputusan..." class="input-field">${report.admin_notes || ''}</textarea>
                        </div>

                        <div class="flex gap-2 justify-end">
                            <button type="button" onclick="closeReportModal()" class="btn-outline text-xs px-4 py-2">Batal</button>
                            <button type="submit" name="status" value="reviewed" class="btn-outline text-xs px-4 py-2">Tandai Ditinjau</button>
                            <button type="submit" name="status" value="closed" class="btn-primary text-xs px-4 py-2">Tutup Laporan</button>
                        </div>
                    </form>
                </div>
            `;
            
            document.getElementById('reportModalContent').innerHTML = content;
            const modal = document.getElementById('reportModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        
        function closeReportModal() {
            const modal = document.getElementById('reportModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        document.getElementById('reportModal').addEventListener('click', closeReportModal);
    </script>
</x-layouts.admin>