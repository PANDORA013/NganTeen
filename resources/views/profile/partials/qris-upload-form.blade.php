@php
/** @var \App\Models\User $user */
$user = auth()->user();
@endphp

<section>
    <!-- Status Messages -->
    @if (session('status') === 'qris-uploaded')
        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>
                <strong>Berhasil!</strong> QRIS berhasil di-upload dan aktif untuk pembayaran.
            </div>
        </div>
    @elseif(session('status') === 'qris-deleted')
        <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <div>
                <strong>Berhasil!</strong> QRIS telah dihapus.
            </div>
        </div>
    @elseif(session('status') === 'no-qris-found')
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
                <strong>Info:</strong> Tidak ada QRIS yang ditemukan untuk dihapus.
            </div>
        </div>
    @endif

    @if ($errors->has('qris_image'))
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <div>
                <strong>Error!</strong> {{ $errors->first('qris_image') }}
            </div>
        </div>
    @endif

    <!-- Upload Form -->
    <form method="POST" action="{{ route('profile.qris.upload') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Upload Area -->
        <div class="mb-4">
            <div id="qris-upload-area" class="qris-upload-area">
                <i class="fas fa-cloud-upload-alt fa-3x text-success mb-3"></i>
                <h5 class="mb-2">{{ $user->qris_image ? 'Ganti QRIS' : 'Upload QRIS' }}</h5>
                <p class="text-muted mb-0">
                    Klik atau drag & drop gambar QRIS Anda di sini<br>
                    <small>Format: JPG, PNG, maksimal 2MB</small>
                </p>
                <input type="file" 
                       id="qris_image" 
                       name="qris_image" 
                       accept="image/*" 
                       class="d-none" 
                       required>
            </div>
        </div>

        <!-- Preview Area -->
        <div id="preview-container" class="mb-4"></div>

        <!-- Requirements -->
        <div class="card bg-light border-0 mb-4">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-check-square me-2 text-success"></i>Persyaratan QRIS
                </h6>
                <ul class="list-unstyled mb-0 small">
                    <li><i class="fas fa-check text-success me-2"></i>Format gambar: JPG, PNG, atau GIF</li>
                    <li><i class="fas fa-check text-success me-2"></i>Ukuran maksimal: 2MB</li>
                    <li><i class="fas fa-check text-success me-2"></i>Gambar harus jelas dan dapat di-scan</li>
                    <li><i class="fas fa-check text-success me-2"></i>Pastikan QRIS masih aktif</li>
                </ul>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-upload me-2"></i>
                {{ $user->qris_image ? 'Perbarui QRIS' : 'Upload QRIS' }}
            </button>
            
            @if($user->qris_image)
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    Terakhir diperbarui: {{ $user->updated_at->diffForHumans() }}
                </small>
            @endif
        </div>
    </form>
</section>