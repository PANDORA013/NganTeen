@extends('layouts.pembeli')

@section('content')
<div class="container py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header rounded-lg mb-4">
                <div class="container">
                    <h1 class="h3 mb-2">
                        <i class="fas fa-home me-2"></i>Selamat Datang, {{ Auth::user()->name }}!
                    </h1>
                    <p class="mb-0 text-white-50">Kelola pesanan dan jelajahi menu terbaru dari warung favorit Anda</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card card-dashboard h-100 menu-item">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-shopping-cart fa-3x text-primary mb-3"></i>
                    </div>
                    <h3 class="h5 card-title mb-3">
                        Keranjang Saya
                    </h3>
                    <p class="card-text text-muted mb-4">Lihat dan kelola item di keranjang belanja Anda</p>
                    <a href="{{ route('pembeli.cart.index') }}" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-eye me-2"></i>Lihat Keranjang
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card card-dashboard h-100 menu-item">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-history fa-3x text-primary mb-3"></i>
                    </div>
                    <h3 class="h5 card-title mb-3">
                        Riwayat Pesanan
                    </h3>
                    <p class="card-text text-muted mb-4">Lihat status dan riwayat pesanan Anda</p>
                    <a href="{{ route('pembeli.orders.index') }}" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-list me-2"></i>Lihat Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Section -->
    <div class="card shadow-green menu-item">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="h5 mb-0">
                <i class="fas fa-utensils me-2"></i>Menu Tersedia
            </h3>
            <span class="badge bg-primary">{{ $menus->count() }} Menu</span>
        </div>
        <div class="card-body p-4">
            @if($menus->count() > 0)
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($menus as $menu)
                        <div class="col">
                            <div class="card h-100 menu-item shadow-sm">
                                @if($menu->gambar)
                                    <div class="position-relative" style="height: 180px; overflow: hidden;">
                                        <img src="{{ Storage::url($menu->gambar) }}" alt="{{ $menu->nama_menu }}" 
                                             class="card-img-top w-100 h-100" style="object-fit: cover;">
                                        <div class="position-absolute top-0 end-0 m-2">
                                            @if($menu->stok > 10)
                                                <span class="stok-badge stok-available">Tersedia</span>
                                            @elseif($menu->stok > 0)
                                                <span class="stok-badge stok-low">Stok Terbatas</span>
                                            @else
                                                <span class="stok-badge stok-empty">Habis</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                        <i class="fas fa-utensils fa-4x text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body d-flex flex-column">
                                    <h4 class="card-title h6 mb-2">{{ $menu->nama_menu }}</h4>
                                    
                                    <div class="mb-3 flex-grow-1">
                                        <div class="menu-warung">
                                            <i class="fas fa-store me-1"></i>{{ $menu->nama_warung }}
                                        </div>
                                        <div class="menu-area">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $menu->area_kampus }}
                                        </div>
                                        <div class="menu-stok mt-2">
                                            <i class="fas fa-boxes me-1"></i>
                                            <span>Stok: {{ $menu->stok }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="menu-price mb-3">
                                        Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                    </div>
                                    
                                    @if($menu->stok > 0)
                                        <form action="{{ route('pembeli.cart.store') }}" method="POST" class="mt-auto">
                                            @csrf
                                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                            <div class="input-group mb-3">
                                                <input type="number" name="jumlah" value="1" min="1" max="{{ $menu->stok }}"
                                                       class="form-control" placeholder="Jumlah">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-cart-plus me-1"></i>Tambah
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <button disabled class="btn btn-outline-secondary w-100 mt-auto">
                                            <i class="fas fa-times me-1"></i>Stok Habis
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted mb-2">Tidak ada menu yang tersedia saat ini</h5>
                    <p class="text-muted">Silakan periksa kembali nanti atau hubungi penjual untuk informasi lebih lanjut.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection