<x-layouts.app title="Pesanan Saya">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pesanan Saya</h1>
        <a href="{{ route('services.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-4 py-2 rounded-lg transition">Cari Jasa</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 divide-y">
        @forelse ($orders as $order)
            @php
                $isBuyer = $order->buyer_id === auth()->id();
                $counterparty = $isBuyer ? $order->service->seller : $order->buyer;
                $statusColor = match ($order->status) {
                    'menunggu_pembayaran' => 'bg-amber-100 text-amber-700',
                    'menunggu_verifikasi' => 'bg-orange-100 text-orange-700',
                    'dibayar', 'selesai' => 'bg-emerald-100 text-emerald-700',
                    'dikerjakan' => 'bg-blue-100 text-blue-700',
                    'menunggu_persetujuan' => 'bg-purple-100 text-purple-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp
            <div class="p-5 flex flex-col sm:flex-row sm:items-center gap-4 justify-between">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-semibold text-gray-800">{{ $order->service->title }}</p>
                        <span class="text-[10px] uppercase tracking-wide px-2 py-0.5 rounded-full {{ $isBuyer ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600' }}">
                            {{ $isBuyer ? 'Sebagai Pembeli' : 'Sebagai Penjual' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $isBuyer ? 'Penjual' : 'Pembeli' }}: {{ $counterparty?->name ?? '-' }} &middot;
                        Pesanan #{{ $order->id }} &middot;
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </p>
                    <span class="inline-block mt-2 text-xs px-3 py-1 rounded-full {{ $statusColor }}">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <p class="text-lg font-bold text-blue-700">Rp{{ number_format($order->final_price, 0, ',', '.') }}</p>
                    @if ($isBuyer && $order->status === 'menunggu_persetujuan')
                        <form method="POST" action="{{ route('order-files.approve', $order) }}" onsubmit="return confirm('Yakin hasil sudah sesuai? Pesanan akan selesai & dana cair otomatis 1 jam kemudian.')">
                            @csrf
                            <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold px-4 py-2 rounded-lg transition active:scale-[0.98]">Selesaikan</button>
                        </form>
                    @endif
                    <a href="{{ route('orders.show', $order) }}" class="bg-gray-800 hover:bg-black text-white text-sm font-bold px-4 py-2 rounded-lg transition">Detail</a>
                </div>
            </div>
        @empty
            <div class="p-10 text-center">
                <p class="text-gray-500 mb-4">Belum ada pesanan.</p>
                <a href="{{ route('services.index') }}" class="text-blue-600 hover:underline font-semibold">Jelajahi jasa yang tersedia →</a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>

</x-layouts.app>
