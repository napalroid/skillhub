// Real-time notification listener
document.addEventListener('DOMContentLoaded', () => {
    const menuRoot = document.getElementById('skillhub-staggered-menu');
    const authenticated = menuRoot?.dataset.authenticated === 'true';
    const userId = menuRoot?.dataset.userId;

    if (authenticated && userId && window.Echo) {
        window.Echo.private(`App.Models.User.${userId}`)
            .listen('.notification.created', (e) => {
                console.log('New notification received:', e);
                
                // Update unread count di bell icon
                const countBadges = document.querySelectorAll('.stagger-account-count');
                countBadges.forEach(badge => {
                    const currentCount = parseInt(badge.textContent) || 0;
                    badge.textContent = currentCount + 1;
                    badge.style.display = 'grid';
                });

                // Tambahkan notifikasi ke dropdown jika dropdown terbuka
                const notificationsList = document.querySelector('.stagger-account-notifications');
                if (notificationsList) {
                    const existingEmpty = notificationsList.querySelector('.stagger-account-empty');
                    if (existingEmpty) {
                        existingEmpty.remove();
                    }

                    const notificationHTML = document.createElement('form');
                    notificationHTML.method = 'POST';
                    notificationHTML.action = `/notifikasi/${e.id}/read`;
                    notificationHTML.innerHTML = `
                        <input type="hidden" name="_token" value="${menuRoot?.dataset.csrfToken || ''}" />
                        <button type="submit" class="stagger-account-notification is-unread" role="menuitem">
                            <strong>${e.title}</strong>
                            <span>${e.message}</span>
                            <small>${e.created_at}</small>
                        </button>
                    `;

                    // Insert di posisi pertama (paling atas)
                    notificationsList.insertBefore(notificationHTML, notificationsList.firstChild);

                    // Batasi maksimal 5 notifikasi di dropdown
                    const allNotifs = notificationsList.querySelectorAll('form');
                    if (allNotifs.length > 5) {
                        allNotifs[allNotifs.length - 1].remove();
                    }
                }

                // Tampilkan toast notification (optional)
                showNotificationToast(e.title, e.message);
            });
    }
});

function showNotificationToast(title, message) {
    // Cek apakah sudah ada toast container
    let toastContainer = document.getElementById('notification-toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'notification-toast-container';
        toastContainer.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;';
        document.body.appendChild(toastContainer);
    }

    const toast = document.createElement('div');
    toast.style.cssText = 'background:#080808;color:#fff;padding:16px 20px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);max-width:350px;animation:slideInRight 0.3s ease-out;';
    toast.innerHTML = `
        <div style="font-weight:700;font-size:14px;margin-bottom:4px;">${title}</div>
        <div style="font-size:13px;opacity:0.9;">${message}</div>
    `;

    toastContainer.appendChild(toast);

    // Auto remove setelah 5 detik
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Add CSS animations
if (!document.getElementById('notification-toast-styles')) {
    const style = document.createElement('style');
    style.id = 'notification-toast-styles';
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}
