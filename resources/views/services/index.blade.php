<x-layouts.app title="Daftar Jasa">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Jasa Tersedia</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse ($services as $service)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <span class="text-xs text-blue-600 font-semibold">{{ $service->subcategory->name }}</span>
                <h2 class="text-lg font-bold text-gray-800 mt-1">{{ $service->title }}</h2>
                <p class="text-gray-500 text-sm mt-2 line-clamp-2">{{ $service->description }}</p>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-blue-700 font-bold">Rp{{ number_format($service->price, 0, ',', '.') }}</span>
                    <a href="{{ route('services.show', $service) }}" class="text-sm bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500 col-span-3 text-center py-12">Belum ada jasa tersedia.</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $services->links() }}
    </div>

</x-layouts.app>