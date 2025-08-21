@extends('layouts.pembeli')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header rounded-lg mb-4">
                <div class="container">
                    <h1 class="h3 mb-2">
                        <i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja
                        @if($keranjang->count() > 0)
                            <small class="badge bg-light text-dark ms-2">{{ $totalQuantity }} Item</small>
                        @endif
                    </h1>
                    <p class="mb-0 text-white-50">
                        @if($keranjang->count() > 0)
                            Kelola {{ $keranjang->count() }} jenis menu ({{ $totalQuantity }} item total) sebelum melanjutkan ke pembayaran
                        @else
                            Keranjang Anda masih kosong
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
            
    @if($keranjang->count() > 0)
        <!-- Cart Items -->
        <div class="card shadow-green mb-4 menu-item">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Item Pesanan
                    <span class="badge bg-primary ms-2">{{ $keranjang->count() }} Menu</span>
                    <span class="badge bg-success ms-1">{{ $totalQuantity }} Item Total</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Menu</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($keranjang as $item)
                                <tr class="menu-item">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                @if($item->menu->gambar)
                                                    <img src="{{ Storage::url($item->menu->gambar) }}" 
                                                         alt="{{ $item->menu->nama_menu }}" 
                                                         class="rounded-lg shadow-sm" 
                                                         style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;"
                                                         data-bs-toggle="modal" data-bs-target="#cartMenuModal-{{ $item->menu->id }}">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded-lg" 
                                                         style="width: 70px; height: 70px;">
                                                        <i class="fas fa-utensils text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold">{{ $item->menu->nama_menu }}</h6>
                                                <div class="menu-warung">{{ $item->menu->nama_warung }}</div>
                                                <div class="menu-stok">
                                                    <i class="fas fa-boxes me-1"></i>Stok: {{ $item->menu->stok }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="fw-bold text-primary">
                                            Rp {{ number_format($item->menu->harga, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="mb-2">
                                            <small class="quantity-label d-block">Jumlah</small>
                                        </div>
                                        <form action="{{ route('pembeli.cart.update', $item->id) }}" method="POST" 
                                              class="quantity-form">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group mx-auto" style="width: 140px;">
                                                <button type="button" class="btn btn-outline-success btn-sm" 
                                                        onclick="updateQuantity(this, -1)"
                                                        style="border-radius: 8px 0 0 8px;">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" name="jumlah" class="form-control text-center fw-bold border-success" 
                                                       value="{{ $item->jumlah }}" min="1" max="{{ $item->menu->stok }}" 
                                                       data-original="{{ $item->jumlah }}"
                                                       style="border-left: none; border-right: none; background-color: #f8f9fa;">
                                                <button type="button" class="btn btn-outline-success btn-sm" 
                                                        onclick="updateQuantity(this, 1)"
                                                        style="border-radius: 0 8px 8px 0;">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-end align-middle">
                                        <span class="h6 fw-bold text-primary mb-0">
                                            Rp {{ number_format($item->menu->harga * $item->jumlah, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center align-middle pe-4">
                                        <form action="{{ route('pembeli.cart.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                    title="Hapus item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card shadow-green menu-item">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calculator me-2"></i>Ringkasan Belanja
                </h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-receipt fa-2x text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0">Total Pembayaran</h6>
                                <small class="text-muted">{{ $keranjang->count() }} jenis menu • {{ $totalQuantity }} item total</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <div class="h3 mb-0 fw-bold text-primary">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                    <a href="{{ route('pembeli.menu.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Lanjutkan Belanja
                    </a>
                    
                    <form id="checkoutForm" action="{{ route('pembeli.checkout.process') }}" method="POST" class="flex-grow-1 ms-md-3">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100" id="checkoutBtn">
                            <i class="fas fa-credit-card me-2"></i>Lanjut ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="card shadow-green menu-item">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-shopping-cart fa-4x text-muted"></i>
                </div>
                <h3 class="h4 mb-3">Keranjang Belanja Kosong</h3>
                <p class="text-muted mb-4">Belum ada item di keranjang Anda. Ayo mulai belanja dan temukan menu favorit!</p>
                <a href="{{ route('pembeli.menu.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-utensils me-2"></i>Jelajahi Menu
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Cart Item Detail Modals -->
@if(isset($keranjang) && $keranjang->count() > 0)
    @foreach($keranjang as $item)
        @if($item->menu->gambar)
        <div class="modal fade" id="cartMenuModal-{{ $item->menu->id }}" tabindex="-1" aria-labelledby="cartMenuModalLabel-{{ $item->menu->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cartMenuModalLabel-{{ $item->menu->id }}">{{ $item->menu->nama_menu }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="{{ Storage::url($item->menu->gambar) }}" alt="{{ $item->menu->nama_menu }}" 
                                     class="img-fluid rounded" style="width: 100%; max-height: 400px; object-fit: cover;">
                            </div>
                            <div class="col-md-6">
                                <h4 class="text-primary">{{ $item->menu->nama_menu }}</h4>
                                @if($item->menu->deskripsi)
                                    <p class="text-muted">{{ $item->menu->deskripsi }}</p>
                                @endif
                                <p><strong>📍 Lokasi:</strong> {{ $item->menu->area_kampus }}</p>
                                <p><strong>🏪 Warung:</strong> {{ $item->menu->nama_warung }}</p>
                                @if(isset($item->menu->kategori))
                                    <p><strong>🏷️ Kategori:</strong> {{ $item->menu->kategori }}</p>
                                @endif
                                <p><strong>💰 Harga:</strong> <span class="h5 text-success">Rp {{ number_format($item->menu->harga, 0, ',', '.') }}</span></p>
                                
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-shopping-cart me-2"></i>Di Keranjang Anda:</h6>
                                    <p class="mb-0">
                                        <strong>Jumlah:</strong> {{ $item->jumlah }} pcs<br>
                                        <strong>Subtotal:</strong> Rp {{ number_format($item->jumlah * $item->menu->harga, 0, ',', '.') }}
                                    </p>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i>Tutup
                                    </button>
                                    <a href="{{ route('pembeli.menu.index') }}" class="btn btn-primary">
                                        <i class="fas fa-utensils me-1"></i>Lihat Menu Lain
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeCartFeatures();
});

function initializeCartFeatures() {
    // Add loading states to forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                
                // Different loading text for checkout form
                if (form.id === 'checkoutForm') {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses Checkout...';
                } else {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                }
                
                // Re-enable after 5 seconds if still disabled (in case of errors)
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }, 5000);
            }
        });
    });
    
    // Add input validation for quantity fields
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('change', function() {
            const form = this.closest('form');
            if (form && form.classList.contains('quantity-form')) {
                const originalValue = this.dataset.original;
                if (this.value !== originalValue) {
                    form.submit();
                }
            }
        });
        
        input.addEventListener('input', function() {
            validateQuantityInput(this);
        });
    });
    
    // Add confirmation to delete buttons
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin menghapus item ini dari keranjang?')) {
                e.preventDefault();
            }
        });
    });
}

