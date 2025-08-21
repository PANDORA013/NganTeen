<div class="sidebar-nav">
    <!-- Main Dashboard -->
    <div class="nav-section">
        <div class="nav-section-title">Control Center</div>
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard Overview</span>
            </a>
        </div>
    </div>

    <!-- Business Control -->
    <div class="nav-section">
        <div class="nav-section-title">Business Control</div>
        <div class="nav-item">
            <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Orders & Sales</span>
                @php
                    $pendingOrders = \App\Models\GlobalOrder::where('payment_status', 'pending')->count();
                @endphp
                @if($pendingOrders > 0)
                    <span class="badge bg-warning ms-auto">{{ $pendingOrders }}</span>
                @endif
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.warungs') }}" class="nav-link {{ request()->routeIs('admin.warungs*') ? 'active' : '' }}">
                <i class="fas fa-store"></i>
                <span>Merchant Management</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.settlements') }}" class="nav-link {{ request()->routeIs('admin.settlements*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i>
                <span>Financial Control</span>
                @php
                    $unsettledCount = \App\Models\GlobalOrder::where('payment_status', 'paid')
                                                            ->where('is_settled', false)
                                                            ->count();
                @endphp
                @if($unsettledCount > 0)
                    <span class="badge bg-success ms-auto">{{ $unsettledCount }}</span>
                @endif
            </a>
        </div>
    </div>

    <!-- User Control -->
    <div class="nav-section">
        <div class="nav-section-title">User Control</div>
        <div class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i>
                <span>User Management</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.content.contact-messages') }}" class="nav-link {{ request()->routeIs('admin.content.contact-messages') ? 'active' : '' }}">
                <i class="fas fa-comments"></i>
                <span>User Messages</span>
                @php
                    $unreadMessages = \Illuminate\Support\Facades\Storage::exists('admin/contact-messages.json') 
                        ? count(array_filter(json_decode(\Illuminate\Support\Facades\Storage::get('admin/contact-messages.json'), true) ?: [], fn($msg) => $msg['status'] === 'unread'))
                        : 0;
                @endphp
                @if($unreadMessages > 0)
                    <span class="badge bg-danger ms-auto">{{ $unreadMessages }}</span>
                @endif
            </a>
        </div>
    </div>

    <!-- Website Control -->
    <div class="nav-section">
        <div class="nav-section-title">Website Control</div>
        <div class="nav-item">
            <a href="{{ route('admin.content.index') }}" class="nav-link {{ request()->routeIs('admin.content.*') && !request()->routeIs('admin.content.contact-messages') ? 'active' : '' }}">
                <i class="fas fa-globe"></i>
                <span>Website Management</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.analytics.index') }}" class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Analytics & Reports</span>
            </a>
        </div>
    </div>

    <!-- System Control -->
    <div class="nav-section">
        <div class="nav-section-title">System Control</div>
        <div class="nav-item">
            <a href="#" class="nav-link" onclick="showSystemStatus()">
                <i class="fas fa-server"></i>
                <span>System Status</span>
                <i class="fas fa-circle text-success ms-auto" style="font-size: 8px;" title="System Online"></i>
            </a>
        </div>
        <div class="nav-item">
            <a href="#" class="nav-link" onclick="showQuickActions()">
                <i class="fas fa-bolt"></i>
                <span>Quick Actions</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('home') }}" target="_blank" class="nav-link">
                <i class="fas fa-external-link-alt"></i>
                <span>View Live Site</span>
            </a>
        </div>
    </div>
</div>
            <a href="{{ route('admin.transactions') }}" class="nav-link {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
                <i class="fas fa-money-check-alt"></i>
                <span>Transaksi</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.settlements') }}" class="nav-link {{ request()->routeIs('admin.settlements*') ? 'active' : '' }}">
                <i class="fas fa-handshake"></i>
                <span>Settlements</span>
                @php
                    $unsettledCount = \App\Models\GlobalOrder::where('payment_status', 'paid')
                                                            ->where('is_settled', false)
                                                            ->count();
                @endphp
                @if($unsettledCount > 0)
                    <span class="badge bg-warning ms-auto">{{ $unsettledCount }}</span>
                @endif
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.payouts') }}" class="nav-link {{ request()->routeIs('admin.payouts*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i>
                <span>Payouts</span>
            </a>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="nav-section">
        <div class="nav-section-title">Quick Actions</div>
        <div class="nav-item">
            <a href="{{ route('home') }}" target="_blank" class="nav-link">
                <i class="fas fa-external-link-alt"></i>
                <span>View Website</span>
            </a>
        </div>
    </div>
</div>

<!-- Sidebar Footer -->
<div class="position-absolute bottom-0 w-100 p-3 text-center">
    <small class="text-muted d-block sidebar-collapsed-hide">
        NganTeen Control Center<br>
        <span class="text-primary">Streamlined Admin v3.0</span>
    </small>
</div>

