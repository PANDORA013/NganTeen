@extends('layouts.admin')

@push('styles')
<style>
/* Force Admin Dashboard Styling */
.admin-dashboard {
    background-color: #F8FAFC !important;
    min-height: 100vh !important;
    font-family: 'Inter', sans-serif !important;
}

.dashboard-header {
    background: linear-gradient(135deg, #1E293B 0%, #334155 100%) !important;
    color: white !important;
    padding: 2rem !important;
    border-radius: 0.75rem !important;
    margin-bottom: 2rem !important;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.15) !important;
}

.dashboard-header h1 {
    color: white !important;
    font-weight: 700 !important;
    font-size: 1.75rem !important;
    margin-bottom: 0.5rem !important;
}

.dashboard-header p {
    color: rgba(255, 255, 255, 0.8) !important;
    margin-bottom: 0 !important;
}

.stats-card {
    background: white !important;
    border: none !important;
    border-radius: 0.75rem !important;
    padding: 1.5rem !important;
    box-shadow: 0 2px 15px rgba(15, 23, 42, 0.1) !important;
    transition: all 0.3s ease !important;
    margin-bottom: 1.5rem !important;
    position: relative !important;
    overflow: hidden !important;
}

.stats-card:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 25px rgba(15, 23, 42, 0.15) !important;
}

.stats-card.primary {
    border-left: 4px solid #1E293B !important;
}

.stats-card.success {
    border-left: 4px solid #10B981 !important;
}

.stats-card.info {
    border-left: 4px solid #3B82F6 !important;
}

.stats-card.warning {
    border-left: 4px solid #F59E0B !important;
}

.stats-number {
    font-size: 2rem !important;
    font-weight: 700 !important;
    color: #1F2937 !important;
    margin-bottom: 0.5rem !important;
    line-height: 1.2 !important;
}

.stats-label {
    font-size: 0.875rem !important;
    color: #6B7280 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    font-weight: 500 !important;
}

.stats-icon {
    font-size: 2.5rem !important;
    opacity: 0.1 !important;
    position: absolute !important;
    right: 1rem !important;
    top: 1rem !important;
    color: #1F2937 !important;
}

.card {
    border: none !important;
    border-radius: 0.75rem !important;
    box-shadow: 0 2px 15px rgba(15, 23, 42, 0.1) !important;
    background-color: white !important;
    margin-bottom: 1.5rem !important;
}

.card-header {
    background-color: #F8FAFC !important;
    border-bottom: 1px solid #E5E7EB !important;
    font-weight: 600 !important;
    color: #1F2937 !important;
    padding: 1rem 1.25rem !important;
    border-radius: 0.75rem 0.75rem 0 0 !important;
}

.card-body {
    padding: 1.25rem !important;
}

.table {
    border-radius: 0.5rem !important;
    overflow: hidden !important;
    margin-bottom: 0 !important;
}

.table th {
    background-color: #F8FAFC !important;
    color: #1F2937 !important;
    font-weight: 600 !important;
    border-bottom: 1px solid #E5E7EB !important;
    padding: 1rem !important;
}

.table td {
    padding: 1rem !important;
    border-bottom: 1px solid #F3F4F6 !important;
    color: #374151 !important;
}

.badge {
    font-weight: 500 !important;
    padding: 0.375rem 0.75rem !important;
    border-radius: 0.375rem !important;
}

.bg-success {
    background-color: #10B981 !important;
}

.bg-danger {
    background-color: #EF4444 !important;
}

.text-muted {
    color: #6B7280 !important;
}

.btn-primary {
    background-color: #1E293B !important;
    border-color: #1E293B !important;
    color: white !important;
    font-weight: 500 !important;
    padding: 0.75rem 1.5rem !important;
    border-radius: 0.5rem !important;
}

.btn-primary:hover {
    background-color: #0F172A !important;
    border-color: #0F172A !important;
    transform: translateY(-1px) !important;
}

.row {
    margin-left: -0.75rem !important;
    margin-right: -0.75rem !important;
}

.col-xl-3, .col-md-6, .col-12 {
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
}

