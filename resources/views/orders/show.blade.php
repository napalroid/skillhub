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
            </div>

            {{-- Negosiasi --}}
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <h2 class="font-bold text-gray-800 mb-3">Negosiasi Harga</h2>

                @foreach ($order->negotiations as $nego)
                    <div class="flex justify-between items-center py-2 border-b last:border-0">
                        <span class="text-sm text-gray-600">
                            {{ $nego->sender->name }} menawar Rp{{ number_format($nego->offered_price, 0, ',', '.') }}
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ $nego->status === 'accepted' ? 'bg-green-100 text-green-700' : ($nego->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ $nego->status }}
                            </span>
                            @if ($nego->status === 'pending' && $nego->sender_id !== auth()->id())
                                <form method="POST" action="{{ route('negotiations.accept', [$order, $nego]) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-xs bg-blue-600 text-white px-2 py-1 rounded">Terima</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach

                <form method="POST" action="{{ route('negotiations.store', $order) }}" class="flex gap-2 mt-4">
                    @csrf
                    <input type="number" name="offered_price" placeholder="Ajukan harga (Rp)" class="flex-1 rounded-lg border-gray-300 text-sm">
                    <button class="bg-gray-800 text-white text-sm px-4 rounded-lg">Tawar</button>
                </form>
            </div>

            {{-- Diskusi --}}
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <h2 class="font-bold text-gray-800 mb-3">Diskusi Pesanan</h2>
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

                @if ($order->payment)
                    <p class="text-sm text-gray-600">Status: <strong>{{ $order->payment->status }}</strong></p>
                    <img src="{{ Storage::url($order->payment->proof_file) }}" class="mt-3 rounded-lg w-full">
                @elseif ($isBuyer)
                    <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <input type="hidden" name="amount" value="{{ $order->final_price }}">
                        <input type="file" name="proof_file" class="text-sm">
                        <button class="w-full bg-blue-600 text-white text-sm py-2 rounded-lg">Upload Bukti Bayar</button>
                    </form>
                @else
                    <p class="text-sm text-gray-500">Menunggu buyer melakukan pembayaran.</p>
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

    @if ($isSeller && in_array($order->status, ['dibayar', 'dikerjakan']))
        <form method="POST" action="{{ route('order-files.store', $order) }}" enctype="multipart/form-data" class="flex gap-2">
            @csrf
            <input type="hidden" name="file_type" value="hasil">
            <input type="file" name="file" class="text-sm flex-1">
            <button class="bg-blue-600 text-white text-sm px-4 rounded-lg">Upload Hasil</button>
        </form>
    @endif

    @if ($isBuyer && $order->status === 'menunggu_persetujuan')
        <div class="flex gap-2 mt-4">
            <form method="POST" action="{{ route('order-files.approve', $order) }}">
                @csrf @method('PATCH')
                <button class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg">✓ Setujui Hasil</button>
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