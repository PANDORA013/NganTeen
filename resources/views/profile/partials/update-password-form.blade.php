<section>
    <div class="mb-3">
        <p class="text-muted">
            <i class="fas fa-info-circle me-2"></i>
            Pastikan akun Anda menggunakan password yang panjang dan acak untuk tetap aman.
        </p>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <!-- Current Password -->
        <div class="row mb-3">
            <label for="update_password_current_password" class="col-sm-4 col-form-label">
                <i class="fas fa-lock me-2"></i>Password Saat Ini
            </label>
            <div class="col-sm-8">
                <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                       id="update_password_current_password" name="current_password" required autocomplete="current-password"
                       placeholder="Masukkan password saat ini">
                @error('current_password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- New Password -->
        <div class="row mb-3">
            <label for="update_password_password" class="col-sm-4 col-form-label">
                <i class="fas fa-key me-2"></i>Password Baru
            </label>
            <div class="col-sm-8">
                <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                       id="update_password_password" name="password" required autocomplete="new-password"
                       placeholder="Masukkan password baru">
                @error('password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-shield-alt me-1"></i>
                    Minimal 8 karakter, kombinasi huruf dan angka
                </div>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="row mb-4">
            <label for="update_password_password_confirmation" class="col-sm-4 col-form-label">
                <i class="fas fa-check-double me-2"></i>Konfirmasi Password
            </label>
            <div class="col-sm-8">
                <input type="password" class="form-control" 
                       id="update_password_password_confirmation" name="password_confirmation" required autocomplete="new-password"
                       placeholder="Ketik ulang password baru">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        Password berhasil diperbarui!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i>Ubah Password
            </button>
        </div>
    </form>
</section>
