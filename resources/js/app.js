import 'bootstrap/dist/js/bootstrap.bundle.min.js';

window.addEventListener('DOMContentLoaded', () => {
    const loader = document.getElementById('appLoader');
    const toast = document.getElementById('appToast');
    const toastText = document.getElementById('appToastText');
    const offlineBanner = document.getElementById('offlineBanner');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const showLoader = () => loader?.classList.add('show');
    const hideLoader = () => loader?.classList.remove('show');

    const showToast = (message) => {
        if (!toast || !toastText || !message) return;
        toastText.textContent = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2400);
    };

    const getDeviceToken = () => {
        const key = 'todoflow_device_token';
        let token = window.localStorage.getItem(key);
        if (!token) {
            token = `tf_${Math.random().toString(36).slice(2)}${Date.now().toString(36)}`;
            window.localStorage.setItem(key, token);
        }
        return token;
    };

    const registerNotificationDevice = async () => {
        if (!csrfToken) return null;

        const payload = {
            device_token: getDeviceToken(),
            permission: 'Notification' in window ? Notification.permission : 'default',
            user_agent: navigator.userAgent,
        };

        try {
            const response = await fetch('/notifications/subscribe', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(payload),
            });

            if (!response.ok) {
                throw new Error('subscribe_failed');
            }

            return await response.json();
        } catch (error) {
            return null;
        }
    };

    const showBrowserNotification = async (item) => {
        if (!('Notification' in window) || Notification.permission !== 'granted') return;

        const openTarget = () => {
            if (item?.url) {
                window.location.href = item.url;
            }
        };

        if ('serviceWorker' in navigator) {
            const registration = await navigator.serviceWorker.getRegistration();
            if (registration) {
                await registration.showNotification(item.title || 'TodoFlow', {
                    body: item.body || '',
                    icon: '/icons/icon-192.png',
                    badge: '/icons/icon-192.png',
                    data: { url: item.url || '/notifications' },
                    tag: `todo-notification-${item.id}`,
                });
                return;
            }
        }

        const notification = new Notification(item.title || 'TodoFlow', {
            body: item.body || '',
            icon: '/icons/icon-192.png',
            tag: `todo-notification-${item.id}`,
        });
        notification.onclick = openTarget;
    };

    const pollNotifications = async () => {
        if (!('Notification' in window) || Notification.permission !== 'granted' || !navigator.onLine) return;

        try {
            const response = await fetch(`/notifications/feed?device_token=${encodeURIComponent(getDeviceToken())}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            if (!response.ok) return;

            const data = await response.json();
            if (!Array.isArray(data.items)) return;

            for (const item of data.items) {
                await showBrowserNotification(item);
                showToast(item.title || 'New notification');
            }
        } catch (error) {
            // silent polling failure
        }
    };

    window.TodoFlow = { showToast, showLoader, hideLoader };

    document.querySelectorAll('form[data-loader="true"]').forEach((form) => {
        form.addEventListener('submit', () => showLoader());
    });

    const dueDateInput = document.getElementById('dueDateInput');
    const dueHourInput = document.getElementById('dueHourInput');
    const dueMinuteInput = document.getElementById('dueMinuteInput');
    const duePeriodInput = document.getElementById('duePeriodInput');
    const dueAtHidden = document.getElementById('dueAtHidden');

    const syncDueAt = () => {
        if (!dueAtHidden) return;
        const date = dueDateInput?.value;
        const hour = dueHourInput?.value;
        const minute = dueMinuteInput?.value;
        const period = duePeriodInput?.value;

        if (!date || !hour || !minute || !period) {
            dueAtHidden.value = '';
            return;
        }

        let h = parseInt(hour, 10);
        if (period === 'PM' && h < 12) h += 12;
        if (period === 'AM' && h === 12) h = 0;
        const hour24 = String(h).padStart(2, '0');
        dueAtHidden.value = `${date}T${hour24}:${minute}`;
    };

    [dueDateInput, dueHourInput, dueMinuteInput, duePeriodInput].forEach((el) => {
        el?.addEventListener('change', syncDueAt);
    });
    syncDueAt();

    document.querySelectorAll('[data-task-toggle]').forEach((button) => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();
            const url = button.dataset.url;
            if (!url || !csrfToken) return;
            showLoader();
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });
                const data = await response.json();
                showToast(data.message || 'Task updated successfully.');
                window.location.reload();
            } catch (error) {
                hideLoader();
                showToast('Something went wrong. Please try again.');
            }
        });
    });

    document.getElementById('enablePushBtn')?.addEventListener('click', async () => {
        if (!('Notification' in window)) {
            showToast('This browser does not support notifications.');
            return;
        }

        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            await registerNotificationDevice();
            showToast('Notification permission was not granted.');
            return;
        }

        const data = await registerNotificationDevice();
        showToast(data?.message || 'Browser notifications enabled for this device.');
        pollNotifications();
    });

    const updateOfflineState = () => offlineBanner?.classList.toggle('show', !navigator.onLine);
    window.addEventListener('online', updateOfflineState);
    window.addEventListener('offline', updateOfflineState);
    updateOfflineState();

    const flashMessage = document.body.dataset.flash;
    if (flashMessage) showToast(flashMessage);

    if ('Notification' in window && Notification.permission === 'granted') {
        registerNotificationDevice().then(() => pollNotifications());
        window.setInterval(pollNotifications, 30000);
    }

    setTimeout(hideLoader, 300);
});
