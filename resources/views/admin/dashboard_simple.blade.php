@extends('layouts.admin')

@section('title', 'Control Center')

@section('content')
<!-- Control Center Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Control Center</h1>
        <p class="text-muted mb-0">Manage your entire platform from one place</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" onclick="location.reload()">
            <i class="fas fa-sync-alt me-1"></i> Refresh
        </button>
        <button class="btn btn-primary" onclick="showQuickActions()">
            <i class="fas fa-bolt me-1"></i> Quick Actions
        </button>
    </div>
</div>

<!-- Main Control Dashboard -->
<div class="row g-4">
    <!-- Business Overview -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Business Overview</h5>
                <small class="text-muted">Real-time data</small>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Sales Control -->
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">Rp {{ number_format($stats['total_revenue'] ?? 0) }}</h4>
                                <small class="text-muted">Total Revenue</small>
                                <div class="mt-1">
                                    <span class="badge bg-success">+{{ number_format($stats['revenue_today'] ?? 0) }} today</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Control -->
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $stats['total_orders'] ?? 0 }}</h4>
                                <small class="text-muted">Total Orders</small>
                                <div class="mt-1">
                                    @if(($stats['pending_orders'] ?? 0) > 0)
                                        <span class="badge bg-danger">{{ $stats['pending_orders'] }} pending</span>
                                    @else
                                        <span class="badge bg-success">All up to date</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Control -->
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $stats['total_users'] ?? 0 }}</h4>
                                <small class="text-muted">Total Users</small>
                                <div class="mt-1">
                                    <span class="badge bg-primary">{{ $stats['buyers_count'] ?? 0 }} buyers</span>
                                    <span class="badge bg-success">{{ $stats['sellers_count'] ?? 0 }} sellers</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Merchant Control -->
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-store"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $stats['total_warungs'] ?? 0 }}</h4>
                                <small class="text-muted">Active Warungs</small>
                                <div class="mt-1">
                                    @if(($stats['unread_messages'] ?? 0) > 0)
                                        <span class="badge bg-warning">{{ $stats['unread_messages'] }} messages</span>
                                    @else
                                        <span class="badge bg-success">All handled</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Controls -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Controls</h5>
            </div>
            <div class="card-body p-2">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.orders') }}" class="btn btn-outline-primary text-start">
                        <i class="fas fa-shopping-cart me-2"></i>
                        <span>Manage Orders</span>
                        @if(($stats['pending_orders'] ?? 0) > 0)
                            <span class="badge bg-danger ms-auto">{{ $stats['pending_orders'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.warungs') }}" class="btn btn-outline-success text-start">
                        <i class="fas fa-store me-2"></i>
                        <span>Manage Merchants</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info text-start">
                        <i class="fas fa-users-cog me-2"></i>
                        <span>Manage Users</span>
                    </a>
                    <a href="{{ route('admin.settlements') }}" class="btn btn-outline-warning text-start">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        <span>Financial Control</span>
                        @if(($stats['issues_count'] ?? 0) > 0)
                            <span class="badge bg-warning ms-auto">{{ $stats['issues_count'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.content.contact-messages') }}" class="btn btn-outline-secondary text-start">
                        <i class="fas fa-comments me-2"></i>
                        <span>User Messages</span>
                        @if(($stats['unread_messages'] ?? 0) > 0)
                            <span class="badge bg-danger ms-auto">{{ $stats['unread_messages'] }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Revenue Chart -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Revenue Trend</h5>
                <small class="text-muted">Last 7 days</small>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Activities</h5>
            </div>
            <div class="card-body">
                @if(!empty($recent_activities))
                    <div class="activity-list">
                        @foreach($recent_activities as $activity)
                        <div class="activity-item d-flex align-items-start mb-3">
                            <div class="activity-icon bg-primary text-white rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-circle" style="font-size: 8px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="activity-title font-weight-bold">{{ $activity['title'] }}</div>
                                <div class="activity-description text-muted small">{{ $activity['description'] }}</div>
                                <div class="activity-time text-muted small">{{ $activity['time'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clock fa-2x mb-3"></i>
                        <p>No recent activities</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- System Status -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Platform Health</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="text-success mb-2">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <h6>System Status</h6>
                            <small class="text-success">All Systems Operational</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="text-success mb-2">
                                <i class="fas fa-database fa-2x"></i>
                            </div>
                            <h6>Database</h6>
                            <small class="text-success">Connected & Optimized</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="text-warning mb-2">
                                <i class="fas fa-server fa-2x"></i>
                            </div>
                            <h6>Server Load</h6>
                            <small class="text-warning">Moderate Usage</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="text-info mb-2">
                                <i class="fas fa-cloud fa-2x"></i>
                            </div>
                            <h6>Backup</h6>
                            <small class="text-info">Auto Backup Active</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chart_data['revenue_labels'] ?? []),
            datasets: [{
                label: 'Revenue',
                data: @json($chart_data['revenue_data'] ?? []),
                borderColor: 'rgb(102, 126, 234)',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.activity-list {
    max-height: 300px;
    overflow-y: auto;
}

.activity-item:last-child {
    margin-bottom: 0 !important;
}

.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-info:hover,
.btn-outline-warning:hover,
.btn-outline-secondary:hover {
    transform: translateX(5px);
    transition: transform 0.2s ease;
}

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 12px;
}

.card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 1px solid #e2e8f0;
    border-radius: 12px 12px 0 0 !important;
}

.bg-light {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
}
</style>
@endpush
