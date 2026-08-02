<x-layouts.app title="Laporan Penyalahgunaan">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Laporan Menunggu Tindak Lanjut</h1>

    <div class="bg-white rounded-xl border border-gray-100 divide-y">
        @forelse ($reports as $report)
            <div class="p-5">
                <p class="text-sm text-gray-500">
                    {{ $report->reporter->name }} melaporkan {{ $report->reportedUser->name }}
                    @if ($report->order) &middot; Pesanan #{{ $report->order->id }} @endif
                </p>
                <p class="text-gray-700 mt-2">{{ $report->reason }}</p>
                <div class="flex gap-2 mt-3">
                    <form method="POST" action="{{ route('admin.reports.resolve', $report) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="reviewed">
                        <button class="text-sm bg-amber-100 text-amber-700 px-3 py-1.5 rounded-lg">Tandai Ditinjau</button>
                    </form>
                    <form method="POST" action="{{ route('admin.reports.resolve', $report) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="closed">
                        <button class="text-sm bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg">Tutup Laporan</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="p-6 text-gray-500 text-center">Tidak ada laporan terbuka.</p>
        @endforelse
    </div>

</x-layouts.app>