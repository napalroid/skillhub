{{-- resources/views/admin/payments/index.blade.php --}}
<x-layouts.app title="Verifikasi Pembayaran">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Pembayaran Menunggu Verifikasi</h1>

    <div class="bg-white rounded-xl border border-gray-100 divide-y">
        @forelse ($payments as $payment)
            <div class="p-5 flex justify-between items-center">
                <div>
                    <p class="font-semibold text-gray-800">{{ $payment->order->service->title }}</p>
                    <p class="text-sm text-gray-500">
                        Buyer: {{ $payment->order->buyer->name }} &middot; Rp{{ number_format($payment->amount, 0, ',', '.') }}
                    </p>
                    <a href="{{ Storage::url($payment->proof_file) }}" target="_blank" class="text-sm text-blue-600 hover:underline">Lihat bukti bayar →</a>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                        @csrf @method('PATCH')
                        <button class="bg-blue-600 text-white text-sm px-3 py-1.5 rounded-lg">Verifikasi</button>
                    </form>
                    <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="rejection_reason" value="Bukti tidak sesuai">
                        <button class="bg-red-100 text-red-700 text-sm px-3 py-1.5 rounded-lg">Tolak</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="p-6 text-gray-500 text-center">Tidak ada pembayaran menunggu verifikasi.</p>
        @endforelse
    </div>

</x-layouts.app>