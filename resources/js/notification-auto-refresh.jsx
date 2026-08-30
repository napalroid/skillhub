import React, { useEffect, useState } from 'react';

export function useNotificationAutoRefresh() {
    const [notifications, setNotifications] = useState([]);
    const [lastCount, setLastCount] = useState(0);
    const [loading, setLoading] = useState(false);

    const fetchNotifications = useCallback(async () => {
        try {
            setLoading(true);
            const res = await fetch('/notifikasi?json=1', { credentials: 'include' });
            const data = await res.json();
            
            if (data.notifications) {
                setNotifications(data.notifications);
                return data.notifications.length;
            }
            return 0;
        } catch (err) {
            console.error('[Notification Auto-Refresh] Fetch error:', err);
            return 0;
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        // Initial fetch
        fetchNotifications();
        
        // Listen for update events from notification-listener.js
        const handleUpdate = async (e) => {
            if (e.detail && e.detail.count > lastCount && lastCount > 0) {
                console.log('[Notification Auto-Refresh] Event received, fetching...');
                fetchNotifications();
                setLastCount(e.detail.count);
            }
        };

        window.addEventListener('notificationUpdate', handleUpdate);
        
        return () => {
            window.removeEventListener('notificationUpdate', handleUpdate);
        };
    }, [fetchNotifications, lastCount]);

    useEffect(() => {
        // Polling fallback (setiap 5 detik)
        const interval = setInterval(async () => {
            try {
                const res = await fetch('/notifikasi/unread-count', { credentials: 'include' });
                const data = await res.json();
                
                if (data.count > lastCount && lastCount > 0) {
                    console.log('[Notification Auto-Refresh] Count increased, fetching...');
                    await fetchNotifications();
                    setLastCount(data.count);
                } else if (lastCount === 0) {
                    // First time, set initial count
                    setLastCount(data.count);
                }
            } catch (err) {
                console.error('[Notification Auto-Refresh] Polling error:', err);
            }
        }, 5000);

        return () => clearInterval(interval);
    }, [lastCount, fetchNotifications]);

    return { notifications, loading, fetchNotifications };
}

export function NotificationProvider({ children }) {
    const { notifications, loading, fetchNotifications } = useNotificationAutoRefresh();
    
    // Store notifications in window for global access
    useEffect(() => {
        window.__NOTIFICATIONS__ = notifications;
    }, [notifications]);

    return children({ notifications, loading, fetchNotifications });
}
