<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pembeli - {{ config('app.name', 'NganTeen') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/pembeli.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="d-flex flex-column h-100 bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('pembeli.dashboard') }}">
                <i class="fas fa-utensils me-2"></i>NganTeen
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pembeli.dashboard') ? 'active' : '' }}" 
                           href="{{ route('pembeli.dashboard') }}">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}" 
                           href="{{ route('menu.index') }}">
                            <i class="fas fa-utensils me-1"></i> Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pembeli.orders.*') ? 'active' : '' }}" 
                           href="{{ route('pembeli.orders.index') }}">
                            <i class="fas fa-history me-1"></i> Riwayat Pesanan
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('pembeli.cart.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i> Keranjang
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                            @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-edit me-2"></i>Profil Saya
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-shrink-0 py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> 
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <span class="text-muted">© {{ date('Y') }} NganTeen. All rights reserved.</span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="social-links">
                        <a href="#" class="text-muted me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enable Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Update cart count in navbar
            function updateCartCount() {
                fetch('{{ route("pembeli.cart.count") }}')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.querySelector('.cart-count');
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }
                    });
            }
            
            // Update cart count every 30 seconds
            setInterval(updateCartCount, 30000);
        });
    </script>
    @stack('scripts')
</body>
</html>
