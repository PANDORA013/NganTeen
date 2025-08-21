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

    <!-- Food News Section -->
    @if(isset($foodNews) && $foodNews->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-newspaper me-2"></i>Berita Makanan Terbaru
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        @foreach($foodNews->take(3) as $news)
                        <div class="col-md-4">
                            <div class="card h-100 border-0">
                                @if($news->image_url)
                                <img src="{{ $news->image_url }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $news->title }}">
                                @endif
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        @if($news->type === 'new_menu')
                                            <span class="badge bg-success me-2">Menu Baru</span>
                                        @elseif($news->type === 'promo')
                                            <span class="badge bg-warning me-2">Promo</span>
                                        @else
                                            <span class="badge bg-info me-2">Pengumuman</span>
                                        @endif
                                        <small class="text-muted">{{ $news->created_at->diffForHumans() }}</small>
                                    </div>
                                    <h6 class="card-title">{{ $news->title }}</h6>
                                    <p class="card-text small">{{ Str::limit($news->content, 100) }}</p>
                                    @if($news->warung)
                                        <div class="d-flex align-items-center mt-2">
                                            <i class="fas fa-store me-1 text-muted"></i>
                                            <small class="text-muted">{{ $news->warung->nama_warung }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($foodNews->count() > 3)
                    <div class="text-center p-3 border-top">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Lihat Semua Berita
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

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
                                    <div class="position-relative" style="height: 200px; overflow: hidden;">
                                        <img src="{{ Storage::url($menu->gambar) }}" alt="{{ $menu->nama_menu }}" 
                                             class="card-img-top w-100 h-100" 
                                             style="object-fit: cover; cursor: pointer;" 
                                             data-bs-toggle="modal" data-bs-target="#dashboardMenuModal-{{ $menu->id }}">
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

<!-- Dashboard Menu Detail Modals -->
@foreach($menus as $menu)
    @if($menu->gambar)
    <div class="modal fade" id="dashboardMenuModal-{{ $menu->id }}" tabindex="-1" aria-labelledby="dashboardMenuModalLabel-{{ $menu->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dashboardMenuModalLabel-{{ $menu->id }}">{{ $menu->nama_menu }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="{{ Storage::url($menu->gambar) }}" alt="{{ $menu->nama_menu }}" 
                                 class="img-fluid rounded" style="width: 100%; max-height: 400px; object-fit: cover;">
                        </div>
                        <div class="col-md-6">
                            <h4 class="text-primary">{{ $menu->nama_menu }}</h4>
                            @if($menu->deskripsi)
                                <p class="text-muted">{{ $menu->deskripsi }}</p>
                            @endif
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
                            
                            <div class="mt-4">
                                <a href="{{ route('pembeli.menu.index') }}" class="btn btn-primary">
                                    🍽️ Lihat Semua Menu
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
@endsection