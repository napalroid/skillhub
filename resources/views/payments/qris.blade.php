<x-layouts.app title="Pembayaran QRIS">
    <div class="mx-auto max-w-xl space-y-5">
        <a href="{{ route('orders.show', $order) }}" class="text-sm font-semibold text-blue-600">&larr; Kembali ke order</a>
        <section class="bg-white p-6 shadow-card">
            <p class="text-xs font-bold tracking-[.14em] text-blue-600">PEMBAYARAN ORDER</p>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $order->service->title }}</h1>
            <dl class="mt-5 space-y-2 text-sm"><div class="flex justify-between"><dt class="text-gray-500">Metode</dt><dd class="font-semibold">QRIS</dd></div><div class="flex justify-between"><dt class="text-gray-500">Total pembayaran</dt><dd class="font-bold text-blue-700">Rp{{ number_format($order->final_price, 0, ',', '.') }}</dd></div></dl>
            @if($order->payment?->qris_url && $order->payment->status === 'pending')
                <div class="mt-6 border border-gray-200 p-4 text-center">
                    <img src="{{ $order->payment->qris_url }}" alt="QRIS Midtrans" class="mx-auto w-full max-w-xs">
                    <p class="mt-4 text-sm text-gray-600">Scan QRIS menggunakan aplikasi pembayaran yang mendukung QRIS.</p>

                    @if(! config('midtrans.is_production'))
                        <div class="mt-5 border-t border-dashed border-gray-200 pt-4 text-left">
                            <p class="text-sm font-bold text-gray-900">Uji aman di Midtrans Sandbox</p>
                            <p class="mt-1 text-xs leading-5 text-gray-600">Jangan bayar QRIS Sandbox dari aplikasi DANA asli. Salin URL gambar QRIS ini, lalu gunakan QRIS Simulator Midtrans untuk mensimulasikan pembayaran.</p>
                            <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                                <button type="button" id="copy-qris-url" data-qris-url="{{ $order->payment->qris_url }}" class="inline-flex justify-center border border-gray-900 px-3 py-2 text-xs font-bold text-gray-900 hover:bg-gray-900 hover:text-white">Salin URL QRIS</button>
                                <a href="https://simulator.sandbox.midtrans.com/openapi/qris/index" target="_blank" rel="noopener noreferrer" class="inline-flex justify-center bg-gray-900 px-3 py-2 text-xs font-bold text-white hover:bg-gray-700">Buka QRIS Simulator</a>
                            </div>
                            <p id="copy-qris-feedback" class="mt-2 hidden text-xs font-semibold text-emerald-700" role="status">URL QRIS tersalin. Tempelkan di QRIS Simulator, lalu pilih pembayaran sukses.</p>
                            <button type="button" id="check-status" data-url="{{ route('orders.payment.check', $order) }}" class="mt-4 inline-flex w-full justify-center border border-emerald-600 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-100">Cek Status Pembayaran</button>
                            <p id="check-status-feedback" class="mt-2 hidden text-xs font-semibold text-emerald-700" role="status">Pembayaran terdeteksi. Memuat ulang…</p>
                        </div>
                    @endif
                </div>
            @elseif($order->payment_status === 'paid')
                <p class="mt-6 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">Pembayaran berhasil diterima.</p>
            @elseif(auth()->id() === $order->buyer_id)
                <form method="POST" action="{{ route('orders.payment.qris', $order) }}" class="mt-6">@csrf<button class="w-full bg-blue-600 px-4 py-3 text-sm font-bold text-white hover:bg-blue-700">Buat QRIS Midtrans</button></form>
            @endif
        </section>
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
</x-layouts.app>
