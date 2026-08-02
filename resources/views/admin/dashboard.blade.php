<x-layouts.app title="Dashboard Admin">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Total User</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_user'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-amber-200 p-5">
            <p class="text-sm text-amber-700">Jasa Menunggu Approval</p>
            <p class="text-2xl font-bold text-amber-700">{{ $stats['jasa_pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-amber-200 bg-amber-50 p-5">
            <p class="text-sm text-amber-700">Pembayaran Menunggu Verifikasi</p>
            <p class="text-2xl font-bold text-amber-700">{{ $stats['pembayaran_pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Pesanan Berjalan</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['pesanan_berjalan'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-red-200 bg-red-50 p-5">
            <p class="text-sm text-red-700">Laporan Terbuka</p>
            <p class="text-2xl font-bold text-red-700">{{ $stats['laporan_terbuka'] }}</p>
        </div>
    </div>

    <a href="{{ route('admin.services.pending') }}" class="text-blue-700 font-semibold hover:underline">
        → Tinjau jasa yang menunggu approval
    </a>

</x-layouts.app>