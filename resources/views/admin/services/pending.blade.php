<x-layouts.admin>
    @php
        $services = $pendingServices;
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8" data-stagger-item>
        <div>
            <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Antrian Persetujuan Jasa</h1>
            <p class="text-sm text-[#555555] mt-1">Review dan setujui/tolak jasa yang diajukan penjual</p>
        </div>
        <a href="{{ route('admin.services.index') }}" class="btn-outline text-xs">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 19.5 3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Semua Jasa
        </a>
    </div>

    {{-- SERVICES TABLE --}}
    <div class="admin-card" data-stagger-container>
        <div class="overflow-hidden">
            @if ($services->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="w-24">Gambar</th>
                                <th>Jasa</th>
                                <th class="w-36">Kategori</th>
                                <th class="w-32">Penjual</th>
                                <th class="w-28">Harga</th>
                                <th class="w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                @php
                                    $mainImage = $service->image ? asset('storage/' . $service->image) : asset('images/skillhub-hero.jpg');
                                @endphp
                                <tr class="row-enter" data-stagger-item>
                                    <td>
                                        <img src="{{ $mainImage }}" alt="{{ $service->title }}"
                                             class="w-20 h-14 object-cover rounded-sm border border-[#DDDDDD]">
                                    </td>
                                    <td>
                                        <p class="font-medium text-black truncate max-w-xs">{{ $service->title }}</p>
                                        <p class="text-[10px] text-[#555555] mt-0.5 line-clamp-1">{{ $service->description }}</p>
                                    </td>
                                    <td>
                                        <span class="text-xs px-2 py-1 rounded-sm border border-[#DDDDDD] bg-white">
                                            {{ $service->subcategory?->name ?? '-' }}
                                        </span>
                                        @if ($service->subcategory?->category)
                                            <p class="text-[10px] text-[#555555] mt-0.5">{{ $service->subcategory->category->name }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-medium text-black">{{ $service->seller?->name ?? 'N/A' }}</p>
                                        <p class="text-[10px] text-[#555555] mt-0.5">{{ $service->seller?->email ?? '' }}</p>
                                    </td>
                                    <td>
                                        <span class="font-heading font-semibold text-sm text-black">
                                            Rp{{ number_format($service->price, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <a href="{{ route('services.show', $service) }}" target="_blank"
                                               class="btn-ghost p-1.5 text-[10px]" aria-label="Lihat di marketplace">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <form action="{{ route('admin.services.reject', $service) }}" method="POST" class="inline" onsubmit="return confirm('Tolak jasa ini?')">
                                                @csrf
                                                <button type="submit" class="btn-danger text-[10px] px-3 py-1.5">Tolak</button>
                                            </form>
                                            <form action="{{ route('admin.services.approve', $service) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="btn-primary text-[10px] px-3 py-1.5">Setujui</button>
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
                        Menampilkan {{ $services->firstItem() }} - {{ $services->lastItem() }} dari {{ $services->total() }} jasa
                    </p>
                    <div class="flex items-center gap-2">
                        {{ $services->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
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

    @section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
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