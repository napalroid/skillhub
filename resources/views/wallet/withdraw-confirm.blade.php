<x-layouts.app title="Konfirmasi Penarikan">

    <div class="max-w-xl mx-auto">
        <div class="fixed inset-0 bg-black/20 backdrop-blur-sm z-40"></div>

        <div class="relative z-50 mt-20">
            <div id="result-card" class="bg-white border border-gray-200 rounded p-8"
                 data-wallet="{{ route('wallet.index') }}"
                 data-retry="{{ route('wallet.withdraw.create') }}"
                 data-amount="{{ $payoutRequest->amount }}"
                 data-method="{{ $payoutRequest->methodLabel() }}"
                 data-account="{{ $payoutRequest->account_identifier }}"
                 data-ref="WD-{{ $payoutRequest->id }}">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h2 class="text-2xl font-thin text-gray-900 mb-2">Konfirmasi Penarikan</h2>
                    <p class="text-sm text-gray-500">Proses akan dimulai dalam beberapa detik</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded p-6 mb-8 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Jumlah</p>
                        <p class="text-3xl font-thin text-gray-900">Rp{{ number_format($payoutRequest->amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Tujuan</p>
                        <p class="text-sm font-medium text-gray-900">{{ $payoutRequest->methodLabel() }} • {{ $payoutRequest->account_identifier }}</p>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Atas Nama</p>
                        <p class="text-sm font-medium text-gray-900">{{ $payoutRequest->account_name }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="h-1 bg-gray-200 rounded-full overflow-hidden">
                        <div id="progress-bar" class="h-full bg-gray-900 transition-all duration-1000 ease-linear" style="width: 0%"
                             data-delay="{{ $processingDelay }}" data-id="{{ $payoutRequest->id }}"></div>
                    </div>
                    <p id="countdown-text" class="text-center text-sm text-gray-500 mt-3">Memproses dalam {{ $processingDelay }} detik...</p>
                </div>

                <div id="processing-status" class="hidden">
                    <div class="flex items-center justify-center mb-4">
                        <svg class="animate-spin h-8 w-8 text-gray-900" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <p class="text-center text-sm text-gray-500">Sedang memproses transfer...</p>
                </div>

                <button type="button" id="cancel-btn" data-cancel="{{ route('wallet.index') }}"
                        class="w-full border border-gray-300 hover:border-gray-900 text-gray-900 text-sm font-bold py-4 rounded transition-all">
                    BATALKAN
                </button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/payout-countdown.js') }}"></script>

</x-layouts.app>
