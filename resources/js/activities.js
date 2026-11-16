// JavaScript para mejorar la experiencia de usuario en las actividades

document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit del formulario de filtros cuando cambian los selects
    const filterForm = document.querySelector('#activity-filters');
    const filterSelects = document.querySelectorAll('#activity-filters select');
    
    if (filterForm && filterSelects.length > 0) {
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                // Pequeño delay para mejor UX
                setTimeout(() => {
                    filterForm.submit();
                }, 100);
            });
        });
    }

    // Búsqueda en tiempo real con debounce
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            // Debounce de 500ms
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    filterForm.submit();
                }
            }, 500);
        });
    }

    // Animaciones escalonadas para las tarjetas
    const activityCards = document.querySelectorAll('.activity-card');
    if (activityCards.length > 0) {
        activityCards.forEach((card, index) => {
            card.classList.add('stagger-animation');
            card.style.animationDelay = `${index * 0.1}s`;
        });
    }

    // Confirmación mejorada para acciones destructivas
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const message = this.getAttribute('data-confirm') || '¿Estás seguro?';
            const actionType = this.getAttribute('data-action-type') || 'eliminar';
            
            showConfirmDialog(message, actionType, () => {
                // Si es un formulario, enviarlo
                const form = this.closest('form');
                if (form) {
                    form.submit();
                } else {
                    // Si es un enlace, navegar
                    window.location.href = this.href;
                }
            });
        });
    });

    // Tooltips personalizados
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(element => {
        element.classList.add('tooltip');
    });

    // Lazy loading para imágenes (si las hay)
    const images = document.querySelectorAll('img[data-src]');
    if (images.length > 0 && 'IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    // Contador de caracteres para textarea (si existe)
    const textareas = document.querySelectorAll('textarea[data-max-length]');
    textareas.forEach(textarea => {
        const maxLength = parseInt(textarea.getAttribute('data-max-length'));
        const counter = document.createElement('div');
        counter.className = 'text-sm text-gray-500 mt-1 text-right';
        counter.textContent = `0/${maxLength}`;
        
        textarea.parentNode.appendChild(counter);
        
        textarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            counter.textContent = `${currentLength}/${maxLength}`;
            
            if (currentLength > maxLength * 0.9) {
                counter.classList.add('text-orange-500');
            } else {
                counter.classList.remove('text-orange-500');
            }
            
            if (currentLength >= maxLength) {
                counter.classList.add('text-red-500');
                counter.classList.remove('text-orange-500');
            } else {
                counter.classList.remove('text-red-500');
            }
        });
    });

    // Smooth scroll para anclas
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Auto-refresh para notificaciones (cada 30 segundos)
    if (document.querySelector('.notification-badge')) {
        setInterval(updateNotificationCount, 30000);
    }
});

// Función para mostrar diálogo de confirmación personalizado
function showConfirmDialog(message, actionType, onConfirm) {
    // Crear el modal
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-sm mx-4 transform transition-all duration-200 scale-95">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900">Confirmar ${actionType}</h3>
                </div>
            </div>
            <div class="mb-6">
                <p class="text-sm text-gray-500">${message}</p>
            </div>
            <div class="flex space-x-3 justify-end">
                <button type="button" class="cancel-btn px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition duration-200">
                    Cancelar
                </button>
                <button type="button" class="confirm-btn px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition duration-200">
                    ${actionType.charAt(0).toUpperCase() + actionType.slice(1)}
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Animar la entrada
    setTimeout(() => {
        modal.querySelector('.bg-white').classList.remove('scale-95');
        modal.querySelector('.bg-white').classList.add('scale-100');
    }, 10);

    // Event listeners
    modal.querySelector('.cancel-btn').addEventListener('click', () => {
        closeModal(modal);
    });

    modal.querySelector('.confirm-btn').addEventListener('click', () => {
        closeModal(modal);
        onConfirm();
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal(modal);
        }
    });

    // Cerrar con ESC
    const escHandler = (e) => {
        if (e.key === 'Escape') {
            closeModal(modal);
            document.removeEventListener('keydown', escHandler);
        }
    };
    document.addEventListener('keydown', escHandler);
}

function closeModal(modal) {
    modal.querySelector('.bg-white').classList.add('scale-95');
    modal.classList.add('opacity-0');
    
    setTimeout(() => {
        modal.remove();
    }, 200);
}

// Función para actualizar el contador de notificaciones
async function updateNotificationCount() {
    try {
        const response = await fetch('/api/notifications/unread-count');
        const data = await response.json();
        
        const badge = document.querySelector('.notification-badge');
        if (badge && data.count !== undefined) {
            if (data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    } catch (error) {
        console.log('Error updating notification count:', error);
    }
}

// Función para mostrar mensajes toast
function showToast(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    const bgColor = {
        'success': 'bg-green-500',
        'error': 'bg-red-500',
        'warning': 'bg-yellow-500',
        'info': 'bg-blue-500'
    }[type] || 'bg-blue-500';

    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    toast.textContent = message;

    document.body.appendChild(toast);

    // Animar entrada
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);

    // Auto-remove
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, duration);

    // Click to dismiss
    toast.addEventListener('click', () => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            toast.remove();
        }, 300);
    });
}

// Manejar formularios AJAX para unirse/abandonar actividades
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.action.includes('/join') || form.action.includes('/leave')) {
        e.preventDefault();
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                if (data.points_info) {
                    showToast(data.points_info, 'info', 5000);
                }
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            showToast('Error al procesar la solicitud', 'error');
        });
    }
});

// Exportar funciones para uso global
window.showToast = showToast;
window.showConfirmDialog = showConfirmDialog;