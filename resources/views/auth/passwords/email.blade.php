<x-guest-layout>
    <!-- Auth Header -->
    <div class="auth-header">
        <div class="auth-logo">
            <i class="fas fa-key"></i>
        </div>
        <h1 class="auth-title">Lupa Kata Sandi?</h1>
        <p class="auth-subtitle">Jangan khawatir, kami akan mengirimkan link reset ke email Anda</p>
    </div>

    <!-- Auth Body -->
    <div class="auth-body">
        @if (session('status'))
            <div class="alert-auth alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i>
                    Alamat Email
                </label>
                <input 
                    id="email" 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="email" 
                    autofocus
                    placeholder="Masukkan alamat email akun Anda"
                />
                @error('email')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-auth-primary">
                <i class="fas fa-paper-plane me-2"></i>
                Kirim Link Reset Password
            </button>
        </form>
    </div>

    <!-- Auth Footer -->
    <div class="auth-footer">
        <p>
            Ingat kata sandi Anda? 
            <a href="{{ route('login') }}" class="auth-link">
                <i class="fas fa-sign-in-alt me-1"></i>
                Kembali ke login
            </a>
        </p>
    </div>
</x-guest-layout>
