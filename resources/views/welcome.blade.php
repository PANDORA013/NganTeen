<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Nganteen') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logo-container img {
            display: inline-block;
            max-width: 200px;
            height: auto;
        }
        .auth-container {
            margin-top: 2rem;
            text-align: right;
        }
        .main-content {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .welcome-card {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 800px;
            width: 100%;
        }
        @media (prefers-color-scheme: dark) {
            body {
                background: #0a0a0a;
                color: #fff;
            }
            .welcome-card {
                background: #161615;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        @if (Route::has('login'))
            <div class="auth-container">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-dark">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-dark me-2">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-dark">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        <div class="main-content">
            <div class="welcome-card">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Nganteen Logo" class="logo-container mb-3">
                    <h1 class="h4">Welcome to Nganteen</h1>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h2 class="h5 mb-3">Getting Started</h2>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="https://laravel.com/docs" class="text-decoration-none">
                                    📚 Documentation
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="https://laracasts.com" class="text-decoration-none">
                                    🎥 Video Tutorials
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h2 class="h5 mb-3">Our Features</h2>
                        <ul class="list-unstyled">
                            <li class="mb-2">🛒 Easy Online Ordering</li>
                            <li class="mb-2">🏪 Multiple Vendors</li>
                            <li class="mb-2">💳 Secure Payments</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
