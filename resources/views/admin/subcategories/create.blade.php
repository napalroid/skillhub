<x-layouts.admin>
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8" data-stagger-item>
        <div>
            <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Tambah Subkategori</h1>
            <p class="text-sm text-[#555555] mt-1">Tambahkan subkategori baru ke kategori yang sudah ada</p>
        </div>
        <a href="{{ route('admin.subcategories.index') }}" class="btn-ghost text-xs">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 19.5 3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="max-w-md">
        {{-- FLASH MESSAGES --}}
        @if (session('success'))
            <div class="mb-4 rounded-sm border border-[#2C9F45]/20 bg-[#2C9F45]/5 px-4 py-3 text-sm text-[#2C9F45]">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-sm border border-[#E4002B]/20 bg-[#E4002B]/5 px-4 py-3 text-sm text-[#E4002B]">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.subcategories.store') }}" class="space-y-6">
            @csrf
            <div class="admin-card p-6 space-y-6">
                <div>
                    <label class="label-field" for="sub-name">Nama Subkategori <span class="text-[#E4002B] font-normal">*</span></label>
                    <input type="text" id="sub-name" name="name" value="{{ old('name') }}" required
                        class="input-field"
                        placeholder="Contoh: Desain Logo">
                    @error('name') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label-field" for="sub-category">Kategori Induk <span class="text-[#E4002B] font-normal">*</span></label>
                    <select id="sub-category" name="category_id" required class="input-field">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                </div>

                @if($categories->isEmpty())
                    <p class="rounded-sm bg-[#EDE734]/20 p-4 text-sm text-[#a16207] border border-[#EDE734]/30">Belum ada kategori. Buat kategori baru terlebih dahulu.</p>
                @endif

                <div class="flex justify-end gap-2 pt-4 border-t border-[#DDDDDD]">
                    <a href="{{ route('admin.subcategories.index') }}" class="btn-outline text-xs px-4 py-2">Batal</a>
                    <button type="submit" class="btn-primary text-xs px-4 py-2" @disabled($categories->isEmpty())>Simpan Subkategori</button>
                </div>
            </div>
        </form>
    </div>

    @section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    });
    </script>
    @endsection
</x-layouts.admin>