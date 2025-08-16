@extends('layouts.penjual')

@section('content')
<div class="container">
    <!-- Professional Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center py-3">
                <div>
                    <h1 class="h2 mb-1" style="color: var(--text-primary);">
                        <i class="fas fa-utensils me-2" style="color: var(--primary);"></i>
                        Kelola Menu
                    </h1>
                    <p class="text-muted mb-0">Kelola dan update menu makanan & minuman Anda</p>
                </div>
                <div>
                    <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Menu Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0" style="color: var(--text-primary);">
                <i class="fas fa-list me-2" style="color: var(--accent);"></i>
                Daftar Menu Anda
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Gambar</th>
                            <th>Nama Menu</th>
                            <th style="width: 120px;">Harga</th>
                            <th style="width: 80px;">Stok</th>
                            <th>Warung</th>
                            <th>Area</th>
                            <th style="width: 150px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td>
                                @if($menu->gambar)
                                    <img src="{{ Storage::url($menu->gambar) }}" alt="{{ $menu->nama_menu }}" 
                                         class="rounded" width="60" height="60" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-utensils text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-1">{{ $menu->nama_menu }}</h6>
                                    <small class="text-muted">{{ Str::limit($menu->deskripsi ?? '', 50) }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold" style="color: var(--primary);">
                                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @if($menu->stok > 10)
                                    <span class="badge bg-success">{{ $menu->stok }}</span>
                                @elseif($menu->stok > 0)
                                    <span class="badge bg-warning">{{ $menu->stok }}</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                            <td>{{ $menu->nama_warung }}</td>
                            <td>{{ $menu->area_kampus }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('penjual.menu.edit', $menu->id) }}" 
                                       class="btn btn-sm btn-accent" title="Edit Menu">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('penjual.menu.destroy', $menu->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Yakin ingin menghapus menu ini?')"
                                                title="Hapus Menu">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
