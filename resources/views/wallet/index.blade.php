<x-layouts.app title="Dompet Saya">

    <div class="mb-6">
        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gray-400">Keuangan</p>
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mt-1">Dompet Saya</h1>
    </div>

    {{-- Saldo --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gray-400">Saldo Tersedia</p>
            <p class="mt-2 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">
                Rp{{ number_format($balance, 0, ',', '.') }}
            </p>
            <p class="mt-2 text-sm text-gray-500">Saldo cair otomatis setelah pesanan selesai. Tarik ke e-wallet atau rekeningmu.</p>
        </div>
        <a href="{{ route('wallet.withdraw.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white text-sm font-bold px-6 py-3 rounded-lg transition active:scale-[0.98]">
            Tarik Saldo
        </a>
    </div>

    {{-- Riwayat --}}
    <div class="mt-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Riwayat Pencairan</h2>

        @php
            $statusStyles = [
                'pending' => 'bg-amber-100 text-amber-700',
                'completed' => 'bg-emerald-100 text-emerald-700',
                'rejected' => 'bg-red-100 text-red-700',
            ];
            $statusLabels = [
                'pending' => 'Menunggu',
                'completed' => 'Selesai',
                'rejected' => 'Ditolak',
            ];
        @endphp

        <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-100">
            @forelse ($requests as $req)
                <div class="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-semibold text-gray-900">{{ $req->methodLabel() }}</p>
                            <span class="text-sm font-bold text-gray-900">Rp{{ number_format($req->amount, 0, ',', '.') }}</span>
                            <span class="text-[10px] uppercase tracking-wider font-bold px-2 py-0.5 rounded-full {{ $statusStyles[$req->status] }}">{{ $statusLabels[$req->status] }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $req->account_identifier }} &middot; {{ $req->account_name }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $req->created_at->format('d M Y, H:i') }}</p>
                        @if ($req->admin_note)
                            <p class="text-xs text-red-600 mt-1">Catatan admin: {{ $req->admin_note }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-10 text-center">
                    <p class="text-gray-500 mb-1">Belum ada riwayat pencairan.</p>
                    <p class="text-sm text-gray-400">Setelah pesanan selesai dan dananya cair, kamu bisa menarik saldo di sini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    </div>

</x-layouts.app>
