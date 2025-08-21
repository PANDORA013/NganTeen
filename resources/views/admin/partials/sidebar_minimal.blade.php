<!-- Sidebar Navigation - Simplified & Clean -->
<div class="admin-sidebar">
    <!-- Brand Header -->
    <div class="sidebar-brand">
        <h4>Admin Panel</h4>
        <span>NganTeen</span>
    </div>

    <!-- Main Navigation -->
    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>

        <!-- Orders -->
        <a href="{{ route('admin.orders') }}" class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            <span>Orders</span>
            @php
                $pendingOrders = \App\Models\GlobalOrder::where('payment_status', 'pending')->count();
            @endphp
            @if($pendingOrders > 0)
                <span class="nav-badge">{{ $pendingOrders }}</span>
            @endif
        </a>

        <!-- Merchants -->
        <a href="{{ route('admin.warungs') }}" class="nav-item {{ request()->routeIs('admin.warungs*') ? 'active' : '' }}">
            <i class="fas fa-store"></i>
            <span>Merchants</span>
        </a>

        <!-- Users -->
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>

        <!-- Payments -->
        <a href="{{ route('admin.settlements') }}" class="nav-item {{ request()->routeIs('admin.settlements*') ? 'active' : '' }}">
            <i class="fas fa-money-bill-wave"></i>
            <span>Payments</span>
            @php
                $unsettledCount = \App\Models\GlobalOrder::where('payment_status', 'paid')
                                                        ->where('is_settled', false)
                                                        ->count();
            @endphp
            @if($unsettledCount > 0)
                <span class="nav-badge warning">{{ $unsettledCount }}</span>
            @endif
        </a>

        <!-- Website Content -->
        <a href="{{ route('admin.content.index') }}" class="nav-item {{ request()->routeIs('admin.content*') ? 'active' : '' }}">
            <i class="fas fa-edit"></i>
            <span>Website</span>
        </a>

        <!-- Quick Tools Dropdown -->
        <div class="nav-dropdown">
            <a href="#" class="nav-item dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#quickToolsMenu" aria-expanded="false">
                <i class="fas fa-tools"></i>
                <span>Quick Tools</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </a>
            <div class="collapse" id="quickToolsMenu">
                <div class="dropdown-content">
                    <a href="{{ route('admin.analytics.index') }}" class="dropdown-item">
                        <i class="fas fa-chart-line"></i>
                        Analytics
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="dropdown-item">
                        <i class="fas fa-user-plus"></i>
                        Add User
                    </a>
                    <a href="{{ route('admin.content.contact-messages') }}" class="dropdown-item">
                        <i class="fas fa-envelope"></i>
                        Messages
                    </a>
                    <a href="{{ route('home') }}" target="_blank" class="dropdown-item">
                        <i class="fas fa-external-link-alt"></i>
                        View Site
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" onclick="location.reload()" class="dropdown-item">
                        <i class="fas fa-sync-alt"></i>
                        Refresh
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Status Footer -->
    <div class="sidebar-footer">
        <div class="status-simple">
            <div class="status-dot"></div>
            <span>System Online</span>
        </div>
    </div>
</div>

<style>
/* Clean and Simple Sidebar */
.admin-sidebar {
    width: 260px;
    height: 100vh;
    background: #ffffff;
    border-right: 1px solid #e5e7eb;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    display: flex;
    flex-direction: column;
}

/* Brand Header */
.sidebar-brand {
    padding: 1.5rem 1.25rem;
    border-bottom: 1px solid #f3f4f6;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.sidebar-brand h4 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
}

.sidebar-brand span {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

/* Navigation */
.sidebar-nav {
    flex: 1;
    padding: 1rem 0;
    overflow-y: auto;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.25rem;
    color: #374151;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    position: relative;
    margin: 0.125rem 0.75rem;
    border-radius: 8px;
}

.nav-item:hover {
    background-color: #f9fafb;
    color: #1f2937;
    text-decoration: none;
}

.nav-item.active {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.nav-item.active:hover {
    color: white;
}

.nav-item i {
    width: 18px;
    margin-right: 0.75rem;
    text-align: center;
    font-size: 0.875rem;
}

.nav-item span {
    flex: 1;
}

/* Navigation Badges */
.nav-badge {
    background: #ef4444;
    color: white;
    font-size: 0.75rem;
    padding: 0.125rem 0.5rem;
    border-radius: 12px;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
}

.nav-badge.warning {
    background: #f59e0b;
}

/* Dropdown */
.nav-dropdown {
    margin: 0.125rem 0.75rem;
}

.dropdown-toggle {
    justify-content: space-between;
}

.toggle-icon {
    transition: transform 0.2s ease;
    font-size: 0.75rem;
}

.dropdown-toggle[aria-expanded="true"] .toggle-icon {
    transform: rotate(180deg);
}

.dropdown-content {
    background: #f9fafb;
    border-radius: 8px;
    margin: 0.5rem 0;
    padding: 0.5rem 0;
    border: 1px solid #e5e7eb;
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    color: #374151;
    text-decoration: none;
    font-size: 0.875rem;
    transition: background-color 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f3f4f6;
    color: #1f2937;
    text-decoration: none;
}

.dropdown-item i {
    width: 16px;
    margin-right: 0.75rem;
    font-size: 0.75rem;
}

.dropdown-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 0.5rem 0;
}

/* Footer */
.sidebar-footer {
    padding: 1rem 1.25rem;
    border-top: 1px solid #f3f4f6;
    background: #f9fafb;
}

.status-simple {
    display: flex;
    align-items: center;
    font-size: 0.875rem;
    color: #6b7280;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    margin-right: 0.5rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Responsive */
@media (max-width: 768px) {
    .admin-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .admin-sidebar.show {
        transform: translateX(0);
    }
}

/* Smooth scrollbar */
.sidebar-nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 2px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>
