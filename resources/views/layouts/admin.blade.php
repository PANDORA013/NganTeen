<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') | NganTeen Admin</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Pro -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0/css/all.css">
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #334155;
            line-height: 1.6;
        }

        /* Header */
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1030;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-brand {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .header-brand i {
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-sidebar-toggle {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-sidebar-toggle:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Header Dropdowns */
        .dropdown-menu {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            min-width: 250px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .dropdown-header {
            padding: 0.75rem 1rem;
            color: #64748b;
            font-weight: 600;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 0.25rem;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            color: #374151;
            transition: all 0.2s ease;
            border-radius: 8px;
            margin: 0 0.5rem;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(3px);
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: #f1f5f9;
        }

        /* User Profile in Header */
        .header-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 25px;
            background: rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }

        .header-user-info:hover {
            background: rgba(255,255,255,0.2);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1020;
        }

        .sidebar-collapsed .admin-sidebar {
            width: var(--sidebar-collapsed-width);
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .sidebar-collapsed .nav-section-title {
            display: none;
        }

        .nav-item {
            margin: 0 1rem 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #64748b;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            background: #f1f5f9;
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .sidebar-collapsed .nav-link span {
            display: none;
        }

        .sidebar-collapsed .nav-link {
            justify-content: center;
        }

        .sidebar-collapsed .nav-link i {
            margin: 0;
        }

        /* Main Content */
        .admin-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
            transition: all 0.3s ease;
        }

        .sidebar-collapsed .admin-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Page Header */
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #64748b;
            margin: 0;
        }

        .page-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Cards */
        .admin-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .card-header {
            padding: 1.5rem 2rem 1rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .card-body {
            padding: 2rem;
        }

        /* Stats Cards */
        .stats-card {
            background: linear-gradient(135deg, var(--card-bg-from, #667eea) 0%, var(--card-bg-to, #764ba2) 100%);
            color: white;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .stats-card:hover::before {
            transform: scale(1.5);
        }

        .stats-content {
            position: relative;
            z-index: 2;
        }

        .stats-icon {
            font-size: 2.5rem;
            opacity: 0.8;
            margin-bottom: 1rem;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            border: none;
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            border: none;
        }

        /* Tables */
        .admin-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: #f8fafc;
            border: none;
            font-weight: 600;
            color: #475569;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            border-color: #e2e8f0;
            vertical-align: middle;
        }

        /* Forms */
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Tooltip Enhancements */
        .tooltip {
            background: rgba(0,0,0,0.9);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Loading States */
        .stat-item.loading .stat-number {
            opacity: 0.5;
            animation: pulse 1.5s infinite;
        }

        .notification-badge.updating {
            animation: bounce 0.6s ease-in-out;
        }

        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* Enhanced Focus States */
        .header-search input:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(255,255,255,0.2);
        }

        /* Search Dropdown Results */
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            max-height: 300px;
            overflow-y: auto;
            z-index: 1040;
            margin-top: 0.5rem;
        }

        .search-result-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-result-item:hover {
            background: #f8fafc;
            transform: translateX(3px);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: var(--sidebar-collapsed-width);
            }
            
            .admin-content {
                margin-left: var(--sidebar-collapsed-width);
            }
            
            .nav-link span {
                display: none;
            }
            
            .nav-section-title {
                display: none;
            }
        }

        @media (max-width: 1200px) {
            .header-stats .stat-item {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 992px) {
            .admin-header {
                padding: 0 1rem;
            }
            
            .header-search {
                margin: 0 0.5rem;
            }
        }

        /* Loading Spinner */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e2e8f0;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Dark Mode */
        [data-bs-theme="dark"] {
            --bs-body-bg: #1a202c;
            --bs-body-color: #e2e8f0;
        }

        [data-bs-theme="dark"] .admin-sidebar {
            background: #2d3748;
        }

        [data-bs-theme="dark"] .admin-card {
            background: #2d3748;
            border-color: #4a5568;
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
        }
    </style>
    
    @stack('styles')
</head>
<body class="@yield('body-class', '')">
    <!-- Loading Overlay -->
    <div class="loading-overlay d-none" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Header -->
    <header class="admin-header">
        <div class="d-flex align-items-center">
            <button class="btn btn-sidebar-toggle me-3" id="sidebarToggle" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="header-brand">
                <i class="fas fa-utensils"></i>
                <span>NganTeen Admin</span>
            </a>
        </div>
        
        <div class="header-controls">
            <!-- Theme Toggle -->
            <button class="btn btn-sidebar-toggle" id="themeToggle" title="Toggle Dark Mode">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
            
            <!-- Notifications -->
            <div class="dropdown">
                <button class="btn btn-sidebar-toggle position-relative" data-bs-toggle="dropdown" title="Notifikasi">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <h6 class="dropdown-header">Notifikasi</h6>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-shopping-cart text-primary me-2"></i>
                        Pesanan baru masuk
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user text-success me-2"></i>
                        User baru mendaftar
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-center" href="#">Lihat Semua</a>
                </div>
            </div>
            
            <!-- Profile -->
            <div class="dropdown">
                <button class="btn btn-sidebar-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-2"></i>
                    <span>{{ Auth::user()->name }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="dropdown-header">
                        <strong>{{ Auth::user()->name }}</strong><br>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user me-2"></i>Profile
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cog me-2"></i>Pengaturan
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="admin-sidebar" id="adminSidebar">
        @include('admin.partials.sidebar_minimal')
    </nav>

    <!-- Main Content -->
    <main class="admin-content">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            // Sidebar Toggle
            $('#sidebarToggle').click(function() {
                $('body').toggleClass('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', $('body').hasClass('sidebar-collapsed'));
            });

            // Load sidebar state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                $('body').addClass('sidebar-collapsed');
            }

            // Theme Toggle
            $('#themeToggle').click(function() {
                const html = document.documentElement;
                const currentTheme = html.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                html.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                
                $('#themeIcon').removeClass('fa-moon fa-sun').addClass(newTheme === 'dark' ? 'fa-sun' : 'fa-moon');
            });

            // Load theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            $('#themeIcon').removeClass('fa-moon fa-sun').addClass(savedTheme === 'dark' ? 'fa-sun' : 'fa-moon');

            // CSRF Token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Global loading functions
            window.showLoading = function() {
                $('#loadingOverlay').removeClass('d-none');
            };

            window.hideLoading = function() {
                $('#loadingOverlay').addClass('d-none');
            };

            // Auto-hide alerts
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
