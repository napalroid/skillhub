<x-layouts.admin>
    @php
        $statusStyles = [
            'pending' => 'badge badge-pending',
            'processing' => 'badge badge-processing',
            'completed' => 'badge badge-success',
            'failed' => 'badge badge-error',
            'rejected' => 'badge badge-error',
            'cancelled' => 'badge badge-error',
        ];
        $statusLabels = [
            'pending' => 'Menunggu',
            'processing' => 'Memproses',
            'completed' => 'Selesai',
            'failed' => 'Gagal',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
        ];
        $filterLabels = [
            'all' => 'Semua',
            'pending' => 'Menunggu',
            'processing' => 'Memproses',
            'completed' => 'Selesai',
            'failed' => 'Gagal',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
        ];
    @endphp

    <div class="admin-card p-6 lg:p-7" data-stagger-item>
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="max-w-[62ch]">
                <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Pencairan Dana</h1>
                <p class="text-sm text-[#555555] mt-2 leading-relaxed">
                    Seller menarik saldo dompet secara <span class="font-bold text-black">mandiri & otomatis</span> ke e-wallet/rekening mereka — tidak perlu persetujuan admin.
                    Halaman ini menampilkan riwayat seluruh pencairan. Pencairan yang masih <span class="font-bold text-black">tertunda</span> (mis. dari data lama) masih bisa diproses manual di sini.
                </p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-3 py-2">
                        <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Menunggu</p>
                        <p class="font-heading font-bold text-sm text-[#E4002B]">{{ $counts['pending'] }}</p>
                    </div>
                    <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-3 py-2">
                        <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Memproses</p>
                        <p class="font-heading font-bold text-sm text-blue-600">{{ $counts['processing'] }}</p>
                    </div>
                    <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-3 py-2">
                        <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Selesai</p>
                        <p class="font-heading font-bold text-sm text-black">{{ $counts['completed'] }}</p>
                    </div>
                    <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-3 py-2">
                        <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Gagal</p>
                        <p class="font-heading font-bold text-sm text-[#E4002B]">{{ $counts['failed'] }}</p>
                    </div>
                    <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-3 py-2">
                        <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Ditolak</p>
                        <p class="font-heading font-bold text-sm text-[#E4002B]">{{ $counts['rejected'] }}</p>
                    </div>
                    <div class="rounded-sm border border-[#DDDDDD] bg-[#F5F5F5] px-3 py-2">
                        <p class="text-[9px] font-heading font-bold uppercase tracking-wider text-[#999999]">Dibatalkan</p>
                        <p class="font-heading font-bold text-sm text-[#E4002B]">{{ $counts['cancelled'] }}</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                @foreach (['all' => 'Semua', 'pending' => 'Menunggu', 'processing' => 'Memproses', 'completed' => 'Selesai', 'failed' => 'Gagal', 'rejected' => 'Ditolak', 'cancelled' => 'Dibatalkan'] as $key => $label)
                    <a href="{{ route('admin.payouts.index', ['status' => $key]) }}"
                       class="px-4 py-2 rounded-full text-[10px] font-heading font-bold uppercase tracking-wider border transition-colors {{ $status === $key ? 'bg-black border-black text-white' : 'bg-white border-[#DDDDDD] text-[#555555] hover:border-black hover:text-black' }}">
                        {{ $label }} <span class="opacity-60">({{ $counts[$key] }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="admin-card mt-6">
        <div class="overflow-hidden">
            @if ($payouts->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Seller</th>
                                <th class="w-32">Jumlah</th>
                                <th class="w-44">Tujuan</th>
                                <th class="w-28">Status</th>
                                <th class="w-32">Tanggal</th>
                                <th class="w-[260px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payouts as $payout)
                                <tr class="row-enter">
                                    <td>
                                        <p class="font-medium text-black truncate max-w-xs text-sm leading-tight">{{ $payout->user->name ?? 'N/A' }}</p>
                                        <p class="text-[10px] text-[#999999] mt-0.5">{{ $payout->user->email ?? '' }}</p>
                                    </td>
                                    <td>
                                        <span class="font-heading font-bold text-sm text-black">Rp{{ number_format($payout->amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <p class="text-xs font-bold text-black">{{ $payout->methodLabel() }}</p>
                                        <p class="text-[10px] text-[#999999]">{{ $payout->account_identifier }}</p>
                                        <p class="text-[10px] text-[#555555]">{{ $payout->account_name }}</p>
                                    </td>
                                    <td>
                                        <span class="{{ $statusStyles[$payout->status] }}">{{ $statusLabels[$payout->status] }}</span>
                                    </td>
                                    <td>
                                        <p class="text-xs text-[#555555]">{{ $payout->created_at->format('d M Y H:i') }}</p>
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-2">
                                            @if ($payout->status === 'pending' && ! $payout->auto_processed)
                                                <form action="{{ route('admin.payouts.process', $payout) }}" method="POST" onsubmit="return confirm('Tandai pencairan Rp{{ number_format($payout->amount, 0, ',', '.') }} ke {{ $payout->methodLabel() }} sebagai selesai?')">
                                                    @csrf
                                                    <button type="submit" class="w-full btn-primary text-[10px] px-4 py-2 justify-center">Proses</button>
                                                </form>
                                                <form action="{{ route('admin.payouts.reject', $payout) }}" method="POST" onsubmit="return confirm('Tolak pencairan ini? Saldo akan dikembalikan ke seller.')">
                                                    @csrf
                                                    <input type="hidden" name="admin_note" value="Data tujuan tidak valid">
                                                    <button type="submit" class="w-full btn-danger text-[10px] px-2 py-1.5 justify-center">Tolak</button>
                                                </form>
                                            @elseif ($payout->status === 'failed')
                                                <form action="{{ route('admin.payout.retry', $payout) }}" method="POST" onsubmit="return confirm('Ulangi pencairan ini? Saldo akan dikurangi lagi dan ditransfer ke user.')">
                                                    @csrf
                                                    <button type="submit" class="w-full btn-outline text-[10px] px-4 py-2 justify-center border-[#999999] text-[#999999] hover:bg-[#999999] hover:text-white">Coba Lagi</button>
                                                </form>
                                                @if ($payout->failure_reason)
                                                    <p class="text-[10px] text-[#E4002B] leading-snug">Alasan: {{ $payout->failure_reason }}</p>
                                                @endif
                                            @else
                                                <p class="text-[10px] text-[#999999] leading-snug">
                                                    @if ($payout->auto_processed)
                                                        Auto Processed
                                                    @else
                                                        Diproses {{ $payout->processed_at?->format('d M Y H:i') }}
                                                    @endif
                                                </p>
                                                @if ($payout->admin_note)
                                                    <p class="text-[10px] text-[#E4002B] leading-snug">Catatan: {{ $payout->admin_note }}</p>
                                                @endif
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
                        Menampilkan {{ $payouts->firstItem() }}–{{ $payouts->lastItem() }} dari {{ $payouts->total() }} permintaan (filter: {{ $filterLabels[$status] ?? $status }})
                    </p>
                    <div class="flex items-center gap-2">
                        {{ $payouts->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l2.5 2.5M15 15l3-3"/></svg>
                    </div>
                    <p class="text-xs font-heading font-bold uppercase tracking-wide text-[#999999]">Tidak ada permintaan pencairan.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
