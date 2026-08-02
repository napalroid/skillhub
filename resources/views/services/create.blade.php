{{-- resources/views/services/create.blade.php --}}
<x-layouts.app title="Ajukan Jasa Baru">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Ajukan Jasa Baru</h1>

    <form method="POST" action="{{ route('services.store') }}" class="bg-white rounded-xl border border-gray-100 p-6 max-w-2xl space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select name="subcategory_id" class="w-full rounded-lg border-gray-300">
                @foreach ($subcategories as $sub)
                    <option value="{{ $sub->id }}" @selected(old('subcategory_id') == $sub->id)>
                        {{ $sub->category->name }} &raquo; {{ $sub->name }}
                    </option>
                @endforeach
            </select>
            @error('subcategory_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Jasa</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-lg border-gray-300" placeholder="Contoh: Jasa Desain Poster Event Sekolah">
            @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price') }}" class="w-full rounded-lg border-gray-300" placeholder="50000">
            @error('price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700">
            Ajukan Jasa
        </button>
    </form>

</x-layouts.app>