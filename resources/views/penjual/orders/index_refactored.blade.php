@extends('layouts.penjual')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-gray-900">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>Kelola Pesanan
                    </h1>
                    <p class="text-muted mb-0">Kelola dan proses pesanan dari pelanggan</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="refreshOrders()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?status=pending">Pesanan Baru</a></li>
                            <li><a class="dropdown-item" href="?status=confirmed">Dikonfirmasi</a></li>
                            <li><a class="dropdown-item" href="?status=preparing">Sedang Disiapkan</a></li>
                            <li><a class="dropdown-item" href="?status=ready">Siap Diambil</a></li>
                            <li><a class="dropdown-item" href="?status=completed">Selesai</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('penjual.orders') }}">Semua Pesanan</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pesanan Baru</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $orders->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Sedang Proses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $orders->whereIn('status', ['confirmed', 'preparing'])->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cogs fa-2x text-gray-300"></i>
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
                                Selesai Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $orders->where('status', 'completed')->whereDate('created_at', today())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
    </div>

    <!-- Orders Table -->
    @if($orders->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Daftar Pesanan ({{ $orders->count() }} dari {{ $orders->total() }})
            </h6>
            <small class="text-muted">
                Halaman {{ $orders->currentPage() }} dari {{ $orders->lastPage() }}
            </small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="100">Order ID</th>
                            <th>Menu</th>
                            <th width="120">Pembeli</th>
                            <th width="80">Qty</th>
                            <th width="120">Total</th>
                            <th width="120">Status</th>
                            <th width="100">Tanggal</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <strong class="text-primary">#{{ $order->id }}</strong>
                                @if($order->globalOrder)
                                    <br><small class="text-muted">Global: #{{ $order->globalOrder->id }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($order->menu && $order->menu->gambar)
                                        <img src="{{ asset('storage/' . $order->menu->gambar) }}" 
                                             alt="{{ $order->menu->nama_menu }}" 
                                             class="rounded me-2"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $order->menu ? $order->menu->nama_menu : 'Menu Dihapus' }}</div>
                                        @if($order->notes)
                                            <small class="text-muted">{{ $order->notes }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($order->globalOrder && $order->globalOrder->buyer)
                                    <div class="fw-bold">{{ $order->globalOrder->buyer->name }}</div>
                                    <small class="text-muted">{{ $order->globalOrder->buyer->email }}</small>
                                @else
                                    <span class="text-muted">Data tidak tersedia</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary fs-6">{{ $order->quantity }}</span>
                            </td>
                            <td>
                                <strong class="text-success">
                                    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg-warning', 'Menunggu', 'fas fa-clock'],
                                        'confirmed' => ['bg-info', 'Dikonfirmasi', 'fas fa-check'],
                                        'preparing' => ['bg-primary', 'Disiapkan', 'fas fa-cogs'],
                                        'ready' => ['bg-success', 'Siap', 'fas fa-bell'],
                                        'completed' => ['bg-success', 'Selesai', 'fas fa-check-circle'],
                                        'cancelled' => ['bg-danger', 'Dibatalkan', 'fas fa-times-circle']
                                    ];
                                    $config = $statusConfig[$order->status] ?? ['bg-secondary', 'Unknown', 'fas fa-question'];
                                @endphp
                                <span class="badge {{ $config[0] }}">
                                    <i class="{{ $config[2] }} me-1"></i>{{ $config[1] }}
                                </span>
                            </td>
                            <td>
                                <div class="text-sm">
                                    {{ $order->created_at->format('d/m/Y') }}
                                    <br>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($order->status === 'pending')
                                    <div class="btn-group-vertical" role="group">
                                        <button class="btn btn-success btn-sm" 
                                                onclick="updateStatus({{ $order->id }}, 'confirmed')">
                                            <i class="fas fa-check me-1"></i>Terima
                                        </button>
                                    </div>
                                @elseif($order->status === 'confirmed')
                                    <button class="btn btn-primary btn-sm" 
                                            onclick="updateStatus({{ $order->id }}, 'preparing')">
                                        <i class="fas fa-cogs me-1"></i>Proses
                                    </button>
                                @elseif($order->status === 'preparing')
                                    <button class="btn btn-warning btn-sm" 
                                            onclick="updateStatus({{ $order->id }}, 'ready')">
                                        <i class="fas fa-bell me-1"></i>Siap
                                    </button>
                                @elseif($order->status === 'ready')
                                    <button class="btn btn-success btn-sm" 
                                            onclick="updateStatus({{ $order->id }}, 'completed')">
                                        <i class="fas fa-check-circle me-1"></i>Selesai
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

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="card shadow">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-4x text-muted"></i>
            </div>
            <h5 class="text-muted mb-3">Belum Ada Pesanan</h5>
            <p class="text-muted mb-4">
                Pesanan akan muncul di sini ketika pelanggan memesan menu dari warung Anda
            </p>
            <a href="{{ route('penjual.menu.index') }}" class="btn btn-primary">
                <i class="fas fa-utensils me-2"></i>Kelola Menu
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin mengubah status pesanan ini?</p>
                <small class="text-muted">Status akan diubah ke: <strong id="newStatusText"></strong></small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="statusForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" id="newStatus">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i>Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.text-xs {
    font-size: 0.7rem !important;
}
.btn-group-vertical .btn {
    margin-bottom: 2px;
}
</style>
@endpush

@push('scripts')
<script>
function updateStatus(orderId, newStatus) {
    const statusText = {
        'confirmed': 'Dikonfirmasi',
        'preparing': 'Sedang Disiapkan', 
        'ready': 'Siap Diambil',
        'completed': 'Selesai'
    };

    document.getElementById('newStatusText').textContent = statusText[newStatus];
    document.getElementById('newStatus').value = newStatus;
    document.getElementById('statusForm').action = `/penjual/orders/${orderId}/status`;
    
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

function refreshOrders() {
    window.location.reload();
}

// Auto refresh every 30 seconds for new orders
setInterval(function() {
    if (document.visibilityState === 'visible') {
        // Only refresh if on pending orders
        const urlParams = new URLSearchParams(window.location.search);
        if (!urlParams.get('status') || urlParams.get('status') === 'pending') {
            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(data => {
                // Update badge count only
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
                const newBadges = doc.querySelectorAll('.h5.mb-0.font-weight-bold.text-gray-800');
                const currentBadges = document.querySelectorAll('.h5.mb-0.font-weight-bold.text-gray-800');
                
                newBadges.forEach((badge, index) => {
                    if (currentBadges[index]) {
                        currentBadges[index].textContent = badge.textContent;
                    }
                });
            });
        }
    }
}, 30000);
</script>
@endpush
