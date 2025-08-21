@extends('layouts.pembeli')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Menu Grid -->
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📱 Menu Available</h2>
                <div class="cart-summary">
                    <a href="{{ route('pembeli.cart.index') }}" class="btn btn-outline-primary position-relative">
                        🛒 Keranjang
                        <span class="cart-count-badge badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" style="display: none;">0</span>
                    </a>
                </div>
            </div>

            <div class="row g-4">
                @forelse($menus as $menu)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        @if($menu->gambar)
                            <img src="{{ Storage::url($menu->gambar) }}" 
                                 class="card-img-top" 
                                 style="height: 220px; object-fit: cover; cursor: pointer;" 
                                 alt="{{ $menu->nama }}"
                                 data-bs-toggle="modal" data-bs-target="#menuModal-{{ $menu->id }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                                <span class="text-muted">🍽️ No Image</span>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $menu->nama }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($menu->deskripsi, 80) }}</p>
                            <p class="card-text"><small class="text-muted">📍 {{ $menu->area_kampus }}</small></p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 text-primary mb-0">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                                    <span class="badge bg-secondary">Stok: {{ $menu->stok }}</span>
                                </div>
                                
                                @if($menu->stok > 0)
                                    <!-- Quantity Selector -->
                                    <div class="input-group input-group-sm mb-3">
                                        <button class="btn btn-outline-secondary btn-quantity-minus" type="button" data-target="quantity-{{ $menu->id }}">-</button>
                                        <input type="number" class="form-control text-center quantity-input" 
                                               id="quantity-{{ $menu->id }}" 
                                               value="1" min="1" max="{{ $menu->stok }}" 
                                               style="max-width: 60px;">
                                        <button class="btn btn-outline-secondary btn-quantity-plus" type="button" data-target="quantity-{{ $menu->id }}">+</button>
                                    </div>
                                    
                                    <!-- Add to Cart Button -->
                                    <button class="btn btn-primary w-100 btn-add-to-cart" 
                                            data-menu-id="{{ $menu->id }}"
                                            data-menu-name="{{ $menu->nama }}"
                                            onclick="addToCartWithQuantity({{ $menu->id }})">
                                        🛒 Tambah ke Keranjang
                                    </button>
                                @else
                                    <button class="btn btn-secondary w-100" disabled>
                                        ❌ Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <h4>🍽️ Belum ada menu tersedia</h4>
                        <p class="text-muted">Silakan cek lagi nanti atau hubungi penjual.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Cart Summary Sidebar -->
        <div class="col-md-4">
            <div class="sticky-top" style="top: 20px;">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">🛒 Quick Cart</h5>
                    </div>
                    <div class="card-body" id="quick-cart-content">
                        <div class="text-center text-muted py-3">
                            <p>Keranjang belanja kosong</p>
                            <small>Pilih menu untuk mulai berbelanja</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('pembeli.cart.index') }}" class="btn btn-primary w-100">
                            Lihat Keranjang Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.cart-count-badge {
    font-size: 0.7rem;
}

.quantity-input {
    -moz-appearance: textfield;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
}

