@extends('layouts.app')

@section('title', 'Jasa Saya - SkillHub')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-wrap items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Jasa Saya (Approved)</h1>
        <a href="{{ route('services.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Ajukan Jasa Baru
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-sm p-5 mb-8 border border-gray-100">
        <form method="GET" action="{{ route('services.my') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category" id="filterCategory" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sub Kategori</label>
                <select name="subcategory" id="filterSubcategory" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                    <option value="">Semua Sub</option>
                    @foreach($subcategories as $sub)
                        <option value="{{ $sub->id }}" data-category="{{ $sub->category_id }}" {{ request('subcategory') == $sub->id ? 'selected' : '' }}>
                            {{ $sub->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium shadow transition">Filter</button>
                <a href="{{ route('services.my') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-medium transition">Reset</a>
            </div>
        </form>
    </div>

    <!-- Daftar Jasa -->
    @if($services->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($services as $service)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200 group">
                    <!-- Thumbnail -->
                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        @if($service->image)
                            <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2">
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">Approved</span>
                        </div>
                    </div>
                    <!-- Content -->
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-800 truncate">{{ $service->title }}</h3>
                        <p class="text-sm text-gray-500 mb-1">{{ $service->subcategory->name ?? '-' }} · {{ $service->subcategory->category->name ?? '-' }}</p>
                        <p class="text-xl font-bold text-blue-600 mb-3">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('services.show', $service->id) }}" class="flex-1 text-center bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium px-3 py-1.5 rounded-lg transition">Lihat</a>
                            <a href="{{ route('services.edit', $service->id) }}" class="flex-1 text-center bg-yellow-50 hover:bg-yellow-100 text-yellow-700 text-sm font-medium px-3 py-1.5 rounded-lg transition">Edit</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $services->appends(request()->query())->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl shadow p-10 text-center border border-dashed border-gray-300">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/></svg>
            <h3 class="text-2xl font-semibold text-gray-600">Belum ada jasa yang disetujui</h3>
            <p class="text-gray-400 mt-2">Ajukan jasa sekarang dan tunggu persetujuan admin.</p>
            <a href="{{ route('services.create') }}" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium shadow transition">Ajukan Jasa</a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('filterCategory');
        const subcategorySelect = document.getElementById('filterSubcategory');
        const allSubOptions = subcategorySelect.querySelectorAll('option');

        function filterSubcategories() {
            const selectedCategory = categorySelect.value;
            subcategorySelect.value = '';

            allSubOptions.forEach(opt => {
                const catId = opt.dataset.category;
                if (selectedCategory === '' || catId == selectedCategory) {
                    opt.style.display = 'block';
                } else {
                    opt.style.display = 'none';
                }
            });
        }

        categorySelect.addEventListener('change', filterSubcategories);
        filterSubcategories();
    });
</script>
@endpush
@endsection