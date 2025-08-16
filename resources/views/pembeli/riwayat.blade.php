@extends('layouts.pembeli')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header rounded-lg mb-4">
                <div class="container">
                    <h1 class="h3 mb-2">
                        <i class="fas fa-history me-2"></i>Riwayat Pesanan
                    </h1>
                    <p class="mb-0 text-white-50">Pantau status dan riwayat pesanan Anda</p>
                </div>
            </div>
        </div>
    </div>

    @forelse($orders as $order)
        <div class="card shadow-green mb-4 menu-item">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-1">
                            <i class="fas fa-receipt me-2"></i>Pesanan #{{ $order->id }}
                        </h5>
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i>{{ $order->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="d-flex align-items-center justify-content-md-end gap-2">
                            @php
                                $statusConfig = match($order->status) {
                                    'pending' => ['color' => 'warning', 'icon' => 'clock', 'text' => 'Menunggu Konfirmasi'],
                                    'diproses' => ['color' => 'info', 'icon' => 'cog fa-spin', 'text' => 'Sedang Diproses'],
                                    'selesai' => ['color' => 'success', 'icon' => 'check-circle', 'text' => 'Selesai'],
                                    'dibatalkan' => ['color' => 'danger', 'icon' => 'times-circle', 'text' => 'Dibatalkan'],
                                    default => ['color' => 'secondary', 'icon' => 'question-circle', 'text' => ucfirst($order->status)]
                                };
                            @endphp
                            <span class="badge bg-{{ $statusConfig['color'] }} px-3 py-2">
                                <i class="fas fa-{{ $statusConfig['icon'] }} me-1"></i>{{ $statusConfig['text'] }}
                            </span>
                            @if($order->canBeCancelled())
                                <form action="{{ route('order.cancel', $order) }}" method="POST" class="d-inline cancel-form">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-outline-danger btn-sm"
                                            title="Batalkan Pesanan">
                                        <i class="fas fa-times me-1"></i>Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Order Items -->
                <div class="order-items mb-4">
                    @foreach($order->items as $item)
                        <div class="order-item d-flex align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="item-image me-3">
                                @if($item->menu->gambar)
                                    <img src="{{ Storage::url($item->menu->gambar) }}" 
                                         alt="{{ $item->menu->nama_menu }}" 
                                         class="rounded-lg shadow-sm" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded-lg shadow-sm" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-utensils text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="item-details flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $item->menu->nama_menu }}</h6>
                                <div class="menu-warung mb-1">{{ $item->menu->nama_warung }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-shopping-basket me-1"></i>
                                    {{ $item->jumlah }} x Rp{{ number_format($item->menu->harga, 0, ',', '.') }}
                                </small>
                            </div>
                            <div class="item-price text-end">
                                <span class="h6 fw-bold text-primary mb-0">
                                    Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="order-summary border-top pt-4">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calculator fa-2x text-primary me-3"></i>
                                <div>
                                    <h6 class="mb-0">Total Pembayaran</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-box me-1"></i>{{ $order->items->count() }} item
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <div class="total-price">
                                <span class="h3 fw-bold text-primary mb-0">
                                    Rp{{ number_format($order->total_harga, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                @if($order->status === 'selesai')
                    <div class="alert alert-success mt-3">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Pesanan Selesai!</strong> Terima kasih telah berbelanja di NganTeen.
                    </div>
                @elseif($order->status === 'diproses')
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Pesanan Sedang Diproses.</strong> Mohon tunggu, pesanan Anda sedang disiapkan.
                    </div>
                @elseif($order->status === 'pending')
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Menunggu Konfirmasi.</strong> Pesanan Anda sedang menunggu konfirmasi dari penjual.
                    </div>
                @endif
            </div>
        </div>
    @empty
        <!-- No Orders - Show Available Menus -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-green">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-history fa-3x text-muted"></i>
                        </div>
                        <h4 class="h5 mb-2">Belum Ada Riwayat Pesanan</h4>
                        <p class="text-muted mb-4">
                            Anda belum pernah melakukan pesanan. Yuk, mulai pesan menu favorit dari pilihan di bawah ini!
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Menus Section -->
        @if($menus->isNotEmpty())
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="h5 fw-bold mb-0">
                            <i class="fas fa-utensils me-2"></i>Menu Terbaru Yang Tersedia
                        </h3>
                        <a href="{{ route('menu.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Lihat Semua
                        </a>
                    </div>
                </div>
            </div>

            <!-- Menu Grid -->
            <div class="row g-4">
                @foreach($menus as $menu)
                    <div class="col-lg-4 col-md-6">
                        <div class="card menu-item shadow-green h-100">
                            <div class="position-relative">
                                @if($menu->gambar)
                                    <img src="{{ Storage::url($menu->gambar) }}" 
                                         class="card-img-top" 
                                         alt="{{ $menu->nama_menu }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-utensils fa-3x text-muted"></i>
                                    </div>
                                @endif
                                
                                <!-- Stok Badge -->
                                <div class="position-absolute top-0 end-0 m-3">
                                    @if($menu->stok > 0)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Tersedia ({{ $menu->stok }})
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>Habis
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold">{{ $menu->nama_menu }}</h5>
                                <p class="menu-warung mb-2">
                                    <i class="fas fa-store me-1"></i>{{ $menu->nama_warung }}
                                </p>
                                
                                <!-- Rating -->
                                <div class="menu-rating mb-2">
                                    @php
                                        $avgRating = $menu->ratings->avg('rating') ?? 0;
                                        $fullStars = floor($avgRating);
                                        $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                                    @endphp
                                    
                                    <div class="d-flex align-items-center">
                                        <div class="stars me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $fullStars)
                                                    <i class="fas fa-star text-warning"></i>
                                                @elseif($i == $fullStars + 1 && $hasHalfStar)
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted">
                                            ({{ number_format($avgRating, 1) }}/5 - {{ $menu->ratings->count() }} ulasan)
                                        </small>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="h5 fw-bold text-primary mb-0">
                                            Rp{{ number_format($menu->harga, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <!-- Add to Cart Button -->
                                    @if($menu->stok > 0)
                                        <form action="{{ route('pembeli.cart.store') }}" method="POST" class="add-to-cart-form">
                                            @csrf
                                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                            <input type="hidden" name="jumlah" value="1">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-times me-2"></i>Stok Habis
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Menus Available -->
            <div class="card shadow-green menu-item">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-utensils fa-4x text-muted"></i>
                    </div>
                    <h3 class="h4 mb-3">Belum Ada Menu Tersedia</h3>
                    <p class="text-muted mb-4">
                        Saat ini belum ada menu yang tersedia. Silakan cek kembali nanti!
                    </p>
                </div>
            </div>
        @endif
    @endforelse

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            <div class="pagination-wrapper">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
/* Order Items Styling */
.order-item {
    transition: all 0.3s ease;
}

.order-item:hover {
    background-color: var(--ngan-primary-50);
    border-radius: 8px;
    margin: 0 -15px;
    padding: 15px !important;
}

.item-image img,
.item-image div {
    transition: transform 0.3s ease;
}

.order-item:hover .item-image img,
.order-item:hover .item-image div {
    transform: scale(1.05);
}

/* Badge Animations */
.badge {
    animation: fadeInUp 0.5s ease;
}

.badge .fa-spin {
    animation: spin 1s linear infinite;
}

/* Pagination Styling */
.pagination-wrapper .pagination {
    margin: 0;
}

.pagination .page-link {
    color: var(--ngan-primary);
    border-color: var(--ngan-primary-200);
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background-color: var(--ngan-primary-50);
    border-color: var(--ngan-primary);
    color: var(--ngan-primary-600);
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, var(--ngan-primary) 0%, var(--ngan-primary-600) 100%);
    border-color: var(--ngan-primary);
    color: white;
    box-shadow: 0 4px 8px rgba(34, 197, 94, 0.3);
}

/* Alert Animations */
.alert {
    animation: slideInUp 0.5s ease;
}

/* Order Summary */
.order-summary {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(34, 197, 94, 0.05) 100%);
    border-radius: 12px;
    padding: 20px;
    margin: -10px;
}

.total-price {
    position: relative;
}

.total-price::before {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--ngan-primary), var(--ngan-primary-600));
    border-radius: 2px;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation to cancel buttons
    document.querySelectorAll('.cancel-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.')) {
                e.preventDefault();
            }
        });
    });
    
    // Add loading state to cancel buttons
    document.querySelectorAll('.cancel-form button').forEach(button => {
        button.addEventListener('click', function() {
            if (this.form.checkValidity()) {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Membatalkan...';
            }
        });
    });

    // Handle add to cart forms
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = form.querySelector('button[type="submit"]');
            const originalContent = button.innerHTML;
            
            // Show loading state
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menambahkan...';
            
            // Submit form via fetch
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success feedback
                    button.innerHTML = '<i class="fas fa-check me-2"></i>Berhasil Ditambahkan!';
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');
                    
                    // Show success toast
                    showToast('Menu berhasil ditambahkan ke keranjang!', 'success');
                    
                    // Update cart count if exists
                    updateCartCount();
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        button.disabled = false;
                        button.innerHTML = originalContent;
                        button.classList.remove('btn-success');
                        button.classList.add('btn-primary');
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast(error.message || 'Gagal menambahkan ke keranjang', 'error');
                
                // Reset button
                button.disabled = false;
                button.innerHTML = originalContent;
            });
        });
    });
});

// Toast notification function
function showToast(message, type = 'success') {
    // Remove existing toasts
    document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed toast-notification`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideInRight 0.3s ease;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}
        <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }
    }, 4000);
}

// Update cart count
function updateCartCount() {
    fetch('/pembeli/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartBadge = document.querySelector('.cart-count');
            if (cartBadge && data.count !== undefined) {
                cartBadge.textContent = data.count;
                if (data.count > 0) {
                    cartBadge.style.display = 'inline';
                } else {
                    cartBadge.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error updating cart count:', error));
}
</script>

<style>
@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideOutRight {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}

/* Menu card hover effects */
.menu-item:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
}

.menu-item .card-img-top {
    transition: transform 0.3s ease;
}

.menu-item:hover .card-img-top {
    transform: scale(1.05);
}

/* Rating stars */
.stars .fa-star {
    font-size: 14px;
}

/* Button animations */
.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

/* Add to cart button success state */
.btn-success {
    animation: pulse 0.5s ease;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>
@endpush
@endsection