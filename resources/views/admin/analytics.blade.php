@extends('layouts.admin')

@section('title', 'Analytics & Reports')

@section('content')
<!-- Analytics Header -->
<div class="analytics-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1 text-dark">Analytics & Reports</h1>
            <p class="text-muted mb-0">Detailed insights and performance metrics</p>
        </div>
        <div class="analytics-actions">
            <button class="btn btn-outline-secondary btn-sm me-2" onclick="exportReport()">
                <i class="fas fa-download"></i> Export
            </button>
            <button class="btn btn-primary btn-sm" onclick="refreshAnalytics()">
                <i class="fas fa-sync-alt"></i> Refresh Data
            </button>
        </div>
    </div>
</div>

<!-- Key Metrics -->
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="metric-card revenue">
            <div class="metric-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="metric-content">
                <h3>Rp {{ number_format(($stats['total_revenue'] ?? 0) / 1000000, 1) }}M</h3>
                <p>Total Revenue</p>
                <span class="metric-change positive">+12.5%</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="metric-card orders">
            <div class="metric-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="metric-content">
                <h3>{{ $stats['total_orders'] ?? 0 }}</h3>
                <p>Total Orders</p>
                <span class="metric-change positive">+8.2%</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="metric-card users">
            <div class="metric-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                <p>Active Users</p>
                <span class="metric-change positive">+15.3%</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="metric-card conversion">
            <div class="metric-icon">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="metric-content">
                <h3>{{ number_format((($stats['total_orders'] ?? 1) / ($stats['total_users'] ?? 1)) * 100, 1) }}%</h3>
                <p>Conversion Rate</p>
                <span class="metric-change negative">-2.1%</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-4">
    <!-- Revenue Chart -->
    <div class="col-lg-8">
        <div class="chart-container">
            <div class="chart-header">
                <h5>Revenue Trend</h5>
                <div class="chart-controls">
                    <button class="btn btn-sm btn-outline-primary active" data-period="7d">7 Days</button>
                    <button class="btn btn-sm btn-outline-primary" data-period="30d">30 Days</button>
                    <button class="btn btn-sm btn-outline-primary" data-period="90d">90 Days</button>
                </div>
            </div>
            <div class="chart-body">
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Order Status Distribution -->
    <div class="col-lg-4">
        <div class="chart-container">
            <div class="chart-header">
                <h5>Order Status</h5>
            </div>
            <div class="chart-body">
                <canvas id="orderStatusChart" width="200" height="200"></canvas>
            </div>
            <div class="chart-legend">
                <div class="legend-item">
                    <span class="legend-dot" style="background: #10b981;"></span>
                    <span>Completed ({{ $stats['completed_orders'] ?? 0 }})</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background: #f59e0b;"></span>
                    <span>Pending ({{ $stats['pending_orders'] ?? 0 }})</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background: #ef4444;"></span>
                    <span>Failed ({{ $stats['failed_orders'] ?? 0 }})</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Tables -->
<div class="row g-4">
    <!-- Top Merchants -->
    <div class="col-lg-6">
        <div class="performance-table">
            <div class="table-header">
                <h5>Top Performing Merchants</h5>
                <a href="{{ route('admin.warungs') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-body">
                @if(!empty($top_warungs) && count($top_warungs) > 0)
                    @foreach(array_slice($top_warungs, 0, 5) as $index => $warung)
                    <div class="performance-row">
                        <div class="rank">{{ $index + 1 }}</div>
                        <div class="info">
                            <div class="name">{{ $warung['name'] ?? 'Unknown' }}</div>
                            <div class="meta">{{ $warung['orders_count'] ?? 0 }} orders</div>
                        </div>
                        <div class="value">
                            Rp {{ number_format(($warung['revenue'] ?? 0) / 1000, 0) }}K
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="no-data">
                        <i class="fas fa-chart-bar text-muted"></i>
                        <p class="text-muted mb-0">No merchant data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent High-Value Orders -->
    <div class="col-lg-6">
        <div class="performance-table">
            <div class="table-header">
                <h5>High-Value Orders</h5>
                <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-body">
                @if(!empty($recent_orders) && count($recent_orders) > 0)
                    @foreach(array_slice($recent_orders, 0, 5) as $order)
                    <div class="performance-row">
                        <div class="rank">#{{ $order['id'] ?? 'N/A' }}</div>
                        <div class="info">
                            <div class="name">{{ $order['customer_name'] ?? 'Guest' }}</div>
                            <div class="meta">{{ $order['created_at_formatted'] ?? 'Unknown time' }}</div>
                        </div>
                        <div class="value">
                            Rp {{ number_format(($order['total_amount'] ?? 0) / 1000, 0) }}K
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="no-data">
                        <i class="fas fa-shopping-cart text-muted"></i>
                        <p class="text-muted mb-0">No recent orders</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Bar -->
