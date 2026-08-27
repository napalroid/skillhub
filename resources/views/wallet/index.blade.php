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
        <div class="bg-white border-2 border-black rounded-none p-6 sm:p-10 mb-10 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-black opacity-5 rounded-bl-full -mr-10 -mt-10 pointer-events-none"></div>
            
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="flex-1">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400 mb-3">Saldo Tersedia</p>
                    <div class="relative inline-block">
                        <p class="text-5xl sm:text-6xl lg:text-7xl font-black tracking-tighter text-black">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </p>
                    </div>
                    <p class="mt-3 text-gray-500 font-medium max-w-md">
                        Saldo yang dapat digunakan untuk penarikan. Dana masuk otomatis setelah pesanan selesai.
                    </p>
                </div>

                <button type="button"
                        onclick="openWithdrawModal()"
                        class="w-full lg:w-auto bg-black hover:bg-gray-800 text-white text-sm font-black uppercase tracking-wide px-8 py-4 transition-all active:scale-[0.98] shadow-sm">
                    TARIK SALDO
                </button>
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
        <div class="bg-white border-2 border-black rounded-none overflow-hidden">
            <div class="p-6 sm:p-8 border-b-2 border-black">
                <h2 class="text-xl sm:text-2xl font-black tracking-tight text-black">
                    RIWAYAT TRANSAKSI
                </h2>
            </div>

            @if ($transactions->count() > 0)
                <div class="divide-y-2 divide-black">
                    @foreach ($transactions as $transaction)
                        <div class="p-6 sm:p-8 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                {{-- Left side: description and reference --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="font-black text-gray-900 text-lg sm:text-xl">
                                            {{ $transaction->typeLabel() }}
                                        </p>
                                        <span class="text-xs font-bold uppercase tracking-wider px-2 py-1 rounded-none bg-gray-100 text-gray-700">
                                            {{ $transaction->referenceLabel() }}
                                        </span>
                                    </div>
                                    @if ($transaction->reference_type === 'order' && $transaction->order)
                                        <p class="text-sm text-gray-500 mt-1 font-medium">
                                            #ORD-{{ sprintf('%05d', $transaction->order_id) }}
                                        </p>
                                    @elseif ($transaction->reference_type === 'payout_request')
                                        <p class="text-sm text-gray-500 mt-1 font-medium">
                                            #WD-{{ sprintf('%05d', $transaction->reference_id) }}
                                        </p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-2 font-medium">
                                        {{ $transaction->created_at->translatedFormat('d F Y') }}
                                    </p>
                                </div>

                                {{-- Right side: amount and status --}}
                                <div class="flex flex-col items-end sm:items-start gap-2 min-w-[120px]">
                                    <p class="font-black text-xl sm:text-2xl {{ $transaction->isPositive() ? 'text-black' : 'text-gray-900' }}">
                                        {{ $transaction->isPositive() ? '+' : '-' }}Rp {{ number_format(abs($transaction->amount), 0, ',', '.') }}
                                    </p>
                                    <span class="text-xs font-bold uppercase tracking-wider px-2 py-1 rounded-none
                                        {{ $transaction->status === 'completed' ? 'bg-green-500 text-white' : 
                                           ($transaction->status === 'pending' ? 'bg-amber-500 text-white' : 'bg-red-500 text-white') }}">
                                        {{ $transaction->status === 'completed' ? 'Berhasil' : 
                                           ($transaction->status === 'pending' ? 'Menunggu' : 'Gagal') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="p-6 sm:p-8 border-t-2 border-black bg-gray-50">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="p-12 sm:p-16 text-center">
                    <div class="max-w-md mx-auto">
                        <p class="text-xl font-bold text-gray-500 mb-3">
                            Belum ada transaksi
                        </p>
                        @if ($filter === 'all')
                            <p class="text-gray-400">
                                Aktivitas saldo kamu akan muncul di sini setelah ada pendapatan dari pesanan atau penarikan.
                            </p>
                        @elseif ($filter === 'income')
                            <p class="text-gray-400">
                                Pendapatan dari pesanan yang selesai akan muncul di sini.
                            </p>
                        @elseif ($filter === 'expense')
                            <p class="text-gray-400">
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
            <div class="bg-white border-2 border-black rounded-none w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
                <div class="p-6 sm:p-8 border-b-2 border-black">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-black tracking-tight text-black">Tarik Saldo</h3>
                        <button type="button" onclick="closeWithdrawModal()" class="text-gray-400 hover:text-black text-2xl leading-none">
                            &times;
                        </button>
                    </div>
                </div>

                <form id="withdrawForm" method="POST" action="{{ route('wallet.withdraw.store') }}" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    
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
                                   class="w-full rounded-none border-2 border-black py-3 pl-10 pr-4 text-lg font-black text-black focus:border-black focus:ring-0 focus:outline-none @error('amount') border-red-500 @enderror">
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
                                class="w-full rounded-none border-2 border-black px-4 py-3 text-base font-black text-black focus:border-black focus:ring-0 focus:outline-none @error('method_type') border-red-500 @enderror">
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
                               class="w-full rounded-none border-2 border-black px-4 py-3 text-base font-black text-black focus:border-black focus:ring-0 focus:outline-none @error('account_identifier') border-red-500 @enderror">
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
                               class="w-full rounded-none border-2 border-black px-4 py-3 text-base font-black text-black focus:border-black focus:ring-0 focus:outline-none @error('account_name') border-red-500 @enderror">
                        @error('account_name')
                            <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 p-4 rounded-none border border-gray-200 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Jumlah</span>
                            <span class="text-sm font-black text-gray-900" id="withdrawAmountDisplay">-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Biaya</span>
                            <span class="text-sm font-black text-gray-900">Rp 0</span>
                        </div>
                        <div class="border-t border-gray-300 pt-2 flex justify-between items-center">
                            <span class="text-base font-bold text-gray-900">Diterima</span>
                            <span class="text-base font-black text-black" id="withdrawNetDisplay">-</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" 
                                id="withdrawSubmitBtn"
                                class="w-full bg-black hover:bg-gray-800 text-white text-base font-black uppercase tracking-wide py-4 transition-all active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                            LANJUTKAN
                        </button>
                        <button type="button" 
                                onclick="closeWithdrawModal()"
                                class="w-full bg-transparent hover:bg-gray-50 text-black text-base font-bold uppercase tracking-wide py-4 transition-all">
                            BATAL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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

