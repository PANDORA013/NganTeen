<x-guest-layout>
    <!-- Auth Header -->
    <div class="auth-header">
        <div class="auth-logo">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h1 class="auth-title">Reset Kata Sandi</h1>
        <p class="auth-subtitle">Masukkan kata sandi baru untuk akun Anda</p>
    </div>

    <!-- Auth Body -->
    <div class="auth-body">
        <form method="POST" action="{{ route('password.store') }}" class="auth-form">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                    value="{{ old('email', $request->email) }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    readonly
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
                    Kata Sandi Baru
                </label>
                <input 
                    id="password" 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                />
                @error('password')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    <i class="fas fa-lock"></i>
                    Konfirmasi Kata Sandi
                </label>
                <input 
                    id="password_confirmation" 
                    type="password" 
                    class="form-control @error('password_confirmation') is-invalid @enderror" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    placeholder="Ketik ulang kata sandi baru"
                />
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-auth-primary">
                <i class="fas fa-save me-2"></i>
                Reset Kata Sandi
            </button>
        </form>
    </div>

    <!-- Auth Footer -->
    <div class="auth-footer">
        <p>
            <i class="fas fa-info-circle me-1"></i>
            Setelah reset, Anda akan diarahkan ke halaman login
        </p>
    </div>
</x-guest-layout>
