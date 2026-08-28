document.addEventListener('DOMContentLoaded', function () {
    const progressBar = document.getElementById('progress-bar');
    const countdownText = document.getElementById('countdown-text');
    const cancelBtn = document.getElementById('cancel-btn');
    const processingStatus = document.getElementById('processing-status');
    if (!progressBar || !countdownText) return;

    const processingDelay = parseInt(progressBar.dataset.delay || '10', 10);
    const payoutRequestId = progressBar.dataset.id;
    let countdown = processingDelay;
    let countdownInterval, progressInterval;

    function startCountdown() {
        progressInterval = setInterval(() => {
            const elapsed = processingDelay - countdown;
            progressBar.style.width = (elapsed / processingDelay) * 100 + '%';
        }, 100);

        countdownInterval = setInterval(() => {
            countdown--;
            countdownText.textContent = `Memproses dalam ${countdown} detik...`;
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                clearInterval(progressInterval);
                processTransfer();
            }
        }, 1000);
    }

    function processTransfer() {
        cancelBtn && cancelBtn.classList.add('hidden');
        countdownText.classList.add('hidden');
        processingStatus && processingStatus.classList.remove('hidden');

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showFailure({ message: 'Terjadi kesalahan sistem', failure_reason: 'CSRF token tidak ditemukan' });
            return;
        }

        fetch(`/wallet/withdraw/${payoutRequestId}/process`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            },
        })
        .then(r => {
            if (!r.ok) {
                throw new Error('Network response was not ok');
            }
            return r.json();
        })
        .then(data => data.success ? showSuccess(data) : showFailure(data))
        .catch((error) => showFailure({ 
            message: 'Terjadi kesalahan sistem', 
            failure_reason: error.message || 'Network error' 
        }));
    }

    function showSuccess(data) {
        const card = document.getElementById('result-card');
        if (!card) return;
        const amount = new Intl.NumberFormat('id-ID').format(parseInt(card.dataset.amount, 10));
        card.innerHTML = `
            <div class="text-center py-8">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-green-50 flex items-center justify-center">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-thin text-gray-900 mb-2">Transfer Berhasil</h2>
                <p class="text-sm text-gray-500 mb-8">Rp${amount} telah dikirim<br>ke ${card.dataset.method} (${card.dataset.account})</p>
                <p class="text-xs text-gray-400 mb-8">Ref: #${card.dataset.ref}</p>
                <a href="${card.dataset.wallet}" class="inline-block bg-gray-900 hover:bg-black text-white text-sm font-bold px-8 py-4 transition-all">KEMBALI KE WALLET</a>
            </div>`;
    }

    function showFailure(data) {
        const card = document.getElementById('result-card');
        if (!card) return;
        card.innerHTML = `
            <div class="text-center py-8">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-thin text-gray-900 mb-2">Transfer Gagal</h2>
                <p class="text-sm text-gray-500 mb-4">Saldo telah dikembalikan ke wallet Anda</p>
                <p class="text-xs text-gray-400 mb-8">Alasan: ${data.failure_reason || data.message}</p>
                <div class="flex gap-3 justify-center">
                    <a href="${card.dataset.retry}" class="inline-block bg-gray-900 hover:bg-black text-white text-sm font-bold px-6 py-3 transition-all">COBA LAGI</a>
                    <a href="${card.dataset.wallet}" class="inline-block border border-gray-300 hover:border-gray-900 text-gray-900 text-sm font-bold px-6 py-3 transition-all">KEMBALI</a>
                </div>
            </div>`;
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            if (confirm('Yakin ingin membatalkan penarikan?')) {
                clearInterval(countdownInterval);
                clearInterval(progressInterval);
                window.location.href = cancelBtn.dataset.cancel;
            }
        });
    }

    startCountdown();
});
