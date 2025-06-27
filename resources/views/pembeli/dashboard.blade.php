@extends('layouts.pembeli')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="h5 card-title">
                        <i class="bi bi-cart3 me-2"></i>Keranjang Saya
                    </h3>
                    <p class="card-text text-muted">Lihat dan kelola item di keranjang belanja Anda</p>
                    <a href="{{ route('pembeli.cart.index') }}" class="btn btn-primary">
                        Lihat Keranjang
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="h5 card-title">
                        <i class="bi bi-list-check me-2"></i>Riwayat Pesanan
                    </h3>
                    <p class="card-text text-muted">Lihat status dan riwayat pesanan Anda</p>
                    <a href="{{ route('pembeli.orders.index') }}" class="btn btn-primary">
                        Lihat Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="h5 mb-0">
                <i class="bi bi-egg-fried me-2"></i>Menu Tersedia
            </h3>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($menus as $menu)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="h6 card-title">{{ $menu->nama_menu }}</h4>
                                <div class="card-text small text-muted mb-3">
                                    <div>Warung: {{ $menu->nama_warung }}</div>
                                    <div>Area: {{ $menu->area_kampus }}</div>
                                    <div>Stok: {{ $menu->stok }}</div>
                                    <div class="fw-bold text-primary">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                                </div>
                                @if($menu->stok > 0)
                                    <form action="{{ route('pembeli.cart.store') }}" method="POST" class="d-flex gap-2">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                        <input type="number" name="jumlah" value="1" min="1" max="{{ $menu->stok }}"
                                               class="form-control form-control-sm" style="width: 80px;">
                                        <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                                            <i class="bi bi-cart-plus"></i> Tambah
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="btn btn-sm btn-outline-secondary w-100">
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection