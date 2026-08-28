<x-layouts.admin>
    @php
        $statusLabels = [
            'approved' => ['label' => 'Aktif', 'class' => 'badge-success'],
            'pending' => ['label' => 'Menunggu', 'class' => 'badge-pending'],
            'rejected' => ['label' => 'Ditolak', 'class' => 'badge-accent'],
        ];
        $statusFilter = request('status', 'all');
        $searchQuery = request('search', '');
        $sortBy = request('sort', 'latest');
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8" data-stagger-item>
        <div>
            <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Kelola Semua Jasa</h1>
            <p class="text-sm text-[#555555] mt-1">Kelola, filter, dan moderasi semua jasa di platform</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            {{-- Status Filter --}}
            <div class="flex items-center gap-1 border border-[#DDDDDD] rounded-sm p-1 bg-white">
                @foreach(['all' => 'Semua', 'approved' => 'Aktif', 'pending' => 'Menunggu', 'rejected' => 'Ditolak'] as $value => $label)
                    <a href="{{ route('admin.services.index', array_merge(request()->query(), ['status' => $value])) }}"
                       class="px-3 py-1.5 text-xs font-bold text-uppercase-tracked rounded-sm transition
                             {{ $statusFilter === $value ? 'bg-black text-white' : 'text-[#555555] hover:text-black hover:bg-[#F5F5F5]' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.services.index') }}" class="flex items-center gap-2">
                @foreach (request()->except(['search', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <label for="service-search" class="sr-only">Cari jasa</label>
                <input type="search" id="service-search" name="search" value="{{ $searchQuery }}"
                    placeholder="Cari judul, deskripsi, penjual..."
                    class="input-field w-64 sm:w-80">
                <button type="submit" class="btn-primary text-xs">Cari</button>
                @if ($searchQuery)
                    <a href="{{ route('admin.services.index', array_merge(request()->except('search'), ['page' => null])) }}"
                       class="btn-ghost text-xs">Reset</a>
                @endif
            </form>

            {{-- Sort --}}
            <select name="sort" onchange="this.form.submit()" class="input-field w-auto py-2">
                <option value="latest" {{ $sortBy === 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ $sortBy === 'oldest' ? 'selected' : '' }}>Terlama</option>
                <option value="price_high" {{ $sortBy === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                <option value="price_low" {{ $sortBy === 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                <option value="title_asc" {{ $sortBy === 'title_asc' ? 'selected' : '' }}>Judul A-Z</option>
            </select>
        </div>
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
                                <th class="w-32">Status</th>
                                <th class="w-36">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                @php
                                    $statusInfo = $statusLabels[$service->status] ?? ['label' => $service->status, 'class' => 'badge-neutral'];
                                    $mainImage = $service->image ? asset('storage/' . $service->image) : asset('images/skillhub-hero.png');
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
                                        <span class="badge {{ $statusInfo['class'] }}">{{ $statusInfo['label'] }}</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <a href="{{ route('services.show', $service) }}" target="_blank"
                                               class="btn-ghost p-1.5 text-[10px]" aria-label="Lihat di marketplace">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            @if ($service->status === 'pending')
                                                <form action="{{ route('admin.services.approve', $service) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn-success text-[10px] px-2 py-1" title="Setujui">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m5 13 4 4L19 7"/></svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.services.reject', $service) }}" method="POST" class="inline" onsubmit="return confirm('Tolak jasa ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn-danger text-[10px] px-2 py-1" title="Tolak">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18 18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </form>
                                            @elseif ($service->status === 'approved')
                                                <form action="{{ route('admin.services.reject', $service) }}" method="POST" class="inline" onsubmit="return confirm('Nonaktifkan jasa ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn-danger text-[10px] px-2 py-1" title="Nonaktifkan">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14 20 4M10 14l-6 6m0 0-6-6m6 6H4"/></svg>
                                                    </button>
                                                </form>
                                            @elseif ($service->status === 'rejected')
                                                <form action="{{ route('admin.services.approve', $service) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn-primary text-[10px] px-2 py-1" title="Aktifkan">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m5 13 4 4L19 7"/></svg>
                                                    </button>
                                                </form>
                                            @endif
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
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 14.15c.864.864 1.545 2.05.864 3.297a4.643 4.643 0 01-1.323 2.426M15 18c0 2.485-2.015 4.5-4.5 4.5s-4.5-2.015-4.5-4.5M15 10.5A4.5 4.5 0 007.5 15M15 10.5A4.5 4.5 0 0122.5 15M15 10.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 10.5h1.5M2.25 15h1.5"/></svg>
                    </div>
                    <p class="text-xs text-[#999999]">Tidak ada jasa ditemukan.</p>
                    @if ($searchQuery || $statusFilter !== 'all')
                        <a href="{{ route('admin.services.index') }}" class="btn-ghost text-xs mt-4 inline-flex">Reset filter</a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        // Stagger table rows
        if (!prefersReduced && window.gsap) {
            gsap.from('.row-enter', {
                opacity: 0, y: 12, duration: 0.4, ease: 'power2.out',
                stagger: 0.03, delay: 0.1
            });
        }

        // Hover effects
        if (!prefersReduced) {
            document.querySelectorAll('.admin-card, .stat-card').forEach(card => {
                card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-2px)');
                card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
            });
        }
    });
    </script>
    @endsection
</x-layouts.admin>