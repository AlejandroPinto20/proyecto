// Cargar notificaciones en el dropdown
function loadNotifications() {
    fetch('/api/notifications')
        .then(response => response.json())
        .then(data => {
            const notificationsList = document.getElementById('notificationsList');
            const notificationBadge = document.getElementById('notificationBadge');
            
            if (data.notifications && data.notifications.length > 0) {
                let html = '';
                let unreadCount = 0;
                
                data.notifications.slice(0, 5).forEach(notification => {
                    if (!notification.read) unreadCount++;
                    
                    const iconClass = getNotificationIcon(notification.type);
                    const timeAgo = formatTimeAgo(notification.created_at);
                    
                    html += `
                        <div class="px-4 py-3 hover:bg-gray-50 ${!notification.read ? 'bg-blue-50' : ''}">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 ${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        ${getNotificationIconPath(notification.type)}
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                                    <p class="text-sm text-gray-500 truncate">${notification.message}</p>
                                    <p class="text-xs text-gray-400 mt-1">${timeAgo}</p>
                                </div>
                                ${!notification.read ? '<div class="w-2 h-2 bg-blue-500 rounded-full"></div>' : ''}
                            </div>
                        </div>
                    `;
                });
                
                notificationsList.innerHTML = html;
                
                // Actualizar badge
                if (unreadCount > 0) {
                    notificationBadge.textContent = unreadCount;
                    notificationBadge.classList.remove('hidden');
                } else {
                    notificationBadge.classList.add('hidden');
                }
            } else {
                notificationsList.innerHTML = `
                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                        No hay notificaciones nuevas
                    </div>
                `;
                notificationBadge.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            document.getElementById('notificationsList').innerHTML = `
                <div class="px-4 py-3 text-sm text-red-500 text-center">
                    Error al cargar notificaciones
                </div>
            `;
        });
}

function getNotificationIcon(type) {
    const icons = {
        'activity_accepted': 'text-green-500',
        'activity_joined': 'text-blue-500',
        'activity_rejected': 'text-red-500',
        'certificate': 'text-purple-500',
        'activity_reminder': 'text-yellow-500',
        'default': 'text-gray-500'
    };
    return icons[type] || icons.default;
}

function getNotificationIconPath(type) {
    const paths = {
        'activity_accepted': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'activity_joined': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"></path>',
        'activity_rejected': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'certificate': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>',
        'activity_reminder': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'default': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h4l4 4v-4h4a2 2 0 002-2z"></path>'
    };
    return paths[type] || paths.default;
}

function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'Hace un momento';
    if (diffInSeconds < 3600) return `Hace ${Math.floor(diffInSeconds / 60)} min`;
    if (diffInSeconds < 86400) return `Hace ${Math.floor(diffInSeconds / 3600)} h`;
    return `Hace ${Math.floor(diffInSeconds / 86400)} días`;
}

// Cargar notificaciones al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    
    // Recargar cada 30 segundos
    setInterval(loadNotifications, 30000);
});