// Update quantity with validation
function updateQuantity(button, change) {
    const input = button.closest('.input-group').querySelector('input[type="number"]');
    const currentValue = parseInt(input.value);
    const newValue = currentValue + change;
    const min = parseInt(input.getAttribute('min'));
    const max = parseInt(input.getAttribute('max'));
    
    if (newValue >= min && newValue <= max) {
        input.value = newValue;
        input.dispatchEvent(new Event('change'));
        
        // Auto-submit form setelah 1 detik untuk memberikan feedback yang smooth
        const form = button.closest('form');
        clearTimeout(window.quantityUpdateTimeout);
        window.quantityUpdateTimeout = setTimeout(() => {
            if (parseInt(input.value) !== parseInt(input.getAttribute('data-original'))) {
                form.submit();
            }
        }, 1000);
    } else if (newValue > max) {
        showToast('Stok tidak mencukupi. Maksimal ' + max + ' item', 'warning');
    } else if (newValue < min) {
        showToast('Jumlah minimal adalah ' + min + ' item', 'warning');
    }
}

// Validate quantity input
function validateQuantityInput(input) {
    const value = parseInt(input.value);
    const max = parseInt(input.getAttribute('max'));
    const min = parseInt(input.getAttribute('min'));
    
    if (isNaN(value) || value < min) {
        input.value = min;
    } else if (value > max) {
        input.value = max;
        showToast('Stok tidak mencukupi. Maksimal ' + max + ' item', 'warning');
    }
}

// Optimized toast notification
function showToast(message, type = 'warning') {
    // Remove existing toasts
    document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = 'alert alert-' + type + ' position-fixed toast-notification';
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideInRight 0.3s ease;';
    
    const iconClass = type === 'warning' ? 'exclamation-triangle' : 'info-circle';
    toast.innerHTML = '<i class="fas fa-' + iconClass + ' me-2"></i>' + message +
        '<button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>';
    
    document.body.appendChild(toast);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }
    }, 4000);
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

.quantity-form .input-group {
    margin: 0 auto;
}

/* Quantity Input Styling */
.quantity-form .input-group .btn {
    border: 2px solid #28a745;
    color: #28a745;
    font-weight: bold;
    transition: all 0.3s ease;
    padding: 8px 12px;
}

.quantity-form .input-group .btn:hover {
    background-color: #28a745;
    color: white;
    transform: scale(1.05);
}

.quantity-form .input-group input {
    border-top: 2px solid #28a745;
    border-bottom: 2px solid #28a745;
    font-weight: bold;
    font-size: 1.1rem;
    color: #28a745;
}

.quantity-form .input-group input:focus {
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    border-color: #28a745;
}

/* Quantity Label */
.quantity-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 8px;
}

.btn-loading {
    position: relative;
    pointer-events: none;
}
</style>
@endpush
@endsection