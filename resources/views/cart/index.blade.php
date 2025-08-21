@extends('layouts.pembeli')

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Section -->
    <div class="hero-section bg-primary text-white py-5">
        <div class="container text-center py-4">
            <h1 class="display-5 fw-bold mb-3">Keranjang Belanja</h1>
            <p class="lead mb-0">Review pesanan Anda sebelum checkout</p>
        </div>
    </div>

    <div class="container py-5">
        @if($cartItems->count() > 0)
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4">Item Pesanan</h5>
                            
                            <!-- Cart Items List -->
                            <div class="list-group list-group-flush">
                                @foreach($cartItems as $item)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            @if($item->menu->image)
                                                <img src="{{ asset('storage/'.$item->menu->image) }}" class="img-fluid rounded" alt="{{ $item->menu->nama_menu }}">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px; width: 80px;">
                                                    <i class="fas fa-utensils text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-5">
                                            <h6 class="mb-1">{{ $item->menu->nama_menu }}</h6>
                                            <small class="text-muted">{{ $item->menu->nama_warung }}</small>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <button class="btn btn-outline-secondary decrement" type="button">-</button>
                                                <input type="number" class="form-control text-center quantity-input" value="{{ $item->quantity }}" min="1" max="{{ $item->menu->stok }}">
                                                <button class="btn btn-outline-secondary increment" type="button">+</button>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <h6 class="mb-0 text-primary">Rp {{ number_format($item->menu->harga * $item->quantity, 0, ',', '.') }}</h6>
                                            <button class="btn btn-sm btn-link text-danger remove-item" data-id="{{ $item->id }}">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4">Ringkasan Pesanan</h5>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <span>Biaya Layanan</span>
                                <span>Rp 2.000</span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between fw-bold mb-4">
                                <span>Total</span>
                                <span class="text-primary">Rp {{ number_format($subtotal + 2000, 0, ',', '.') }}</span>
                            </div>
                            
                            <a href="{{ route('global.checkout') }}" class="btn btn-primary w-100 py-3">
                                Lanjut ke Pembayaran
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                <h4 class="fw-bold mb-3">Keranjang Anda Kosong</h4>
                <p class="text-muted mb-4">Tambahkan beberapa menu untuk memulai pesanan</p>
                <a href="{{ route('pembeli.menu.index') }}" class="btn btn-primary px-4">
                    <i class="fas fa-utensils me-2"></i> Lihat Menu
                </a>
            </div>
        @endif
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    document.querySelectorAll('.increment').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.quantity-input');
            input.value = parseInt(input.value) + 1;
            updateCartItem(this);
        });
    });

    document.querySelectorAll('.decrement').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.quantity-input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                updateCartItem(this);
            }
        });
    });

    // Remove item
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Hapus item dari keranjang?')) {
                const itemId = this.getAttribute('data-id');
                removeCartItem(itemId);
            }
        });
    });

    function updateCartItem(element) {
        // AJAX implementation will be added here
        console.log('Updating cart item quantity');
    }

    function removeCartItem(itemId) {
        // AJAX implementation will be added here
        console.log('Removing cart item:', itemId);
    }
});
</script>
@endsection
