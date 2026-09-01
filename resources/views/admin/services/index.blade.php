<x-layouts.admin>
    @php
        $statusLabels = [
            'approved' => ['label' => 'Aktif', 'class' => 'badge-success'],
            'pending' => ['label' => 'Menunggu', 'class' => 'badge-pending'],
            'rejected' => ['label' => 'Ditolak', 'class' => 'badge-error'],
            'disabled' => ['label' => 'Dinonaktifkan', 'class' => 'badge-error'],
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
                @foreach(['all' => 'Semua', 'approved' => 'Aktif', 'pending' => 'Menunggu', 'rejected' => 'Ditolak', 'disabled' => 'Dinonaktifkan'] as $value => $label)
                    <a href="{{ route('admin.services.index', array_merge(request()->query(), ['status' => $value])) }}"
                       class="px-3 py-1.5 text-xs font-bold text-uppercase-tracked rounded-sm transition
                             {{ $statusFilter === $value ? 'bg-black text-white' : 'text-[#555555] hover:text-black hover:bg-[#F5F5F5]' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Category + Subcategory Filter --}}
            <form method="GET" action="{{ route('admin.services.index') }}" class="flex items-center gap-2">
                @foreach (request()->except(['search', 'page', 'category', 'subcategory']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <select name="category" id="category-filter" onchange="this.form.submit()" class="input-field w-auto py-2">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryFilter == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="subcategory" id="subcategory-filter" onchange="this.form.submit()"
                        class="input-field w-auto py-2" {{ $categoryFilter ? '' : 'disabled' }}>
                    <option value="">Semua Subkategori</option>
                    @foreach (\App\Models\Subcategory::where('category_id', $categoryFilter)->orderBy('name')->get() as $sub)
                        <option value="{{ $sub->id }}" {{ $subcategoryFilter == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                    @endforeach
                </select>
            </form>

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.services.index') }}" class="flex items-center gap-2">
                @foreach (request()->except(['search', 'page', 'category', 'subcategory']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="category" value="{{ $categoryFilter }}">
                <input type="hidden" name="subcategory" value="{{ $subcategoryFilter }}">
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
            <form method="GET" action="{{ route('admin.services.index') }}" class="flex items-center gap-2">
                @foreach (request()->except(['page', 'sort']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <select name="sort" onchange="this.form.submit()" class="input-field w-auto py-2">
                    <option value="latest" {{ $sortBy === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ $sortBy === 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="price_high" {{ $sortBy === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="price_low" {{ $sortBy === 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="title_asc" {{ $sortBy === 'title_asc' ? 'selected' : '' }}>Judul A-Z</option>
                </select>
            </form>
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
                                    $mainImage = $service->image ? asset('storage/' . $service->image) : asset('images/skillhub-hero.webp');
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
                                                <form id="deactivate-form-{{ $service->id }}" action="{{ route('admin.services.reject', $service) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="button" onclick="if(confirm('Nonaktifkan jasa ini dari marketplace?')) document.getElementById('deactivate-form-{{ $service->id }}').submit()" class="text-[#E4002B] hover:text-[#C90021] transition p-1 rounded hover:bg-[#F5F5F5] cursor-pointer" title="Nonaktifkan dari marketplace">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            @elseif ($service->status === 'rejected' || $service->status === 'disabled')
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

        // Reset subkategori saat kategori berubah agar tidak mengirim id milik kategori lain
        const categorySelect = document.getElementById('category-filter');
        const subcategorySelect = document.getElementById('subcategory-filter');
        if (categorySelect && subcategorySelect) {
            categorySelect.addEventListener('change', function () {
                subcategorySelect.value = '';
            });
        }

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