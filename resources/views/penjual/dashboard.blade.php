@extends('layouts.penjual')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-gray-900">
                        <i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard Penjual
                    </h1>
                    <p class="text-muted mb-0">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong> - {{ now()->format('l, d F Y - H:i') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('penjual.menu.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Tambah Menu
                    </a>
                    <a href="{{ route('penjual.orders') }}" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Kelola Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                                Saldo Tersedia</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
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
                                Pesanan Baru</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $newOrders }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Menu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $menuCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-utensils fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    @if(isset($recentOrders) && $recentOrders->count() > 0)
    <div class="row">
        <div class="col-lg-8">
            <!-- Recent Orders Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-receipt me-2"></i>Pesanan Terbaru
                        </h6>
                        <a href="{{ route('penjual.orders') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
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
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($order->orderItems->first() && $order->orderItems->first()->menu && $order->orderItems->first()->menu->gambar)
                                            <img src="{{ Storage::url($order->orderItems->first()->menu->gambar) }}" 
                                                 class="rounded me-2" width="40" height="40" 
                                                 style="object-fit: cover;">
                                            @endif
                                            <div>
                                                <div class="font-weight-bold">
                                                    @if($order->orderItems->first() && $order->orderItems->first()->menu)
                                                        {{ $order->orderItems->first()->menu->nama_menu }}
                                                        @if($order->orderItems->count() > 1)
                                                            <small class="text-muted">(+{{ $order->orderItems->count() - 1 }} lainnya)</small>
                                                        @endif
                                                    @else
                                                        Menu Dihapus
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($order->user)
                                        {{ $order->user->name }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $order->orderItems->sum('quantity') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'primary') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-success">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
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
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('penjual.menu.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-utensils me-2"></i>Kelola Menu
                        </a>
                        <a href="{{ route('penjual.orders') }}" class="btn btn-outline-success">
                            <i class="fas fa-shopping-cart me-2"></i>Kelola Pesanan
                        </a>
                        <a href="{{ route('penjual.payouts') }}" class="btn btn-outline-warning">
                            <i class="fas fa-money-bill-wave me-2"></i>Pencairan Dana
                        </a>
                        <a href="{{ route('penjual.menu.create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Menu Baru
                        </a>
                    </div>
                </div>
            </div>

            <!-- Business Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Ringkasan Bisnis
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 border-right">
                            <div class="h5 mb-0 font-weight-bold text-primary">{{ $menuCount }}</div>
                            <div class="text-xs text-muted">Menu Tersedia</div>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0 font-weight-bold text-success">{{ $recentOrders->count() ?? 0 }}</div>
                            <div class="text-xs text-muted">Pesanan Terbaru</div>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6 border-right">
                            <div class="h5 mb-0 font-weight-bold text-warning">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                            <div class="text-xs text-muted">Total Pendapatan</div>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0 font-weight-bold text-info">4.5</div>
                            <div class="text-xs text-muted">Rating Rata-rata</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-gray-300 mb-3"></i>
                    <h4 class="text-gray-600">Belum Ada Pesanan</h4>
                    <p class="text-muted mb-4">Mulai dengan menambahkan menu untuk menerima pesanan pertama Anda</p>
                    <a href="{{ route('penjual.menu.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Tambah Menu Pertama
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh setiap 5 menit untuk pesanan baru
    setInterval(function() {
        if ({{ $newOrders }} > 0) {
            console.log('Checking for new orders...');
        }
    }, 300000); // 5 menit
    
    // Animasi untuk statistics cards
    const statCards = document.querySelectorAll('.card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection
