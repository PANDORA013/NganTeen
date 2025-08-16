/**
 * Real-time notifications handler for NganTeen app
 * Handles order status updates and new menu notifications
 */

class NotificationHandler {
    constructor() {
        this.init();
        this.setupChannels();
    }

    init() {
        // Create notification container if it doesn't exist
        if (!document.getElementById('notification-container')) {
            const container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }

        // Check if user is authenticated
        this.userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
        this.userRole = document.querySelector('meta[name="user-role"]')?.getAttribute('content');
    }

    setupChannels() {
        if (typeof window.Echo === 'undefined') {
            console.warn('Laravel Echo is not initialized');
            return;
        }

        // Subscribe to menu updates for all users
        this.subscribeToMenuUpdates();

        // Subscribe to user-specific notifications if authenticated
        if (this.userId) {
            this.subscribeToUserNotifications();
        }

        // Subscribe to area-specific updates if on home/menu page
        this.subscribeToAreaUpdates();
    }

    subscribeToMenuUpdates() {
        window.Echo.channel('menu.updates')
            .listen('.menu.added', (e) => {
                this.showNotification({
                    title: 'Menu Baru!',
                    message: e.message,
                    type: 'info',
                    data: e.menu
                });
                
                // Update menu list if we're on the menu page
                this.updateMenuList(e.menu);
            });
    }

    subscribeToUserNotifications() {
        window.Echo.private(`user.${this.userId}`)
            .listen('.order.status.updated', (e) => {
                this.showNotification({
                    title: 'Status Pesanan Diperbarui',
                    message: e.message,
                    type: this.getNotificationType(e.new_status),
                    data: e
                });

                // Update order status in UI if on orders page
                this.updateOrderStatus(e.order_id, e.new_status);
            });
    }

    subscribeToAreaUpdates() {
        // Get current area from page or localStorage
        const currentArea = this.getCurrentArea();
        if (currentArea) {
            const areaChannel = currentArea.replace(/\s+/g, '').toLowerCase();
            window.Echo.channel(`area.${areaChannel}`)
                .listen('.menu.added', (e) => {
                    this.showNotification({
                        title: `Menu Baru di ${e.menu.area_kampus}!`,
                        message: `${e.menu.nama} - Rp ${this.formatPrice(e.menu.harga)}`,
                        type: 'success',
                        data: e.menu
                    });
                });
        }
    }

    getCurrentArea() {
        // Try to get from page data or localStorage
        return localStorage.getItem('selectedArea') || 
               document.querySelector('meta[name="current-area"]')?.getAttribute('content') ||
               'Kampus A'; // default
    }

    showNotification({ title, message, type = 'info', data = null, duration = 5000 }) {
        const container = document.getElementById('notification-container');
        const notification = document.createElement('div');
        
        const typeClasses = {
            success: 'bg-green-500 border-green-600',
            error: 'bg-red-500 border-red-600',
            warning: 'bg-yellow-500 border-yellow-600',
            info: 'bg-blue-500 border-blue-600'
        };

        notification.className = `
            ${typeClasses[type]} text-white p-4 rounded-lg border-l-4 shadow-lg
            transform transition-all duration-300 ease-in-out
            max-w-sm cursor-pointer hover:shadow-xl
        `;

        notification.innerHTML = `
            <div class="flex items-start">
                <div class="flex-1">
                    <h4 class="font-bold text-sm">${title}</h4>
                    <p class="text-sm mt-1 opacity-90">${message}</p>
                </div>
                <button class="ml-2 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;

        // Add click handler for navigation if needed
        if (data && data.id) {
            notification.addEventListener('click', () => {
                if (type === 'info' && data.area_kampus) {
                    // Navigate to menu page
                    window.location.href = `/menu?area=${encodeURIComponent(data.area_kampus)}`;
                } else if (data.order_id) {
                    // Navigate to order details
                    window.location.href = `/orders/${data.order_id}`;
                }
            });
        }

        container.appendChild(notification);

        // Auto remove after duration
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.transform = 'translateX(100%)';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }
        }, duration);

        // Play notification sound if enabled
        this.playNotificationSound();
    }

    getNotificationType(status) {
        switch (status) {
            case 'ready':
            case 'paid':
                return 'success';
            case 'cancelled':
                return 'error';
            case 'pending':
                return 'warning';
            default:
                return 'info';
        }
    }

    updateOrderStatus(orderId, newStatus) {
        const orderElement = document.querySelector(`[data-order-id="${orderId}"]`);
        if (orderElement) {
            const statusElement = orderElement.querySelector('.order-status');
            if (statusElement) {
                statusElement.textContent = newStatus;
                statusElement.className = `order-status badge ${this.getStatusClass(newStatus)}`;
            }
        }
    }

    updateMenuList(menu) {
        const menuContainer = document.querySelector('.menu-grid, .menu-list');
        if (menuContainer) {
            // Add new menu item to the list
            const menuItem = this.createMenuElement(menu);
            menuContainer.insertBefore(menuItem, menuContainer.firstChild);
            
            // Highlight new item
            menuItem.classList.add('animate-pulse');
            setTimeout(() => menuItem.classList.remove('animate-pulse'), 3000);
        }
    }

    createMenuElement(menu) {
        const menuItem = document.createElement('div');
        menuItem.className = 'menu-item bg-white rounded-lg shadow-md p-4 border-2 border-green-200';
        menuItem.innerHTML = `
            <div class="aspect-w-16 aspect-h-9 mb-3">
                <img src="${menu.gambar || '/images/default-menu.jpg'}" 
                     alt="${menu.nama}" 
                     class="w-full h-32 object-cover rounded-lg">
            </div>
            <h3 class="font-semibold text-lg text-gray-800">${menu.nama}</h3>
            <p class="text-green-600 font-bold text-xl">Rp ${this.formatPrice(menu.harga)}</p>
            <p class="text-sm text-gray-600">oleh ${menu.user_name}</p>
            <p class="text-xs text-gray-500">${menu.area_kampus}</p>
            <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full mt-2">
                Baru!
            </span>
        `;
        return menuItem;
    }

    getStatusClass(status) {
        const classes = {
            pending: 'bg-yellow-100 text-yellow-800',
            ready: 'bg-green-100 text-green-800',
            paid: 'bg-blue-100 text-blue-800',
            cancelled: 'bg-red-100 text-red-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }

    formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }

    playNotificationSound() {
        // Only play if user hasn't disabled notifications
        if (localStorage.getItem('notifications-sound') !== 'false') {
            try {
                const audio = new Audio('/sounds/notification.mp3');
                audio.volume = 0.3;
                audio.play().catch(() => {
                    // Ignore errors if sound file doesn't exist or autoplay is blocked
                });
            } catch (e) {
                // Ignore sound errors
            }
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new NotificationHandler();
});

// Export for manual initialization if needed
window.NotificationHandler = NotificationHandler;
