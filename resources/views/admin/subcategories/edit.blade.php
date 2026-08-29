<x-layouts.admin>
    {{-- PAGE HEADER --}}
    <div class="mb-8" data-stagger-item>
        <div class="flex items-start justify-between gap-4 mb-2">
            <div>
                <h1 class="font-heading font-bold text-3xl lg:text-4xl text-black uppercase tracking-tight">Edit Subkategori</h1>
                <div class="mt-2 flex items-center gap-3 text-sm">
                    <span class="text-[#555555]">{{ $subcategory->category->name }}</span>
                    <span class="text-[#DDDDDD]">/</span>
                    <span class="font-heading font-bold text-black">{{ $subcategory->name }}</span>
                </div>
            </div>
            <a href="{{ route('admin.categories.edit', $subcategory->category) }}" class="btn-ghost text-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 19.5 3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        {{-- SUBCATEGORY INFO FORM --}}
        <div class="lg:col-span-1">
            <div class="admin-card p-6">
                <h2 class="font-heading font-bold text-sm uppercase tracking-wider text-black mb-4">Informasi Subkategori</h2>

                <form method="POST" action="{{ route('admin.subcategories.update', $subcategory) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <label class="label-field" for="sub-name">Nama <span class="text-[#E4002B] font-normal">*</span></label>
                        <input type="text" id="sub-name" name="name" value="{{ $subcategory->name }}" required class="input-field">
                        @error('name') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label-field" for="sub-category">Kategori Induk <span class="text-[#E4002B] font-normal">*</span></label>
                        <select id="sub-category" name="category_id" required class="input-field">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($subcategory->category_id == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-[#DDDDDD]">
                        <button type="submit" class="btn-primary text-xs px-4 py-2">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
