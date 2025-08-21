@extends('layouts.penjual')

@section('title', 'Tambah Menu Baru')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Menu Baru</h5>
                    <a href="{{ route('penjual.menu.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Kelola Menu
                    </a>
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
                                <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                       id="harga" name="harga" value="{{ old('harga') }}" min="1000" required
                                       aria-describedby="harga-help">
                                <div id="harga-help" class="form-text">Minimum harga: Rp 1.000</div>
                                @error('harga')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                                       id="stok" name="stok" value="{{ old('stok', 1) }}" min="0" required
                                       aria-describedby="stok-help">
                                <div id="stok-help" class="form-text">Jumlah stok yang tersedia</div>
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
                                @foreach(['Kampus A', 'Kampus B', 'Kampus C'] as $area)
                                    <option value="{{ $area }}" {{ old('area_kampus') == $area ? 'selected' : '' }}>{{ $area }}</option>
                                @endforeach
                            </select>
                            @error('area_kampus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori Menu <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach(['Makanan', 'Minuman', 'Snack', 'Paket', 'Lainnya'] as $kategori)
                                    <div class="form-check">
                                        <input class="form-check-input @error('kategori') is-invalid @enderror" 
                                               type="radio" name="kategori" id="kategori_{{ $kategori }}" 
                                               value="{{ $kategori }}" {{ old('kategori') == $kategori ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="kategori_{{ $kategori }}">
                                            {{ $kategori }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('kategori')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Menu <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="3" 
                                      placeholder="Deskripsikan menu Anda..." required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
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
                                   id="gambar" name="gambar" accept="image/*" aria-describedby="gambar-help">
                            <div id="gambar-help" class="form-text">Format: JPG, JPEG, PNG (Maks. 2MB)</div>
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="mt-2" id="image-preview" style="display: none;">
                                <img id="preview" src="#" alt="Preview Gambar" 
                                     class="img-thumbnail" 
                                     style="max-height: 200px; max-width: 100%; object-fit: cover; border-radius: 8px;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="{{ route('penjual.menu.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Batal
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
            // Convert formatted numbers back to numeric before submission
            const priceInputs = form.querySelectorAll('input[name="harga"], input[name*="price"]');
            priceInputs.forEach(input => {
                if (input.value) {
                    // Remove formatting (convert 10.000 to 10000)
                    input.value = input.value.replace(/\./g, '');
                    console.log('Form submission - converted price:', input.value);
                }
            });
            
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            
            form.classList.add('was-validated')
        }, false)
    })
    
    // Enhanced image preview functionality
    const imageInput = document.getElementById('gambar');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview');
    
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.');
                    this.value = '';
                    imagePreview.style.display = 'none';
                    return;
                }
                
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    this.value = '';
                    imagePreview.style.display = 'none';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                    
                    // Add smooth animation
                    imagePreview.style.opacity = '0';
                    setTimeout(() => {
                        imagePreview.style.transition = 'opacity 0.3s ease-in-out';
                        imagePreview.style.opacity = '1';
                    }, 10);
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }
})()
</script>
@endpush
@endsection
