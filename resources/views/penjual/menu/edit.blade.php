@extends('layouts.penjual')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Menu</h5>
                    <a href="{{ route('penjual.menu.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('penjual.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_menu" class="form-label">Nama Menu</label>
                            <input type="text" class="form-control" id="nama_menu" name="nama_menu" 
                                   value="{{ old('nama_menu', $menu->nama_menu) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="harga" class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-control" id="harga" name="harga" 
                                       value="{{ old('harga', $menu->harga) }}" min="1000" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stok" class="form-label">Stok Tersedia</label>
                                <input type="number" class="form-control" id="stok" name="stok" 
                                       value="{{ old('stok', $menu->stok) }}" min="0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="area_kampus" class="form-label">Area Kampus <span class="text-danger">*</span></label>
                            <select class="form-select @error('area_kampus') is-invalid @enderror" id="area_kampus" name="area_kampus" required>
                                <option value="" disabled>Pilih Area Kampus</option>
                                @foreach(['Kampus A', 'Kampus B', 'Kampus C'] as $area)
                                    <option value="{{ $area }}" {{ old('area_kampus', $menu->area_kampus) == $area ? 'selected' : '' }}>{{ $area }}</option>
                                @endforeach
                            </select>
                            @error('area_kampus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori Menu</label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach(['Makanan', 'Minuman', 'Snack', 'Paket', 'Lainnya'] as $kategori)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kategori" 
                                               id="kategori_{{ $kategori }}" value="{{ $kategori }}"
                                               {{ old('kategori', $menu->kategori) == $kategori ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="kategori_{{ $kategori }}">
                                            {{ $kategori }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Menu</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="nama_warung" class="form-label">Nama Warung</label>
                            <input type="text" class="form-control" id="nama_warung" name="nama_warung" 
                                   value="{{ old('nama_warung', $menu->nama_warung) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="gambar" class="form-label">Gambar Menu</label>
                            @if($menu->gambar)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($menu->gambar) }}" alt="{{ $menu->nama_menu }}" 
                                         class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                            <div class="form-text">Format: JPG, JPEG, PNG (Maks. 2MB)</div>
                        </div>

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="{{ route('penjual.menu.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
