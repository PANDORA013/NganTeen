@extends('layouts.penjual')

@section('styles')
<link href="{{ asset('css/professional-penjual.css') }}" rel="stylesheet">
<style>
    /* Professional Orders Page Styles */
    .orders-filters {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        border: 1px solid #e5e7eb;
        margin-bottom: 2rem;
    }
    
    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }
    
    .filter-label {
        font-weight: 600;
        color: #374151;
        margin-right: 0.5rem;
    }
    
    .status-filter {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .status-chip {
        padding: 0.5rem 1rem;
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .status-chip:hover {
        background: #e5e7eb;
        color: #1f2937;
        text-decoration: none;
    }
    
    .status-chip.active {
        background: #2563eb;
        border-color: #2563eb;
        color: white;
    }
    
    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .orders-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    
    .orders-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    
    .order-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }
    
    .order-header {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .order-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .order-id {
        font-weight: 700;
        color: #1f2937;
        font-size: 1.125rem;
    }
    
    .order-time {
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    .order-status-badge {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-confirmed {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .status-preparing {
        background: #fde68a;
        color: #b45309;
    }
    
    .status-ready {
        background: #a7f3d0;
        color: #047857;
    }
    
    .status-completed {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .order-body {
        padding: 1.5rem;
    }
    
    .order-details {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1.5rem;
        align-items: start;
    }
    
    .order-items {
        flex: 1;
    }
    
    .order-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .order-item:last-child {
        border-bottom: none;
    }
    
    .item-image {
        width: 4rem;
        height: 4rem;
        background: #f3f4f6;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #9ca3af;
        flex-shrink: 0;
    }
    
    .item-details {
        flex: 1;
    }
    
    .item-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .item-description {
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    .item-quantity {
        font-weight: 600;
        color: #374151;
        font-size: 1.125rem;
    }
    
    .item-price {
        font-weight: 700;
        color: #1f2937;
        text-align: right;
    }
    
    .order-summary {
        background: #f8fafc;
        border-radius: 0.75rem;
        padding: 1.5rem;
        min-width: 280px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
    }
    
    .summary-row.total {
        border-top: 1px solid #e5e7eb;
        margin-top: 0.5rem;
        padding-top: 1rem;
        font-weight: 700;
        font-size: 1.125rem;
    }
    
    .customer-info {
        background: #f8fafc;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .customer-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .customer-contact {
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    .order-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        padding: 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
    }
    
    .order-notes {
        background: #fffbeb;
        border: 1px solid #fcd34d;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .notes-title {
        font-weight: 600;
        color: #92400e;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .notes-content {
        color: #b45309;
        font-size: 0.875rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
    }
    
    .empty-icon {
        width: 4rem;
        height: 4rem;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #9ca3af;
        margin: 0 auto 1.5rem;
    }
    
    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    
    .empty-description {
        color: #6b7280;
        margin-bottom: 1.5rem;
    }
    
    .stats-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-item {
        background: white;
        border-radius: 0.75rem;
        padding: 1.25rem;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        border: 1px solid #e5e7eb;
        text-align: center;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .refresh-btn {
        animation: none;
    }
    
    .refresh-btn.spinning {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
        .orders-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }
        
        .orders-actions {
            justify-content: stretch;
        }
        
        .orders-actions > * {
            flex: 1;
        }
        
        .order-details {
            grid-template-columns: 1fr;
        }
        
        .order-summary {
            min-width: auto;
        }
        
        .filter-group {
            flex-direction: column;
            align-items: stretch;
        }
        
        .status-filter {
            justify-content: center;
        }
        
        .stats-overview {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Professional Page Header -->
    <div class="professional-page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1><i class="fas fa-shopping-cart me-3"></i>Kelola Pesanan</h1>
                    <p class="mb-0">Kelola dan proses pesanan dari pelanggan dengan efisien</p>
                    <nav aria-label="breadcrumb" class="mt-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('penjual.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Pesanan</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="text-white">
                        <div class="h4 mb-0">{{ $orderStats['total_orders'] ?? 0 }}</div>
                        <small>Total Pesanan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Professional Alerts -->
    @if(session('success'))
    <div class="professional-alert professional-alert-success">
        <i class="fas fa-check-circle"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="professional-alert professional-alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    <!-- Professional Statistics Overview -->
    @if(isset($orderStats))
    <div class="stats-overview professional-fade-in">
        <div class="stat-item">
            <div class="stat-value">{{ $orderStats['pending_orders'] ?? 0 }}</div>
            <div class="stat-label">Menunggu Konfirmasi</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $orderStats['processing_orders'] ?? 0 }}</div>
            <div class="stat-label">Sedang Diproses</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $orderStats['completed_today'] ?? 0 }}</div>
            <div class="stat-label">Selesai Hari Ini</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">Rp {{ number_format($orderStats['revenue_today'] ?? 0, 0, ',', '.') }}</div>
            <div class="stat-label">Pendapatan Hari Ini</div>
        </div>
    </div>
    @endif

    <!-- Professional Filters -->
    <div class="orders-filters professional-slide-up">
        <div class="filter-group">
            <span class="filter-label">Filter Status:</span>
            <div class="status-filter">
                <a href="{{ route('penjual.orders') }}" class="status-chip {{ !request('status') ? 'active' : '' }}">
                    Semua
                </a>
                <a href="{{ route('penjual.orders', ['status' => 'pending']) }}" class="status-chip {{ request('status') === 'pending' ? 'active' : '' }}">
                    Pending
                </a>
                <a href="{{ route('penjual.orders', ['status' => 'confirmed']) }}" class="status-chip {{ request('status') === 'confirmed' ? 'active' : '' }}">
                    Dikonfirmasi
                </a>
                <a href="{{ route('penjual.orders', ['status' => 'preparing']) }}" class="status-chip {{ request('status') === 'preparing' ? 'active' : '' }}">
                    Disiapkan
                </a>
                <a href="{{ route('penjual.orders', ['status' => 'ready']) }}" class="status-chip {{ request('status') === 'ready' ? 'active' : '' }}">
                    Siap
                </a>
                <a href="{{ route('penjual.orders', ['status' => 'completed']) }}" class="status-chip {{ request('status') === 'completed' ? 'active' : '' }}">
                    Selesai
                </a>
            </div>
        </div>
    </div>

    <!-- Professional Orders Header -->
    <div class="orders-header">
        <h2 class="orders-title">
            @if(request('status'))
                Pesanan {{ ucfirst(request('status')) }}
            @else
                Semua Pesanan
            @endif
        </h2>
        <div class="orders-actions">
            <button class="btn btn-professional-outline" onclick="refreshOrders()" id="refreshBtn">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
            <div class="dropdown">
                <button class="btn btn-professional-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-2"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv me-2"></i>Export CSV</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>Export PDF</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Professional Orders List -->
    @if($orders->count() > 0)
        @foreach($orders as $order)
        <div class="order-card professional-fade-in">
            <div class="order-header">
                <div class="order-info">
                    <div class="order-id">Pesanan #{{ $order->id }}</div>
                    <div class="order-time">
                        <i class="fas fa-clock me-1"></i>{{ $order->created_at->diffForHumans() }}
                    </div>
                </div>
                <div class="order-status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </div>
            </div>

            <div class="order-body">
                <div class="order-details">
                    <div class="order-items">
                        <div class="order-item">
                            <div class="item-image">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="item-details">
                                <div class="item-name">{{ $order->menu->nama_menu ?? 'Menu Dihapus' }}</div>
                                <div class="item-description">
                                    @if($order->menu)
                                        {{ Str::limit($order->menu->deskripsi, 100) }}
                                    @else
                                        Menu sudah tidak tersedia
                                    @endif
                                </div>
                            </div>
                            <div class="item-quantity">{{ $order->quantity }}x</div>
                            <div class="item-price">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Quantity:</span>
                            <span>{{ $order->quantity }} item</span>
                        </div>
                        <div class="summary-row">
                            <span>Harga per item:</span>
                            <span>Rp {{ number_format($order->subtotal / $order->quantity, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>

                        <div class="customer-info">
                            <div class="customer-name">
                                <i class="fas fa-user me-2"></i>{{ $order->globalOrder->buyer->name ?? 'Customer' }}
                            </div>
                            @if($order->globalOrder && $order->globalOrder->buyer)
                            <div class="customer-contact">
                                <i class="fas fa-envelope me-2"></i>{{ $order->globalOrder->buyer->email }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($order->notes)
                <div class="order-notes">
                    <div class="notes-title">
                        <i class="fas fa-sticky-note"></i>Catatan Pesanan
                    </div>
                    <div class="notes-content">{{ $order->notes }}</div>
                </div>
                @endif
            </div>

            @if($order->status !== 'completed' && $order->status !== 'cancelled')
            <div class="order-actions">
                @if($order->status === 'pending')
                <form action="{{ route('penjual.orders.update-status', $order) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="btn btn-professional-success">
                        <i class="fas fa-check me-2"></i>Konfirmasi
                    </button>
                </form>
                @elseif($order->status === 'confirmed')
                <form action="{{ route('penjual.orders.update-status', $order) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="preparing">
                    <button type="submit" class="btn btn-professional-warning">
                        <i class="fas fa-clock me-2"></i>Mulai Siapkan
                    </button>
                </form>
                @elseif($order->status === 'preparing')
                <form action="{{ route('penjual.orders.update-status', $order) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="ready">
                    <button type="submit" class="btn btn-professional-success">
                        <i class="fas fa-bell me-2"></i>Siap Diambil
                    </button>
                </form>
                @elseif($order->status === 'ready')
                <form action="{{ route('penjual.orders.update-status', $order) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn btn-professional-primary">
                        <i class="fas fa-check-double me-2"></i>Selesaikan
                    </button>
                </form>
                @endif
                
                <button type="button" class="btn btn-professional-outline" onclick="showOrderDetails({{ $order->id }})">
                    <i class="fas fa-eye me-2"></i>Detail
                </button>
            </div>
            @endif
        </div>
        @endforeach

        <!-- Professional Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->withQueryString()->links() }}
        </div>
    @else
        <!-- Professional Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="empty-title">Belum Ada Pesanan</div>
            <div class="empty-description">
                @if(request('status'))
                    Tidak ada pesanan dengan status "{{ request('status') }}" saat ini.
                @else
                    Belum ada pesanan masuk. Pesanan akan muncul di sini setelah pelanggan memesan.
                @endif
            </div>
            <a href="{{ route('penjual.dashboard') }}" class="btn btn-professional-primary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    @endif
</div>

<!-- Professional Loading Overlay -->
<div id="loadingOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5); z-index: 9999;">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="text-center text-white">
            <div class="professional-loading mb-3"></div>
            <div>Memuat pesanan...</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Professional auto-refresh functionality
    let autoRefreshInterval;
    
    function refreshOrders() {
        const refreshBtn = document.getElementById('refreshBtn');
        const icon = refreshBtn.querySelector('i');
        
        // Add spinning animation
        icon.classList.add('spinning');
        refreshBtn.disabled = true;
        
        // Show loading overlay
        document.getElementById('loadingOverlay').classList.remove('d-none');
        
        // Reload page after short delay to show loading state
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }
    
    function startAutoRefresh() {
        autoRefreshInterval = setInterval(() => {
            // Only auto-refresh if no status changes are pending
            if (!document.querySelector('form button[disabled]')) {
                refreshOrders();
            }
        }, 60000); // Refresh every minute
    }
    
    // Make refreshOrders available globally
    window.refreshOrders = refreshOrders;
    
    // Start auto-refresh
    startAutoRefresh();
    
    // Stop auto-refresh when page is hidden
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            clearInterval(autoRefreshInterval);
        } else {
            startAutoRefresh();
        }
    });
    
    // Professional form submissions with loading states
    document.querySelectorAll('form button[type="submit"]').forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('form');
            const icon = this.querySelector('i');
            
            // Add loading state
            this.disabled = true;
            if (icon) {
                icon.className = 'fas fa-spinner fa-spin me-2';
            }
            this.innerHTML = this.innerHTML.replace(/\w+$/, 'Memproses...');
            
            // Submit form
            setTimeout(() => form.submit(), 500);
        });
    });
    
    // Professional status update notifications
    if (window.Echo) {
        window.Echo.private(`warung.{{ Auth::user()->warung->id ?? 0 }}`)
            .listen('OrderStatusUpdated', (e) => {
                // Show notification
                showNotification('Pesanan #' + e.order.id + ' telah diperbarui', 'success');
                
                // Auto-refresh after 2 seconds
                setTimeout(() => {
                    refreshOrders();
                }, 2000);
            });
    }
    
    function showNotification(message, type = 'info') {
        // Create professional notification
        const notification = document.createElement('div');
        notification.className = `professional-alert professional-alert-${type} position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 10000; max-width: 400px;';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
            <div>${message}</div>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }
    
    // Professional keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+R or F5 - Refresh orders
        if ((e.ctrlKey && e.key === 'r') || e.key === 'F5') {
            e.preventDefault();
            refreshOrders();
        }
        
        // Escape - Clear filters
        if (e.key === 'Escape') {
            window.location.href = '{{ route("penjual.orders") }}';
        }
    });
});

function showOrderDetails(orderId) {
    // Professional modal implementation would go here
    console.log('Show order details for order:', orderId);
}
</script>
@endsection