<!-- Quick Actions Modal -->
<div class="modal fade" id="quickActionsModal" tabindex="-1" aria-labelledby="quickActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickActionsModalLabel">
                    <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100 border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                                <h6>Add New User</h6>
                                <p class="text-muted small">Quickly create new user account</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Create User</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-eye fa-3x text-success mb-3"></i>
                                <h6>Monitor Orders</h6>
                                <p class="text-muted small">View real-time order status</p>
                                <a href="{{ route('admin.orders') }}" class="btn btn-success btn-sm">View Orders</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-cog fa-3x text-warning mb-3"></i>
                                <h6>System Settings</h6>
                                <p class="text-muted small">Configure website settings</p>
                                <a href="{{ route('admin.content.website-settings') }}" class="btn btn-warning btn-sm">Settings</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-info">
                            <div class="card-body text-center">
                                <i class="fas fa-download fa-3x text-info mb-3"></i>
                                <h6>Export Data</h6>
                                <p class="text-muted small">Download reports and data</p>
                                <button class="btn btn-info btn-sm" onclick="exportData()">Export</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Status Modal -->
<div class="modal fade" id="systemStatusModal" tabindex="-1" aria-labelledby="systemStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="systemStatusModalLabel">
                    <i class="fas fa-server text-success me-2"></i>System Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-database fa-2x text-success me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Database</h6>
                                        <small class="text-success">Online & Connected</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-server fa-2x text-success me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Server</h6>
                                        <small class="text-success">Running Smoothly</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-shield-alt fa-2x text-success me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Security</h6>
                                        <small class="text-success">Protected</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users fa-2x text-info me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Active Users</h6>
                                        <small class="text-muted">{{ \App\Models\User::count() }} Total Users</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Quick Stats</h6>
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="text-primary">
                                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                <h5>{{ \App\Models\GlobalOrder::count() }}</h5>
                                <small>Total Orders</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-success">
                                <i class="fas fa-store fa-2x mb-2"></i>
                                <h5>{{ \App\Models\Warung::count() }}</h5>
                                <small>Active Warungs</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-warning">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h5>{{ \App\Models\User::where('role', 'pembeli')->count() }}</h5>
                                <small>Customers</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-info">
                                <i class="fas fa-money-bill fa-2x mb-2"></i>
                                <h5>{{ number_format(\App\Models\GlobalOrder::where('payment_status', 'paid')->sum('total_amount')) }}</h5>
                                <small>Revenue</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.sidebar-collapsed .sidebar-collapsed-hide {
    display: none !important;
}

.nav-link .badge {
    font-size: 0.7rem;
}

/* Hover effects for sidebar */
.nav-link {
    position: relative;
    overflow: hidden;
}

.nav-link::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.5s;
}

.nav-link:hover::after {
    left: 100%;
}

/* Active link animation */
.nav-link.active {
    position: relative;
    overflow: hidden;
}

.nav-link.active::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: rgba(255,255,255,0.3);
    animation: activeGlow 2s ease-in-out infinite alternate;
}

@keyframes activeGlow {
    from { opacity: 0.3; }
    to { opacity: 1; }
}

/* Section dividers */
.nav-section:not(:last-child) {
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 1rem;
}

/* Tooltip for collapsed sidebar */
.sidebar-collapsed .nav-link {
    position: relative;
}

.sidebar-collapsed .nav-link:hover::before {
    content: attr(title);
    position: absolute;
    left: 100%;
    top: 50%;
    transform: translateY(-50%);
    background: #1e293b;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    white-space: nowrap;
    z-index: 1000;
    font-size: 0.875rem;
    margin-left: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style>

<script>
$(document).ready(function() {
    // Add tooltip titles for collapsed sidebar
    $('.nav-link').each(function() {
        const text = $(this).find('span').text().trim();
        $(this).attr('title', text);
    });
    
    // Smooth scrolling for active item
    const activeLink = $('.nav-link.active');
    if (activeLink.length) {
        $('.admin-sidebar').animate({
            scrollTop: activeLink.offset().top - $('.admin-sidebar').offset().top - 100
        }, 300);
    }
});

// Show Quick Actions Modal
function showQuickActions() {
    $('#quickActionsModal').modal('show');
}

// Show System Status Modal
function showSystemStatus() {
    $('#systemStatusModal').modal('show');
}

// Export Data Function
function exportData() {
    // Create export options
    const exportOptions = [
        { name: 'Orders Report', url: '{{ route("admin.orders") }}?export=csv' },
        { name: 'Users Report', url: '{{ route("admin.users.index") }}?export=csv' },
        { name: 'Revenue Report', url: '{{ route("admin.settlements") }}?export=csv' }
    ];
    
    let optionsHtml = '<div class="list-group">';
    exportOptions.forEach(option => {
        optionsHtml += `<a href="${option.url}" class="list-group-item list-group-item-action">
            <i class="fas fa-download me-2"></i>${option.name}
        </a>`;
    });
    optionsHtml += '</div>';
    
    // Show options in modal
    $('#quickActionsModal .modal-body').html(`
        <h6>Select Export Type:</h6>
        ${optionsHtml}
    `);
}
</script>


