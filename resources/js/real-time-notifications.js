// Real-time notifications system
class RealTimeNotifications {
    constructor() {
        this.eventSource = null;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectDelay = 1000;
        this.init();
    }

    init() {
        this.createNotificationContainer();
        this.connectToStream();
        this.loadStoredNotifications();
    }

    createNotificationContainer() {
        if (!document.getElementById('notification-container')) {
            const container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }
    }

    connectToStream() {
        if (typeof EventSource === 'undefined') {
            console.warn('SSE not supported, using polling fallback');
            this.startPolling();
            return;
        }

        try {
            this.eventSource = new EventSource('/notifications/stream');
            
            this.eventSource.onmessage = (event) => {
                let notification;

                try {
                    notification = JSON.parse(event.data);
                } catch (error) {
                    console.warn('Ignored invalid notification payload:', error);
                    return;
                }

                if (!this.isDisplayableNotification(notification)) {
                    return;
                }

                this.showNotification(notification);
                this.storeNotification(notification);
            };

            this.eventSource.onerror = () => {
                this.handleConnectionError();
            };

            this.eventSource.onopen = () => {
                this.reconnectAttempts = 0;
            };
        } catch (error) {
            console.error('Error connecting to notification stream:', error);
            this.startPolling();
        }
    }

    handleConnectionError() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            setTimeout(() => {
                this.connectToStream();
            }, this.reconnectDelay * this.reconnectAttempts);
        } else {
            console.warn('Max reconnection attempts reached, switching to polling');
            this.startPolling();
        }
    }

    startPolling() {
        setInterval(() => {
            fetch('/notifications/latest')
                .then(response => response.json())
                .then(notifications => {
                    notifications.forEach(notification => {
                        if (!this.isNotificationSeen(notification.id)) {
                            this.showNotification(notification);
                            this.storeNotification(notification);
                        }
                    });
                })
                .catch(error => console.error('Polling error:', error));
        }, 30000); // Poll every 30 seconds
    }

    showNotification(notification) {
        const toast = document.createElement('div');
        toast.className = `
            bg-white rounded-lg shadow-lg p-4 max-w-sm transform transition-all duration-300 
            translate-x-full opacity-0 border-l-4 ${this.getBorderColor(notification.type)}
        `;
        
        toast.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${this.getIcon(notification.type)}
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                    <p class="text-sm text-gray-500 mt-1">${notification.message}</p>
                    <p class="text-xs text-gray-400 mt-2">${this.formatTime(notification.created_at)}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="ml-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;

        const container = document.getElementById('notification-container');
        container.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }

    getBorderColor(type) {
        const colors = {
            'success': 'border-green-500',
            'error': 'border-red-500',
            'warning': 'border-yellow-500',
            'info': 'border-blue-500'
        };
        return colors[type] || 'border-gray-500';
    }

    getIcon(type) {
        const icons = {
            'success': '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
            'error': '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
            'warning': '<svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
            'info': '<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
        };
        return icons[type] || icons['info'];
    }

    formatTime(timestamp) {
        const date = new Date(timestamp);
        if (Number.isNaN(date.getTime())) return '';

        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) return 'Ahora';
        if (diff < 3600000) return `${Math.floor(diff / 60000)}m`;
        if (diff < 86400000) return `${Math.floor(diff / 3600000)}h`;
        return date.toLocaleDateString();
    }

    storeNotification(notification) {
        const stored = JSON.parse(localStorage.getItem('notifications') || '[]');
        const withoutDuplicate = stored.filter(item => item.id !== notification.id);
        withoutDuplicate.unshift(notification);
        localStorage.setItem('notifications', JSON.stringify(withoutDuplicate.slice(0, 50)));
    }

    loadStoredNotifications() {
        const stored = JSON.parse(localStorage.getItem('notifications') || '[]');
        // Only show recent notifications (last 5 minutes)
        const recent = stored.filter(n => {
            const age = Date.now() - new Date(n.created_at).getTime();
            return this.isDisplayableNotification(n) && age >= 0 && age < 300000;
        });

        localStorage.setItem('notifications', JSON.stringify(recent.slice(0, 50)));
        
        recent.slice(0, 3).forEach(notification => {
            this.showNotification(notification);
        });
    }

    isNotificationSeen(id) {
        const stored = JSON.parse(localStorage.getItem('notifications') || '[]');
        return stored.some(n => n.id === id);
    }

    isDisplayableNotification(notification) {
        if (!notification || notification.type === 'connection') return false;

        const hasContent = typeof notification.title === 'string' && notification.title.trim() !== ''
            && typeof notification.message === 'string';
        const hasValidDate = !Number.isNaN(new Date(notification.created_at).getTime());

        return hasContent && hasValidDate;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new RealTimeNotifications();
});
