// Admin Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Dashboard loaded');

    // Mobile menu toggle
    window.toggleSidebar = function() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.classList.toggle('show');
        }
    };

    // Auto-refresh dashboard stats every 30 seconds
    setInterval(function() {
        if (window.location.pathname.includes('/admin/dashboard')) {
            refreshDashboardStats();
        }
    }, 30000);

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize chart if exists
    if (typeof Chart !== 'undefined' && document.getElementById('revenueChart')) {
        initializeCharts();
    }

    // Add loading states to buttons
    document.querySelectorAll('.btn-loading').forEach(button => {
        button.addEventListener('click', function() {
            this.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Loading...';
            this.disabled = true;
        });
    });

    // Add confirmation dialogs for delete actions
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
});

function refreshDashboardStats() {
    fetch('/admin/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            // Update stats cards
            updateStatsCards(data);
        })
        .catch(error => {
            console.error('Error refreshing stats:', error);
        });
}

function updateStatsCards(data) {
    // Update orders today
    const ordersToday = document.querySelector('.orders-today-count');
    if (ordersToday && data.orders_today !== undefined) {
        animateNumber(ordersToday, data.orders_today);
    }

    // Update revenue today
    const revenueToday = document.querySelector('.revenue-today-amount');
    if (revenueToday && data.revenue_today !== undefined) {
        revenueToday.textContent = 'Rp ' + numberFormat(data.revenue_today);
    }
}

function animateNumber(element, targetNumber) {
    const startNumber = parseInt(element.textContent) || 0;
    const duration = 1000; // 1 second
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const currentNumber = Math.round(startNumber + (targetNumber - startNumber) * progress);
        element.textContent = currentNumber;

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

function numberFormat(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function initializeCharts() {
    // Chart initialization will be handled by the dashboard view
    console.log('Charts ready to initialize');
}

// Export functions for global use
window.adminDashboard = {
    refreshStats: refreshDashboardStats,
    toggleSidebar: window.toggleSidebar,
    animateNumber: animateNumber
};
