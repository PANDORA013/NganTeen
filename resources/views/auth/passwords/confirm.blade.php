<x-guest-layout>
    <!-- Auth Header -->
    <div class="auth-header">
        <div class="auth-logo">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h1 class="auth-title">Konfirmasi Kata Sandi</h1>
        <p class="auth-subtitle">Silakan konfirmasi kata sandi Anda untuk melanjutkan</p>
    </div>

    <!-- Auth Body -->
    <div class="auth-body">
        <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
            @csrf

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i>
                    Kata Sandi
                </label>
                <input 
                    id="password" 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Masukkan kata sandi Anda"
                    autofocus
                />
                @error('password')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-auth-primary">
                <i class="fas fa-check me-2"></i>
                Konfirmasi Password
            </button>

            <!-- Forgot Password Link -->
            @if (Route::has('password.request'))
                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}" class="auth-link">
                        <i class="fas fa-key me-1"></i>
                        Lupa kata sandi?
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Auth Footer -->
    <div class="auth-footer">
        <p>
            <i class="fas fa-info-circle me-1"></i>
            Untuk keamanan, kami perlu memverifikasi identitas Anda
        </p>
    </div>
</x-guest-layout>
