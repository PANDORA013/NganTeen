@extends('layouts.admin')

@section('title', 'Analytics & Reports')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-gray-900">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Analytics & Reports
                    </h1>
                    <p class="text-muted mb-0">Monitor performa platform dan analisis data pengguna</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-2"></i>Export Data
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Export Excel</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>Export PDF</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($analytics['overview']['total_users']) }}</div>
                            <div class="text-xs text-muted">
                                <i class="fas fa-users me-1"></i>
                                {{ $analytics['overview']['total_pembeli'] }} Pembeli, {{ $analytics['overview']['total_penjual'] }} Penjual
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($analytics['overview']['total_revenue'], 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-muted">
                                <i class="fas fa-money-bill-wave me-1"></i>
                                From {{ number_format($analytics['overview']['total_orders']) }} orders
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Active Menus
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($analytics['overview']['total_menus']) }}</div>
                            <div class="text-xs text-muted">
                                <i class="fas fa-store me-1"></i>
                                {{ $analytics['overview']['active_warungs'] }} Active Warungs
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-utensils fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($analytics['overview']['pending_orders']) }}</div>
                            <div class="text-xs text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Requires attention
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-area me-2"></i>Revenue Trend (30 Days)
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            Last 30 Days
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                            <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                            <li><a class="dropdown-item" href="#">Last 90 Days</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-chart-pie me-2"></i>User Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="userDistributionChart" width="100%" height="200"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Pembeli</span>
                            <span class="font-weight-bold">{{ $analytics['overview']['total_pembeli'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Penjual</span>
                            <span class="font-weight-bold">{{ $analytics['overview']['total_penjual'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Admin</span>
                            <span class="font-weight-bold">{{ $analytics['overview']['total_users'] - $analytics['overview']['total_pembeli'] - $analytics['overview']['total_penjual'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Menus & Recent Activities -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-trophy me-2"></i>Popular Menus
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Ranking</th>
                                    <th>Menu</th>
                                    <th>Warung</th>
                                    <th>Total Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analytics['popular_menus'] as $index => $menu)
                                <tr>
                                    <td>
                                        @if($index < 3)
                                            <span class="badge badge-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark') }}">
                                                #{{ $index + 1 }}
                                            </span>
                                        @else
                                            <span class="text-muted">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold">{{ $menu->nama_menu }}</td>
                                    <td>{{ $menu->nama_warung }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ number_format($menu->total_sold) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-clock me-2"></i>Recent Activities
                    </h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @foreach($analytics['recent_activities'] as $activity)
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            @if($activity['type'] == 'order')
                                <div class="bg-success rounded-circle p-2">
                                    <i class="fas fa-shopping-cart text-white"></i>
                                </div>
                            @else
                                <div class="bg-info rounded-circle p-2">
                                    <i class="fas fa-user-plus text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="small font-weight-bold">{{ $activity['message'] }}</div>
                            @if(isset($activity['amount']))
                                <div class="small text-success">Rp {{ number_format($activity['amount'], 0, ',', '.') }}</div>
                            @endif
                            <div class="small text-muted">{{ $activity['created_at']->diffForHumans() }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-link me-2"></i>Detailed Analytics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.analytics.user-registrations') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus me-2"></i>User Registrations
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.analytics.order-analytics') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-shopping-cart me-2"></i>Order Analytics
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.analytics.revenue-analytics') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-chart-line me-2"></i>Revenue Analytics
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-users me-2"></i>User Management
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30'],
            datasets: [{
                label: 'Revenue (Rp)',
                data: [{{ implode(',', array_map(function($item) { return $item['revenue'] ?? 0; }, $analytics['recent_activities']->take(30)->toArray())) }}],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
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

    // User Distribution Chart
    const userCtx = document.getElementById('userDistributionChart').getContext('2d');
    new Chart(userCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pembeli', 'Penjual', 'Admin'],
            datasets: [{
                data: [
                    {{ $analytics['overview']['total_pembeli'] }}, 
                    {{ $analytics['overview']['total_penjual'] }}, 
                    {{ $analytics['overview']['total_users'] - $analytics['overview']['total_pembeli'] - $analytics['overview']['total_penjual'] }}
                ],
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(255, 99, 132)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endsection
