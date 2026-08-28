<x-layouts.app title="Verifikasi Rekening">

    <div class="max-w-xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('wallet.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-900 transition-colors mb-2">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dompet
            </a>
            <div class="flex items-baseline">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gray-400 mb-1">Verifikasi Diperlukan</p>
                <span class="h-[1px] bg-gray-900 w-12 ml-3"></span>
            </div>
            <h1 class="text-4xl font-thin tracking-tight text-gray-900">Nomor Rekening Baru</h1>
        </div>

        <div class="bg-white border border-gray-200 rounded p-8">
            <div class="flex items-center justify-center mb-6">
                <div class="w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>

            <div class="text-center mb-8">
                <h2 class="text-xl font-medium text-gray-900 mb-2">Nomor Rekening Berbeda</h2>
                <p class="text-sm text-gray-500">Kami mendeteksi nomor rekening yang berbeda dari penarikan sebelumnya. Pastikan informasi sudah benar.</p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded p-6 mb-8 space-y-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Jumlah</p>
                    <p class="text-2xl font-thin text-gray-900">Rp{{ number_format($payoutRequest->amount, 0, ',', '.') }}</p>
                </div>
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Metode</p>
                    <p class="text-sm font-medium text-gray-900">{{ $payoutRequest->methodLabel() }}</p>
                </div>
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Nomor Rekening</p>
                    <p class="text-lg font-medium text-gray-900">{{ $payoutRequest->account_identifier }}</p>
                </div>
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Atas Nama</p>
                    <p class="text-sm font-medium text-gray-900">{{ $payoutRequest->account_name }}</p>
                </div>
            </div>

            <div class="space-y-3">
                <a href="{{ route('wallet.withdraw.confirm', $payoutRequest) }}" 
                   class="block w-full bg-gray-900 hover:bg-black text-white text-sm font-bold py-4 rounded transition-all active:scale-[0.98] text-center">
                    LANJUTKAN PENARIKAN
                </a>
                <form method="POST" action="{{ route('wallet.withdraw.cancel', $payoutRequest) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="block w-full border border-gray-300 hover:border-gray-900 text-gray-900 text-sm font-bold py-4 rounded transition-all text-center"
                            onclick="return confirm('Yakin ingin membatalkan penarikan? Saldo akan dikembalikan.')">
                        BATALKAN PENARIKAN
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-gray-400 mt-6">
                Pastikan nomor rekening dan nama pemilik sudah benar sebelum melanjutkan.
            </p>
        </div>
    </div>

</x-layouts.app>
