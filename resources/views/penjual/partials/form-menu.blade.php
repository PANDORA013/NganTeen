<!-- Edit Menu Form -->
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-edit me-2"></i>{{ isset($menuEdit) ? 'Edit Menu' : 'Tambah Menu Baru' }}
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ isset($menuEdit) ? route('penjual.menu.update', $menuEdit->id) : route('penjual.menu.store') }}" 
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($menuEdit))
                @method('PUT')
            @endif

            @if(isset($menuEdit) && $menuEdit->gambar)
                <div class="mb-4">
                    <label class="form-label fw-bold">Foto Menu Saat Ini</label>
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $menuEdit->gambar) }}" 
                             class="img-fluid rounded shadow" 
                             style="max-height: 200px;"
                             alt="{{ $menuEdit->nama_menu }}">
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">Foto Menu</label>
                    <input type="file" name="gambar" accept="image/*" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Menu</label>
                    <input type="text" name="nama_menu" 
                           value="{{ old('nama_menu', $menuEdit->nama_menu ?? '') }}" 
                           required class="form-control">
                    @error('nama_menu')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga (Rp)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga" 
                               value="{{ old('harga', $menuEdit->harga ?? '') }}" 
                               required min="0" class="form-control">
                    </div>
                    @error('harga')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" 
                           value="{{ old('stok', $menuEdit->stok ?? '') }}" 
                           required min="0" class="form-control">
                    @error('stok')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Area Kampus</label>
                    <select name="area_kampus" required class="form-select">
                        <option value="">Pilih Area</option>
                        <option value="Kampus A" {{ (old('area_kampus', $menuEdit->area_kampus ?? '') == 'Kampus A') ? 'selected' : '' }}>
                            Kampus A
                        Kampus A
                    </option>
                    <option value="Kampus B" {{ (old('area_kampus', $menuEdit->area_kampus ?? '') == 'Kampus B') ? 'selected' : '' }}>
                        Kampus B
                    </option>
                    <option value="Kampus C" {{ (old('area_kampus', $menuEdit->area_kampus ?? '') == 'Kampus C') ? 'selected' : '' }}>
                        Kampus C
                        </option>
                        <option value="Kampus B" {{ (old('area_kampus', $menuEdit->area_kampus ?? '') == 'Kampus B') ? 'selected' : '' }}>
                            Kampus B
                        </option>
                        <option value="Kampus C" {{ (old('area_kampus', $menuEdit->area_kampus ?? '') == 'Kampus C') ? 'selected' : '' }}>
                            Kampus C
                        </option>
                    </select>
                    @error('area_kampus')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Nama Warung</label>
                    <input type="text" name="nama_warung" 
                           value="{{ old('nama_warung', $menuEdit->nama_warung ?? '') }}" 
                           required class="form-control">
                    @error('nama_warung')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Deskripsi Menu (Opsional)</label>
                    <textarea name="deskripsi" rows="3" class="form-control" 
                              placeholder="Jelaskan menu Anda...">{{ old('deskripsi', $menuEdit->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                @if(isset($menuEdit))
                    <button type="button" onclick="window.history.back()" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                @endif
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>{{ isset($menuEdit) ? 'Update Menu' : 'Simpan Menu' }}
                </button>
            </div>
        </form>
    </div>
</div>
