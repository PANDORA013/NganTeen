<x-guest-layout>
    <!-- Auth Header -->
    <div class="auth-header">
        <div class="auth-logo">
            <i class="fas fa-utensils"></i>
        </div>
        <h1 class="auth-title">Selamat Datang Kembali</h1>
        <p class="auth-subtitle">Masuk ke akun NganTeen Anda untuk melanjutkan</p>
    </div>

    <!-- Auth Body -->
    <div class="auth-body">
        <!-- Session Status -->
        @if (session('status'))
            <div class="alert-auth alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
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
                    autofocus 
                    autocomplete="username"
                    placeholder="Masukkan alamat email Anda"
                />
                @error('email')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

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
                />
                @error('password')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-check">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="form-check-input" 
                    name="remember"
                />
                <label for="remember_me" class="form-check-label">
                    Ingat saya
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-auth-primary">
                <i class="fas fa-sign-in-alt me-2"></i>
                Masuk ke Akun
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
            Belum punya akun? 
            <a href="{{ route('register') }}" class="auth-link">
                <i class="fas fa-user-plus me-1"></i>
                Daftar sekarang
            </a>
        </p>
    </div>
</x-guest-layout>
