<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Marketplace') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Semua Jasa</h1>
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Kembali ke Dashboard</a>
            </div>

            @if($services->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($services as $service)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                            <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-400">
                                <!-- Placeholder Image -->
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 truncate">{{ $service->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $service->user->name }}</p>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-blue-600 font-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                    <a href="{{ route('services.show', $service) }}" class="text-sm text-blue-600 hover:underline">Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8">
                    {{ $services->links() }}
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                    <p class="text-gray-500">Belum ada jasa yang tersedia saat ini.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>