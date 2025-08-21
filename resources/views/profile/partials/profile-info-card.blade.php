{{-- Profile Information Card --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-gradient-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-user-circle me-2"></i>Informasi Akun
        </h5>
    </div>
    <div class="card-body">
        {{-- Email Verification Status --}}
        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
            <div class="flex-shrink-0">
                @if(auth()->user()->email_verified_at)
                    <i class="fas fa-envelope-circle-check text-success fa-2x"></i>
                @else
                    <i class="fas fa-envelope text-warning fa-2x"></i>
                @endif
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-1 fw-semibold">Email Terverifikasi</h6>
                <p class="mb-0 text-muted small">{{ auth()->user()->email }}</p>
                @if(!auth()->user()->email_verified_at)
                    <span class="badge bg-warning text-dark mt-1">Belum Terverifikasi</span>
                @endif
            </div>
        </div>

        {{-- Password Last Updated --}}
        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
            <div class="flex-shrink-0">
                <i class="fas fa-key text-primary fa-2x"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-1 fw-semibold">Password Terakhir Diubah</h6>
                <p class="mb-0 text-muted small">
                    @if(auth()->user()->password_updated_at)
                        {{ auth()->user()->password_updated_at->diffForHumans() }}
                    @elseif(auth()->user()->updated_at && auth()->user()->updated_at != auth()->user()->created_at)
                        {{ auth()->user()->updated_at->diffForHumans() }}
                    @else
                        Sejak pendaftaran
                    @endif
                </p>
            </div>
        </div>

        {{-- Last Login --}}
        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
            <div class="flex-shrink-0">
                <i class="fas fa-clock text-info fa-2x"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-1 fw-semibold">Login Terakhir</h6>
                <p class="mb-0 text-muted small">
                    {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'Tidak diketahui' }}
                </p>
            </div>
        </div>

        {{-- Account Role --}}
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                @if(auth()->user()->isAdmin())
                    <i class="fas fa-crown text-warning fa-2x"></i>
                @elseif(auth()->user()->isPenjual())
                    <i class="fas fa-store text-success fa-2x"></i>
                @else
                    <i class="fas fa-user text-primary fa-2x"></i>
                @endif
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-1 fw-semibold">Role Akun</h6>
                <span class="badge 
                    @if(auth()->user()->isAdmin()) 
                        bg-warning text-dark
                    @elseif(auth()->user()->isPenjual()) 
                        bg-success
                    @else 
                        bg-primary
                    @endif
                ">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>
    </div>
    
    {{-- Action Buttons --}}
    <div class="card-footer bg-light">
        <div class="d-grid gap-2">
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-edit me-2"></i>Edit Profil
            </a>
            <a href="{{ route('profile.password.edit') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-key me-2"></i>Ubah Password
            </a>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.card-header.bg-gradient-primary {
    border-bottom: none;
}

.fa-envelope-circle-check {
    color: #198754 !important;
}

.border-bottom {
    border-color: #e9ecef !important;
}

.card-footer {
    border-top: 1px solid #e9ecef;
}
</style>
