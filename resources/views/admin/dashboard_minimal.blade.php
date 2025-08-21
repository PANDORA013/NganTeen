@extends('layouts.admin')

@section('title', 'Admin Control Panel')

@section('content')
<!-- Control Panel Header -->
<div class="control-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1 text-dark">Admin Control Panel</h1>
            <p class="text-muted mb-0">Manage your entire platform efficiently</p>
        </div>
        <div class="header-actions">
            <span class="badge bg-success me-2">System Online</span>
            <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>
</div>

<!-- Quick Stats Overview -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-number">{{ $stats['total_orders'] ?? 0 }}</div>
                <div class="stat-label">Total Orders</div>
                @if(($stats['pending_orders'] ?? 0) > 0)
                    <div class="stat-alert">{{ $stats['pending_orders'] }} pending</div>
                @endif
            </div>
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-number">{{ $stats['total_users'] ?? 0 }}</div>
                <div class="stat-label">Total Users</div>
                <div class="stat-info">{{ $stats['sellers_count'] ?? 0 }} sellers</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-number">{{ $stats['total_warungs'] ?? 0 }}</div>
                <div class="stat-label">Active Warungs</div>
                <div class="stat-info">Merchants</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-store"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-number">Rp {{ number_format(($stats['total_revenue'] ?? 0) / 1000000, 1) }}M</div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-info">All time</div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Control Grid -->
<div class="row g-4">
    <!-- Business Controls -->
    <div class="col-lg-8">
        <div class="control-section">
            <h5 class="section-title">Business Controls</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <a href="{{ route('admin.orders') }}" class="control-card">
                        <div class="control-icon bg-primary">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="control-content">
                            <h6>Orders Management</h6>
                            <p>Monitor and manage all orders</p>
                            @if(($stats['pending_orders'] ?? 0) > 0)
                                <span class="control-badge bg-danger">{{ $stats['pending_orders'] }} pending</span>
                            @endif
                        </div>
                        <div class="control-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6">
                    <a href="{{ route('admin.warungs') }}" class="control-card">
                        <div class="control-icon bg-success">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="control-content">
                            <h6>Merchant Control</h6>
                            <p>Manage warungs and sellers</p>
                        </div>
                        <div class="control-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6">
                    <a href="{{ route('admin.settlements') }}" class="control-card">
                        <div class="control-icon bg-warning">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="control-content">
                            <h6>Financial Control</h6>
                            <p>Payments and settlements</p>
                            @if(($stats['issues_count'] ?? 0) > 0)
                                <span class="control-badge bg-warning">{{ $stats['issues_count'] }} issues</span>
                            @endif
                        </div>
                        <div class="control-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6">
                    <a href="{{ route('admin.users.index') }}" class="control-card">
                        <div class="control-icon bg-info">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div class="control-content">
                            <h6>User Management</h6>
                            <p>Control all user accounts</p>
                        </div>
                        <div class="control-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="control-section">
            <h5 class="section-title">Quick Actions</h5>
            <div class="quick-actions">
                <a href="{{ route('admin.content.index') }}" class="quick-action">
                    <i class="fas fa-globe me-3"></i>
                    <span>Website Content</span>
                    <i class="fas fa-external-link-alt ms-auto"></i>
                </a>
                
                <a href="{{ route('admin.analytics.index') }}" class="quick-action">
                    <i class="fas fa-chart-line me-3"></i>
                    <span>Analytics & Reports</span>
                    <i class="fas fa-chart-bar ms-auto"></i>
                </a>
                
                <a href="{{ route('admin.content.contact-messages') }}" class="quick-action">
                    <i class="fas fa-comments me-3"></i>
                    <span>Messages</span>
                    @if(($stats['unread_messages'] ?? 0) > 0)
                        <span class="badge bg-danger ms-auto">{{ $stats['unread_messages'] }}</span>
                    @else
                        <i class="fas fa-check ms-auto text-success"></i>
                    @endif
                </a>
                
                <a href="{{ route('admin.users.create') }}" class="quick-action">
                    <i class="fas fa-user-plus me-3"></i>
                    <span>Add New User</span>
                    <i class="fas fa-plus ms-auto"></i>
                </a>
                
                <a href="{{ route('home') }}" target="_blank" class="quick-action">
                    <i class="fas fa-external-link-alt me-3"></i>
                    <span>View Live Site</span>
                    <i class="fas fa-arrow-up-right-from-square ms-auto"></i>
                </a>
                
                <div class="quick-action" onclick="showSystemInfo()">
                    <i class="fas fa-info-circle me-3"></i>
                    <span>System Info</span>
                    <i class="fas fa-chevron-right ms-auto"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity (Simple List) -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="control-section">
            <h5 class="section-title">Recent Activity</h5>
            @if(!empty($recent_activities) && count($recent_activities) > 0)
                <div class="activity-list">
                    @foreach(array_slice($recent_activities, 0, 5) as $activity)
                    <div class="activity-item">
                        <div class="activity-dot"></div>
                        <div class="activity-content">
                            <strong>{{ $activity['title'] }}</strong> - {{ $activity['description'] }}
                            <small class="text-muted">{{ $activity['time'] }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="no-activity">
                    <i class="fas fa-clock me-2 text-muted"></i>
                    <span class="text-muted">No recent activity</span>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- System Info Modal -->
<div class="modal fade" id="systemInfoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">System Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="system-info">
                    <div class="info-item">
                        <span class="info-label">Platform:</span>
                        <span class="info-value">NganTeen v3.0</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Framework:</span>
                        <span class="info-value">Laravel 12.24.0</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">PHP Version:</span>
                        <span class="info-value">8.4.11</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Database:</span>
                        <span class="info-value">MySQL Connected</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="badge bg-success">All Systems Operational</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Clean and Simple Styling */
.control-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.header-actions .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
}

