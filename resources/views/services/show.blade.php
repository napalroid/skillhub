<x-layouts.app :title="$service->title">

    <div class="bg-white rounded-xl border border-gray-100 p-8 max-w-3xl">
        <span class="text-xs text-blue-600 font-semibold">{{ $service->subcategory->name }}</span>
        <h1 class="text-2xl font-bold text-gray-800 mt-1">{{ $service->title }}</h1>
        <p class="text-sm text-gray-500 mt-1">oleh {{ $service->seller->name }}</p>

        <p class="text-gray-700 mt-4 leading-relaxed">{{ $service->description }}</p>

        <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-100">
            <span class="text-2xl font-bold text-blue-700">Rp{{ number_format($service->price, 0, ',', '.') }}</span>

            @auth
                @if (auth()->id() !== $service->user_id)
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        <button class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700">
                            Pesan Jasa Ini
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700">
                    Masuk untuk Memesan
                </a>
            @endauth
        </div>
    </div>

</x-layouts.app>