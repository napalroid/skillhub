<x-layouts.admin>
    @php
        $initialSubcategories = $subcategories->map(function ($sub) {
            return [
                'id' => $sub->id,
                'name' => $sub->name,
                'category_id' => $sub->category_id,
                'category_name' => $sub->category->name,
                'services_count' => $sub->services_count,
            ];
        })->values()->all();

        $categoriesForSelect = $categories->map(function ($cat) {
            return ['id' => $cat->id, 'name' => $cat->name];
        })->values()->all();
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8" data-stagger-item>
        <div>
            <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Kelola Subkategori</h1>
            <p class="text-sm text-[#555555] mt-1">Kelola subkategori di bawah masing-masing kategori</p>
        </div>
        <button @click="showCreateModal = true; editingSubcategory = null" class="btn-primary text-xs">
            <span class="inline-flex items-center justify-center w-5 h-5">+</span> Tambah Subkategori
        </button>
    </div>

    {{-- SUB CATEGORY TABLE --}}
    <div class="admin-card" data-stagger-container>
        <div class="overflow-hidden">
            @if ($subcategories->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th class="w-48">Kategori</th>
                                <th class="w-24">Jasa</th>
                                <th class="w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subcategories as $sub)
                                <tr class="row-enter" data-stagger-item>
                                    <td>
                                        <p class="font-medium text-black">{{ $sub->name }}</p>
                                    </td>
                                    <td>
                                        <span class="text-xs px-2 py-1 rounded-sm border border-[#DDDDDD] bg-white">
                                            {{ $sub->category->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-neutral">{{ $sub->services_count }} jasa</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <a href="{{ route('admin.subcategories.edit', $sub) }}" class="btn-ghost text-xs px-2 py-1">Edit</a>
                                            <form action="{{ route('admin.subcategories.destroy', $sub) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN: Akan menghapus subkategori INI DAN SEMUA JASANYA! Yakin?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger text-xs px-2 py-1">Hapus</button>
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
                        Menampilkan {{ $subcategories->firstItem() }} - {{ $subcategories->lastItem() }} dari {{ $subcategories->total() }} subkategori
                    </p>
                    <div class="flex items-center gap-2">
                        {{ $subcategories->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7.25a2.25 2.25 0 0 1 2.25-2.25h7.5a2.25 2.25 0 0 1 2.25 2.25v9.5a2.25 2.25 0 0 1-2.25 2.25h-7.5A2.25 2.25 0 0 1 4 16.75z"/></svg>
                    </div>
                    <p class="text-xs text-[#999999]">Belum ada subkategori.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- CREATE/EDIT SUB CATEGORY MODAL --}}
    <div x-show="showCreateModal" x-cloak @click.outside="showCreateModal = false"
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
                <h3 class="font-heading font-semibold text-sm text-black" x-text="editingSubcategory ? 'Edit Subkategori' : 'Tambah Subkategori'"></h3>
                <button type="button" @click="showCreateModal = false; editingSubcategory = null" class="btn-ghost p-1" aria-label="Tutup modal">&times;</button>
            </div>
            <form method="POST" :action="editingSubcategory ? '{{ url('admin/subcategories') }}/' + editingSubcategory.id : '{{ route('admin.subcategories.store') }}'">
                @csrf
                <input type="hidden" name="_method" x-bind:value="editingSubcategory ? 'PUT' : 'POST'">
                <div class="space-y-4">
                    <div>
                        <label class="label-field" for="sub-name">Nama Subkategori <span class="text-[#E4002B] font-normal">*</span></label>
                        <input type="text" id="sub-name" name="name" required
                            class="input-field"
                            placeholder="Contoh: Desain Logo"
                            x-model="formData.name">
                    </div>
                    <div>
                        <label class="label-field" for="sub-category">Kategori <span class="text-[#E4002B] font-normal">*</span></label>
                        <select id="sub-category" name="category_id" required class="input-field" x-model="formData.category_id">
                            <option value="">Pilih Kategori</option>
                            <template x-for="cat in categoriesForSelect" :key="cat.id">
                                <option :value="cat.id" x-text="cat.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-[#DDDDDD]">
                        <button type="button" @click="showCreateModal = false; editingSubcategory = null; formData = { name: '', category_id: '' }" class="btn-outline text-xs px-4 py-2">Batal</button>
                        <button type="submit" class="btn-primary text-xs px-4 py-2" x-text="editingSubcategory ? 'Simpan Perubahan' : 'Tambah Subkategori'"></button>
                    </div>
                </div>
            </form>
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