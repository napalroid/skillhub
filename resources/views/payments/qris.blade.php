<x-layouts.app title="Pembayaran QRIS">
    <div class="mx-auto max-w-xl space-y-5">
        <a href="{{ route('orders.show', $order) }}" class="text-sm font-semibold text-blue-600">&larr; Kembali ke order</a>
        <section class="bg-white p-6 shadow-card">
            <p class="text-xs font-bold tracking-[.14em] text-blue-600">PEMBAYARAN ORDER</p>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $order->service->title }}</h1>
            <dl class="mt-5 space-y-2 text-sm"><div class="flex justify-between"><dt class="text-gray-500">Metode</dt><dd class="font-semibold">QRIS</dd></div><div class="flex justify-between"><dt class="text-gray-500">Total pembayaran</dt><dd class="font-bold text-blue-700">Rp{{ number_format($order->final_price, 0, ',', '.') }}</dd></div></dl>
            @if($order->payment?->qris_url && $order->payment->status === 'pending')
                <div class="mt-6 border border-gray-200 p-4 text-center"><img src="{{ $order->payment->qris_url }}" alt="QRIS Midtrans" class="mx-auto w-full max-w-xs"><p class="mt-4 text-sm text-gray-600">Scan QRIS menggunakan aplikasi pembayaran yang mendukung QRIS.</p></div>
            @elseif($order->payment_status === 'paid')
                <p class="mt-6 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">Pembayaran berhasil diterima.</p>
            @elseif(auth()->id() === $order->buyer_id)
                <form method="POST" action="{{ route('orders.payment.qris', $order) }}" class="mt-6">@csrf<button class="w-full bg-blue-600 px-4 py-3 text-sm font-bold text-white hover:bg-blue-700">Buat QRIS Midtrans</button></form>
            @endif
        </section>
    </div>
</x-layouts.app>
