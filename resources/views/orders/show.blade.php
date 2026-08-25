<x-layouts.app title="Pesanan #{{ $order->id }}">

    <div class="grid md:grid-cols-3 gap-6">

        <div class="md:col-span-2 space-y-6">

            {{-- Info Pesanan --}}
<div class="bg-white rounded-xl border border-gray-100 p-6">
                <h1 class="text-xl font-bold text-gray-800">{{ $order->service->title }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Seller: {{ $order->service->seller->name }} &middot; Buyer: {{ $order->buyer->name }}
                </p>
                <span class="inline-block mt-3 text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
                <p class="text-2xl font-bold text-blue-700 mt-4">
                    Rp{{ number_format($order->final_price, 0, ',', '.') }}
                </p>
                @if($isBuyer && $order->status === 'menunggu_pembayaran')
                    <a href="{{ route('orders.payment.show', $order) }}" class="inline-block mt-4 bg-blue-600 px-4 py-2 text-sm font-bold text-white">Bayar Sekarang</a>
                @endif
            </div>

            {{-- Diskusi Pesanan --}}
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-bold text-gray-800">Diskusi Pesanan</h2>
                    <a href="{{ route('conversations.show', $order->service->conversations()->where('buyer_id', $order->buyer_id)->where('seller_id', $order->service->user_id)->first()?->id ?? '#') }}" class="text-xs text-blue-600 hover:underline">Lihat riwayat diskusi harga</a>
                </div>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach ($order->messages as $msg)
                        <div class="text-sm">
                            <span class="font-semibold text-gray-700">{{ $msg->sender->name }}:</span>
                            <span class="text-gray-600">{{ $msg->message }}</span>
                        </div>
                    @endforeach
                </div>
                <form method="POST" action="{{ route('order-messages.store', $order) }}" class="flex gap-2 mt-4">
                    @csrf
                    <input type="text" name="message" placeholder="Tulis pesan..." class="flex-1 rounded-lg border-gray-300 text-sm">
                    <button class="bg-gray-800 text-white text-sm px-4 rounded-lg">Kirim</button>
                </form>
            </div>

        </div>

        {{-- Sidebar: Pembayaran --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <h2 class="font-bold text-gray-800 mb-3">Pembayaran</h2>

                @php
                    $paymentStatusLabel = match ($order->payment_status) {
                        'paid' => $order->payment?->isAdminConfirmed() ? 'Saldo dikonfirmasi — seller sedang mengerjakan' : 'QRIS lunas — menunggu konfirmasi saldo admin (escrow)',
                        'pending' => 'Menunggu pembayaran',
                        'expired' => 'Kedaluwarsa',
                        'failed' => 'Gagal',
                        default => ucfirst((string) $order->payment_status),
                    };
                @endphp

                @if ($order->payment_status === 'paid' && $order->payment?->isAdminConfirmed())
                    <p class="rounded-lg bg-black p-3 text-sm font-bold text-white">Saldo dikonfirmasi admin. Seller telah diberi instruksi untuk segera mengerjakan pesanan jasa.</p>
                @elseif ($order->payment_status === 'paid')
                    <p class="rounded-lg bg-[#E4002B] p-3 text-sm font-bold text-white">Jasa terbayarkan. Saldo QRIS masuk menunggu konfirmasi admin sebelum seller mulai mengerjakan.</p>
                    <p class="mt-3 text-xs text-gray-500">Admin akan mencocokkan dana masuk di rekening, lalu menekan <strong>Konfirmasi Saldo Masuk</strong> di Transaksi.</p>
                @elseif ($isBuyer && $order->status === 'menunggu_pembayaran')
                    <p class="text-sm text-gray-600">Bayar aman melalui QRIS Midtrans Sandbox. Setelah sukses, status menjadi Jasa Terbayarkan di admin.</p>
                    <a href="{{ route('orders.payment.show', $order) }}" class="mt-4 block w-full rounded-lg bg-blue-600 py-2.5 text-center text-sm font-bold text-white transition hover:bg-blue-700">Bayar dengan QRIS</a>
                @elseif ($order->payment)
                    <p class="text-sm text-gray-600">Status pembayaran: <strong>{{ $paymentStatusLabel }}</strong></p>
                @else
                    <p class="text-sm text-gray-500">Menunggu buyer melakukan pembayaran.</p>
                @endif

                @if ($isSeller)
                    <div class="mt-4 pt-3 border-t border-gray-100 space-y-2">
                        <a href="{{ route('wallet.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:underline">
                            Lihat Dompet &rarr;
                        </a>
                        @if ($order->status === 'menunggu_persetujuan')
                            <p class="text-xs text-gray-500">Hasil sudah dikirim. Menunggu buyer menyetujui — setelah disetujui, dana cair otomatis ke dompet <strong>1 jam</strong> kemudian.</p>
                        @elseif ($order->status === 'dikerjakan')
                            <p class="text-xs text-gray-500">Sedang dikerjakan. Upload hasil di bawah untuk menyerahkan pesanan ke buyer.</p>
                        @elseif ($order->status === 'dibayar')
                            <p class="text-xs text-gray-500">Dana sudah di-escrow. Mulai kerjakan lalu upload hasil.</p>
                        @elseif ($order->status === 'selesai')
                            <p class="text-xs text-gray-500">Pesanan selesai. Dana cair otomatis ke dompet 1 jam setelah penyelesaian.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6">
    <h2 class="font-bold text-gray-800 mb-3">File Pesanan</h2>

    <div class="space-y-2 mb-4">
        @foreach ($order->files as $file)
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">
                    <span class="font-semibold">{{ ucfirst($file->file_type) }}</span> oleh {{ $file->uploader->name }}
                </span>
                <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Unduh</a>
            </div>
        @endforeach
    </div>

    @if ($isSeller && $order->status === 'dibayar')
        <form method="POST" action="{{ route('orders.start-work', $order) }}">
            @csrf
            <button class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg">▶ Mulai Kerjakan</button>
        </form>
    @endif

    @if ($isSeller && in_array($order->status, ['dibayar', 'dikerjakan']))
        <form method="POST" action="{{ route('order-files.store', $order) }}" enctype="multipart/form-data" class="mt-2">
            @csrf
            <input type="hidden" name="file_type" value="hasil">
            <div class="flex flex-col sm:flex-row gap-2">
                <input type="file" name="file" accept=".pdf,.zip,.png,.jpg,.jpeg,.doc,.docx,.ppt,.pptx"
                       class="text-sm flex-1 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-900 file:text-white file:cursor-pointer hover:file:bg-black">
                <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition active:scale-[0.98]">Upload Hasil</button>
            </div>
            <p class="text-xs text-gray-400 mt-1.5">Format: PDF / ZIP / JPG / PNG / DOC (maks 5MB).</p>
        </form>
    @endif

    @if ($isBuyer && $order->status === 'menunggu_persetujuan')
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-3 mt-4">
            <p class="text-sm text-emerald-800">Hasil sudah dikirim seller. Jika sudah sesuai, tekan <strong>Selesaikan Pesanan</strong>. Dana akan cair otomatis ke seller <strong>1 jam</strong> setelah itu (jeda anti-salah-klik).</p>
        </div>
        <div class="flex gap-2 mt-3">
            <form method="POST" action="{{ route('order-files.approve', $order) }}">
                @csrf
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-4 py-2 rounded-lg">✓ Selesaikan Pesanan</button>
            </form>
            <button onclick="document.getElementById('revisiForm').classList.toggle('hidden')"
                class="bg-amber-100 text-amber-700 text-sm px-4 py-2 rounded-lg">↺ Minta Revisi</button>
        </div>

        <form id="revisiForm" method="POST" action="{{ route('order-files.revise', $order) }}" class="hidden mt-3 flex gap-2">
            @csrf
            <input type="text" name="revision_note" placeholder="Jelaskan revisi yang diinginkan..." class="flex-1 text-sm rounded-lg border-gray-300">
            <button class="bg-amber-600 text-white text-sm px-4 rounded-lg">Kirim</button>
        </form>
    @endif

    @if ($order->status === 'selesai')
        <div class="rounded-lg bg-gray-100 border p-3 mt-4">
            @if ($order->payment && $order->payment->status === 'released')
                <p class="text-sm text-gray-700">✅ Pesanan selesai & dana sudah cair ke saldo dompet seller.</p>
            @else
                <p class="text-sm text-gray-700">⏳ Pesanan selesai. Dana akan cair otomatis ke seller dalam 1 jam sejak penyelesaian.</p>
            @endif
        </div>
    @endif
</div>

{{-- Review & Rating --}}
@if ($isBuyer && $order->status === 'selesai')
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h2 class="font-bold text-gray-800 mb-3">Beri Review</h2>

        @if ($order->review)
            <p class="text-sm text-gray-600">⭐ {{ $order->review->rating }}/5 — {{ $order->review->comment }}</p>
        @else
            <form method="POST" action="{{ route('reviews.store', $order) }}" class="space-y-3">
                @csrf
                <select name="rating" class="rounded-lg border-gray-300 text-sm">
                    <option value="5">⭐⭐⭐⭐⭐ Sangat Puas</option>
                    <option value="4">⭐⭐⭐⭐ Puas</option>
                    <option value="3">⭐⭐⭐ Cukup</option>
                    <option value="2">⭐⭐ Kurang</option>
                    <option value="1">⭐ Buruk</option>
                </select>
                <textarea name="comment" rows="2" placeholder="Komentar (opsional)" class="w-full rounded-lg border-gray-300 text-sm"></textarea>
                <button class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg">Kirim Review</button>
            </form>
        @endif
    </div>
@endif

{{-- Laporkan Penyalahgunaan --}}
<div class="text-center">
    <button onclick="document.getElementById('reportForm').classList.toggle('hidden')" class="text-sm text-red-500 hover:underline">
        ⚠ Laporkan masalah pada pesanan ini
    </button>
    <form id="reportForm" method="POST" action="{{ route('reports.store') }}" class="hidden mt-3 bg-white rounded-xl border border-red-100 p-4 text-left space-y-3">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <input type="hidden" name="reported_user_id" value="{{ $isBuyer ? $order->service->user_id : $order->buyer_id }}">
        <textarea name="reason" rows="3" placeholder="Jelaskan masalahnya..." class="w-full rounded-lg border-gray-300 text-sm"></textarea>
        <button class="bg-red-600 text-white text-sm px-4 py-2 rounded-lg">Kirim Laporan</button>
    </form>
</div>

</x-layouts.app>
