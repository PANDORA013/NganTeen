<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Vite CSS -->
    @vite(['resources/css/admin.css'])
    
    @stack('styles')
</head>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar">
                @include('admin.partials.sidebar')
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-10 main-content">
                <!-- Topbar -->
                <div class="topbar d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-link mobile-menu-btn me-3 d-lg-none" type="button" onclick="toggleSidebar()">
                            <i class="fas fa-bars text-primary"></i>
                        </button>
                        <div>
                            <h4 class="mb-0 text-gray-800">@yield('title', 'Dashboard')</h4>
                            <p class="text-muted mb-0 small">Sistem Manajemen NganTeen</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-3">{{ now()->format('d M Y, H:i') }}</span>
                        <div class="dropdown">
                            <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fa-lg text-primary"></i>
                                <span class="ms-1 text-gray-800">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <main class="px-4 pb-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Vite JS -->
    @vite(['resources/js/admin-dashboard.js'])
    
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            if (window.innerWidth <= 991.98 && 
                !sidebar.contains(event.target) && 
                !menuBtn.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
