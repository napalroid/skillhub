<x-layouts.admin>
    @php
        $filterLabels = [
            'all' => 'Semua',
            'masuk' => 'Uang Masuk',
            'keluar' => 'Uang Keluar',
            'pending' => 'Pending Konfirmasi',
            'expired' => 'Kadaluarsa',
        ];
        $sortOptions = [
            'latest' => 'Terbaru',
            'oldest' => 'Terlama',
            'amount_desc' => 'Jumlah Tertinggi',
            'amount_asc' => 'Jumlah Terendah',
        ];
    @endphp

    @if($expiringSoon > 0)
        <div class="admin-card bg-[#FFF3CD] border-[#FFE69C] p-4 mb-6" data-stagger-item>
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-[#856404] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <div>
                    <p class="font-heading font-bold text-sm text-[#856404]">Perhatian!</p>
                    <p class="text-xs text-[#856404] mt-1">Ada {{ $expiringSoon }} transaksi yang akan kadaluarsa dalam 1 jam! Segera konfirmasi.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="admin-card p-6 lg:p-7" data-stagger-item>
        <div class="flex flex-col gap-6">
            <div class="max-w-[62ch]">
                <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Riwayat Escrow</h1>
                <p class="text-sm text-[#555555] mt-2 leading-relaxed">
                    Tracking saldo escrow yang tertahan. Saat buyer membayar QRIS, saldo masuk sebagai <span class="font-bold text-black">Pending</span>.
                    Admin konfirmasi saldo masuk → status jadi <span class="font-bold text-black">Selesai</span> dan escrow bertambah.
                    Saat dana dicairkan ke seller, escrow berkurang.
                </p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-4 py-3">
                    <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Saldo Escrow</p>
                    <p class="font-heading font-bold text-lg text-black mt-1">Rp{{ number_format($currentBalance, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-4 py-3">
                    <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Pending</p>
                    <p class="font-heading font-bold text-lg text-[#E4002B] mt-1">Rp{{ number_format($pendingBalance, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-4 py-3">
                    <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Masuk Hari Ini</p>
                    <p class="font-heading font-bold text-lg text-[#2C9F45] mt-1">Rp{{ number_format($todayIn, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-4 py-3">
                    <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Keluar Hari Ini</p>
                    <p class="font-heading font-bold text-lg text-[#555555] mt-1">Rp{{ number_format($todayOut, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                @foreach (['all' => 'Semua', 'masuk' => 'Uang Masuk', 'keluar' => 'Uang Keluar', 'pending' => 'Pending', 'expired' => 'Expired', 'proses_tahan' => 'Proses Tahan Dana', 'cair' => 'Cair'] as $key => $label)
                    <a href="{{ route('admin.escrow.index', ['filter' => $key, 'sort' => $sort]) }}"
                       class="px-4 py-2 rounded-full text-[10px] font-heading font-bold uppercase tracking-wider border transition-colors {{ $filter === $key ? 'bg-black border-black text-white' : 'bg-white border-[#DDDDDD] text-[#555555] hover:border-black hover:text-black' }}">
                        {{ $label }} 
                        @if($key === 'proses_tahan')
                            <span class="opacity-60">({{ $counts['pending'] ?? 0 }})</span>
                        @elseif($key === 'cair')
                            <span class="opacity-60">({{ $counts['keluar'] ?? 0 }})</span>
                        @else
                            <span class="opacity-60">({{ $counts[$key] ?? 0 }})</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="admin-card mt-6" data-stagger-container>
        <div class="flex flex-col gap-3 border-b border-[#DDDDDD] p-5 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-heading font-semibold text-sm text-black uppercase tracking-tight">Transaksi Escrow</h2>

            <form method="GET" action="{{ route('admin.escrow.index') }}" class="flex items-center gap-2">
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
            @if ($transactions->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th class="w-24">Tipe</th>
                                <th class="w-32">Jumlah</th>
                                <th class="w-32">Saldo Sebelum</th>
                                <th class="w-32">Saldo Sesudah</th>
                                <th>Keterangan</th>
                                <th class="w-32">Batas Waktu</th>
                                <th class="w-28">Status</th>
                                <th class="w-[200px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                @php
                                    $order = $transaction->order ?? $transaction->payment?->order;
                                    $service = $order?->service;
                                    $buyer = $order?->buyer;
                                @endphp
                                <tr class="row-enter">
                                    <td>
                                        <p class="text-xs text-[#555555]">{{ $transaction->created_at->format('d M Y') }}</p>
                                        <p class="text-[10px] text-[#999999]">{{ $transaction->created_at->format('H:i') }}</p>
                                    </td>
                                    <td>
                                        @if($transaction->type === 'in')
                                            <span class="badge badge-success">Masuk</span>
                                        @else
                                            <span class="badge" style="background:#555;color:#fff;border-color:#555;">Keluar</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="font-heading font-bold text-sm {{ $transaction->type === 'in' ? 'text-[#2C9F45]' : 'text-[#555555]' }}">
                                            {{ $transaction->type === 'in' ? '+' : '-' }}Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-xs text-[#555555]">Rp{{ number_format($transaction->balance_before, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="text-xs text-black font-medium">Rp{{ number_format($transaction->balance_after, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <p class="text-xs text-[#555555]">{{ $transaction->description ?? '—' }}</p>
                                        @if($order)
                                            <p class="text-[10px] text-[#999999] mt-1">
                                                Order #{{ $order->id }}
                                                @if($service)
                                                    · {{ \Illuminate\Support\Str::limit($service->title, 30) }}
                                                @endif
                                            </p>
                                            @if($buyer)
                                                <p class="text-[10px] text-[#999999]">Buyer: {{ $buyer->name }}</p>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->expires_at)
                                            <p class="text-xs text-[#555555]">{{ $transaction->expires_at->format('d M H:i') }}</p>
                                            @if($transaction->isPending())
                                                @php
                                                    $remaining = $transaction->timeRemaining();
                                                    $hours = floor($remaining / 3600);
                                                    $minutes = floor(($remaining % 3600) / 60);
                                                @endphp
                                                @if($remaining > 0)
                                                    <p class="text-[10px] font-bold {{ $remaining < 3600 ? 'text-[#E4002B]' : 'text-[#FF8C00]' }}">
                                                        Sisa: {{ $hours }}j {{ $minutes }}m
                                                    </p>
                                                @else
                                                    <p class="text-[10px] font-bold text-[#E4002B]">LEWAT</p>
                                                @endif
                                            @endif
                                        @else
                                            <span class="text-xs text-[#999999]">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->status === 'pending')
                                            <span class="badge" style="background:#FF8C00;color:#fff;border-color:#FF8C00;">Pending</span>
                                        @elseif($transaction->status === 'completed')
                                            <span class="badge badge-success">Selesai</span>
                                            @if($transaction->processed_at)
                                                <p class="text-[9px] text-[#999999] mt-1">{{ $transaction->processed_at->format('d M H:i') }}</p>
                                            @endif
                                        @elseif($transaction->status === 'expired')
                                            <span class="badge badge-error">Expired</span>
                                        @elseif($transaction->status === 'cancelled')
                                            <span class="badge badge-neutral">Batal</span>
                                        @else
                                            <span class="badge badge-neutral">{{ $transaction->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-2">
                                            @if($transaction->isPending())
                                                <form action="{{ route('admin.escrow.confirm', $transaction) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa saldo sudah masuk ke rekening?')">
                                                    @csrf
                                                    <button type="submit" class="w-full btn-primary text-[10px] px-4 py-2 justify-center">Konfirmasi</button>
                                                </form>
                                                <form action="{{ route('admin.escrow.reject', $transaction) }}" method="POST" onsubmit="return confirm('Tolak transaksi ini?')">
                                                    @csrf
                                                    <button type="submit" class="w-full btn-danger text-[10px] px-2 py-1.5 justify-center">Tolak</button>
                                                </form>
                                            @endif
                                            @if($order)
                                                <a href="{{ route('orders.show', $order) }}" class="btn-ghost text-[10px] px-2 py-1.5 justify-center border border-[#DDDDDD]">Lihat Pesanan</a>
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
                        Menampilkan {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi (filter: {{ $filterLabels[$filter] ?? $filter }})
                    </p>
                    <div class="flex items-center gap-2">
                        {{ $transactions->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                    </div>
                    <p class="text-xs font-heading font-bold uppercase tracking-wide text-[#999999]">Tidak ada transaksi pada filter ini.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