.btn-add-to-cart:disabled {
    opacity: 0.6;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.btn-add-to-cart.adding {
    animation: pulse 0.6s ease-in-out;
}
</style>
@endpush

@push('scripts')
<!-- jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Cart Manager Script -->
<script src="{{ asset('js/cart-manager.js') }}"></script>

<script>
// Enhanced functions for this specific page
function addToCartWithQuantity(menuId) {
    const quantity = parseInt($(`#quantity-${menuId}`).val()) || 1;
    const $button = $(`.btn-add-to-cart[data-menu-id="${menuId}"]`);
    
    // Add animation
    $button.addClass('adding');
    
    // Use CartManager to add to cart
    window.cartManager.addToCart($button[0]);
    
    // Remove animation after completion
    setTimeout(() => {
        $button.removeClass('adding');
    }, 600);
}

// Quantity selector handlers
$(document).on('click', '.btn-quantity-plus', function() {
    const target = $(this).data('target');
    const $input = $(`#${target}`);
    const current = parseInt($input.val());
    const max = parseInt($input.attr('max'));
    
    if (current < max) {
        $input.val(current + 1);
        updateAddToCartButton(target, current + 1);
    }
});

$(document).on('click', '.btn-quantity-minus', function() {
    const target = $(this).data('target');
    const $input = $(`#${target}`);
    const current = parseInt($input.val());
    
    if (current > 1) {
        $input.val(current - 1);
        updateAddToCartButton(target, current - 1);
    }
});

$(document).on('change', '.quantity-input', function() {
    const quantity = parseInt($(this).val());
    const max = parseInt($(this).attr('max'));
    const min = parseInt($(this).attr('min'));
    
    if (quantity > max) {
        $(this).val(max);
    } else if (quantity < min) {
        $(this).val(min);
    }
    
    updateAddToCartButton($(this).attr('id'), $(this).val());
});

function updateAddToCartButton(inputId, quantity) {
    const menuId = inputId.replace('quantity-', '');
    const $button = $(`.btn-add-to-cart[data-menu-id="${menuId}"]`);
    $button.data('quantity', quantity);
}

// Quick cart update function
function updateQuickCart() {
    $.ajax({
        url: '/api/cart/items',
        type: 'GET',
        success: function(response) {
            if (response.success && response.data.items.length > 0) {
                let cartHtml = '';
                response.data.items.slice(0, 3).forEach(item => {
                    cartHtml += `
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div>
                                <small class="fw-bold">${item.menu.nama}</small><br>
                                <small class="text-muted">${item.jumlah}x Rp ${new Intl.NumberFormat('id-ID').format(item.menu.harga)}</small>
                            </div>
                            <small class="fw-bold">Rp ${new Intl.NumberFormat('id-ID').format(item.jumlah * item.menu.harga)}</small>
                        </div>
                    `;
                });
                
                if (response.data.items.length > 3) {
                    cartHtml += `<small class="text-muted">dan ${response.data.items.length - 3} item lainnya...</small>`;
                }
                
                cartHtml += `
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong>Rp ${new Intl.NumberFormat('id-ID').format(response.data.total)}</strong>
                    </div>
                `;
                
                $('#quick-cart-content').html(cartHtml);
            }
        }
    });
}

// Update quick cart on page load and when items are added
$(document).ready(function() {
    updateQuickCart();
    
    // Listen for cart updates
    $(document).on('cartUpdated', function() {
        updateQuickCart();
    });
});
</script>

<!-- Menu Detail Modals -->
@foreach($menus as $menu)
    @if($menu->gambar)
    <div class="modal fade" id="menuModal-{{ $menu->id }}" tabindex="-1" aria-labelledby="menuModalLabel-{{ $menu->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuModalLabel-{{ $menu->id }}">{{ $menu->nama }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{ Storage::url($menu->gambar) }}" alt="{{ $menu->nama }}" 
                                 class="img-fluid rounded" style="width: 100%; max-height: 400px; object-fit: cover;">
                        </div>
                        <div class="col-md-6">
                            <h4 class="text-primary">{{ $menu->nama }}</h4>
                            <p class="text-muted">{{ $menu->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                            <p><strong>📍 Lokasi:</strong> {{ $menu->area_kampus }}</p>
                            <p><strong>🏪 Warung:</strong> {{ $menu->nama_warung }}</p>
                            @if(isset($menu->kategori))
                                <p><strong>🏷️ Kategori:</strong> {{ $menu->kategori }}</p>
                            @endif
                            <p><strong>💰 Harga:</strong> <span class="h5 text-success">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span></p>
                            <p><strong>📦 Stok:</strong> 
                                @if($menu->stok > 10)
                                    <span class="badge bg-success">{{ $menu->stok }} tersedia</span>
                                @elseif($menu->stok > 0)
                                    <span class="badge bg-warning">{{ $menu->stok }} tersisa</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </p>
                            
                            @if($menu->stok > 0)
                                <div class="mt-4">
                                    <div class="input-group mb-3">
                                        <button class="btn btn-outline-secondary btn-quantity-minus" type="button" data-target="modal-quantity-{{ $menu->id }}">-</button>
                                        <input type="number" class="form-control text-center quantity-input" 
                                               id="modal-quantity-{{ $menu->id }}" 
                                               value="1" min="1" max="{{ $menu->stok }}" 
                                               style="max-width: 80px;">
                                        <button class="btn btn-outline-secondary btn-quantity-plus" type="button" data-target="modal-quantity-{{ $menu->id }}">+</button>
                                    </div>
                                    <button class="btn btn-primary w-100 add-to-cart-btn" 
                                            data-menu-id="{{ $menu->id }}" 
                                            data-quantity-target="modal-quantity-{{ $menu->id }}">
                                        🛒 Tambah ke Keranjang
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-warning mt-4">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Menu ini sedang habis
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endpush
