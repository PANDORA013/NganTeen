<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Foto Profil -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="text-center">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/'.$user->profile_photo) }}" alt="Foto Profil" class="rounded-circle shadow-green" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow-green" style="width: 150px; height: 150px; margin: 0 auto;">
                            <i class="fas fa-user fa-4x text-muted"></i>
                        </div>
                    @endif
                    <h6 class="mt-3 mb-0">{{ $user->name }}</h6>
                    <small class="text-muted">{{ $user->role }}</small>
                </div>
            </div>
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="profile_photo" class="form-label">
                        <i class="fas fa-camera me-2"></i>Foto Profil
                    </label>
                    <input id="profile_photo" name="profile_photo" type="file" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*">
                    @error('profile_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>Format yang didukung: JPG, PNG, maksimal 2MB
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <!-- Nama -->
        <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">
                <i class="fas fa-user me-2"></i>Nama Lengkap
            </label>
            <div class="col-sm-9">
                <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Email -->
        <div class="row mb-3">
            <label for="email" class="col-sm-3 col-form-label">
                <i class="fas fa-envelope me-2"></i>Email
            </label>
            <div class="col-sm-9">
                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="alert alert-warning mt-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Email belum terverifikasi.</strong>
                        <button form="send-verification" class="btn btn-link p-0 text-decoration-none">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2">
                            <i class="fas fa-check-circle me-2"></i>
                            Link verifikasi baru telah dikirim ke alamat email Anda.
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Role Info -->
        <div class="row mb-4">
            <label class="col-sm-3 col-form-label">
                <i class="fas fa-tag me-2"></i>Role
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>Role akun tidak dapat diubah setelah registrasi
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        Profil berhasil diperbarui!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</section>
