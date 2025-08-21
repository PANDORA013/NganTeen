@extends(auth()->user()->isPenjual() ? 'layouts.penjual' : 'layouts.pembeli')

@section('title', 'Ubah Password')

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
                                <i class="fas fa-key me-2"></i>Ubah Password
                            </h1>
                            <p class="mb-0 text-white-50">Pastikan akun Anda menggunakan password yang aman</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Password Update Form -->
            <div class="card shadow-green menu-item">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Keamanan Password
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Security Tips -->
                    <div class="alert alert-info d-flex align-items-start" role="alert">
                        <i class="fas fa-info-circle fa-lg me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Tips Password Aman</h6>
                            <ul class="mb-0 small">
                                <li>Gunakan minimal 8 karakter</li>
                                <li>Kombinasikan huruf besar, kecil, angka, dan simbol</li>
                                <li>Hindari menggunakan informasi pribadi</li>
                                <li>Jangan gunakan password yang sama di platform lain</li>
                            </ul>
                        </div>
                    </div>

                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Security Status -->
            <div class="card shadow-green menu-item mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Status Keamanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-0">Email Terverifikasi</h6>
                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-warning fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-0">Password Terakhir Diubah</h6>
                                    <small class="text-muted">
                                        {{ auth()->user()->updated_at ? auth()->user()->updated_at->diffForHumans() : 'Tidak diketahui' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-sign-in-alt text-info fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-0">Login Terakhir</h6>
                                    <small class="text-muted">
                                        {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'Tidak diketahui' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-shield text-primary fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-0">Role Akun</h6>
                                    <small class="text-muted">
                                        {{ auth()->user()->isPenjual() ? 'Penjual' : 'Pembeli' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Password strength indicator
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });
        }
    });

    function checkPasswordStrength(password) {
        const strengthBar = document.getElementById('password-strength');
        const strengthText = document.getElementById('strength-text');
        
        if (!strengthBar || !strengthText) return;

        let strength = 0;
        let feedback = [];

        // Length check
        if (password.length >= 8) strength += 1;
        else feedback.push('Minimal 8 karakter');

        // Uppercase check
        if (/[A-Z]/.test(password)) strength += 1;
        else feedback.push('Huruf besar');

        // Lowercase check
        if (/[a-z]/.test(password)) strength += 1;
        else feedback.push('Huruf kecil');

        // Number check
        if (/\d/.test(password)) strength += 1;
        else feedback.push('Angka');

        // Special character check
        if (/[^A-Za-z0-9]/.test(password)) strength += 1;
        else feedback.push('Karakter khusus');

        // Update strength bar
        const percentage = (strength / 5) * 100;
        strengthBar.style.width = percentage + '%';

        // Update color and text
        let className = 'bg-danger';
        let text = 'Sangat Lemah';

        if (strength >= 4) {
            className = 'bg-success';
            text = 'Kuat';
        } else if (strength >= 3) {
            className = 'bg-warning';
            text = 'Sedang';
        } else if (strength >= 2) {
            className = 'bg-info';
            text = 'Lemah';
        }

        strengthBar.className = `progress-bar ${className}`;
        strengthText.textContent = text;
        
        if (feedback.length > 0 && password.length > 0) {
            strengthText.textContent += ` (Butuh: ${feedback.join(', ')})`;
        }
    }
</script>
@endpush
@endsection
