@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Dashboard Admin</h1>
        <p class="page-subtitle">Selamat datang kembali, {{ Auth::user()->name }}! Berikut ringkasan platform NganTeen hari ini.</p>
    </div>
    <div class="page-actions">
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-plus me-2"></i>Aksi Cepat
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('admin.content.testimonials') }}"><i class="fas fa-comment me-2"></i>Tambah Testimonial</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.content.help-center') }}"><i class="fas fa-question me-2"></i>Tambah Artikel Bantuan</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="fas fa-user-plus me-2"></i>Kelola User</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('admin.content.website-settings') }}"><i class="fas fa-cog me-2"></i>Pengaturan Website</a></li>
            </ul>
        </div>
        <button type="button" class="btn btn-outline-primary" onclick="location.reload()">
            <i class="fas fa-sync-alt me-2"></i>Refresh Data
        </button>
    </div>
</div>

<!-- Stats Overview -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #667eea; --card-bg-to: #764ba2;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ $stats['total_users'] ?? 0 }}</div>
                            <div class="stats-label">Total Users</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i>
                            +{{ $stats['new_users_today'] ?? 0 }} hari ini
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #f093fb; --card-bg-to: #f5576c;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ $stats['total_orders'] ?? 0 }}</div>
                            <div class="stats-label">Total Pesanan</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i>
                            +{{ $stats['new_orders_today'] ?? 0 }} hari ini
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #4facfe; --card-bg-to: #00f2fe;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ $stats['total_warungs'] ?? 0 }}</div>
                            <div class="stats-label">Total Warung</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-store"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i>
                            +{{ $stats['new_warungs_today'] ?? 0 }} hari ini
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #43e97b; --card-bg-to: #38f9d7;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</div>
                            <div class="stats-label">Total Revenue</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i>
                            +Rp {{ number_format($stats['revenue_today'] ?? 0, 0, ',', '.') }} hari ini
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Analytics -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-area text-primary me-2"></i>
                        Grafik Pendapatan 7 Hari Terakhir
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary active" data-period="7days">7 Hari</button>
                        <button type="button" class="btn btn-outline-primary" data-period="30days">30 Hari</button>
                        <button type="button" class="btn btn-outline-primary" data-period="90days">90 Hari</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie text-success me-2"></i>
                    Distribusi User
                </h5>
            </div>
            <div class="card-body">
                <canvas id="userDistributionChart" height="200"></canvas>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Pembeli</span>
                        <span class="fw-bold">{{ $stats['buyers_count'] ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Penjual</span>
                        <span class="fw-bold">{{ $stats['sellers_count'] ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Admin</span>
                        <span class="fw-bold">{{ $stats['admins_count'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities & Quick Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-6">
        <div class="admin-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock text-info me-2"></i>
                        Aktivitas Terbaru
                    </h5>
                    <a href="{{ route('admin.analytics.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @forelse($recent_activities ?? [] as $activity)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">{{ $activity['title'] ?? 'Aktivitas' }}</h6>
                            <p class="timeline-text text-muted">{{ $activity['description'] ?? 'Deskripsi aktivitas' }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $activity['time'] ?? 'Beberapa menit yang lalu' }}
                            </small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada aktivitas terbaru</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar text-warning me-2"></i>
                    Statistik Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="quick-stat">
                            <div class="quick-stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="quick-stat-content">
                                <div class="quick-stat-number">{{ $stats['pending_orders'] ?? 0 }}</div>
                                <div class="quick-stat-label">Pesanan Pending</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="quick-stat">
                            <div class="quick-stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="quick-stat-content">
                                <div class="quick-stat-number">{{ $stats['completed_orders'] ?? 0 }}</div>
                                <div class="quick-stat-label">Pesanan Selesai</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="quick-stat">
                            <div class="quick-stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="quick-stat-content">
                                <div class="quick-stat-number">{{ $stats['unread_messages'] ?? 0 }}</div>
                                <div class="quick-stat-label">Pesan Belum Dibaca</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="quick-stat">
                            <div class="quick-stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="quick-stat-content">
                                <div class="quick-stat-number">{{ $stats['issues_count'] ?? 0 }}</div>
                                <div class="quick-stat-label">Isu Perlu Perhatian</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Performers & Recent Orders -->
<div class="row g-4">
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-alt text-primary me-2"></i>
                        Pesanan Terbaru
                    </h5>
                    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua Pesanan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Warung</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_orders ?? [] as $order)
                            <tr>
                                <td><span class="badge bg-light text-dark">#{{ $order['id'] ?? 'N/A' }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order['customer'] ?? 'User') }}&background=667eea&color=fff" alt="Avatar" class="rounded-circle">
                                        </div>
                                        <span>{{ $order['customer'] ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td>{{ $order['warung'] ?? 'Unknown' }}</td>
                                <td><strong>Rp {{ number_format($order['total'] ?? 0, 0, ',', '.') }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $order['status_color'] ?? 'secondary' }}">
                                        {{ $order['status'] ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td>{{ $order['time'] ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-success" title="Update Status">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada pesanan terbaru</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy text-warning me-2"></i>
                    Top Performer
                </h5>
            </div>
            <div class="card-body">
                <div class="top-performers">
                    @forelse($top_warungs ?? [] as $index => $warung)
                    <div class="performer-item d-flex align-items-center mb-3 {{ $index === 0 ? 'champion' : '' }}">
                        <div class="performer-rank">
                            @if($index === 0)
                                <i class="fas fa-crown text-warning"></i>
                            @else
                                <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <div class="performer-info ms-3 flex-grow-1">
                            <h6 class="performer-name mb-1">{{ $warung['name'] ?? 'Unknown Warung' }}</h6>
                            <small class="text-muted">{{ $warung['orders_count'] ?? 0 }} pesanan • Rp {{ number_format($warung['revenue'] ?? 0, 0, ',', '.') }}</small>
                        </div>
                        <div class="performer-score">
                            <span class="badge bg-success">{{ $warung['rating'] ?? '0.0' }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Data performer akan muncul setelah ada transaksi</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #007bff;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.timeline-text {
    margin-bottom: 5px;
    font-size: 0.85rem;
}

.quick-stat {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.quick-stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.quick-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.quick-stat-number {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
}

.quick-stat-label {
    font-size: 0.75rem;
    color: #64748b;
}

.performer-item {
    padding: 10px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.performer-item.champion {
    background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
    border: 2px solid #fdcb6e;
}

.performer-item:hover {
    background: #f8f9fa;
}

.performer-rank {
    width: 30px;
    text-align: center;
}

.avatar {
    width: 32px;
    height: 32px;
}

.avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chart_data['revenue_labels'] ?? ['Hari 1', 'Hari 2', 'Hari 3', 'Hari 4', 'Hari 5', 'Hari 6', 'Hari 7']) !!},
            datasets: [{
                label: 'Revenue (Rp)',
                data: {!! json_encode($chart_data['revenue_data'] ?? [100000, 150000, 120000, 180000, 200000, 170000, 250000]) !!},
                borderColor: '#667eea',
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
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // User Distribution Chart
    const userCtx = document.getElementById('userDistributionChart').getContext('2d');
    const userChart = new Chart(userCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pembeli', 'Penjual', 'Admin'],
            datasets: [{
                data: [
                    {{ $stats['buyers_count'] ?? 80 }}, 
                    {{ $stats['sellers_count'] ?? 15 }}, 
                    {{ $stats['admins_count'] ?? 5 }}
                ],
                backgroundColor: ['#667eea', '#f093fb', '#43e97b'],
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

    // Auto refresh data every 5 minutes
    setInterval(function() {
        location.reload();
    }, 300000);

    // Period change handlers
    $('[data-period]').click(function() {
        $(this).addClass('active').siblings().removeClass('active');
        // Implement period change logic here
    });
});
</script>
@endpush
