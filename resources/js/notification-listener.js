// Real-time notification listener dengan React support
document.addEventListener('DOMContentLoaded', () => {
    const menuRoot = document.getElementById('skillhub-staggered-menu');
    const authenticated = menuRoot?.dataset.authenticated === 'true';
    const userId = menuRoot?.dataset.userId;

    if (authenticated && userId && window.Echo) {
        window.Echo.private(`App.Models.User.${userId}`)
            .listen('.notification.created', (e) => {
                console.log('[Notif] WebSocket event received:', e);
                window.dispatchEvent(new CustomEvent('notificationUpdate', { detail: e }));
            });
    }

    // AUTO-REFRESH FALLBACK - Check unread count every 5 seconds
    let lastUnreadCount = null;
    
    setInterval(async () => {
        try {
            const countRes = await fetch('/notifikasi/unread-count', { credentials: 'include' });
            const countData = await countRes.json();
            
            if (lastUnreadCount !== null && countData.count !== lastUnreadCount) {
                console.log('[Notif] Unread count changed:', lastUnreadCount, '->', countData.count);
                window.dispatchEvent(new CustomEvent('notificationUpdate'));
            }
            
            lastUnreadCount = countData.count;
        } catch (err) {
            console.error('[Notif] Poll error:', err);
        }
    }, 5000);
});

function updateNotificationUI(data) {
    const menuRoot = document.getElementById('skillhub-staggered-menu');
    const notificationsList = document.querySelector('.stagger-account-notifications');
    
    if (!notificationsList || !menuRoot) return;
    
    // Clear existing notifications
    notificationsList.innerHTML = '';
    
    // Handle array of notifications or single event
    const notifications = Array.isArray(data) ? data : [data];
    
    if (notifications.length === 0) {
        notificationsList.innerHTML = '<p class="stagger-account-empty">Belum ada notifikasi.</p>';
        return;
    }
    
    // Show max 5 notifications
    notifications.slice(0, 5).forEach(notif => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/notifikasi/${notif.id}/read`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="${menuRoot.dataset.csrfToken || ''}" />
            <button type="submit" class="stagger-account-notification ${notif.is_read ? '' : 'is-unread'}" role="menuitem">
                <strong>${notif.title}</strong>
                <span>${notif.message}</span>
                <small>${notif.time}</small>
            </button>
        `;
        notificationsList.appendChild(form);
    });
}
