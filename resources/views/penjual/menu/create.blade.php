@extends('layouts.penjual')

@section('title', 'Tambah Menu Baru')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0">Tambah Menu Baru</h2>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('penjual.menu.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama_menu" class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_menu') is-invalid @enderror" 
                                   id="nama_menu" name="nama_menu" value="{{ old('nama_menu') }}" required>
                            @error('nama_menu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                           id="harga" name="harga" value="{{ old('harga') }}" min="100" required>
                                </div>
                                @error('harga')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                                       id="stok" name="stok" value="{{ old('stok', 1) }}" min="0" required>
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="area_kampus" class="form-label">Area Kampus <span class="text-danger">*</span></label>
                            <select class="form-select @error('area_kampus') is-invalid @enderror" 
                                    id="area_kampus" name="area_kampus" required>
                                <option value="" disabled selected>Pilih Area Kampus</option>
                                <option value="Kampus A" {{ old('area_kampus') == 'Kampus A' ? 'selected' : '' }}>Kampus A</option>
                                <option value="Kampus B" {{ old('area_kampus') == 'Kampus B' ? 'selected' : '' }}>Kampus B</option>
                                <option value="Kampus C" {{ old('area_kampus') == 'Kampus C' ? 'selected' : '' }}>Kampus C</option>
                            </select>
                            @error('area_kampus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama_warung" class="form-label">Nama Warung <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_warung') is-invalid @enderror" 
                                   id="nama_warung" name="nama_warung" value="{{ old('nama_warung') }}" required>
                            @error('nama_warung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="gambar" class="form-label">Foto Menu</label>
                            <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                                   id="gambar" name="gambar" accept="image/*">
                            <div class="form-text">Format: JPG, JPEG, PNG (Maks. 2MB)</div>
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="mt-2" id="image-preview" style="display: none;">
                                <img id="preview" src="#" alt="Preview Gambar" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('penjual.menu.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Menu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Image preview
function readURL(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('image-preview');
            
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('gambar').addEventListener('change', function() {
    readURL(this);
});

// Form validation
(function () {
    'use strict'
    
    const forms = document.querySelectorAll('.needs-validation')
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
@endpush
@endsection
