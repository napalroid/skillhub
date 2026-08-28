<x-layouts.app title="Dompet Saya">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- PAGE HEADER --}}
        <div class="mb-10">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400 mb-2">Keuangan</p>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-black">
                DOMPET
            </h1>
            <p class="mt-2 text-gray-500 text-base sm:text-lg">Kelola saldo dan riwayat transaksi kamu.</p>
        </div>

        {{-- BALANCE SECTION - MAIN FOCAL POINT --}}
        <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 border-2 border-black rounded-lg p-8 sm:p-10 mb-10 relative overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24 pointer-events-none"></div>
            
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8 relative z-10">
                <div class="flex-1">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                        Saldo Tersedia
                    </p>
                    <div class="relative inline-block group">
                        <p id="balanceText" class="text-5xl sm:text-6xl lg:text-7xl font-black tracking-tighter text-white drop-shadow-lg cursor-pointer transition-all duration-300 hover:scale-105 font-mono">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </p>
                        <p id="decryptedBalance" class="text-5xl sm:text-6xl lg:text-7xl font-black tracking-tighter text-transparent drop-shadow-lg font-mono absolute inset-0 pointer-events-none opacity-0 transition-opacity duration-300">
                            Rp !@#$%^&*#$!@%^&
                        </p>
                    </div>
                    <div class="mt-5 space-y-2 max-w-md">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-gray-300 leading-relaxed">Dana masuk otomatis setelah pesanan selesai</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-gray-300 leading-relaxed">Proses penarikan instant ke e-wallet/bank</p>
                        </div>
                    </div>
                </div>

                <button type="button"
                        onclick="openWithdrawModal()"
                        class="w-full lg:w-auto bg-white hover:bg-gray-100 text-black text-sm font-black uppercase tracking-wide px-10 py-4 transition-all active:scale-[0.98] shadow-xl rounded-lg flex items-center justify-center gap-3 group">
                    <svg class="w-5 h-5 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m0 0l-4-4m4 4l4-4"/>
                    </svg>
                    TARIK SALDO
                </button>
            </div>
        </div>

        {{-- INFO CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
            <div class="bg-white border border-gray-300 p-6 hover:shadow-lg transition-shadow duration-300">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-600 mb-3">Minimal Penarikan</p>
                <p class="text-2xl font-black text-black">Rp 10.000</p>
            </div>

            <div class="bg-white border border-gray-300 p-6 hover:shadow-lg transition-shadow duration-300">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-600 mb-3">Waktu Proses</p>
                <p class="text-2xl font-black text-black">Instant - 1 Jam</p>
            </div>

            <div class="bg-white border border-gray-300 p-6 hover:shadow-lg transition-shadow duration-300">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-600 mb-3">Biaya Admin</p>
                <p class="text-2xl font-black text-black">Rp 0</p>
            </div>
        </div>

        {{-- TRANSACTION FILTERS --}}
        <div class="mb-8">
            <div class="flex flex-wrap gap-4">
                <button type="button"
                        onclick="filterTransactions('all')"
                        class="filter-btn px-6 py-3 text-sm font-bold border-2 border-black transition-all focus:outline-none focus:border-black focus:ring-2 focus:ring-black/20 {{ $filter === 'all' ? 'bg-black text-white' : 'bg-white text-black hover:bg-black hover:text-white' }}">
                    SEMUA
                </button>
                <button type="button"
                        onclick="filterTransactions('income')"
                        class="filter-btn px-6 py-3 text-sm font-bold border-2 border-black transition-all focus:outline-none focus:border-black focus:ring-2 focus:ring-black/20 {{ $filter === 'income' ? 'bg-black text-white' : 'bg-white text-black hover:bg-black hover:text-white' }}">
                    UANG MASUK
                </button>
                <button type="button"
                        onclick="filterTransactions('expense')"
                        class="filter-btn px-6 py-3 text-sm font-bold border-2 border-black transition-all focus:outline-none focus:border-black focus:ring-2 focus:ring-black/20 {{ $filter === 'expense' ? 'bg-black text-white' : 'bg-white text-black hover:bg-black hover:text-white' }}">
                    UANG KELUAR
                </button>
            </div>
        </div>

        {{-- TRANSACTION HISTORY --}}
        <div class="bg-white border border-gray-300 overflow-hidden">
            <div class="p-8 sm:p-10 border-b border-gray-300">
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-black">
                    RIWAYAT TRANSAKSI
                </h2>
            </div>

            @if ($transactions->count() > 0)
                <div class="divide-y divide-gray-300">
                    @foreach ($transactions as $transaction)
                        <div class="p-8 sm:p-10 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                                {{-- Left side: description and reference --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <p class="font-black text-gray-900 text-lg sm:text-2xl">
                                            {{ $transaction->typeLabel() }}
                                        </p>
                                        <span class="text-xs font-bold uppercase tracking-wider px-3 py-1.5 border border-gray-300 text-gray-700 bg-white">
                                            {{ $transaction->referenceLabel() }}
                                        </span>
                                    </div>
                                    @if ($transaction->reference_type === 'order' && $transaction->order)
                                        <p class="text-sm text-gray-600 mt-2 font-medium">
                                            #ORD-{{ sprintf('%05d', $transaction->order_id) }}
                                        </p>
                                    @elseif ($transaction->reference_type === 'payout_request')
                                        <p class="text-sm text-gray-600 mt-2 font-medium">
                                            #WD-{{ sprintf('%05d', $transaction->reference_id) }}
                                        </p>
                                    @endif
                                    <p class="text-sm text-gray-500 mt-2 font-medium">
                                        {{ $transaction->created_at->translatedFormat('d F Y') }}
                                    </p>
                                </div>

                                {{-- Right side: amount and status --}}
                                <div class="flex flex-col items-end sm:items-start gap-3 min-w-[150px]">
                                    <p class="font-black text-2xl sm:text-3xl {{ $transaction->isPositive() ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->isPositive() ? '+' : '-' }}Rp {{ number_format(abs($transaction->amount), 0, ',', '.') }}
                                    </p>
                                    <span class="text-xs font-bold uppercase tracking-wider px-3 py-1.5 border {{ $transaction->status === 'completed' ? 'border-green-500 text-green-700 bg-green-50' : 
                                       ($transaction->status === 'pending' ? 'border-amber-500 text-amber-700 bg-amber-50' : 'border-red-500 text-red-700 bg-red-50') }}">
                                        {{ $transaction->status === 'completed' ? 'Berhasil' : 
                                           ($transaction->status === 'pending' ? 'Menunggu' : 'Gagal') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="p-8 sm:p-10 border-t border-gray-300 bg-white">
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
                            <p class="text-gray-500 text-base">
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
                                        <span>Proses pencairan membutuhkan waktu instant hingga 1 jam kerja</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-amber-600 font-bold">•</span>
                                        <span>Minimal penarikan Rp 10.000, tanpa biaya admin</span>
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
                                   min="10000" 
                                   step="1000"
                                   placeholder="0"
                                   class="w-full rounded-lg border-2 border-gray-300 py-3 pl-10 pr-4 text-lg font-black text-black focus:border-black focus:ring-0 focus:outline-none @error('amount') border-red-500 @enderror">
                        </div>
                        <p class="text-xs text-gray-400 mt-2">
                            Minimal Rp10.000. Maksimal sesuai saldo tersedia.
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

