<x-guest-layout>
    <!-- Auth Header -->
    <div class="auth-header">
        <div class="auth-logo">
            <i class="fas fa-user-plus"></i>
        </div>
        <h1 class="auth-title">Bergabung dengan NganTeen</h1>
        <p class="auth-subtitle">Buat akun baru dan nikmati kemudahan pesan makanan</p>
    </div>

    <!-- Auth Body -->
    <div class="auth-body">
        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-user"></i>
                    Nama Lengkap
                </label>
                <input 
                    id="name" 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    autocomplete="name"
                    placeholder="Masukkan nama lengkap Anda"
                />
                @error('name')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

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
                    autocomplete="username"
                    placeholder="Masukkan alamat email aktif Anda"
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
                    placeholder="Ketik ulang kata sandi Anda"
                />
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Role Selection -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-users"></i>
                    Daftar Sebagai
                </label>
                <div class="role-selector">
                    <div class="role-option">
                        <input 
                            type="radio" 
                            id="role_pembeli" 
                            name="role" 
                            value="pembeli" 
                            {{ old('role', 'pembeli') == 'pembeli' ? 'checked' : '' }}
                            required
                        />
                        <label for="role_pembeli">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Pembeli</span>
                        </label>
                    </div>
                    <div class="role-option">
                        <input 
                            type="radio" 
                            id="role_penjual" 
                            name="role" 
                            value="penjual" 
                            {{ old('role') == 'penjual' ? 'checked' : '' }}
                            required
                        />
                        <label for="role_penjual">
                            <i class="fas fa-store"></i>
                            <span>Penjual</span>
                        </label>
                    </div>
                </div>
                @error('role')
                    <div class="invalid-feedback" style="display: block;">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-auth-primary">
                <i class="fas fa-user-plus me-2"></i>
                Buat Akun Sekarang
            </button>
        </form>
    </div>

    <!-- Auth Footer -->
    <div class="auth-footer">
        <p>
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="auth-link">
                <i class="fas fa-sign-in-alt me-1"></i>
                Masuk di sini
            </a>
        </p>
    </div>
</x-guest-layout>
