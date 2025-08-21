@extends('layouts.pembeli')

@section('title', 'Daftar Menu')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header rounded-lg mb-4">
                <div class="container">
                    <h1 class="h3 mb-2">
                        <i class="fas fa-utensils me-2"></i>Daftar Menu
                    </h1>
                    <p class="mb-0 text-white-50">Temukan menu favorit dari berbagai warung di kampus</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h5 fw-bold mb-0">Semua Menu Tersedia</h2>
                @auth
                    @if(auth()->user()->isPenjual())
                        <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah Menu
                        </a>
                    @endif
                @endauth
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Total Menu:</span>
                        <span class="badge bg-primary rounded-pill fs-6">
                            {{ $menus->total() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu List -->
    <div class="row g-4">
        @forelse($menus as $menu)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm menu-item">
                @if($menu->gambar)
                    <div class="position-relative" style="height: 200px; overflow: hidden;">
                        <img src="{{ $menu->photo_url }}" class="card-img-top w-100 h-100" alt="{{ $menu->nama_menu }}" style="object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($menu->stok > 10)
                                <span class="stok-badge stok-available">Tersedia</span>
                            @elseif($menu->stok > 0)
                                <span class="stok-badge stok-low">Terbatas</span>
                            @else
                                <span class="stok-badge stok-empty">Habis</span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-utensils fa-4x text-muted"></i>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">{{ $menu->nama_menu }}</h5>
                        <span class="badge bg-primary rounded-pill">{{ $menu->area_kampus }}</span>
                    </div>
                    
                    <div class="mb-3 flex-grow-1">
                        <div class="menu-warung mb-2">
                            <i class="fas fa-store me-1"></i>{{ $menu->nama_warung }}
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="rating-display">
                                @php
                                    $avgRating = $menu->averageRating();
                                    $totalRatings = $menu->ratings->count();
                                @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa{{ $i <= floor($avgRating) ? 's' : 'r' }} fa-star text-warning"></i>
                                @endfor
                                <span class="small ms-1 text-muted">
                                    {{ number_format($avgRating, 1) }} ({{ $totalRatings }})
                                </span>
                            </div>
                            <div class="menu-price">
                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                            </div>
                        </div>
                        
                        <div class="menu-stok">
                            <i class="fas fa-boxes me-1"></i>
                            @if($menu->stok > 0)
                                <span class="text-success">Stok: {{ $menu->stok }}</span>
                            @else
                                <span class="text-danger">Stok Habis</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-auto">
                        @auth
                            @if(auth()->user()->isPembeli())
                                @if($menu->stok > 0)
                                    <form action="{{ route('pembeli.cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                        <div class="input-group mb-2">
                                            <input type="number" name="jumlah" value="1" min="1" max="{{ $menu->stok }}"
                                                   class="form-control" placeholder="Jumlah">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-cart-plus me-1"></i>Tambah
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <button disabled class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-times me-1"></i>Stok Habis
                                    </button>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-sign-in-alt me-1"></i>Login untuk Pesan
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card shadow-green menu-item">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-utensils fa-4x text-muted"></i>
                    </div>
                    <h3 class="h4 mb-3">Tidak Ada Menu Tersedia</h3>
                    <p class="text-muted mb-4">Saat ini tidak ada menu yang tersedia. Silakan periksa kembali nanti.</p>
                    @auth
                        @if(auth()->user()->isPenjual())
                            <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Tambah Menu Pertama
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($menus->hasPages())
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Page navigation">
            {{ $menus->links() }}
        </nav>
    </div>
    @endif
</div>

@push('styles')
<style>
.rating-display .fas.fa-star,
.rating-display .far.fa-star {
    font-size: 0.875rem;
}

.pagination .page-link {
    color: var(--ngan-primary);
    border-color: var(--ngan-primary-200);
    padding: 0.5rem 0.75rem;
}

.pagination .page-link:hover {
    background-color: var(--ngan-primary-50);
    border-color: var(--ngan-primary);
    color: var(--ngan-primary-600);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, var(--ngan-primary) 0%, var(--ngan-primary-600) 100%);
    border-color: var(--ngan-primary);
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: var(--ngan-text-muted);
    background-color: var(--ngan-light);
    border-color: var(--ngan-border);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle add to cart forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menambah...';
                submitBtn.disabled = true;
                
                // Reset button if form submission fails
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            }
        });
    });

    // Enhanced quantity validation
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('change', function() {
            const value = parseInt(this.value);
            const max = parseInt(this.getAttribute('max'));
            const min = parseInt(this.getAttribute('min')) || 1;
            
            if (isNaN(value) || value < min) {
                this.value = min;
                showToast('Jumlah minimal adalah ' + min, 'warning');
            } else if (value > max) {
                this.value = max;
                showToast('Stok tidak mencukupi. Maksimal ' + max + ' item', 'warning');
            }
        });
    });
});
</script>
@endpush
@endsection