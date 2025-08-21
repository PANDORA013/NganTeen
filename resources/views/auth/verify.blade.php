<x-guest-layout>
    <!-- Auth Header -->
    <div class="auth-header">
        <div class="auth-logo">
            <i class="fas fa-envelope-open"></i>
        </div>
        <h1 class="auth-title">Verifikasi Email</h1>
        <p class="auth-subtitle">Kami telah mengirim link verifikasi ke email Anda</p>
    </div>

    <!-- Auth Body -->
    <div class="auth-body">
        <div class="text-center mb-4">
            <p class="text-muted">
                Sebelum melanjutkan, mohon periksa email Anda untuk link verifikasi. 
                Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkan ulang.
            </p>
        </div>

        @if (session('resent'))
            <div class="alert-auth alert-success">
                <i class="fas fa-check-circle me-2"></i>
                Link verifikasi baru telah dikirim ke alamat email Anda.
            </div>
        @endif

        <div class="d-flex flex-column gap-3">
            <!-- Resend Verification Email -->
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn-auth-primary">
                    <i class="fas fa-redo me-2"></i>
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- Auth Footer -->
    <div class="auth-footer">
        <p>
            <i class="fas fa-question-circle me-1"></i>
            Periksa folder spam/junk jika tidak menemukan email kami
        </p>
    </div>
</x-guest-layout>
