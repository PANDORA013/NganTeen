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
                    <p class="mb-0 text-white-50">Kelola informasi akun dan foto profil Anda</p>
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

        <!-- Profile Info Card -->
        <div class="col-lg-4">
            @include('profile.partials.profile-info-card')
        </div>
    </div>

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
