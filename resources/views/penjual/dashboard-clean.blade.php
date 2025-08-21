@extends('layouts.app')

@section('content')
<!-- Professional Page Header -->
<div class="professional-page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1><i class="fas fa-tachometer-alt me-2"></i>Dashboard Penjual</h1>
                <p class="mb-2">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong></p>
                <small class="opacity-75">
                    <i class="fas fa-clock me-1"></i>{{ now()->format('l, d F Y - H:i') }}
                </small>
            </div>
            <div class="col-lg-4 text-end">
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('penjual.menu.create') }}" class="btn btn-professional btn-professional-success">
                        <i class="fas fa-plus me-2"></i>Tambah Menu
                    </a>
                    <a href="{{ route('penjual.orders') }}" class="btn btn-professional btn-professional-outline">
                        <i class="fas fa-shopping-bag me-2"></i>Kelola Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Financial Overview Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-3"><i class="fas fa-chart-line me-2 text-success"></i>Ringkasan Keuangan</h3>
        </div>
        <div class="col-md-3 mb-3">
            <div class="professional-stat-card stat-success">
                <div class="professional-stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="professional-stat-label">Total Pendapatan</div>
                <div class="professional-stat-change positive">
                    <i class="fas fa-arrow-up me-1"></i>Dari {{ $menuCount }} menu
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="professional-stat-card stat-info">
                <div class="professional-stat-value">Rp {{ $totalRevenue > 0 ? number_format($totalRevenue, 0, ',', '.') : '0' }}</div>
                <div class="professional-stat-label">Saldo Tersedia</div>
                <div class="professional-stat-change positive">
                    <i class="fas fa-wallet me-1"></i>Siap dicairkan
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="professional-stat-card">
                <div class="professional-stat-value">{{ $newOrders }}</div>
                <div class="professional-stat-label">Pesanan Baru</div>
                <div class="professional-stat-change">
                    <i class="fas fa-shopping-cart me-1"></i>Perlu diproses
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="professional-stat-card stat-warning">
                <div class="professional-stat-value">{{ $menuCount }}</div>
                <div class="professional-stat-label">Total Menu</div>
                <div class="professional-stat-change">
                    <i class="fas fa-utensils me-1"></i>Menu aktif
                </div>
            </div>
        </div>
    </div>

    <!-- Business Operations Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-3"><i class="fas fa-cogs me-2 text-primary"></i>Operasional Bisnis</h3>
        </div>
    </div>

    <!-- Recent Orders -->
    @if(isset($orders) && $orders->count() > 0)
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="professional-card">
                <div class="professional-card-header">
                    <h5><i class="fas fa-receipt me-2"></i>Pesanan Terbaru</h5>
                    <a href="{{ route('penjual.orders') }}" class="btn btn-professional btn-professional-outline btn-sm">
                        Lihat Semua
                    </a>
                </div>
                <div class="professional-card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Pelanggan</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders->take(5) as $order)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($order->menu && $order->menu->gambar)
                                            <img src="{{ Storage::url($order->menu->gambar) }}" 
                                                 class="rounded me-2" width="40" height="40" 
                                                 style="object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $order->menu->nama_menu ?? 'Menu Dihapus' }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($order->globalOrder && $order->globalOrder->buyer)
                                        {{ $order->globalOrder->buyer->name }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $order->quantity }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'primary') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong>
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
            <!-- Quick Actions -->
            <div class="professional-card">
                <div class="professional-card-header">
                    <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="professional-card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('penjual.menu.index') }}" class="btn btn-professional btn-professional-outline">
                            <i class="fas fa-utensils me-2"></i>Kelola Menu
                        </a>
                        <a href="{{ route('penjual.orders') }}" class="btn btn-professional btn-professional-outline">
                            <i class="fas fa-shopping-cart me-2"></i>Kelola Pesanan
                        </a>
                        <a href="{{ route('penjual.payouts') }}" class="btn btn-professional btn-professional-outline">
                            <i class="fas fa-money-bill-wave me-2"></i>Pencairan Dana
                        </a>
                        <a href="{{ route('penjual.menu.create') }}" class="btn btn-professional btn-professional-success">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Menu Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="professional-card text-center py-5">
                <div class="professional-card-body">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h4>Belum Ada Pesanan</h4>
                    <p class="text-muted mb-4">Mulai dengan menambahkan menu untuk menerima pesanan pertama Anda</p>
                    <a href="{{ route('penjual.menu.create') }}" class="btn btn-professional btn-professional-success">
                        <i class="fas fa-plus me-2"></i>Tambah Menu Pertama
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Business Summary -->
    <div class="row">
        <div class="col-12">
            <div class="professional-card">
                <div class="professional-card-header">
                    <h5><i class="fas fa-chart-pie me-2"></i>Ringkasan Bisnis</h5>
                </div>
                <div class="professional-card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $menuCount }}</h4>
                                <small class="text-muted">Menu Tersedia</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-success">{{ $orders->count() ?? 0 }}</h4>
                                <small class="text-muted">Total Pesanan</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-warning">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                                <small class="text-muted">Total Pendapatan</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info">4.5</h4>
                            <small class="text-muted">Rating Rata-rata</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('css/professional-penjual.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh setiap 5 menit untuk pesanan baru
    setInterval(function() {
        if ({{ $newOrders }} > 0) {
            console.log('Checking for new orders...');
        }
    }, 300000); // 5 menit
    
    // Animasi untuk statistics cards
    const statCards = document.querySelectorAll('.professional-stat-card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('professional-fade-in');
        }, index * 100);
    });
});
</script>
@endpush
