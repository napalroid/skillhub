document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    console.log('[Notif] DOMContentLoaded fired');
    console.log('[Notif] CSRF Token:', csrfToken);
    
    if (!csrfToken) {
        console.error('[Notif] CSRF token not found!');
    }
    
    if (!window.Echo) {
        console.error('[Notif] ⚠️ Echo not initialized, falling back to polling only');
    } else {
        console.log('[Notif] ✅ Echo object found:', window.Echo);
    }
    
    const menuRoot = document.getElementById('skillhub-staggered-menu');
    const authenticated = menuRoot?.dataset.authenticated === 'true';
    const userId = menuRoot?.dataset.userId;

    if (!authenticated || !userId) {
        console.log('[Notif] User not authenticated');
        return;
    }

    let wsConnected = false;
    let pollingIntervalId = null;
    let lastUnreadCount = null;
    let reconnectAttempts = 0;
    const maxReconnectDelay = 30000;

    const displayedNotifications = new Set();

    function sendAck(notificationId) {
        fetch(`/notifikasi/${notificationId}/ack`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
            },
            credentials: 'include',
        })
        .then(response => response.json())
        .then(data => {
            console.log(`[Notif] ACK sent for notification ${notificationId}`);
        })
        .catch(error => {
            console.error(`[Notif] Failed to send ACK for ${notificationId}:`, error);
        });
    }

    function showNotification(data) {
        if (displayedNotifications.has(data.id)) {
            console.log(`[Notif] Notification ${data.id} already displayed, skipping duplicate`);
            return;
        }

        displayedNotifications.add(data.id);

        window.dispatchEvent(new CustomEvent('notificationUpdate', { detail: data }));

        sendAck(data.id);
    }

    async function syncPendingNotifications() {
        try {
            const response = await fetch('/notifikasi/pending', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                console.error('[Notif] Failed to fetch pending notifications');
                return;
            }

            const data = await response.json();

            if (data.notifications && data.notifications.length > 0) {
                console.log(`[Notif] Syncing ${data.notifications.length} pending notifications`);
                data.notifications.forEach(notif => {
                    showNotification(notif);
                });
            }
        } catch (error) {
            console.error('[Notif] Error syncing pending notifications:', error);
        }
    }

    function startPolling() {
        if (pollingIntervalId) return;

        let adaptiveInterval = 3000;
        let idleTime = 0;

        pollingIntervalId = setInterval(async () => {
            if (wsConnected) {
                return;
            }

            if (document.hidden) {
                idleTime += adaptiveInterval;
                if (idleTime > 30000) {
                    adaptiveInterval = 30000;
                } else if (idleTime > 10000) {
                    adaptiveInterval = 10000;
                }
            } else {
                idleTime = 0;
                adaptiveInterval = 3000;
            }

            try {
                const countRes = await fetch('/notifikasi/unread-count', { 
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (countRes.status === 401) {
                    console.log('[Notif] User not authenticated, stopping polling');
                    stopPolling();
                    return;
                }

                if (!countRes.ok) {
                    console.error('[Notif] Poll error: HTTP', countRes.status);
                    return;
                }

                const countData = await countRes.json();

                if (lastUnreadCount !== null && countData.count !== lastUnreadCount) {
                    console.log('[Notif] 📊 Polling: Unread count changed:', lastUnreadCount, '->', countData.count);
                    await syncPendingNotifications();
                    window.dispatchEvent(new CustomEvent('notificationUpdate'));
                }

                lastUnreadCount = countData.count;
            } catch (err) {
                console.error('[Notif] Poll error:', err);
            }
        }, adaptiveInterval);
    }

    function stopPolling() {
        if (pollingIntervalId) {
            clearInterval(pollingIntervalId);
            pollingIntervalId = null;
        }
    }

    function reconnectWebSocket() {
        if (reconnectAttempts >= 5) {
            console.log('[Notif] Max reconnect attempts reached, switching to polling mode');
            startPolling();
            return;
        }

        const delay = Math.min(1000 * Math.pow(2, reconnectAttempts), maxReconnectDelay);

        console.log(`[Notif] Scheduling reconnect attempt ${reconnectAttempts + 1} in ${delay}ms`);

        setTimeout(() => {
            if (!wsConnected && window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                console.log('[Notif] Attempting to reconnect WebSocket...');
                window.Echo.connector.pusher.connect();
                reconnectAttempts++;
            }
        }, delay);
    }

    if (window.Echo && window.Echo.private) {
        try {
            console.log('[Notif] Connecting to WebSocket channel for user', userId);

            window.Echo.private(`App.Models.User.${userId}`)
                .listen('.notification.created', (e) => {
                    console.log('[Notif] ✅ WebSocket notification received:', e);
                    wsConnected = true;
                    reconnectAttempts = 0;
                    showNotification(e);
                })
                .error((error) => {
                    console.error('[Notif] ❌ WebSocket error:', error);
                    wsConnected = false;
                    reconnectWebSocket();
                });

            if (window.Echo.connector && window.Echo.connector.pusher && window.Echo.connector.pusher.connection) {
                window.Echo.connector.pusher.connection.bind('connected', () => {
                    console.log('[Notif] ✅ WebSocket connected');
                    wsConnected = true;
                    reconnectAttempts = 0;
                    stopPolling();
                    syncPendingNotifications();
                });

                window.Echo.connector.pusher.connection.bind('disconnected', () => {
                    console.log('[Notif] ⚠️ WebSocket disconnected');
                    wsConnected = false;
                    startPolling();
                    reconnectWebSocket();
                });

                window.Echo.connector.pusher.connection.bind('unavailable', () => {
                    console.log('[Notif] ⚠️ WebSocket unavailable');
                    wsConnected = false;
                    startPolling();
                });

                window.Echo.connector.pusher.connection.bind('failed', () => {
                    console.log('[Notif] ❌ WebSocket connection failed');
                    wsConnected = false;
                    startPolling();
                    reconnectWebSocket();
                });
            }
        } catch (err) {
            console.warn('[Notif] ⚠️ Failed to connect WebSocket:', err.message);
            startPolling();
        }
    } else {
        console.warn('[Notif] ⚠️ Echo not initialized, falling back to polling only');
        startPolling();
    }

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden && wsConnected) {
            console.log('[Notif] Tab visible, syncing pending notifications');
            syncPendingNotifications();
        }
    });

    window.addEventListener('online', () => {
        console.log('[Notif] Network online, syncing pending notifications');
        syncPendingNotifications();
    });

    syncPendingNotifications();
});

function updateNotificationUI(data) {
    const menuRoot = document.getElementById('skillhub-staggered-menu');
    const notificationsList = document.querySelector('.stagger-account-notifications');

    if (!notificationsList || !menuRoot) return;

    notificationsList.innerHTML = '';

    const notifications = Array.isArray(data) ? data : [data];

    if (notifications.length === 0) {
        notificationsList.innerHTML = '<p class="stagger-account-empty">Belum ada notifikasi.</p>';
        return;
    }

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
