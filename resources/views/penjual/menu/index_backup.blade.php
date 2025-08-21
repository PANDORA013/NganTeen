@extends('layouts.penjual')

@section('content')
<div class="container">
    <!-- Clean Header - Focused on Menu Management -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-utensils me-2 text-primary"></i>
                        Kelola Menu
                    </h1>
                    <p class="text-muted mb-0">Tambah, edit, dan kelola menu Anda</p>
                </div>
                <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Menu
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Menu List Table -->
    @if($menus->count() > 0)
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-secondary"></i>
                Daftar Menu ({{ $menus->count() }} menu)
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">Gambar</th>
                            <th>Menu</th>
                            <th style="width: 120px;">Harga</th>
                            <th style="width: 80px;">Stok</th>
                            <th style="width: 120px;">Kategori</th>
                            <th style="width: 150px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td>
                                @if($menu->gambar)
                                    <img src="{{ Storage::url($menu->gambar) }}" alt="{{ $menu->nama_menu }}" 
                                         class="rounded border" 
                                         width="60" height="60" 
                                         style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-utensils text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $menu->nama_menu }}</h6>
                                    <small class="text-muted">{{ Str::limit($menu->deskripsi ?? 'Tidak ada deskripsi', 40) }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @if($menu->stok > 10)
                                    <span class="badge bg-success">{{ $menu->stok }}</span>
                                @elseif($menu->stok > 0)
                                    <span class="badge bg-warning text-dark">{{ $menu->stok }}</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $menu->kategori ?? 'Lainnya' }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('penjual.menu.edit', $menu->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Edit Menu">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('penjual.menu.destroy', $menu->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Yakin ingin menghapus menu {{ $menu->nama_menu }}?')"
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
    @else
    <!-- Empty State -->
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
            <h5 class="text-muted mb-2">Belum ada menu</h5>
            <p class="text-muted mb-4">Mulai tambahkan menu pertama untuk warung Anda</p>
            <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Menu Pertama
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
