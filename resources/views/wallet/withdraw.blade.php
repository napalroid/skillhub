<x-layouts.app title="Penarikan Saldo">

    <div class="max-w-xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('wallet.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-900 transition-colors mb-2">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dompet
            </a>
            <div class="flex items-baseline">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gray-400 mb-1">Pencairan Dana</p>
                <span class="h-[1px] bg-gray-900 w-12 ml-3"></span>
            </div>
            <h1 class="text-4xl font-thin tracking-tight text-gray-900">Tarik Saldo</h1>
        </div>

        <div class="bg-white border border-gray-200 rounded p-8">
            <div class="flex items-center justify-between border-b border-gray-100 pb-5 mb-8">
                <p class="text-sm text-gray-500 font-medium">Saldo Tersedia</p>
                <p class="text-2xl font-thin tracking-tight text-gray-900">Rp{{ number_format($balance, 0, ',', '.') }}</p>
            </div>

            <form method="POST" action="{{ route('wallet.withdraw.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="amount" class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Jumlah Penarikan</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">Rp</span>
                        <input type="number" name="amount" id="amount" min="{{ $minAmount }}" step="1000"
                               value="{{ old('amount') }}" placeholder="0"
                               class="w-full rounded border border-gray-300 py-3 pl-10 pr-3 text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 focus:outline-none @error('amount') border-red-500 @enderror">
                    </div>
                    @error('amount')
                        <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-2">
                        Minimal Rp{{ number_format($minAmount, 0, ',', '.') }}. Maksimal sesuai saldo.
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-3 uppercase tracking-wide">Metode Pencairan</label>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach ($methods as $value => $label)
                            <x-payment-method-card
                                :value="$value"
                                :label="$label"
                                :icon="config("payout.methods.{$value}.icon", '💰')"
                                :checked="old('method_type', $default['type']) === $value" />
                        @endforeach
                    </div>
                    @error('method_type')
                        <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="account_identifier" class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Nomor Rekening / E-Wallet</label>
                    <input type="text" name="account_identifier" id="account_identifier"
                           value="{{ old('account_identifier', $default['account']) }}"
                           placeholder="08xxxxxxxxxx / nomor rekening"
                           class="w-full rounded border border-gray-300 py-3 px-4 text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 focus:outline-none @error('account_identifier') border-red-500 @enderror">
                    @error('account_identifier')
                        <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="account_name" class="block text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Nama Pemilik Rekening</label>
                    <input type="text" name="account_name" id="account_name"
                           value="{{ old('account_name', $default['name']) }}"
                           placeholder="Sesuai rekening / e-wallet"
                           class="w-full rounded border border-gray-300 py-3 px-4 text-gray-900 focus:border-gray-900 focus:ring-1 focus:ring-gray-900 focus:outline-none @error('account_name') border-red-500 @enderror">
                    @error('account_name')
                        <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white text-sm font-bold py-4 rounded transition-all active:scale-[0.98]">
                        LANJUTKAN
                    </button>
                </div>
                <p class="text-center text-xs text-gray-400 mt-6">
                    Penarikan akan diproses dalam {{ config('payout.processing_delay_seconds', 10) }} detik setelah konfirmasi. Tingkat keberhasilan: {{ config('payout.simulation_success_rate', 60) }}%.
                </p>
            </form>
        </div>
    </div>

</x-layouts.app>
