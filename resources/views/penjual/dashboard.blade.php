@extends('layouts.penjual')

@section('content')
<div class="container">
    <!-- Professional Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h1 class="h2 mb-1" style="color: var(--text-primary);">
                        <i class="fas fa-tachometer-alt me-2" style="color: var(--primary);"></i>
                        Dashboard Seller
                    </h1>
                    <p class="text-muted mb-0">Kelola bisnis Anda dengan mudah dan efisien</p>
                </div>
                <div>
                    <button id="refreshStats" class="btn btn-accent">
                        <i class="fas fa-sync-alt me-2"></i>Refresh Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Professional Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stats-card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-utensils fa-2x" style="color: var(--primary);"></i>
                    </div>
                    <h5 class="card-title" style="color: var(--text-primary);">Total Menu</h5>
                    <h2 id="menuCount" class="mb-2" style="color: var(--primary);">{{ $menuCount ?? 0 }}</h2>
                    <p class="text-muted small mb-0">Menu aktif di toko Anda</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card warning h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-clock fa-2x" style="color: var(--warning);"></i>
                    </div>
                    <h5 class="card-title" style="color: var(--text-primary);">Pesanan Baru</h5>
                    <h2 id="newOrders" class="mb-2" style="color: var(--warning);">{{ $newOrders ?? 0 }}</h2>
                    <p class="text-muted small mb-0">Menunggu konfirmasi</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card success h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-money-bill-wave fa-2x" style="color: var(--success);"></i>
                    </div>
                    <h5 class="card-title" style="color: var(--text-primary);">Total Pendapatan</h5>
                    <h2 id="totalRevenue" class="mb-2" style="color: var(--success);">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h2>
                    <p class="text-muted small mb-0">Dari pesanan selesai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Professional Quick Actions -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0" style="color: var(--text-primary);">
                <i class="fas fa-bolt me-2" style="color: var(--accent);"></i>
                Aksi Cepat
            </h5>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Menu Baru
                </a>
                <a href="{{ route('penjual.orders.index') }}" class="btn btn-accent">
                    <i class="fas fa-shopping-bag me-2"></i>Kelola Pesanan
                </a>
                <a href="{{ route('penjual.menu.index') }}" class="btn btn-secondary">
                    <i class="fas fa-utensils me-2"></i>Daftar Menu
                </a>
            </div>
        </div>
    </div>

    <!-- Professional Recent Orders -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0" style="color: var(--text-primary);">
                <i class="fas fa-history me-2" style="color: var(--accent);"></i>
                Pesanan Terbaru
            </h5>
            <a href="{{ route('penjual.orders.index') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-right me-1"></i>Lihat Semua
            </a>
        </div>
                        <div class="card-body p-0">
                            @if(isset($recentOrders) && $recentOrders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Pelanggan</th>
                                                <th>Tanggal</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentOrders as $order)
                                                <tr>
                                                    <td>#{{ $order->id }}</td>
                                                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                                    <td>
                                                        @php
                                                            $statusClasses = [
                                                                'pending' => 'bg-warning',
                                                                'processing' => 'bg-info',
                                                                'siap_diambil' => 'bg-primary',
                                                                'selesai' => 'bg-success',
                                                                'batal' => 'bg-danger',
                                                                'cancelled' => '-secondary'
                                                            ];
                                                            $statusTexts = [
                                                                'pending' => 'Menunggu',
                                                                'processing' => 'Diproses',
                                                                'siap_diambil' => 'Siap Diambil',
                                                                'selesai' => 'Selesai',
                                                                'batal' => 'Dibatalkan',
                                                                'cancelled' => 'Dibatalkan'
                                                            ];
                                                        @endphp
                                                        <span class="badge {{ $statusClasses[$order->status] ?? 'bg-secondary' }}">
                                                            {{ $statusTexts[$order->status] ?? $order->status }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('penjual.orders.show', $order->id) }}" 
                                                           class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye me-1"></i>Lihat
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                                    <h6 class="text-muted">Belum ada pesanan terbaru</h6>
                                    <p class="text-muted small mb-0">Pesanan akan muncul di sini setelah pelanggan memesan</p>
                                </div>
                            @endif
                        </div>
                    </div>

    <!-- Professional Help Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card" style="border-left: 4px solid var(--accent);">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-2" style="color: var(--text-primary);">
                                <i class="fas fa-lightbulb me-2" style="color: var(--accent);"></i>
                                Tips untuk Meningkatkan Penjualan
                            </h6>
                            <p class="mb-0 text-muted small">
                                Pastikan foto menu menarik, deskripsi jelas, dan stok selalu tersedia untuk pengalaman pelanggan yang optimal.
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#helpModal">
                                <i class="fas fa-question-circle me-1"></i>Panduan Lengkap
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Professional Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary), var(--accent)); color: white;">
                <h5 class="modal-title" id="helpModalLabel">
                    <i class="fas fa-graduation-cap me-2"></i>Panduan Seller NganTeen
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3" style="color: var(--primary);">
                            <i class="fas fa-rocket me-2"></i>Panduan Memulai
                        </h6>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-plus-circle fa-lg me-3" style="color: var(--success);"></i>
                                    </div>
                                    <div>
                                        <strong>Tambah Menu</strong><br>
                                        <small class="text-muted">Gunakan tombol "Tambah Menu Baru" untuk menambahkan produk dengan foto menarik</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-shopping-bag fa-lg me-3" style="color: var(--accent);"></i>
                                    </div>
                                    <div>
                                        <strong>Kelola Pesanan</strong><br>
                                        <small class="text-muted">Monitor pesanan masuk dan update status secara real-time</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-chart-line fa-lg me-3" style="color: var(--warning);"></i>
                                    </div>
                                    <div>
                                        <strong>Analisis Penjualan</strong><br>
                                        <small class="text-muted">Pantau statistik dan pendapatan melalui dashboard</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3" style="color: var(--primary);">
                            <i class="fas fa-lightbulb me-2"></i>Tips Sukses
                        </h6>
                        <div class="alert alert-light border-start border-5" style="border-color: var(--accent) !important;">
                            <small>
                                <strong>Foto Berkualitas:</strong> Gunakan pencahayaan yang baik dan sudut menarik untuk foto menu<br><br>
                                <strong>Deskripsi Jelas:</strong> Jelaskan bahan, rasa, dan keunikan menu secara detail<br><br>
                                <strong>Stok Update:</strong> Selalu perbarui stok untuk menghindari kekecewaan pelanggan<br><br>
                                <strong>Respon Cepat:</strong> Konfirmasi pesanan dengan cepat untuk kepuasan pelanggan
                            </small>
                        </div>
                    </div>
                </div>
                <div class="mt-4 p-3 rounded" style="background-color: var(--background);">
                    <div class="text-center">
                        <i class="fas fa-info-circle fa-2x mb-2" style="color: var(--accent);"></i>
                        <p class="mb-0 small text-muted">
                            Butuh bantuan lebih lanjut? Hubungi tim support kami melalui WhatsApp atau email yang tersedia di footer
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Tutup
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-external-link-alt me-1"></i>Hubungi Support
                </button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    // Function to update dashboard stats
    function updateDashboardStats() {
        fetch('{{ route("penjual.dashboard.stats") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update menu count
            document.getElementById('menuCount').textContent = data.menuCount;
            
            // Update new orders
            document.getElementById('newOrders').textContent = data.newOrders;
            
            // Update total revenue
            document.getElementById('totalRevenue').textContent = data.formattedRevenue;
            
            // Show success message
            showToast('Data berhasil diperbarui', 'success');
        })
        .catch(error => {
            console.error('Error fetching stats:', error);
            showToast('Gagal memperbarui data', 'error');
        });
    }
    
    // Show toast notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.style.zIndex = '1100';
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', function () {
            document.body.removeChild(toast);
        });
    }
    
    // Update stats when refresh button is clicked
    document.getElementById('refreshStats').addEventListener('click', updateDashboardStats);
    
    // Update stats every 5 minutes (300000 ms)
    setInterval(updateDashboardStats, 300000);
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection