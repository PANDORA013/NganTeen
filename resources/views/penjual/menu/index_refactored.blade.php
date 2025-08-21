@extends('layouts.penjual')

@section('content')
<div class="container-fluid px-4">
    <!-- Clean Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-gray-900">
                        <i class="fas fa-utensils me-2 text-primary"></i>Kelola Menu
                    </h1>
                    <p class="text-muted mb-0">Tambah, edit, dan kelola menu Anda dengan mudah</p>
                </div>
                <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus me-2"></i>Tambah Menu
                </a>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Menu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $menus->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-utensils fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Stok Tersedia</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $menus->where('stok', '>', 0)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Stok Habis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $menus->where('stok', 0)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Kategori</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $menus->unique('kategori')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Table -->
    @if($menus->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Menu</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="menuTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="80">Gambar</th>
                            <th>Nama Menu</th>
                            <th width="100">Harga</th>
                            <th width="80">Stok</th>
                            <th width="100">Kategori</th>
                            <th width="120">Status</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td class="text-center">
                                @if($menu->gambar)
                                    <img src="{{ asset('storage/' . $menu->gambar) }}" 
                                         alt="{{ $menu->nama_menu }}" 
                                         class="img-thumbnail"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $menu->nama_menu }}</div>
                                <small class="text-muted">{{ Str::limit($menu->deskripsi, 50) }}</small>
                            </td>
                            <td>
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($menu->stok > 0)
                                    <span class="badge bg-success">{{ $menu->stok }}</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $menu->kategori }}</span>
                            </td>
                            <td>
                                @if($menu->stok > 0)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Tersedia
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation me-1"></i>Habis
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('penjual.menu.edit', $menu) }}" 
                                       class="btn btn-primary btn-sm"
                                       title="Edit Menu">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $menu->id }}, '{{ $menu->nama_menu }}')"
                                            title="Hapus Menu">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
    <div class="card shadow">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="fas fa-utensils fa-4x text-muted"></i>
            </div>
            <h5 class="text-muted mb-3">Belum Ada Menu</h5>
            <p class="text-muted mb-4">Mulai tambahkan menu pertama Anda untuk menarik lebih banyak pelanggan</p>
            <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Menu Pertama
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin menghapus menu <strong id="menuName"></strong>?</p>
                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.text-xs {
    font-size: 0.7rem !important;
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(menuId, menuName) {
    document.getElementById('menuName').textContent = menuName;
    document.getElementById('deleteForm').action = `/penjual/menu/${menuId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Initialize DataTable if available
document.addEventListener('DOMContentLoaded', function() {
    if (typeof DataTable !== 'undefined' && document.getElementById('menuTable')) {
        new DataTable('#menuTable', {
            pageLength: 10,
            order: [[1, 'asc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
            }
        });
    }
});
</script>
@endpush