.g-4 > * {
    margin-bottom: 1.5rem !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

.fade-in {
    animation: fadeIn 0.5s ease-in !important;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@section('content')
<div class="admin-dashboard">
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-0">Admin Dashboard</h1>
                <p class="mb-0">Welcome back, {{ Auth::user()->name }}. Here's what's happening today.</p>
            </div>
            <div class="text-white-50 small">
                <i class="fas fa-clock me-1"></i>
                Last updated: {{ now()->format('M d, Y H:i') }}
            </div>
        </div>
    </div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Today's Orders -->
    <div class="col-xl-3 col-md-6">
        <div class="stats-card primary fade-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ $stats['total_orders_today'] }}</div>
                    <div class="stats-label">Order Hari Ini</div>
                    <div class="mt-2">
                        <span class="badge bg-{{ $stats['growth_percentage'] >= 0 ? 'success' : 'danger' }} badge-sm">
                            <i class="fas fa-{{ $stats['growth_percentage'] >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                            {{ abs($stats['growth_percentage']) }}%
                        </span>
                        <small class="ms-1">vs bulan lalu</small>
                    </div>
                </div>
                <i class="fas fa-shopping-cart stats-icon"></i>
            </div>
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Revenue -->
    <div class="col-xl-3 col-md-6">
        <div class="stats-card success fade-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">Rp {{ number_format($stats['total_revenue_today']) }}</div>
                    <div class="stats-label">Revenue Hari Ini</div>
                    <div class="mt-2">
                        <small>Avg: Rp {{ number_format($stats['average_order_value']) }}</small>
                    </div>
                </div>
                <i class="fas fa-dollar-sign stats-icon"></i>
            </div>
        </div>
    </div>

    <!-- Active Warungs -->
    <div class="col-xl-3 col-md-6">
        <div class="stats-card info fade-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ $stats['active_warungs'] }}</div>
                    <div class="stats-label">Warung Aktif</div>
                    <div class="mt-2">
                        <small>dari {{ $stats['total_warungs'] }} total</small>
                    </div>
                </div>
                <i class="fas fa-store stats-icon"></i>
            </div>
        </div>
    </div>
    <!-- Pending Settlements -->
    <div class="col-xl-3 col-md-6">
        <div class="stats-card warning fade-in">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stats-number">{{ $stats['pending_settlements'] }}</div>
                    <div class="stats-label">Pending Settlements</div>
                    <div class="mt-2">
                        <small>Rp {{ number_format($stats['pending_settlements_amount']) }}</small>
                    </div>
                </div>
                <i class="fas fa-handshake stats-icon"></i>
            </div>
        </div>
    </div>
</div>
<!-- Secondary Stats Row -->
<div class="row g-4 mb-4">
    <!-- Total Users -->
    <div class="col-xl-2 col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-users text-primary mb-2" style="font-size: 2rem;"></i>
                <div class="h4 mb-0 text-primary">{{ number_format($stats['total_users']) }}</div>
                <div class="small text-muted">Total Users</div>
            </div>
        </div>
    </div>
            </div>
        </div>

    <!-- Monthly Orders -->
    <div class="col-xl-2 col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-shopping-bag text-success mb-2" style="font-size: 2rem;"></i>
                <div class="h4 mb-0 text-success">{{ number_format($stats['total_orders_month']) }}</div>
                <div class="small text-muted">Order Bulan Ini</div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue -->
    <div class="col-xl-2 col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-chart-line text-info mb-2" style="font-size: 2rem;"></i>
                <div class="h4 mb-0 text-info">{{ number_format($stats['total_revenue_month'] / 1000000, 1) }}M</div>
                <div class="small text-muted">Revenue Bulan Ini</div>
            </div>
        </div>
    </div>

    <!-- Failed Orders -->
    <div class="col-xl-2 col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-exclamation-triangle text-warning mb-2" style="font-size: 2rem;"></i>
                <div class="h4 mb-0 text-warning">{{ $stats['failed_orders'] }}</div>
                <div class="small text-muted">Order Gagal</div>
            </div>
        </div>
    </div>

    <!-- All Time Revenue -->
    <div class="col-xl-4 col-md-8">
        <div class="card text-center bg-gradient-primary text-white">
            <div class="card-body">
                <i class="fas fa-trophy mb-2" style="font-size: 2rem;"></i>
                <div class="h4 mb-0">Rp {{ number_format($stats['total_revenue_all_time'] / 1000000, 1) }}M</div>
                <div class="small">Total Revenue (All Time)</div>
                @if($stats['top_warung_today'])
                    <div class="small mt-2">
                        <i class="fas fa-crown"></i> Top: {{ $stats['top_warung_today']->nama_warung }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Profile Information and Charts Row -->
<div class="row g-4 mb-4">
    <!-- Profile Information -->
    <div class="col-xl-4 col-lg-5">
        @include('profile.partials.profile-info-card')
    </div>
    
    <!-- Quick Actions -->
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools text-primary me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users me-2"></i>Kelola Users
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.warungs') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-store me-2"></i>Kelola Warungs
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Kelola Orders
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.transactions') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-credit-card me-2"></i>Transaksi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Data Row -->
<div class="row g-4 mb-4">
    <!-- Revenue Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chart-area text-primary me-2"></i>
                    Revenue & Orders Trend
                </h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="downloadChart()">
                            <i class="fas fa-download me-2"></i>Download Chart
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="printChart()">
                            <i class="fas fa-print me-2"></i>Print Chart
                        </a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Distribution -->
    <div class="col-xl-4 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie text-info me-2"></i>
                    Status Distribusi Order
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="orderStatusChart"></canvas>
                </div>
                <div class="mt-3 text-center">
                    <div class="d-flex justify-content-center flex-wrap gap-3">
                        <span class="badge bg-success">
                            <i class="fas fa-circle me-1"></i> Paid ({{ $orderStatusData['paid'] }})
                        </span>
                        <span class="badge bg-warning">
                            <i class="fas fa-circle me-1"></i> Pending ({{ $orderStatusData['pending'] }})
                        </span>
                        <span class="badge bg-danger">
                            <i class="fas fa-circle me-1"></i> Failed ({{ $orderStatusData['failed'] }})
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Row -->
<div class="row g-4 mb-4">
    <!-- Top Performing Warungs -->
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy text-warning me-2"></i>
                    Top Performing Warungs
                </h5>
                <small class="text-muted">30 hari terakhir</small>
            </div>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Rank</th>
                                    <th>Warung</th>
                                    <th>Orders</th>
                                    <th>Revenue</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topWarungs as $index => $warung)
                                <tr>
                                    <td>
                                        <span class="badge badge-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark') }}">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <div class="avatar-initial bg-primary rounded-circle">
                                                    {{ substr($warung->nama_warung, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $warung->nama_warung }}</div>
                                                <small class="text-muted">{{ $warung->nama_pemilik }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $warung->total_orders }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">
                                            Rp {{ number_format($warung->total_revenue) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-{{ $index == 0 ? 'success' : 'primary' }}" 
                                                 style="width: {{ $topWarungs->isNotEmpty() ? ($warung->total_revenue / $topWarungs->first()->total_revenue) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="fas fa-info-circle me-2"></i>Belum ada data warung
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Recent Activities
                    </h6>
                </div>
                <div class="card-body">
                    <div class="activity-feed" style="max-height: 400px; overflow-y: auto;">
                        @forelse($recentActivities as $activity)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-{{ $activity['color'] }} rounded-circle p-2" style="width: 40px; height: 40px;">
                                    <i class="fas fa-{{ $activity['icon'] }} text-white" style="font-size: 14px;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="small fw-bold text-dark">
                                    {{ $activity['message'] }}
                                </div>
                                <div class="small text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $activity['time']->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>Belum ada aktivitas terbaru
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Warung Balances -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-wallet me-2"></i>Saldo Warung
                    </h6>
                    <a href="{{ route('admin.payouts') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-money-check-alt me-2"></i>Kelola Payout
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Warung</th>
                                    <th>Pemilik</th>
                                    <th>Saldo</th>
                                    <th>Bank</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($warungBalances as $warung)
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold">{{ $warung->nama_warung }}</div>
                                            <small class="text-muted">{{ $warung->lokasi }}</small>
                                        </td>
                                        <td>{{ $warung->nama_pemilik }}</td>
                                        <td>
                                            <div class="font-weight-bold">Rp {{ number_format($warung->balance) }}</div>
                                            @if($warung->wallet->pending_balance > 0)
                                                <small class="text-warning">Pending: Rp {{ number_format($warung->wallet->pending_balance) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $warung->rekening_bank }}<br>
                                            <small class="text-muted">{{ $warung->no_rekening }}</small>
                                        </td>
                                        <td>
                                            @if($warung->balance > 0)
                                                <button onclick="createPayout({{ $warung->id }}, '{{ $warung->nama_warung }}', {{ $warung->balance }})"
                                                        class="btn btn-success btn-sm">
                                                    <i class="fas fa-money-check-alt me-1"></i>Cairkan
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payout Modal -->
<div class="modal fade" id="payoutModal" tabindex="-1" aria-labelledby="payoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payoutModalLabel">
                    <i class="fas fa-money-check-alt me-2"></i>Buat Payout
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="payoutForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Warung</label>
                        <p id="warungName" class="text-dark"></p>
                    </div>
                    <div class="mb-3">
                        <label for="payoutAmount" class="form-label font-weight-bold">Jumlah</label>
                        <input type="number" name="amount" id="payoutAmount" min="10000" 
                               class="form-control" required>
                        <small class="form-text text-muted">Minimum Rp 10.000</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Buat Payout
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function createPayout(warungId, warungName, maxAmount) {
    document.getElementById('warungName').textContent = warungName;
    document.getElementById('payoutAmount').max = maxAmount;
    document.getElementById('payoutForm').action = `/admin/payouts/create/${warungId}`;
    
    // Show modal using Bootstrap
    var modal = new bootstrap.Modal(document.getElementById('payoutModal'));
    modal.show();
}

// Chart.js initialization
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('revenueChart').getContext('2d');
    var orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');

    // Revenue & Orders Trend chart
    var revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData->pluck('date')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($chartData->pluck('revenue')) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.2)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                fill: true,
            }, {
                label: 'Orders',
                data: {!! json_encode($chartData->pluck('orders')) !!},
                backgroundColor: 'rgba(28, 200, 138, 0.2)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 2,
                fill: true,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah'
                    }
                }
            },
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Enhanced Revenue & Orders Chart
    var revenueCtx = document.getElementById('revenueChart').getContext('2d');
    var chartData = @json($chartData);
    
    var revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: chartData.map(item => item.date),
            datasets: [
                {
                    label: 'Revenue (Rp)',
                    data: chartData.map(item => item.revenue),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    yAxisID: 'y',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Orders',
                    data: chartData.map(item => item.orders),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    yAxisID: 'y1',
                    tension: 0.3,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                            } else {
                                return context.dataset.label + ': ' + context.raw + ' orders';
                            }
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue (Rp)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Orders'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Enhanced Order Status Distribution chart
    var orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    var totalOrders = {{ $orderStatusData['paid'] + $orderStatusData['pending'] + $orderStatusData['failed'] }};
    
    var orderStatusChart = new Chart(orderStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Paid', 'Pending', 'Failed'],
            datasets: [{
                data: [{{ $orderStatusData['paid'] }}, {{ $orderStatusData['pending'] }}, {{ $orderStatusData['failed'] }}],
                backgroundColor: [
                    '#28a745', // Success green
                    '#ffc107', // Warning yellow  
                    '#dc3545'  // Danger red
                ],
                hoverBackgroundColor: [
                    '#218838',
                    '#e0a800', 
                    '#c82333'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    display: false, // We show custom legend below
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.raw;
                            var percentage = totalOrders > 0 ? Math.round((value / totalOrders) * 100) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Auto refresh data every 30 seconds
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            refreshDashboardData();
        }
    }, 30000);

    function refreshDashboardData() {
        // Add subtle loading indicator
        $('.card-body').addClass('opacity-75');
        
        // Simulated refresh - in real app, this would fetch new data
        setTimeout(function() {
            $('.card-body').removeClass('opacity-75');
            // Update timestamps
            updateTimestamps();
        }, 1000);
    }

    function updateTimestamps() {
        $('.activity-feed .small.text-muted').each(function() {
            var timeText = $(this).text();
            // Update relative time display
            if (timeText.includes('seconds ago')) {
                $(this).html('<i class="fas fa-clock me-1"></i>Just now');
            }
        });
    }

    // Enhanced interaction features
    $('.card').hover(
        function() {
            $(this).addClass('shadow-lg').removeClass('shadow');
        },
        function() {
            $(this).addClass('shadow').removeClass('shadow-lg');
        }
    );

    // Smooth scroll to sections
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        var target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
});

function downloadChart() {
    const canvas = document.getElementById('revenueChart');
    const url = canvas.toDataURL('image/png');
    const a = document.createElement('a');
    a.href = url;
    a.download = 'revenue-chart-' + new Date().toISOString().split('T')[0] + '.png';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

function printChart() {
    const printWindow = window.open('', '_blank');
    const canvas = document.getElementById('revenueChart');
    const chartImage = canvas.toDataURL('image/png');
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Revenue Chart - NganTeen Admin</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; margin: 20px; }
                    h1 { color: #333; }
                    img { max-width: 100%; height: auto; border: 1px solid #ddd; }
                    .print-info { margin: 20px 0; color: #666; }
                </style>
            </head>
            <body>
                <h1>Revenue & Orders Chart</h1>
                <div class="print-info">Generated on: ${new Date().toLocaleDateString('id-ID')}</div>
                <img src="${chartImage}" alt="Revenue Chart" />
                <div class="print-info">NganTeen Admin Dashboard</div>
            </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
}

// Real-time notifications (placeholder for future implementation)
function showNotification(message, type = 'info') {
    const notification = $(`
        <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999;">
            <i class="fas fa-info-circle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    $('body').append(notification);
    
    setTimeout(function() {
        notification.alert('close');
    }, 5000);
}

// Performance monitoring
window.addEventListener('load', function() {
    const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
    console.log('Dashboard loaded in ' + loadTime + 'ms');
    
    if (loadTime > 3000) {
        console.warn('Dashboard load time is high. Consider optimization.');
    }
});
</script>
@endsection
