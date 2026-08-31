@extends('layouts.app')

@section('title', 'Pembayaran QRIS')

@section('content')
<style>
    .payment-card {
        background: #fff;
        border: 1px solid #e5e5e5;
        padding: 2rem;
    }
    
    .qris-container {
        background: #f5f5f5;
        border: 2px solid #000;
        padding: 2rem;
    }
</style>

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
    <div class="grid lg:grid-cols-[380px_1fr] gap-6">
        
        {{-- SIDEBAR: Order Summary --}}
        <div class="space-y-4">
            <div class="payment-card">
                @if($order->service->thumbnail)
                    <img src="{{ Storage::url($order->service->thumbnail) }}" alt="{{ $order->service->title }}" class="w-full h-48 object-cover mb-4 border border-gray-200">
                @else
                    <div class="w-full h-48 bg-gray-200 mb-4 flex items-center justify-center border border-gray-300">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
                
                <h2 class="font-heading text-sm font-black uppercase tracking-wider mb-3">Detail Pesanan</h2>
                <h3 class="text-base font-bold mb-4">{{ $order->service->title }}</h3>
                
                <div class="space-y-2 text-xs pb-4 border-b border-gray-200">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Penjual</span>
                        <span class="font-bold">{{ $order->service->seller->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Order ID</span>
                        <span class="font-bold">#{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Metode Pembayaran</span>
                        <span class="font-bold">QRIS</span>
                    </div>
                </div>
                
                <div class="pt-4">
                    <div class="flex justify-between items-center">
                        <span class="font-heading text-xs font-black uppercase tracking-wider">Total Pembayaran</span>
                        <span class="font-heading text-xl font-black">Rp{{ number_format($order->final_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('orders.show', $order) }}" class="btn-outline w-full block text-center">
                Kembali ke Pesanan
            </a>
        </div>
        
        {{-- MAIN: Payment Section --}}
        <div class="payment-card">
            <h1 class="font-heading text-lg font-black uppercase tracking-wider mb-6">Pembayaran QRIS</h1>
            
            @if($order->payment?->qris_url && $order->payment->status === 'pending')
                <div class="qris-container text-center">
                    <img src="{{ $order->payment->qris_url }}" alt="QRIS Midtrans" class="mx-auto w-full max-w-[280px] bg-white p-4">
                    <p class="mt-4 text-xs font-bold uppercase tracking-wider">Scan QRIS menggunakan aplikasi pembayaran yang mendukung QRIS</p>
                    
                    @if($order->payment->expires_at)
                        <p class="mt-2 text-xs text-gray-600">Berlaku hingga: {{ $order->payment->expires_at->format('d M Y, H:i') }}</p>
                    @endif
                </div>

                @if(! config('midtrans.is_production'))
                    <div class="mt-6 p-4 bg-[#EDE734] border-2 border-black">
                        <h3 class="font-heading text-xs font-black uppercase tracking-wider mb-3">Mode Sandbox Testing</h3>
                        <p class="text-xs leading-relaxed text-gray-900 mb-4">
                            Jangan bayar QRIS Sandbox dari aplikasi DANA asli. Salin URL gambar QRIS ini, lalu gunakan QRIS Simulator Midtrans untuk mensimulasikan pembayaran.
                        </p>
                        <div class="flex flex-col gap-2">
                            <button type="button" id="copy-qris-url" data-qris-url="{{ $order->payment->qris_url }}" class="btn-outline w-full">
                                Salin URL QRIS
                            </button>
                            <a href="https://simulator.sandbox.midtrans.com/openapi/qris/index" target="_blank" rel="noopener noreferrer" class="btn-primary w-full text-center">
                                Buka QRIS Simulator
                            </a>
                        </div>
                        <p id="copy-qris-feedback" class="mt-3 hidden text-xs font-bold text-black bg-white px-3 py-2 border border-black" role="status">
                            URL QRIS tersalin. Tempelkan di QRIS Simulator, lalu pilih pembayaran sukses.
                        </p>
                        <button type="button" id="check-status" data-url="{{ route('orders.payment.check', $order) }}" class="mt-4 btn-success w-full">
                            Cek Status Pembayaran
                        </button>
                        <p id="check-status-feedback" class="mt-3 hidden text-xs font-bold text-[#2C9F45] bg-white px-3 py-2 border border-[#2C9F45]" role="status">
                            Pembayaran terdeteksi. Memuat ulang…
                        </p>
                    </div>
                @endif
                
            @elseif($order->payment_status === 'paid')
                <div class="bg-[#2C9F45] text-white p-6 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="font-heading text-sm font-black uppercase tracking-wider mb-2">Pembayaran Berhasil</h2>
                    <p class="text-xs">Pembayaran QRIS telah diterima. Menunggu konfirmasi admin untuk memulai pesanan.</p>
                </div>
                
                @if(auth()->id() === $order->buyer_id)
                    <form method="POST" action="{{ route('order-messages.store', $order) }}" class="mt-6 p-4 bg-gray-50 border border-gray-200">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $order->service_id }}">
                        <h3 class="font-heading text-xs font-black uppercase tracking-wider mb-3">Tinggalkan Pesan untuk Seller</h3>
                        <textarea name="message" rows="3" placeholder="Opsional: Tambahkan instruksi atau detail tambahan untuk seller..." class="w-full text-xs px-3 py-2 border border-gray-300 rounded focus:border-black outline-none mb-3"></textarea>
                        <button type="submit" class="btn-outline w-full">
                            Kirim Pesan
                        </button>
                    </form>
                @endif
                
            @elseif(auth()->id() === $order->buyer_id)
                <div class="text-center py-8">
                    <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <h2 class="font-heading text-sm font-black uppercase tracking-wider mb-2">Belum Ada QRIS</h2>
                    <p class="text-xs text-gray-600 mb-6">Klik tombol di bawah untuk membuat kode QRIS pembayaran</p>
                    <form method="POST" action="{{ route('orders.payment.qris', $order) }}">
                        @csrf
                        <button type="submit" class="btn-primary">
                            Buat QRIS Midtrans
                        </button>
                    </form>
                </div>
            @endif
            
            <div class="mt-6 p-4 bg-gray-50 border border-gray-200">
                <h3 class="font-heading text-xs font-black uppercase tracking-wider mb-2">Cara Pembayaran</h3>
                <ol class="text-xs space-y-2 text-gray-700">
                    <li class="flex gap-2">
                        <span class="font-bold">1.</span>
                        <span>Buka aplikasi pembayaran digital (GoPay, OVO, Dana, LinkAja, ShopeePay, dll)</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="font-bold">2.</span>
                        <span>Pilih menu Scan QR atau QRIS</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="font-bold">3.</span>
                        <span>Arahkan kamera ke kode QRIS di atas</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="font-bold">4.</span>
                        <span>Konfirmasi pembayaran sejumlah Rp{{ number_format($order->final_price, 0, ',', '.') }}</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="font-bold">5.</span>
                        <span>Selesai! Status pembayaran akan otomatis terupdate</span>
                    </li>
                </ol>
            </div>
        </div>
        
    </div>
</div>

@if(! config('midtrans.is_production'))
    <script>
        document.getElementById('copy-qris-url')?.addEventListener('click', async function () {
            try {
                await navigator.clipboard.writeText(this.dataset.qrisUrl);
                document.getElementById('copy-qris-feedback')?.classList.remove('hidden');
            } catch (error) {
                window.prompt('Salin URL QRIS ini:', this.dataset.qrisUrl);
            }
        });

        const checkBtn = document.getElementById('check-status');
        const feedback = document.getElementById('check-status-feedback');
        let checking = false;
        let settled = false;

        function checkStatus() {
            if (checking || settled || ! checkBtn) return;
            checking = true;
            fetch(checkBtn.dataset.url, { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    if (data.ok && (data.payment_status === 'paid' || data.payment_status === 'verified' || data.payment_status === 'released')) {
                        settled = true;
                        feedback?.classList.remove('hidden');
                        setTimeout(() => window.location.reload(), 800);
                    }
                })
                .catch(() => {})
                .finally(() => { checking = false; });
        }

        checkBtn?.addEventListener('click', checkStatus);
        const interval = setInterval(checkStatus, 5000);
        window.addEventListener('beforeunload', () => clearInterval(interval));
    </script>
@endif
@endsection
