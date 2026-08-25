<x-layouts.admin>
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8" data-stagger-item>
        <div>
            <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Tambah Kategori</h1>
            <p class="text-sm text-[#555555] mt-1">Buat kategori baru dan tambahkan subkategori sekaligus</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn-ghost text-xs">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 19.5 3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="max-w-3xl">
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

        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="admin-card p-6 space-y-6">
                <div>
                    <label class="label-field" for="cat-name">Nama Kategori <span class="text-[#E4002B] font-normal">*</span></label>
                    <input type="text" id="cat-name" name="name" value="{{ old('name') }}" required
                        class="input-field"
                        placeholder="Contoh: Desain & Grafis">
                    @error('name') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label-field" for="cat-icon">Icon Kategori <span class="text-[#999999] font-normal">(opsional)</span></label>
                    <p class="text-xs text-[#999999] mb-2">Icon kecil untuk daftar kategori. Bisa upload file atau pakai emoji default sistem.</p>
                    <label class="flex cursor-pointer items-center gap-3 rounded-md border-2 border-dashed border-[#DDDDDD] px-4 py-4 hover:border-black hover:bg-[#F5F5F5] transition"
                           x-data="{ dragActive: false, iconName: '' }"
                           @dragover.prevent="dragActive = true"
                           @dragleave="dragActive = false"
                           @drop.prevent="dragActive = false; $refs.iconInput.files = $event.dataTransfer.files; iconName = $event.dataTransfer.files[0]?.name || ''"
                           :class="{ 'border-black bg-[#F5F5F5]': dragActive }">
                        <span class="text-2xl">✨</span>
                        <span class="min-w-0 flex-1 truncate text-sm text-[#555555]" x-text="iconName || 'Klik untuk pilih file icon'"></span>
                        <span class="text-xs font-bold text-black shrink-0">PILIH</span>
                        <input type="file" x-ref="iconInput" id="cat-icon" name="icon" accept="image/png,image/jpeg,image/webp" class="hidden"
                            @change="iconName = $event.target.files[0]?.name || ''">
                    </label>
                    <p class="mt-1 text-xs text-[#999999]">PNG, JPG, atau WEBP. Maksimal 2 MB.</p>
                    @error('icon') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label-field" for="cat-image">Gambar Marketplace (Hero Section) <span class="text-[#999999] font-normal">(opsional)</span></label>
                    <p class="text-xs text-[#999999] mb-2">Gambar ini akan tampil di hero section marketplace saat kursor mengarah ke kategori ini. Rasio 16:9 disarankan.</p>
                    <label class="flex cursor-pointer items-center gap-3 rounded-md border-2 border-dashed border-[#DDDDDD] px-4 py-4 hover:border-black hover:bg-[#F5F5F5] transition"
                           x-data="{ dragActive: false, imageName: '' }"
                           @dragover.prevent="dragActive = true"
                           @dragleave="dragActive = false"
                           @drop.prevent="dragActive = false; $refs.imageInput.files = $event.dataTransfer.files; imageName = $event.dataTransfer.files[0]?.name || ''"
                           :class="{ 'border-black bg-[#F5F5F5]': dragActive }">
                        <span class="text-2xl">🖼</span>
                        <span class="min-w-0 flex-1 truncate text-sm text-[#555555]" x-text="imageName || 'Klik atau drag gambar ke sini'"></span>
                        <span class="text-xs font-bold text-black shrink-0">PILIH</span>
                        <input type="file" x-ref="imageInput" id="cat-image" name="image" accept="image/png,image/jpeg,image/webp" class="hidden"
                            @change="imageName = $event.target.files[0]?.name || ''">
                    </label>
                    <p class="mt-1 text-xs text-[#999999]">PNG, JPG, atau WEBP. Maksimal 2 MB.</p>
                    @error('image') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                </div>

                <div class="rounded-sm bg-[#F5F5F5] p-4">
                    <label class="label-field" for="cat-subs">Subkategori (pisahkan dengan koma) <span class="text-[#999999] font-normal">(opsional)</span></label>
                    <p class="text-xs text-[#999999] mb-2">Contoh: Desain Logo, Desain Poster, Desain Sosial Media, Ilustrasi</p>
                    <textarea id="cat-subs" name="subcategories" rows="3"
                        class="input-field resize-none"
                        placeholder="Desain Logo, Desain Poster, Desain Sosial Media, Ilustrasi"></textarea>
                    @error('subcategories') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-[#DDDDDD]">
                    <a href="{{ route('admin.categories.index') }}" class="btn-outline text-xs px-4 py-2">Batal</a>
                    <button type="submit" class="btn-primary text-xs px-4 py-2">Simpan Kategori</button>
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