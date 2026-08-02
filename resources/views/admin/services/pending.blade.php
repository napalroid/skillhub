<x-layouts.app title="Jasa Menunggu Approval">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Jasa Menunggu Approval</h1>

    <div class="bg-white rounded-xl border border-gray-100 divide-y">
        @forelse ($services as $service)
            <div class="p-5 flex justify-between items-start">
                <div>
                    <span class="text-xs text-blue-600 font-semibold">{{ $service->subcategory->name }}</span>
                    <h3 class="font-bold text-gray-800">{{ $service->title }}</h3>
                    <p class="text-sm text-gray-500">oleh {{ $service->seller->name }} &middot; Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-600 mt-2">{{ $service->description }}</p>
                </div>
                <div class="flex gap-2 shrink-0 ml-4">
                    <form method="POST" action="{{ route('admin.services.approve', $service) }}">
                        @csrf @method('PATCH')
                        <button class="bg-blue-600 text-white text-sm px-3 py-1.5 rounded-lg hover:bg-blue-700">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.services.reject', $service) }}">
                        @csrf @method('PATCH')
                        <button class="bg-red-100 text-red-700 text-sm px-3 py-1.5 rounded-lg hover:bg-red-200">Reject</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="p-6 text-gray-500 text-center">Tidak ada jasa yang menunggu approval.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $services->links() }}</div>

</x-layouts.app>