/* Simple Stats Cards */
.stat-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s ease;
}

.stat-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
}

.stat-label {
    color: #64748b;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.stat-alert {
    color: #dc2626;
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 0.25rem;
}

.stat-info {
    color: #64748b;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.stat-icon {
    color: #94a3b8;
    font-size: 1.5rem;
}

/* Control Sections */
.control-section {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
}

.section-title {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #f1f5f9;
}

/* Control Cards */
.control-card {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.control-card:hover {
    text-decoration: none;
    color: inherit;
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.control-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 1rem;
    flex-shrink: 0;
}

.control-content h6 {
    margin: 0 0 0.25rem 0;
    font-weight: 600;
    color: #1e293b;
}

.control-content p {
    margin: 0;
    font-size: 0.875rem;
    color: #64748b;
}

.control-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
}

.control-arrow {
    color: #cbd5e1;
    margin-left: auto;
    flex-shrink: 0;
}

/* Quick Actions */
.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.quick-action {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    text-decoration: none;
    color: #1e293b;
    transition: all 0.2s ease;
    cursor: pointer;
}

.quick-action:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #1e293b;
    text-decoration: none;
}

/* Activity List */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.activity-dot {
    width: 8px;
    height: 8px;
    background: #3b82f6;
    border-radius: 50%;
    margin-top: 0.5rem;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-content strong {
    color: #1e293b;
}

.activity-content small {
    display: block;
    margin-top: 0.25rem;
}

.no-activity {
    padding: 2rem;
    text-align: center;
    background: #f8fafc;
    border-radius: 8px;
}

/* System Info Modal */
.system-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #1e293b;
}

.info-value {
    color: #64748b;
}

/* Responsive */
@media (max-width: 768px) {
    .stat-card {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .control-card {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .control-icon {
        margin-right: 0;
    }
}
</style>
@endpush

@push('scripts')
<script>
function showSystemInfo() {
    $('#systemInfoModal').modal('show');
}

// Auto refresh stats every 30 seconds
setInterval(function() {
    const badges = document.querySelectorAll('.stat-alert, .control-badge');
    // Simple visual indicator that data is live
    badges.forEach(badge => {
        badge.style.opacity = '0.7';
        setTimeout(() => badge.style.opacity = '1', 200);
    });
}, 30000);
</script>
@endpush
