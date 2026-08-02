<x-layouts.app title="Dashboard">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Halo, {{ auth()->user()->name }} 👋</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Jasa Aktif</p>
            <p class="text-2xl font-bold text-blue-700">{{ $stats['jasa_aktif'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Jasa Menunggu Approval</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['jasa_pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Pesanan Berjalan</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['pesanan_berjalan'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Pesanan Selesai</p>
            <p class="text-2xl font-bold text-gray-700">{{ $stats['pesanan_selesai'] }}</p>
        </div>
    </div>

    <h2 class="text-lg font-bold text-gray-800 mb-4">Pesanan Terbaru</h2>
    <div class="bg-white rounded-xl border border-gray-100 divide-y">
        @forelse ($pesanan_terbaru as $order)
            <div class="p-4 flex justify-between items-center">
                <div>
                    <p class="font-semibold text-gray-800">{{ $order->service->title }}</p>
                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                </div>
                <span class="text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-700">{{ str_replace('_', ' ', $order->status) }}</span>
            </div>
        @empty
            <p class="p-4 text-gray-500 text-center">Belum ada pesanan.</p>
        @endforelse
    </div>

</x-layouts.app>