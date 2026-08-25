<x-layouts.admin>
    @php
        $filterLabels = [
            'all' => 'Semua',
            'verification' => 'Verifikasi Pembayaran',
            'escrow' => 'Proses Tahan Dana',
            'cair' => 'Cair',
        ];
        $sortOptions = [
            'latest' => 'Terbaru',
            'oldest' => 'Terlama',
            'amount_desc' => 'Jumlah Tertinggi',
            'amount_asc' => 'Jumlah Terendah',
            'status' => 'Status',
        ];
    @endphp

    {{-- ============ KOLOM 1: TRANSAKSI & ESCROW + TOMBOL FILTER ============ --}}
    <div class="admin-card p-6 lg:p-7" data-stagger-item>
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-[62ch]">
                <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Transaksi & Escrow</h1>
                <p class="text-sm text-[#555555] mt-2 leading-relaxed">
                    Setelah buyer membayar QRIS, transaksi muncul di <span class="font-bold text-black">Verifikasi Pembayaran</span>.
                    Admin menekan <span class="font-bold text-black">Konfirmasi Saldo Masuk</span> → dana masuk ke
                    <span class="font-bold text-black">Proses Tahan Dana</span>. Bila jasa selesai dan dana dicairkan,
                    status berubah menjadi <span class="font-bold text-black">Cair</span>.
                </p>

                <div class="mt-4 flex flex-wrap gap-3">
                    <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-3 py-2">
                        <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Saldo Escrow</p>
                        <p class="font-heading font-bold text-sm text-black">Rp{{ number_format($escrowBalance, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-3 py-2">
                        <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Perlu Konfirmasi</p>
                        <p class="font-heading font-bold text-sm text-[#E4002B]">{{ $awaitingConfirm }}</p>
                    </div>
                    <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-3 py-2">
                        <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Bukti Pending</p>
                        <p class="font-heading font-bold text-sm text-black">{{ $pendingProof }}</p>
                    </div>
                </div>
            </div>

            {{-- Tombol filter berada di kolom 1 --}}
            <div class="flex flex-wrap gap-3">
                @foreach (['verification' => 'Verifikasi Pembayaran', 'escrow' => 'Proses Tahan Dana', 'cair' => 'Cair', 'all' => 'Semua'] as $key => $label)
                    <a href="{{ route('admin.payments.index', ['filter' => $key, 'sort' => $sort]) }}"
                       class="px-4 py-2 rounded-full text-[10px] font-heading font-bold uppercase tracking-wider border transition-colors {{ $filter === $key ? 'bg-black border-black text-white' : 'bg-white border-[#DDDDDD] text-[#555555] hover:border-black hover:text-black' }}">
                        {{ $label }} <span class="opacity-60">({{ $counts[$key] }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ============ KOLOM 2: JASA (TABEL TRANSAKSI + PERSORTIRAN) ============ --}}
    <div class="admin-card mt-6" data-stagger-container>
        <div class="flex flex-col gap-3 border-b border-[#DDDDDD] p-5 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-heading font-semibold text-sm text-black uppercase tracking-tight">Jasa</h2>

            {{-- Persortiran berada di kolom 2 --}}
            <form method="GET" action="{{ route('admin.payments.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <label for="sort" class="text-[10px] font-heading font-bold uppercase tracking-wider text-[#999999]">Urutkan</label>
                <select name="sort" id="sort" onchange="this.form.submit()"
                        class="rounded-full border border-[#DDDDDD] bg-white px-3 py-1.5 text-xs text-black focus:border-black focus:outline-none">
                    @foreach ($sortOptions as $value => $label)
                        <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-hidden">
            @if ($payments->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Pesanan</th>
                                <th class="w-44">Pembeli / Seller</th>
                                <th class="w-28">Jumlah</th>
                                <th class="w-28">Status</th>
                                <th class="w-32">Tanggal</th>
                                <th class="w-[300px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                @php
                                    $paymentStatus = $payment->status;
                                    $orderStatus = $payment->order->status;
                                    $service = $payment->order->service;
                                @endphp
                                <tr class="row-enter">
                                    <td>
                                        <p class="font-medium text-black truncate max-w-xs text-sm leading-tight">{{ $service->title }}</p>
                                        <p class="text-[10px] text-[#999999] mt-0.5">Order #{{ $payment->order->id }} · {{ $orderStatus }}</p>
                                        @if ($payment->gateway_transaction_id)
                                            <p class="text-[9px] text-[#999999] mt-1 font-heading uppercase tracking-wide">Tx {{ \Illuminate\Support\Str::limit($payment->gateway_transaction_id, 22) }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-bold text-black">{{ $payment->order->buyer->name ?? 'N/A' }}</p>
                                        <p class="text-[10px] text-[#999999]">{{ $payment->order->buyer->email ?? '' }}</p>
                                        <p class="text-[10px] text-[#555555] mt-1">Seller: {{ $service->seller?->name ?? '—' }}</p>
                                    </td>
                                    <td>
                                        <span class="font-heading font-bold text-sm text-black">
                                            Rp{{ number_format($payment->amount, 0, ',', '.') }}
                                        </span>
                                        @if ($payment->payment_type)
                                            <p class="text-[9px] font-heading font-bold uppercase tracking-wide text-[#999999] mt-0.5">{{ $payment->payment_type }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($paymentStatus === 'paid')
                                            <span class="badge bg-black text-white border-black">Terbayarkan</span>
                                            <p class="text-[9px] text-[#E4002B] font-heading font-bold uppercase tracking-wide mt-1">Perlu konfirmasi saldo</p>
                                        @elseif ($paymentStatus === 'verified')
                                            <span class="badge badge-success">Escrow</span>
                                            @if ($payment->admin_confirmed_at)
                                                <p class="text-[9px] text-[#999999] mt-1">{{ $payment->admin_confirmed_at->format('d M Y H:i') }}</p>
                                            @endif
                                        @elseif ($paymentStatus === 'released')
                                            <span class="badge" style="background:#2C9F45;color:#fff;border-color:#2C9F45;">Cair</span>
                                            @if ($payment->admin_confirmed_at)
                                                <p class="text-[9px] text-[#999999] mt-1">Cair {{ $payment->updated_at->format('d M Y H:i') }}</p>
                                            @endif
                                        @elseif ($paymentStatus === 'pending')
                                            <span class="badge badge-pending">Pending</span>
                                        @elseif ($paymentStatus === 'rejected')
                                            <span class="badge badge-accent">Ditolak</span>
                                        @else
                                            <span class="badge badge-neutral">{{ $paymentStatus }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs text-[#555555]">{{ $payment->created_at->format('d M Y H:i') }}</p>
                                        @if ($payment->expires_at)
                                            <p class="text-[10px] text-[#999999]">exp {{ $payment->expires_at->format('d M H:i') }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-2">
                                            @if ($paymentStatus === 'paid')
                                                <form action="{{ route('admin.payments.confirm-balance', $payment) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa saldo QRIS sudah masuk ke rekening admin? Seller akan diberi tahu untuk mengerjakan pesanan.')">
                                                    @csrf
                                                    <button type="submit" class="w-full btn-primary text-[10px] px-4 py-2 justify-center">Konfirmasi Saldo Masuk</button>
                                                </form>
                                                <div class="flex gap-1.5">
                                                    <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" class="flex-1" onsubmit="return confirm('Tolak pembayaran ini?')">
                                                        @csrf
                                                        <input type="hidden" name="rejection_reason" value="Saldo tidak masuk / bukti tidak valid">
                                                        <button type="submit" class="w-full btn-outline text-[10px] px-2 py-1.5 justify-center border-[#E4002B] text-[#E4002B] hover:bg-[#E4002B] hover:text-white">Tolak</button>
                                                    </form>
                                                    <a href="{{ route('orders.show', $payment->order) }}" class="flex-1 btn-ghost text-[10px] px-2 py-1.5 justify-center border border-[#DDDDDD]">Detail</a>
                                                </div>
                                            @elseif ($paymentStatus === 'pending')
                                                <div class="flex gap-1.5">
                                                    <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="flex-1">
                                                        @csrf
                                                        <button type="submit" class="w-full btn-primary text-[10px] px-2 py-1.5 justify-center">Verifikasi</button>
                                                    </form>
                                                    <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" class="flex-1" onsubmit="return confirm('Tolak pembayaran ini?')">
                                                        @csrf
                                                        <input type="hidden" name="rejection_reason" value="Bukti tidak sesuai">
                                                        <button type="submit" class="w-full btn-danger text-[10px] px-2 py-1.5 justify-center">Tolak</button>
                                                    </form>
                                                </div>
                                                <a href="{{ route('orders.show', $payment->order) }}" class="btn-ghost text-[10px] px-2 py-1 justify-center border border-[#DDDDDD]">Detail pesanan</a>
                                            @elseif ($paymentStatus === 'verified')
                                                <a href="{{ route('orders.show', $payment->order) }}" class="btn-ghost text-[10px] px-2 py-1.5 justify-center border border-[#DDDDDD]">Lihat pesanan</a>
                                                @if ($payment->order && $payment->order->status === 'selesai')
                                                    <form action="{{ route('admin.orders.release', $payment->order) }}" method="POST" onsubmit="return confirm('Cairkan dana Rp{{ number_format($payment->amount, 0, ',', '.') }} ke saldo dompet seller?')">
                                                        @csrf
                                                        <button type="submit" class="w-full btn-primary text-[10px] px-4 py-2 justify-center">Cairkan Sekarang</button>
                                                    </form>
                                                    <p class="text-[9px] text-[#2C9F45] font-heading font-bold uppercase tracking-wide leading-snug">Siap cair (otomatis 1 jam)</p>
                                                @else
                                                    <p class="text-[9px] text-[#555555] leading-snug">Dana ditahan. Cairkan setelah jasa selesai.</p>
                                                @endif
                                                <form action="{{ route('admin.orders.refund', $payment->order) }}" method="POST" onsubmit="return confirm('Batalkan pesanan & kembalikan dana ke buyer?')">
                                                    @csrf
                                                    <button type="submit" class="w-full btn-outline text-[10px] px-2 py-1.5 justify-center border-[#E4002B] text-[#E4002B] hover:bg-[#E4002B] hover:text-white">Batalkan & Refund</button>
                                                </form>
                                            @elseif ($paymentStatus === 'released')
                                                <a href="{{ route('orders.show', $payment->order) }}" class="btn-ghost text-[10px] px-2 py-1.5 justify-center border border-[#DDDDDD]">Lihat pesanan</a>
                                                <p class="text-[9px] text-[#2C9F45] font-heading font-bold uppercase tracking-wide leading-snug">Dana sudah cair</p>
                                            @else
                                                <a href="{{ route('orders.show', $payment->order) }}" class="btn-ghost text-[10px] px-2 py-1.5 justify-center border border-[#DDDDDD]">Lihat pesanan</a>
                                            @endif

                                            @if ($payment->proof_file)
                                                <a href="{{ asset('storage/' . $payment->proof_file) }}" target="_blank" class="inline-flex items-center justify-center gap-1 text-[10px] font-heading font-bold uppercase tracking-wide text-[#555555] hover:text-black">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    Bukti bayar
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-[#DDDDDD] flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-[#999999]">
                        Menampilkan {{ $payments->firstItem() }}–{{ $payments->lastItem() }} dari {{ $payments->total() }} transaksi (filter: {{ $filterLabels[$filter] ?? $filter }})
                    </p>
                    <div class="flex items-center gap-2">
                        {{ $payments->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l2.5 2.5M15 15l3-3"/></svg>
                    </div>
                    <p class="text-xs font-heading font-bold uppercase tracking-wide text-[#999999]">Tidak ada transaksi pada filter ini.</p>
                    <p class="text-xs text-[#999999] mt-1">Ganti filter di atas atau tunggu QRIS baru masuk.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
