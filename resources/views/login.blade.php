<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Nganteen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .logo {
            max-width: 200px;
            margin-bottom: 2rem;
        }
        .form-control:focus {
            border-color: #212529;
            box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.25);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="login-container bg-white">
            <div class="text-center">
                <img src="{{ asset('images/logo.png') }}" alt="Nganteen Logo" class="logo">
                <h4 class="mb-4">Masuk ke Akun Anda</h4>
            </div>
            
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show mb-3">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('actionlogin') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required 
                           autocomplete="email">
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required 
                           autocomplete="current-password">
                </div>

                <button type="submit" class="btn btn-dark w-100 mb-3">Login</button>
            </form>

            <p class="text-center mb-0">
                Belum punya akun? <a href="{{ route('register') }}" class="text-dark">Daftar</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
