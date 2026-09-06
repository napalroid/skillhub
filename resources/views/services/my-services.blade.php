@extends('layouts.app')

@section('title', 'Jasa Saya - SkillHub')

@section('content')
<div class="relative min-h-screen" style="background: linear-gradient(135deg, #f5f5f5 0%, #ffffff 50%, #fafafa 100%);">
    <!-- Header Section dengan Asymmetric Layout -->
    <div class="max-w-[1280px] mx-auto px-4 lg:px-8 pt-12 pb-8">
        <div class="grid lg:grid-cols-12 gap-8 items-end">
            <!-- Title Block -->
            <div class="lg:col-span-7">
                <div class="relative">
                    <div class="absolute -left-1 top-0 w-1 h-16 bg-black"></div>
                    <h1 class="text-5xl lg:text-6xl font-black tracking-tight mb-3 pl-6" style="font-family: 'Montserrat', sans-serif; line-height: 0.95;">
                        <span class="block text-black">JASA</span>
                        <span class="block" style="color: #E4002B;">SAYA</span>
                    </h1>
                </div>
                <p class="text-sm text-gray-600 pl-6 mt-4 max-w-md font-medium tracking-wide">
                    Kelola portofolio layanan yang telah disetujui dan siap untuk klien
                </p>
            </div>
            
            <!-- Action Block -->
            <div class="lg:col-span-5 flex justify-end">
                <a href="{{ route('services.create') }}" 
                   class="bg-black text-white font-bold text-xs uppercase tracking-wider px-6 py-3 border-2 border-black hover:bg-white hover:text-black transition-all duration-150 inline-flex items-center gap-2"
                   style="font-family: 'Montserrat', sans-serif; border-radius: 4px;">
                    <span class="inline-flex w-7 h-7 rounded-full bg-black/10 items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </span>
                    Ajukan Jasa Baru
                </a>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-12">
            <div class="bg-white border border-gray-300 shadow-sm p-5 border-l-4 border-l-black hover:shadow-md transition-shadow duration-200" style="border-radius: 8px;">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em;">Total</div>
                <div class="text-3xl font-black text-black" style="font-family: 'Montserrat', sans-serif;">{{ $services->total() }}</div>
            </div>
            <div class="bg-white border border-gray-300 shadow-sm p-5 border-l-4 hover:shadow-md transition-shadow duration-200" style="border-radius: 8px; border-left-color: #E4002B;">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em;">Approved</div>
                <div class="text-3xl font-black" style="font-family: 'Montserrat', sans-serif; color: #E4002B;">{{ $services->total() }}</div>
            </div>
            <div class="bg-white border border-gray-300 shadow-sm p-5 border-l-4 hover:shadow-md transition-shadow duration-200" style="border-radius: 8px; border-left-color: #2C9F45;">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em;">Aktif</div>
                <div class="text-3xl font-black" style="font-family: 'Montserrat', sans-serif; color: #2C9F45;">{{ $services->total() }}</div>
            </div>
            <div class="bg-white border border-gray-300 shadow-sm p-5 border-l-4 hover:shadow-md transition-shadow duration-200" style="border-radius: 8px; border-left-color: #0051BA;">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em;">Views</div>
                <div class="text-3xl font-black" style="font-family: 'Montserrat', sans-serif; color: #0051BA;">—</div>
            </div>
        </div>
    </div>

    <!-- Filter Section dengan Brutal Design -->
    <div class="max-w-[1280px] mx-auto px-4 lg:px-8 mb-12">
        <form method="GET" action="{{ route('services.my') }}" id="filterForm" class="bg-white border-2 border-black shadow-sm p-6 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200" style="border-radius: 8px;">
            <div class="grid md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em;">Kategori</label>
                    <select name="category" id="filterCategory" class="w-full px-4 py-3 bg-white border border-gray-300 focus:outline-none focus:border-black focus:ring-2 focus:ring-black/10 transition-all text-sm" style="border-radius: 4px;">
                        <option value="">— Semua Kategori —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em;">Sub Kategori</label>
                    <select name="subcategory" id="filterSubcategory" class="w-full px-4 py-3 bg-white border border-gray-300 focus:outline-none focus:border-black focus:ring-2 focus:ring-black/10 transition-all text-sm" style="border-radius: 4px;">
                        <option value="">— Semua Sub —</option>
                        @foreach($subcategories as $sub)
                            <option value="{{ $sub->id }}" data-category="{{ $sub->category_id }}" {{ request('subcategory') == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-black text-white font-bold text-xs uppercase tracking-wider px-6 py-3 border-2 border-black hover:bg-white hover:text-black transition-all duration-150" style="font-family: 'Montserrat', sans-serif; border-radius: 4px; letter-spacing: 0.08em;">Terapkan</button>
                    <a href="{{ route('services.my') }}" class="flex-1 text-center bg-transparent text-black font-bold text-xs uppercase tracking-wider px-6 py-3 border-2 border-black hover:bg-black hover:text-white transition-all duration-150" style="font-family: 'Montserrat', sans-serif; border-radius: 4px; letter-spacing: 0.08em;">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Services Grid dengan Asymmetric Cards -->
    <div class="max-w-[1280px] mx-auto px-4 lg:px-8 pb-16">
        @if($services->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="servicesGrid">
                @foreach($services as $index => $service)
                    <div class="service-card group" data-index="{{ $index }}" style="animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; animation-delay: {{ $index * 0.05 }}s; opacity: 0;">
                        <div class="bg-white border-2 border-black shadow-sm overflow-hidden h-full flex flex-col transition-all duration-300 hover:-translate-y-1 hover:shadow-xl" style="border-radius: 8px;">
                            <!-- Image Section -->
                            <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                                @if($service->image)
                                    <img src="{{ asset('storage/'.$service->image) }}" 
                                         alt="{{ $service->title }}" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                         loading="lazy">
                                @else
                                    <div class="flex items-center justify-center h-full bg-gradient-to-br from-gray-100 to-gray-200">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider px-3 py-1 border" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; background: rgba(44, 159, 69, 0.1); color: #2C9F45; border-color: rgba(44, 159, 69, 0.2); border-radius: 999px;">Approved</span>
                                </div>
                                
                                <!-- Overlay on Hover -->
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300"></div>
                            </div>

                            <!-- Content Section -->
                            <div class="p-5 flex-1 flex flex-col">
                                <!-- Category -->
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em;">
                                        {{ $service->subcategory->category->name ?? 'N/A' }}
                                    </span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-xs text-gray-500">
                                        {{ $service->subcategory->name ?? '-' }}
                                    </span>
                                </div>

                                <!-- Title -->
                                <h3 class="text-xl font-bold text-black mb-3 leading-tight" style="font-family: 'Montserrat', sans-serif; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $service->title }}
                                </h3>

                                <!-- Price -->
                                <div class="mt-auto pt-4 border-t border-gray-100">
                                    <div class="flex items-end justify-between mb-4">
                                        <div>
                                            <div class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em;">Harga</div>
                                            <div class="text-2xl font-black text-black" style="font-family: 'Montserrat', sans-serif;">
                                                {{ number_format($service->price / 1000, 0) }}<span class="text-base">K</span>
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            IDR
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="grid grid-cols-3 gap-2">
                                        <a href="{{ route('services.show', $service->id) }}" 
                                           class="text-center bg-transparent text-black font-bold text-xs uppercase tracking-wider px-3 py-2 border-2 border-black hover:bg-black hover:text-white transition-all duration-150"
                                           style="font-family: 'Montserrat', sans-serif; border-radius: 4px; letter-spacing: 0.08em;">
                                            Lihat
                                        </a>
                                        <a href="{{ route('services.slots.manage', $service) }}" 
                                           class="text-center bg-blue-600 text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-2 border-blue-600 hover:bg-white hover:text-blue-600 transition-all duration-150"
                                           style="font-family: 'Montserrat', sans-serif; border-radius: 4px; letter-spacing: 0.08em;"
                                           title="Kelola Jam Tersedia">
                                            Jam
                                        </a>
                                        <a href="{{ route('services.edit', $service->id) }}" 
                                           class="text-center bg-black text-white font-bold text-xs uppercase tracking-wider px-3 py-2 border-2 border-black hover:bg-white hover:text-black transition-all duration-150"
                                           style="font-family: 'Montserrat', sans-serif; border-radius: 4px; letter-spacing: 0.08em;">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $services->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State dengan Brutal Design -->
            <div class="bg-white border-2 border-black shadow-sm p-16 text-center max-w-2xl mx-auto hover:-translate-y-0.5 hover:shadow-md transition-all duration-200" style="border-radius: 8px;">
                <div class="w-24 h-24 rounded-full bg-gray-100 border-2 border-gray-300 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-black mb-3 uppercase tracking-tight" style="font-family: 'Montserrat', sans-serif;">
                    Belum Ada Jasa
                </h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    Anda belum memiliki jasa yang disetujui. Mulai ajukan jasa pertama Anda dan tunggu persetujuan admin.
                </p>
                <a href="{{ route('services.create') }}" 
                   class="inline-flex items-center gap-2 bg-black text-white font-bold text-xs uppercase tracking-wider px-6 py-3 border-2 border-black hover:bg-white hover:text-black transition-all duration-150"
                   style="font-family: 'Montserrat', sans-serif; border-radius: 4px; letter-spacing: 0.08em; background-color: #E4002B; border-color: #E4002B;">
                    <span class="inline-flex w-7 h-7 rounded-full bg-white/20 items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </span>
                    Ajukan Jasa Sekarang
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<style>
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(24px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.service-card {
    will-change: transform, opacity;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f5f5f5;
}

::-webkit-scrollbar-thumb {
    background: #ddd;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #999;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('filterCategory');
    const subcategorySelect = document.getElementById('filterSubcategory');
    const allSubOptions = subcategorySelect.querySelectorAll('option');

    function filterSubcategories() {
        const selectedCategory = categorySelect.value;
        const currentSubValue = subcategorySelect.value;
        let shouldClearSub = true;

        allSubOptions.forEach(opt => {
            const catId = opt.dataset.category;
            if (selectedCategory === '' || catId == selectedCategory || !catId) {
                opt.style.display = 'block';
                if (catId == selectedCategory && opt.value == currentSubValue) {
                    shouldClearSub = false;
                }
            } else {
                opt.style.display = 'none';
            }
        });

        if (shouldClearSub && selectedCategory !== '') {
            subcategorySelect.value = '';
        }
    }

    categorySelect.addEventListener('change', filterSubcategories);
    filterSubcategories();

    // Smooth scroll reveal effect
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.service-card').forEach(card => {
        observer.observe(card);
    });
});
</script>
@endpush
@endsection