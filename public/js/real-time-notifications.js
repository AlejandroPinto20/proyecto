/**
 * Real-time Notifications System
 * Optimized for UNIFRANZ Volunteer Platform
 */

class RealTimeNotifications {
    constructor() {
        this.notifications = [];
        this.unreadCount = 0;
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.init();
    }

    init() {
        this.setupEventSource();
        this.setupUI();
        this.loadInitialNotifications();
    }

    setupEventSource() {
        // Use Server-Sent Events for real-time updates (fallback for WebSockets)
        if (typeof(EventSource) !== "undefined") {
            this.eventSource = new EventSource('/notifications/stream');
            
            this.eventSource.onopen = () => {
                this.isConnected = true;
                this.reconnectAttempts = 0;
                console.log('✅ Notifications stream connected');
            };

            this.eventSource.onmessage = (event) => {
                const notification = JSON.parse(event.data);
                this.handleNewNotification(notification);
            };

            this.eventSource.onerror = () => {
                this.isConnected = false;
                this.handleConnectionError();
            };
        } else {
            // Fallback to polling for older browsers
            this.setupPolling();
        }
    }

    setupPolling() {
        setInterval(() => {
            this.checkForNewNotifications();
        }, 30000); // Check every 30 seconds
    }

    async checkForNewNotifications() {
        try {
            const response = await fetch('/api/notifications/latest', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                data.notifications.forEach(notification => {
                    if (!this.notifications.find(n => n.id === notification.id)) {
                        this.handleNewNotification(notification);
                    }
                });
            }
        } catch (error) {
            console.error('Error checking notifications:', error);
        }
    }

    handleNewNotification(notification) {
        this.notifications.unshift(notification);
        this.unreadCount++;
        
        this.updateUI();
        this.showToast(notification);
        this.playNotificationSound();
        
        // Store in localStorage for persistence
        this.saveToStorage();
    }

    showToast(notification) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-white border-l-4 border-orange-500 rounded-lg shadow-lg p-4 max-w-sm z-50 transform translate-x-full transition-transform duration-300';
        
        toast.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                    <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    playNotificationSound() {
        // Create audio element for notification sound
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
        audio.volume = 0.3;
        audio.play().catch(() => {}); // Ignore errors if audio fails
    }

    updateUI() {
        // Update notification badge
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            badge.textContent = this.unreadCount;
            badge.classList.toggle('hidden', this.unreadCount === 0);
        }

        // Update dropdown content
        this.updateDropdownContent();
    }

    updateDropdownContent() {
        const dropdown = document.querySelector('#notifications-dropdown');
        if (!dropdown) return;

        const recentNotifications = this.notifications.slice(0, 5);
        
        dropdown.innerHTML = `
            <div class="px-4 py-2 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900">Notificaciones</h3>
            </div>
            ${recentNotifications.length > 0 ? 
                recentNotifications.map(notification => `
                    <div class="px-4 py-3 hover:bg-gray-50 ${!notification.read_at ? 'bg-blue-50' : ''}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-2 w-2 bg-orange-500 rounded-full ${notification.read_at ? 'opacity-0' : ''}"></div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                                <p class="text-sm text-gray-600">${notification.message}</p>
                                <p class="text-xs text-gray-500 mt-1">${this.formatTime(notification.created_at)}</p>
                            </div>
                        </div>
                    </div>
                `).join('') :
                '<div class="px-4 py-8 text-center text-gray-500 text-sm">No hay notificaciones</div>'
            }
            <div class="px-4 py-2 border-t border-gray-200">
                <a href="/notifications" class="text-sm text-orange-500 hover:text-orange-600">Ver todas</a>
            </div>
        `;
    }

    formatTime(timestamp) {
        const now = new Date();
        const time = new Date(timestamp);
        const diff = now - time;
        
        if (diff < 60000) return 'Ahora';
        if (diff < 3600000) return `${Math.floor(diff / 60000)}m`;
        if (diff < 86400000) return `${Math.floor(diff / 3600000)}h`;
        return `${Math.floor(diff / 86400000)}d`;
    }

    async markAsRead(notificationId) {
        try {
            const response = await fetch(`/api/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const notification = this.notifications.find(n => n.id === notificationId);
                if (notification && !notification.read_at) {
                    notification.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                    this.updateUI();
                    this.saveToStorage();
                }
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            const response = await fetch('/api/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                this.unreadCount = 0;
                this.updateUI();
                this.saveToStorage();
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }

    setupUI() {
        // Add click handlers for notification interactions
        document.addEventListener('click', (e) => {
            if (e.target.closest('.mark-as-read')) {
                const notificationId = e.target.closest('.mark-as-read').dataset.notificationId;
                this.markAsRead(notificationId);
            }
            
            if (e.target.closest('.mark-all-read')) {
                this.markAllAsRead();
            }
        });
    }

    async loadInitialNotifications() {
        try {
            const response = await fetch('/api/notifications', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.notifications = data.notifications || [];
                this.unreadCount = data.unread_count || 0;
                this.updateUI();
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
            this.loadFromStorage();
        }
    }

    saveToStorage() {
        try {
            localStorage.setItem('notifications', JSON.stringify({
                notifications: this.notifications.slice(0, 50), // Keep only recent 50
                unreadCount: this.unreadCount,
                timestamp: Date.now()
            }));
        } catch (error) {
            console.error('Error saving to storage:', error);
        }
    }

    loadFromStorage() {
        try {
            const stored = localStorage.getItem('notifications');
            if (stored) {
                const data = JSON.parse(stored);
                // Only use stored data if it's less than 1 hour old
                if (Date.now() - data.timestamp < 3600000) {
                    this.notifications = data.notifications || [];
                    this.unreadCount = data.unreadCount || 0;
                    this.updateUI();
                }
            }
        } catch (error) {
            console.error('Error loading from storage:', error);
        }
    }

    handleConnectionError() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            const delay = Math.pow(2, this.reconnectAttempts) * 1000; // Exponential backoff
            
            setTimeout(() => {
                console.log(`🔄 Attempting to reconnect... (${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
                this.setupEventSource();
            }, delay);
        } else {
            console.log('❌ Max reconnection attempts reached. Falling back to polling.');
            this.setupPolling();
        }
    }

    destroy() {
        if (this.eventSource) {
            this.eventSource.close();
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.realTimeNotifications = new RealTimeNotifications();
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.realTimeNotifications) {
        window.realTimeNotifications.destroy();
    }
});