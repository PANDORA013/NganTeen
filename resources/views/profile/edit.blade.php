@extends(auth()->user()->isPenjual() ? 'layouts.penjual' : 'layouts.pembeli')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header rounded-lg mb-4">
                <div class="container">
                    <h1 class="h3 mb-2">
                        <i class="fas fa-user-circle me-2"></i>Profil Saya
                    </h1>
                    <p class="mb-0 text-white-50">Kelola informasi akun dan keamanan profil Anda</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Information -->
        <div class="col-lg-8">
            <div class="card shadow-green menu-item">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Informasi Profil
                    </h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="col-lg-4">
            <div class="card shadow-green menu-item">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Keamanan Akun
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle text-success fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-0">Akun Terverifikasi</h6>
                            <small class="text-muted">Email Anda sudah terverifikasi</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-clock text-warning fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-0">Login Terakhir</h6>
                            <small class="text-muted">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'Tidak diketahui' }}</small>
                        </div>
                    </div>
                    <a href="#password-section" class="btn btn-outline-primary w-100">
                        <i class="fas fa-key me-2"></i>Ubah Password
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Update Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-green menu-item" id="password-section">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>Ubah Password
                    </h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isPenjual())
        <!-- QRIS Section (Only for Penjual) -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-green menu-item">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-qrcode me-2"></i>QRIS Payment Method
                        </h5>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.qris-upload-form')
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Danger Zone -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-danger menu-item">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Zona Berbahaya
                    </h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
