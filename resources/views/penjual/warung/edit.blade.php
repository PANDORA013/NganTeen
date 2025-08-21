@extends('layouts.penjual')

@section('title', 'Edit Warung')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Data Warung
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle me-2"></i>Edit Data Warung</h5>
                        <p class="mb-0">Perbarui informasi warung Anda. Pastikan data yang dimasukkan akurat untuk kelancaran operasional.</p>
                    </div>

                    <form action="{{ route('penjual.warung.update') }}" method="POST" id="warungEditForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Informasi Dasar Warung -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                </h5>
                                
                                <div class="mb-3">
                                    <label for="nama_warung" class="form-label">
                                        <i class="fas fa-store me-1"></i>Nama Warung <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nama_warung') is-invalid @enderror" 
                                           id="nama_warung" 
                                           name="nama_warung" 
                                           value="{{ old('nama_warung', $warung->nama_warung) }}" 
                                           required>
                                    @error('nama_warung')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="lokasi" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>Lokasi <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('lokasi') is-invalid @enderror" 
                                           id="lokasi" 
                                           name="lokasi" 
                                           value="{{ old('lokasi', $warung->lokasi) }}" 
                                           required>
                                    @error('lokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="no_hp" class="form-label">
                                        <i class="fas fa-phone me-1"></i>Nomor HP <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('no_hp') is-invalid @enderror" 
                                           id="no_hp" 
                                           name="no_hp" 
                                           value="{{ old('no_hp', $warung->no_hp) }}" 
                                           required>
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">
                                        <i class="fas fa-edit me-1"></i>Deskripsi Warung
                                    </label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" 
                                              name="deskripsi" 
                                              rows="3">{{ old('deskripsi', $warung->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Informasi Rekening -->
                            <div class="col-md-6">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-credit-card me-2"></i>Informasi Rekening
                                </h5>
                                
                                <div class="alert alert-warning">
                                    <small><i class="fas fa-exclamation-triangle me-1"></i>
                                    Data rekening digunakan untuk pencairan dana dari penjualan.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="nama_pemilik" class="form-label">
                                        <i class="fas fa-user me-1"></i>Nama Pemilik Rekening <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nama_pemilik') is-invalid @enderror" 
                                           id="nama_pemilik" 
                                           name="nama_pemilik" 
                                           value="{{ old('nama_pemilik', $warung->nama_pemilik) }}" 
                                           required>
                                    @error('nama_pemilik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="rekening_bank" class="form-label">
                                        <i class="fas fa-university me-1"></i>Bank <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('rekening_bank') is-invalid @enderror" 
                                            id="rekening_bank" 
                                            name="rekening_bank" 
                                            required>
                                        <option value="">Pilih Bank</option>
                                        <option value="BCA" {{ old('rekening_bank', $warung->rekening_bank) == 'BCA' ? 'selected' : '' }}>BCA</option>
                                        <option value="BNI" {{ old('rekening_bank', $warung->rekening_bank) == 'BNI' ? 'selected' : '' }}>BNI</option>
                                        <option value="BRI" {{ old('rekening_bank', $warung->rekening_bank) == 'BRI' ? 'selected' : '' }}>BRI</option>
                                        <option value="Mandiri" {{ old('rekening_bank', $warung->rekening_bank) == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                        <option value="CIMB Niaga" {{ old('rekening_bank', $warung->rekening_bank) == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                                        <option value="Danamon" {{ old('rekening_bank', $warung->rekening_bank) == 'Danamon' ? 'selected' : '' }}>Danamon</option>
                                        <option value="BTN" {{ old('rekening_bank', $warung->rekening_bank) == 'BTN' ? 'selected' : '' }}>BTN</option>
                                        <option value="Permata" {{ old('rekening_bank', $warung->rekening_bank) == 'Permata' ? 'selected' : '' }}>Permata</option>
                                    </select>
                                    @error('rekening_bank')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="no_rekening" class="form-label">
                                        <i class="fas fa-credit-card me-1"></i>Nomor Rekening <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('no_rekening') is-invalid @enderror" 
                                           id="no_rekening" 
                                           name="no_rekening" 
                                           value="{{ old('no_rekening', $warung->no_rekening) }}" 
                                           required>
                                    @error('no_rekening')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info mt-3">
                                    <small>
                                        <strong>Status Warung:</strong> 
                                        <span class="badge bg-success">{{ ucfirst($warung->status) }}</span><br>
                                        <strong>Terdaftar:</strong> {{ $warung->created_at->format('d M Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('penjual.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Dashboard
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>Perbarui Data Warung
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('warungEditForm');
    const requiredFields = form.querySelectorAll('[required]');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi (*)');
        }
    });
    
    // Phone number formatting
    const phoneInput = document.getElementById('no_hp');
    phoneInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.startsWith('0')) {
            this.value = value;
        } else if (value.length > 0) {
            this.value = '0' + value;
        }
    });
    
    // Account number formatting
    const accountInput = document.getElementById('no_rekening');
    accountInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });
});
</script>
@endpush
