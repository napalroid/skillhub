<x-layouts.app title="Tarik Saldo">

    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('wallet.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Dompet</a>
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gray-400 mt-3">Pencairan</p>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mt-1">Tarik Saldo</h1>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                <p class="text-sm text-gray-500">Saldo tersedia</p>
                <p class="text-xl font-extrabold tracking-tight text-gray-900">Rp{{ number_format($balance, 0, ',', '.') }}</p>
            </div>

            <form method="POST" action="{{ route('wallet.withdraw.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-1.5">Jumlah Penarikan</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="number" name="amount" id="amount" min="10000" step="1000"
                               value="{{ old('amount') }}" placeholder="10000"
                               class="w-full rounded-lg border border-gray-300 py-2.5 pl-9 pr-3 text-sm text-gray-900 focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 focus:outline-none @error('amount') border-red-400 @enderror">
                    </div>
                    @error('amount')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-400 mt-1.5">Minimal Rp10.000. Maksimal sesuai saldo tersedia.</p>
                </div>

                <div>
                    <label for="method_type" class="block text-sm font-semibold text-gray-700 mb-1.5">Tujuan Pencairan</label>
                    <select name="method_type" id="method_type"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 focus:outline-none @error('method_type') border-red-400 @enderror">
                        @foreach ($methods as $value => $label)
                            <option value="{{ $value }}" @selected(old('method_type', $default['type']) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('method_type')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="account_identifier" class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor / Rekening Tujuan</label>
                    <input type="text" name="account_identifier" id="account_identifier"
                           value="{{ old('account_identifier', $default['account']) }}"
                           placeholder="08xxxxxxxxxx / nomor rekening"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 focus:outline-none @error('account_identifier') border-red-400 @enderror">
                    @error('account_identifier')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="account_name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Pemilik</label>
                    <input type="text" name="account_name" id="account_name"
                           value="{{ old('account_name', $default['name']) }}"
                           placeholder="Sesuai rekening / e-wallet"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10 focus:outline-none @error('account_name') border-red-400 @enderror">
                    @error('account_name')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center bg-gray-900 hover:bg-black text-white text-sm font-bold px-6 py-3 rounded-lg transition active:scale-[0.98]">
                    Tarik Sekarang
                </button>
                <p class="text-xs text-gray-400 mt-3 text-center">Pencairan diproses otomatis & langsung dikirim ke tujuan yang kamu pilih. Tidak perlu menunggu persetujuan admin.</p>
            </form>
        </div>
    </div>

</x-layouts.app>