<div class="quick-actions-bar mt-4">
    <div class="d-flex justify-content-center gap-3">
        <a href="{{ route('admin.dashboard') }}" class="quick-action-btn">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Dashboard</span>
        </a>
        <button class="quick-action-btn" onclick="generateReport()">
            <i class="fas fa-file-pdf"></i>
            <span>Generate PDF</span>
        </button>
        <button class="quick-action-btn" onclick="scheduleReport()">
            <i class="fas fa-clock"></i>
            <span>Schedule Report</span>
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Analytics Styles */
.analytics-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.analytics-actions .btn {
    border-color: rgba(255,255,255,0.3);
    color: white;
}

.analytics-actions .btn:hover {
    background: rgba(255,255,255,0.2);
}

/* Metric Cards */
.metric-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.metric-card.revenue::before { background: linear-gradient(90deg, #10b981, #059669); }
.metric-card.orders::before { background: linear-gradient(90deg, #3b82f6, #1d4ed8); }
.metric-card.users::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
.metric-card.conversion::before { background: linear-gradient(90deg, #f59e0b, #d97706); }

.metric-icon {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 40px;
    height: 40px;
    background: rgba(0,0,0,0.05);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: #64748b;
}

.metric-content h3 {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
    color: #1e293b;
}

.metric-content p {
    color: #64748b;
    margin: 0 0 0.5rem 0;
    font-size: 0.875rem;
}

.metric-change {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.metric-change.positive {
    background: #dcfce7;
    color: #166534;
}

.metric-change.negative {
    background: #fef2f2;
    color: #dc2626;
}

/* Chart Containers */
.chart-container {
    background: white;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.chart-header {
    padding: 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chart-header h5 {
    margin: 0;
    color: #1e293b;
    font-weight: 600;
}

.chart-controls .btn {
    border-radius: 20px;
    margin-left: 0.5rem;
}

.chart-controls .btn.active {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.chart-body {
    padding: 1.5rem;
}

.chart-legend {
    padding: 1rem 1.5rem;
    border-top: 1px solid #f1f5f9;
    background: #f9fafb;
}

.legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.legend-item:last-child {
    margin-bottom: 0;
}

.legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 0.5rem;
}

/* Performance Tables */
.performance-table {
    background: white;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.table-header {
    padding: 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f9fafb;
}

.table-header h5 {
    margin: 0;
    color: #1e293b;
    font-weight: 600;
}

.table-body {
    padding: 1rem;
}

.performance-row {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.performance-row:last-child {
    border-bottom: none;
}

.rank {
    width: 30px;
    height: 30px;
    background: #f1f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    color: #64748b;
    margin-right: 1rem;
}

.info {
    flex: 1;
}

.info .name {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.125rem;
}

.info .meta {
    font-size: 0.75rem;
    color: #64748b;
}

.value {
    font-weight: 600;
    color: #059669;
}

.no-data {
    text-align: center;
    padding: 2rem;
}

.no-data i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

/* Quick Actions Bar */
.quick-actions-bar {
    padding: 1.5rem;
    background: white;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.quick-action-btn {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    text-decoration: none;
    color: #374151;
    font-weight: 500;
    transition: all 0.2s ease;
}

.quick-action-btn:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #1f2937;
    text-decoration: none;
    transform: translateY(-1px);
}

.quick-action-btn i {
    margin-right: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .chart-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .performance-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .quick-actions-bar .d-flex {
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($chart_data['revenue_labels'] ?? []),
        datasets: [{
            label: 'Revenue',
            data: @json($chart_data['revenue_data'] ?? []),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
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
                grid: {
                    color: '#f1f5f9'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Order Status Chart
const orderCtx = document.getElementById('orderStatusChart').getContext('2d');
const orderChart = new Chart(orderCtx, {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Pending', 'Failed'],
        datasets: [{
            data: [
                {{ $stats['completed_orders'] ?? 0 }},
                {{ $stats['pending_orders'] ?? 0 }},
                {{ $stats['failed_orders'] ?? 0 }}
            ],
            backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Functions
function refreshAnalytics() {
    location.reload();
}

function exportReport() {
    alert('Export functionality coming soon!');
}

function generateReport() {
    alert('PDF generation coming soon!');
}

function scheduleReport() {
    alert('Report scheduling coming soon!');
}

// Period switching
document.querySelectorAll('[data-period]').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('[data-period]').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        // TODO: Reload chart with new period data
    });
});
</script>
@endpush
