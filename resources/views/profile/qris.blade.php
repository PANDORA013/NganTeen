@extends('layouts.penjual')

@section('title', 'Kelola QRIS')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header rounded-lg mb-4">
                <div class="container">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="h3 mb-2">
                                <i class="fas fa-qrcode me-2"></i>Kelola QRIS
                            </h1>
                            <p class="mb-0 text-white-50">Kelola metode pembayaran QRIS untuk toko Anda</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Current QRIS Status -->
        <div class="col-lg-4">
            <div class="card shadow-green menu-item h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Status QRIS
                    </h5>
                </div>
                <div class="card-body">
                    @if(auth()->user()->qris_image)
                        <div class="text-center mb-3">
                            <div class="qris-preview-container">
                                <img src="{{ Storage::url(auth()->user()->qris_image) }}" 
                                     alt="QRIS Code" 
                                     class="img-fluid rounded border"
                                     style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>
                                <strong>QRIS Aktif</strong><br>
                                <small>Pembeli dapat melakukan pembayaran</small>
                            </div>
                        </div>
                        <div class="d-grid">
                            <form method="POST" action="{{ route('profile.qris.delete') }}" 
                                  onsubmit="return confirm('Yakin ingin menghapus QRIS? Pembeli tidak akan bisa melakukan pembayaran.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash me-2"></i>Hapus QRIS
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center mb-3">
                            <i class="fas fa-qrcode fa-5x text-muted"></i>
                        </div>
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <strong>QRIS Belum Diatur</strong><br>
                                <small>Upload QRIS untuk menerima pembayaran</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- QRIS Upload Form -->
        <div class="col-lg-8">
            <div class="card shadow-green menu-item">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-upload me-2"></i>{{ auth()->user()->qris_image ? 'Perbarui QRIS' : 'Upload QRIS' }}
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Instructions -->
                    <div class="alert alert-info d-flex align-items-start" role="alert">
                        <i class="fas fa-lightbulb fa-lg me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading mb-2">Panduan Upload QRIS</h6>
                            <ol class="mb-0 small">
                                <li>Buka aplikasi mobile banking atau e-wallet Anda</li>
                                <li>Pilih menu "Terima Uang" atau "Merchant"</li>
                                <li>Generate QRIS code dan screenshot</li>
                                <li>Upload gambar QRIS di bawah ini</li>
                                <li>Pastikan QRIS dapat di-scan dengan jelas</li>
                            </ol>
                        </div>
                    </div>

                    @include('profile.partials.qris-upload-form')
                </div>
            </div>

            <!-- QRIS Information -->
            <div class="card shadow-green menu-item mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>Tentang QRIS
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-shield-alt text-success fa-lg me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Aman & Terpercaya</h6>
                                    <small class="text-muted">QRIS menggunakan standar keamanan Bank Indonesia</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-bolt text-warning fa-lg me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Pembayaran Instan</h6>
                                    <small class="text-muted">Transaksi langsung masuk ke rekening Anda</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-mobile-alt text-primary fa-lg me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Multi Platform</h6>
                                    <small class="text-muted">Dapat dibayar dari semua e-wallet dan mobile banking</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-chart-line text-info fa-lg me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Tingkatkan Penjualan</h6>
                                    <small class="text-muted">Lebih mudah bagi pembeli untuk bertransaksi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .qris-preview-container {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border: 2px dashed #dee2e6;
    }
    
    .qris-upload-area {
        border: 2px dashed #28a745;
        border-radius: 10px;
        padding: 40px 20px;
        text-align: center;
        background: #f8fff9;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .qris-upload-area:hover {
        border-color: #20c997;
        background: #e8f8f0;
    }
    
    .qris-upload-area.dragover {
        border-color: #17a2b8;
        background: #e8f4f8;
        transform: scale(1.02);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('qris-upload-area');
    const fileInput = document.getElementById('qris_image');
    const preview = document.getElementById('preview-container');
    
    if (uploadArea && fileInput) {
        // Click to upload
        uploadArea.addEventListener('click', () => fileInput.click());
        
        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });
        
        // File input change
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });
    }
    
    function handleFileSelect(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                if (preview) {
                    preview.innerHTML = `
                        <div class="qris-preview-container">
                            <img src="${e.target.result}" alt="Preview QRIS" 
                                 class="img-fluid rounded border" 
                                 style="max-width: 200px; max-height: 200px;">
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-check me-1"></i>File siap di-upload: ${file.name}
                                </small>
                            </div>
                        </div>
                    `;
                }
            };
            reader.readAsDataURL(file);
        }
    }
});
</script>
@endpush
@endsection
