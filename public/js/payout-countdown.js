document.addEventListener('DOMContentLoaded', function () {
    const progressBar = document.getElementById('progress-bar');
    const countdownText = document.getElementById('countdown-text');
    const cancelBtn = document.getElementById('cancel-btn');
    const processingStatus = document.getElementById('processing-status');
    const actions = document.getElementById('wh-confirm-actions');
    const dialog = document.getElementById('wh-confirm-dialog');
    if (!progressBar || !countdownText) return;

    if (dialog && typeof dialog.showModal === 'function' && !dialog.open) {
        dialog.showModal();
    }

    const processingDelay = parseInt(progressBar.dataset.delay || '10', 10);
    const payoutRequestId = progressBar.dataset.id;
    let countdown = processingDelay;
    let countdownInterval;
    let progressInterval;

    function startCountdown() {
        countdown = processingDelay;
        progressBar.style.width = '0%';

        progressInterval = setInterval(function () {
            const elapsed = processingDelay - countdown;
            progressBar.style.width = (elapsed / processingDelay) * 100 + '%';
        }, 100);

        countdownInterval = setInterval(function () {
            countdown--;
            countdownText.textContent = 'Memproses dalam ' + countdown + ' detik';
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                clearInterval(progressInterval);
                progressBar.style.width = '100%';
                processTransfer();
            }
        }, 1000);
    }

    function processTransfer() {
        if (cancelBtn) cancelBtn.hidden = true;
        if (actions) actions.hidden = true;
        countdownText.hidden = true;
        if (processingStatus) processingStatus.hidden = false;

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            finish(false, 'CSRF token tidak ditemukan');
            return;
        }

        fetch('/wallet/withdraw/' + payoutRequestId + '/process', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            },
        })
            .then(function (r) {
                if (!r.ok) throw new Error('Network response was not ok');
                return r.json();
            })
            .then(function (data) {
                if (data.success) finish(true, data);
                else finish(false, data.failure_reason || data.message);
            })
            .catch(function (error) {
                finish(false, error.message || 'Network error');
            });
    }

    function finish(ok, payload) {
        const card = document.getElementById('result-card');
        const amount = card
            ? new Intl.NumberFormat('id-ID').format(parseInt(card.dataset.amount, 10))
            : '';
        if (dialog && dialog.open) dialog.close();

        if (typeof window.whNotifyPersist === 'function') {
            if (ok) {
                window.whNotifyPersist({
                    type: 'success',
                    title: 'Transfer berhasil',
                    message: 'Rp' + amount + ' dikirim ke ' + (card.dataset.method || '') + ' (' + (card.dataset.account || '') + '). Ref #' + (card.dataset.ref || ''),
                });
            } else {
                const reason = typeof payload === 'string' ? payload : '';
                window.whNotifyPersist({
                    type: 'error',
                    title: 'Transfer gagal',
                    message: reason || 'Saldo telah dikembalikan ke dompet.',
                });
            }
        } else if (typeof window.whNotify === 'function') {
            if (ok) {
                window.whNotify({
                    type: 'success',
                    title: 'Transfer berhasil',
                    message: 'Rp' + amount + ' dikirim ke ' + (card.dataset.method || '') + ' (' + (card.dataset.account || '') + '). Ref #' + (card.dataset.ref || ''),
                });
            } else {
                const reason = typeof payload === 'string' ? payload : '';
                window.whNotify({
                    type: 'error',
                    title: 'Transfer gagal',
                    message: reason || 'Saldo telah dikembalikan ke dompet.',
                });
            }
        }

        window.location.href = ok
            ? (card && card.dataset.wallet) || '/dompet'
            : (card && card.dataset.retry) || '/dompet/tarik';
    }

    startCountdown();

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function () {
            clearInterval(countdownInterval);
            clearInterval(progressInterval);

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/dompet/tarik/' + payoutRequestId + '/batal';

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            }

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        });
    }

    if (dialog) {
        dialog.addEventListener('cancel', function (event) {
            event.preventDefault();
        });
    }
});
