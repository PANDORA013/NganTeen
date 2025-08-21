<!-- Daftar Menu -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-utensils me-2"></i>Daftar Menu
        </h6>
    </div>
    <div class="card-body">
        @if($menus->isEmpty())
            <div class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-utensils fa-3x mb-3"></i>
                    <p>Belum ada menu yang ditambahkan.</p>
                    <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Tambah Menu Pertama
                    </a>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($menus as $menu)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($menu->gambar)
                                <img src="{{ asset('storage/' . $menu->gambar) }}" 
                                     alt="{{ $menu->nama_menu }}" 
                                     class="card-img-top" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="card-body">
                                <h5 class="card-title">{{ $menu->nama_menu }}</h5>
                                <div class="mb-2">
                                    <small class="text-muted">Harga:</small> 
                                    <span class="fw-bold text-success">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Stok:</small> 
                                    <span class="fw-bold">{{ $menu->stok }}</span>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Area:</small> 
                                    <span class="badge bg-info">{{ $menu->area_kampus }}</span>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted">Warung:</small> 
                                    <span class="text-dark">{{ $menu->nama_warung }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                                    <a href="{{ route('penjual.menu.edit', $menu) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('penjual.menu.destroy', $menu) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin menghapus menu ini?')"
                                                class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
