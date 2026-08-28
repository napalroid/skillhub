<x-layouts.app title="Dompet Saya">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- PAGE HEADER --}}
        <div class="mb-10">
            <div class="flex items-baseline gap-3 mb-2">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Keuangan</p>
                <span class="h-[1px] bg-gray-900 w-12 flex-shrink-0"></span>
            </div>
            <h1 class="text-4xl font-thin tracking-tight text-gray-900">DOMPET</h1>
            <p class="mt-2 text-gray-500 text-sm">Kelola saldo dan riwayat transaksi kamu.</p>
        </div>

        {{-- BALANCE SECTION --}}
        <div class="bg-white border border-gray-200 rounded p-8 mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Saldo Tersedia</p>
                    <p class="text-3xl font-thin tracking-tight text-gray-900">Rp {{ number_format($balance, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('wallet.withdraw.create') }}" 
                   class="inline-flex items-center justify-center bg-gray-900 hover:bg-black text-white text-sm font-bold px-8 py-4 rounded transition-all active:scale-[0.98]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m0 0l-4-4m4 4l4-4"/>
                    </svg>
                    TARIK SALDO
                </a>
            </div>
        </div>

        {{-- INFO BARS --}} 
        <div class="grid grid-cols-3 gap-3 mb-10">
            <div class="bg-gray-50 border border-gray-200 rounded p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Minimal</p>
                <p class="text-lg font-bold text-gray-900">Rp {{ number_format(config('payout.min_amount', 50000), 0, ',', '.') }}</p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Proses</p>
                <p class="text-lg font-bold text-gray-900">{{ config('payout.processing_delay_seconds', 10) }}s</p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Biaya</p>
                <p class="text-lg font-bold text-gray-900">Rp 0</p>
            </div>
        </div>

        {{-- TRANSACTION FILTERS --}}
        <div class="mb-8">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('wallet.index', ['filter' => 'all']) }}" 
                   class="px-5 py-2.5 text-xs font-bold border border-gray-300 rounded transition-colors {{ $filter === 'all' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-900 hover:border-gray-900' }}">
                    SEMUA
                </a>
                <a href="{{ route('wallet.index', ['filter' => 'income']) }}" 
                   class="px-5 py-2.5 text-xs font-bold border border-gray-300 rounded transition-colors {{ $filter === 'income' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-900 hover:border-gray-900' }}">
                    MASUK
                </a>
                <a href="{{ route('wallet.index', ['filter' => 'expense']) }}" 
                   class="px-5 py-2.5 text-xs font-bold border border-gray-300 rounded transition-colors {{ $filter === 'expense' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-900 hover:border-gray-900' }}">
                    KELUAR
                </a>
            </div>
        </div>

        {{-- TRANSACTION HISTORY --}}
        <div class="bg-white border border-gray-200 rounded overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-thin tracking-tight text-gray-900">RIWAYAT TRANSAKSI</h2>
            </div>

            @if ($transactions->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach ($transactions as $transaction)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-lg font-medium text-gray-900">{{ $transaction->typeLabel() }}</p>
                                        <span class="text-xs font-medium px-2 py-0.5 border border-gray-200 rounded text-gray-600">
                                            {{ $transaction->referenceLabel() }}
                                        </span>
                                    </div>
                                    @if ($transaction->reference_type === 'order' && $transaction->order)
                                        <p class="text-xs text-gray-500 font-medium">Ref: #ORD-{{ sprintf('%05d', $transaction->order_id) }}</p>
                                    @elseif ($transaction->reference_type === 'payout_request')
                                        <p class="text-xs text-gray-500 font-medium">Ref: #WD-{{ sprintf('%05d', $transaction->reference_id) }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <p class="text-xl font-thin {{ $transaction->isPositive() ? 'text-green-600' : 'text-gray-900' }}">
                                        {{ $transaction->isPositive() ? '+' : '-' }}Rp {{ number_format(abs($transaction->amount), 0, ',', '.') }}
                                    </p>
                                    @php
                                        $statusColor = match($transaction->status) {
                                            'completed' => 'border-green-500 text-green-700 bg-green-50',
                                            'pending' => 'border-amber-500 text-amber-700 bg-amber-50',
                                            'failed' => 'border-red-500 text-red-700 bg-red-50',
                                            default => 'border-gray-400 text-gray-600 bg-gray-50'
                                        };
                                        $statusLabel = match($transaction->status) {
                                            'completed' => 'Berhasil',
                                            'pending' => 'Memproses',
                                            'failed' => 'Gagal',
                                            default => 'Unknown'
                                        };
                                    @endphp
                                    <span class="text-xs font-bold px-3 py-1 border {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="p-6 border-t border-gray-100 bg-gray-50">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="p-16 sm:p-20 text-center">
                    <div class="max-w-md mx-auto">
                        <p class="text-2xl font-bold text-gray-600 mb-3">
                            Belum ada transaksi
                        </p>
                        @if ($filter === 'all')
                            <p class="text-gray-500 text-base">
                                Aktivitas saldo kamu akan muncul di sini setelah ada pendapatan dari pesanan atau penarikan.
                            </p>
                        @elseif ($filter === 'income')
                            <p class="text-gray-500 text-base">
                                Pendapatan dari pesanan yang selesai akan muncul di sini.
                            </p>
                        @elseif ($filter === 'expense')
                            <p class="text-sm text-gray-500">
                                Riwayat penarikan saldo akan muncul di sini.
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- MOBILE PAGINATION (bottom) --}}
        <div class="mt-6 lg:hidden">
            {{ $transactions->links() }}
        </div>
    </div>

    {{-- WITHDRAWAL MODAL --}}
    <div id="withdrawModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeWithdrawModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 sm:p-6">
            <div class="bg-white border-2 border-gray-200 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
                <div class="p-6 sm:p-8 border-b-2 border-gray-200 bg-gradient-to-r from-slate-50 to-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-black tracking-tight text-black">Tarik Saldo</h3>
                            <p class="text-xs text-gray-500 mt-1">Pastikan data sudah benar sebelum melanjutkan</p>
                        </div>
                        <button type="button" onclick="closeWithdrawModal()" class="text-gray-400 hover:text-black text-3xl leading-none transition-colors hover:rotate-90 transform duration-200">
                            &times;
                        </button>
                    </div>
                </div>

                <form id="withdrawForm" method="POST" action="{{ route('wallet.withdraw.store') }}" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    
                    {{-- IMPORTANT INFO ALERT --}}
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs font-bold uppercase tracking-wider text-amber-800 mb-2">Perhatian Penting</p>
                                <ul class="text-xs text-amber-900 space-y-1.5 leading-relaxed">
                                    <li class="flex items-start gap-2">
                                        <span class="text-amber-600 font-bold">•</span>
                                        <span>Pastikan <strong>nomor akun</strong> dan <strong>nama pemilik</strong> sudah benar</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-amber-600 font-bold">•</span>
                                        <span>Dana tidak dapat dikembalikan jika terjadi kesalahan input</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-amber-600 font-bold">•</span>
                                        <span>Proses pencairan membutuhkan waktu {{ config('payout.processing_delay_seconds', 10) }} detik</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-amber-600 font-bold">•</span>
                                        <span>Minimal penarikan Rp {{ number_format(config('payout.min_amount', 50000), 0, ',', '.') }}, tanpa biaya admin</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-[0.1em] text-gray-400 mb-2">Saldo Tersedia</label>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-black text-gray-900">Rp</span>
                            <span id="modalBalance" class="text-3xl font-black text-gray-900">{{ number_format($balance, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div>
                        <label for="withdrawAmount" class="block text-sm font-black text-gray-900 mb-2">Jumlah Penarikan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg font-black">Rp</span>
                            <input type="number" 
                                   name="amount" 
                                   id="withdrawAmount" 
                                   min="{{ config('payout.min_amount', 50000) }}" 
                                   step="1000"
                                   placeholder="0"
                                   class="w-full rounded-lg border-2 border-gray-300 py-3 pl-10 pr-4 text-lg font-black text-black focus:border-black focus:ring-0 focus:outline-none @error('amount') border-red-500 @enderror">
                        </div>
                        <p class="text-xs text-gray-400 mt-2">
                            Minimal Rp{{ number_format(config('payout.min_amount', 50000), 0, ',', '.') }}. Maksimal sesuai saldo tersedia.
                        </p>
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="withdrawMethod" class="block text-sm font-black text-gray-900 mb-2">Metode Pencairan</label>
                        <select name="method_type" id="withdrawMethod"
                                class="w-full rounded-lg border-2 border-gray-300 px-4 py-3 text-base font-black text-black focus:border-black focus:ring-0 focus:outline-none @error('method_type') border-red-500 @enderror">
                            @foreach ($methods as $value => $label)
                                <option value="{{ $value }}" 
                                        @selected(old('method_type', $default['type']) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('method_type')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="withdrawAccount" class="block text-sm font-black text-gray-900 mb-2">Nomor Rekening / Akun</label>
                        <input type="text" 
                               name="account_identifier" 
                               id="withdrawAccount"
                               value="{{ old('account_identifier', $default['account']) }}"
                               placeholder="08xxxxxxxxxx / nomor rekening"
                               class="w-full rounded-lg border-2 border-gray-300 px-4 py-3 text-base font-black text-black focus:border-black focus:ring-0 focus:outline-none @error('account_identifier') border-red-500 @enderror">
                        <p class="text-xs text-red-500 mt-1.5 font-medium">⚠️ Periksa kembali, kesalahan nomor tidak dapat dikembalikan</p>
                        @error('account_identifier')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="withdrawAccountName" class="block text-sm font-black text-gray-900 mb-2">Nama Pemilik</label>
                        <input type="text" 
                               name="account_name" 
                               id="withdrawAccountName"
                               value="{{ old('account_name', $default['name']) }}"
                               placeholder="Sesuai rekening / e-wallet"
                               class="w-full rounded-lg border-2 border-gray-300 px-4 py-3 text-base font-black text-black focus:border-black focus:ring-0 focus:outline-none @error('account_name') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1.5">Pastikan nama sesuai dengan akun tujuan</p>
                        @error('account_name')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-5 rounded-lg border-2 border-gray-200 space-y-3">
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Ringkasan Penarikan</p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Jumlah</span>
                            <span class="text-sm font-black text-gray-900" id="withdrawAmountDisplay">-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Biaya Admin</span>
                            <span class="text-sm font-black text-green-600">Rp 0</span>
                        </div>
                        <div class="border-t-2 border-gray-300 pt-3 flex justify-between items-center">
                            <span class="text-base font-bold text-gray-900">Diterima</span>
                            <span class="text-xl font-black text-black" id="withdrawNetDisplay">-</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" 
                                id="withdrawSubmitBtn"
                                class="w-full bg-black hover:bg-gray-800 text-white text-base font-black uppercase tracking-wide py-4 rounded-lg transition-all active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed shadow-lg">
                            LANJUTKAN PENARIKAN
                        </button>
                        <button type="button" 
                                onclick="closeWithdrawModal()"
                                class="w-full bg-transparent hover:bg-gray-50 text-black text-base font-bold uppercase tracking-wide py-4 rounded-lg transition-all border-2 border-gray-200">
                            BATAL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const balanceText = document.getElementById('balanceText');
        const decryptedBalance = document.getElementById('decryptedBalance');
        const originalBalance = balanceText.textContent;
        const symbols = '!@#$%^&*';
        
        function getRandomSymbols(length) {
            let result = '';
            for (let i = 0; i < length; i++) {
                result += symbols[Math.floor(Math.random() * symbols.length)];
            }
            return result;
        }

        function startDecryptAnimation() {
            let iterations = 0;
            const interval = setInterval(() => {
                iterations++;
                const decrypted = originalBalance.split('').map((char, index) => {
                    if (index < iterations) return originalBalance[index];
                    if (char === ' ' || char === 'R' || char === 'p' || char === '.' || char === ',') return char;
                    return symbols[Math.floor(Math.random() * symbols.length)];
                }).join('');
                
                balanceText.textContent = decrypted;
                
                if (iterations >= originalBalance.length) {
                    clearInterval(interval);
                    balanceText.textContent = originalBalance;
                }
            }, 30);
        }

        window.addEventListener('load', function() {
            setTimeout(startDecryptAnimation, 300);
        });

        let currentWithdrawAmount = 0;

        function openWithdrawModal() {
            document.getElementById('withdrawModal').classList.remove('hidden');
            document.getElementById('withdrawAmount').value = '';
            document.getElementById('withdrawAmountDisplay').textContent = '-';
            document.getElementById('withdrawNetDisplay').textContent = '-';
            currentWithdrawAmount = 0;
            updateWithdrawPreview();
        }

        function closeWithdrawModal() {
            document.getElementById('withdrawModal').classList.add('hidden');
        }

        document.getElementById('withdrawAmount').addEventListener('input', function(e) {
            const amount = parseInt(e.target.value.replace(/\D/g, '')) || 0;
            currentWithdrawAmount = amount;
            updateWithdrawPreview();
        });

        function updateWithdrawPreview() {
            const balance = {{ $balance }};
            
            if (currentWithdrawAmount > 0) {
                document.getElementById('withdrawAmountDisplay').textContent = 'Rp ' + currentWithdrawAmount.toLocaleString('id-ID');
                document.getElementById('withdrawNetDisplay').textContent = 'Rp ' + currentWithdrawAmount.toLocaleString('id-ID');
                
                if (currentWithdrawAmount > balance) {
                    document.getElementById('withdrawAmount').classList.add('border-red-500');
                } else {
                    document.getElementById('withdrawAmount').classList.remove('border-red-500');
                }
            } else {
                document.getElementById('withdrawAmountDisplay').textContent = '-';
                document.getElementById('withdrawNetDisplay').textContent = '-';
            }
        }

        function filterTransactions(filter) {
            const url = new URL(window.location.href);
            url.searchParams.set('filter', filter);
            window.location.href = url.toString();
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeWithdrawModal();
            }
        });

        // Close modal on backdrop click
        document.getElementById('withdrawModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeWithdrawModal();
            }
        });
    </script>

</x-layouts.app>

