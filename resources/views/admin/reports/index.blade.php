<x-layouts.admin>
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8" data-stagger-item>
        <div>
            <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Laporan Penyalahgunaan</h1>
            <p class="text-sm text-[#555555] mt-1">Kelola laporan dari pengguna</p>
        </div>
    </div>

    {{-- REPORTS TABLE --}}
    <div class="admin-card" data-stagger-container>
        <div class="overflow-hidden">
            @if ($reports->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="w-24">Gambar Jasa</th>
                                <th>Detail Laporan</th>
                                <th class="w-36">Pelapor</th>
                                <th class="w-36">Dilaporkan</th>
                                <th class="w-32">Tanggal</th>
                                <th class="w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                @php
                                    $serviceImage = $report->order?->service->image 
                                        ? asset('storage/' . $report->order->service->image) 
                                        : asset('images/skillhub-hero.png');
                                @endphp
                                <tr class="row-enter" data-stagger-item>
                                    <td>
                                        <img src="{{ $serviceImage }}" alt="{{ $report->order->service->title ?? 'Jasa' }}"
                                             class="w-20 h-14 object-cover rounded-sm border border-[#DDDDDD]">
                                    </td>
                                    <td>
                                        @if ($report->order)
                                            <p class="font-medium text-black truncate max-w-xs">{{ $report->order->service->title }}</p>
                                            <p class="text-[10px] text-[#555555] mt-0.5">Order #{{ $report->order->id }}</p>
                                        @else
                                            <p class="font-medium text-black truncate max-w-xs">Laporan umum (tanpa pesanan)</p>
                                        @endif
                                        <p class="text-xs text-[#555555] mt-1 line-clamp-2">{{ $report->reason }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-medium text-black">{{ $report->reporter->name ?? 'N/A' }}</p>
                                        <p class="text-[10px] text-[#555555] mt-0.5">{{ $report->reporter->email ?? '' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-medium text-black">{{ $report->reportedUser->name ?? 'N/A' }}</p>
                                        <p class="text-[10px] text-[#555555] mt-0.5">{{ $report->reportedUser->email ?? '' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs text-[#555555]">{{ $report->created_at->format('d M Y H:i') }}</p>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <form action="{{ route('admin.reports.resolve', $report) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="reviewed">
                                                <button type="submit" class="btn-outline text-xs px-3 py-1.5" title="Tandai Ditinjau">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                                                    Ditinjau
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.reports.resolve', $report) }}" method="POST" class="inline" onsubmit="return confirm('Tutup laporan ini?')">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="closed">
                                                <button type="submit" class="btn-ghost text-xs px-3 py-1.5" title="Tutup Laporan">Tutup</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-4 border-t border-[#DDDDDD] flex items-center justify-between">
                    <p class="text-xs text-[#999999]">
                        Menampilkan {{ $reports->firstItem() }} - {{ $reports->lastItem() }} dari {{ $reports->total() }} laporan
                    </p>
                    <div class="flex items-center gap-2">
                        {{ $reports->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                    </div>
                    <p class="text-xs text-[#999999]">Tidak ada laporan terbuka.</p>
                </div>
            @endif
        </div>
    </div>

    @section('scripts')
    <script>
    document.addEventueseListener('DOMContentLoaded', function() {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        if (!prefersReduced && window.gsap) {
            gsap.from('.row-enter', {
                opacity: 0, y: 12, duration: 0.4, ease: 'power2.out',
                stagger: 0.03, delay: 0.1
            });
        }
    });
    </script>
    @endsection
</x-layouts.admin>