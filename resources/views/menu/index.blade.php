@extends('layouts.app')

@section('title', 'Daftar Menu')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h4 fw-bold mb-0">Daftar Menu</h2>
                @auth
                    @if(auth()->user()->isPenjual())
                        <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Tambah Menu
                        </a>
                    @endif
                @endauth
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Total Menu:</span>
                        <span id="menuCounter" class="badge bg-primary rounded-pill">
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
            <div class="card h-100 border-0 shadow-sm">
                @if($menu->gambar)
                    <div class="position-relative" style="height: 180px; overflow: hidden;">
                        <img src="{{ $menu->photo_url }}" class="img-fluid w-100 h-100" alt="{{ $menu->nama_menu }}" style="object-fit: cover;">
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="fas fa-utensils fa-4x text-muted"></i>
                    </div>
                @endif
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-1">{{ $menu->nama_menu }}</h5>
                        <span class="badge bg-primary">{{ $menu->area_kampus }}</span>
                    </div>
                    <p class="text-muted small mb-2">{{ $menu->nama_warung }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $menu->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                            <span class="small ms-1">({{ $menu->rating }})</span>
                        </div>
                        <span class="fw-bold">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        @if($menu->stok > 0)
                            <span class="badge bg-success bg-opacity-10 text-success">Tersedia ({{ $menu->stok }})</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger">Habis</span>
                        @endif
                        
                        @auth
                            @if(auth()->user()->isPembeli())
                                <button class="btn btn-sm btn-primary" onclick="addToCart({{ $menu->id }})">
                                    <i class="fas fa-cart-plus me-1"></i> Pesan
                                </button>
                            @elseif(auth()->user()->isPenjual())
                                <div class="d-flex gap-2">
                                    <a href="{{ route('penjual.menu.edit', $menu->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit Menu">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('penjual.menu.destroy', $menu->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="tooltip" 
                                                title="Hapus Menu"
                                                onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i> Tidak ada menu yang tersedia saat ini.
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($menus->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
            {{ $menus->links('pagination::bootstrap-5') }}
        </nav>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function addToCart(menuId) {
        // Implementasi fungsi addToCart
        alert('Menambahkan menu ke keranjang: ' + menuId);
    }

    // Function to update menu counter
    function updateMenuCounter(change) {
        const counterElement = document.getElementById('menuCounter');
        if (counterElement) {
            let currentCount = parseInt(counterElement.textContent) || 0;
            currentCount += change;
            counterElement.textContent = currentCount;
        }
    }

    // Handle menu deletion
    document.addEventListener('click', function(e) {
        if (e.target.closest('form[action*="/menu/"]') && 
            e.target.closest('form').getAttribute('action').includes('/menu/') &&
            confirm('Yakin ingin menghapus menu ini?')) {
            // Wait for the form to be submitted and page to reload
            // The counter will be updated on page load
            return true;
        }
    });

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Listen for Livewire events if using Livewire
        if (window.livewire) {
            window.livewire.on('menuAdded', () => updateMenuCounter(1));
            window.livewire.on('menuDeleted', () => updateMenuCounter(-1));
        }
    });
</script>
@endpush
@endsection