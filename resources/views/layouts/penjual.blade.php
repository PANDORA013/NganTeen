<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Penjual - {{ config('app.name', 'NganTeen') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/penjual.css') }}" rel="stylesheet">
    
    @stack('styles')
    
    <style>
        .sr-only {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }
        
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #000;
            color: #fff;
            padding: 8px;
            text-decoration: none;
            z-index: 1000;
        }
        
        .skip-link:focus {
            top: 6px;
        }
        
        /* Navigation Link Fixes */
        .navbar-nav .nav-link {
            position: relative;
            z-index: 2;
            cursor: pointer !important;
            pointer-events: auto !important;
        }
        
        .navbar-nav .nav-item {
            position: relative;
        }
        
        .navbar-nav .badge {
            pointer-events: none !important;
            z-index: 1;
        }
        
        /* Ensure clickable area */
        .nav-link:hover {
            text-decoration: none;
        }
        
        /* Debug untuk memastikan link bisa diklik */
        .nav-link {
            border: 1px solid transparent;
        }
        
        .nav-link:hover {
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
    </style>
</head>
<body class="d-flex flex-column h-100 bg-light">
    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('penjual.dashboard') }}">
                <i class="fas fa-store me-2 text-primary"></i>
                <span class="text-primary">NganTeen</span> 
                <span class="text-secondary">Seller</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Main Navigation -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}" 
                           href="/penjual/dashboard">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('penjual.menu.*') ? 'active' : '' }}" 
                           href="/penjual/menu">
                            <i class="fas fa-utensils me-1"></i>Kelola Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('penjual.orders*') ? 'active' : '' }}" 
                           href="/penjual/orders">
                            <i class="fas fa-shopping-bag me-1"></i>Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('penjual.payouts*') ? 'active' : '' }}" 
                           href="/penjual/payouts">
                            <i class="fas fa-money-bill-wave me-1"></i>Pencairan
                        </a>
                    </li>
                </ul>
                
                <!-- User Menu -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('penjual.profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>Profil Saya
                                </a>
                            </li>
                            @if(Auth::user()->warung)
                                <li>
                                    <a class="dropdown-item" href="{{ route('penjual.warung.edit') }}">
                                        <i class="fas fa-store me-2"></i>Edit Warung
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="{{ route('penjual.warung.setup') }}">
                                        <i class="fas fa-store me-2"></i>Daftarkan Warung
                                    </a>
                                </li>
                            @endif
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
    <main id="main-content" class="flex-shrink-0 py-4" role="main">
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
        <div class="container text-center">
            <div class="row">
                <div class="col-md-6 text-md-start">
                    <span class="text-muted">© {{ date('Y') }} NganTeen Seller Panel. All rights reserved.</span>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="text-muted">
                        <i class="fas fa-user-shield me-1"></i> {{ Auth::user()->name }}
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enhanced initialization with modal error handling
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize modals first
            initializeModals();
            
            // Enable Bootstrap tooltips
            initializeTooltips();
            
            // Initialize number formatting
            initializeNumberFormatting();
            
            // Setup form submission handlers
            setupFormSubmissionHandlers();
        });
        
        function initializeModals() {
            try {
                if (typeof bootstrap === 'undefined' || typeof bootstrap.Modal === 'undefined') {
                    console.warn('Bootstrap Modal not available in penjual layout.');
                    return;
                }
                
                const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"]');
                console.log('Penjual layout - Found modal triggers:', modalTriggers.length);
                
                modalTriggers.forEach((trigger, index) => {
                    try {
                        const targetId = trigger.getAttribute('data-bs-target');
                        if (targetId) {
                            const targetModal = document.querySelector(targetId);
                            if (targetModal) {
                                const modalInstance = new bootstrap.Modal(targetModal, {
                                    backdrop: true,
                                    keyboard: true,
                                    focus: true
                                });
                                
                                console.log(`Penjual modal ${index + 1} initialized:`, targetId);
                            }
                        }
                    } catch (error) {
                        console.error(`Error initializing penjual modal ${index + 1}:`, error);
                    }
                });
                
            } catch (error) {
                console.error('Penjual modal initialization error:', error);
            }
        }
        
        function initializeTooltips() {
            try {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
                console.log('Penjual layout - Tooltips initialized:', tooltipList.length);
            } catch (error) {
                console.error('Error initializing tooltips:', error);
            }
        }

        // Number formatting functions
        function initializeNumberFormatting() {
            // Find all price inputs and apply formatting
            const priceInputs = document.querySelectorAll('input[name="harga"], input[name*="price"], input[id="harga"], input[placeholder*="harga"], input[placeholder*="price"]');
            
            console.log('Number formatting initialized. Found inputs:', priceInputs.length);
            
            priceInputs.forEach((input, index) => {
                console.log(`Applying formatting to input ${index + 1}:`, input.name, input.id, input.placeholder);
                
                // Set input mode for better mobile experience
                input.setAttribute('inputmode', 'numeric');
                
                // Add event listeners
                input.addEventListener('input', handleNumberInput);
                input.addEventListener('blur', handleNumberInput);
                input.addEventListener('focus', function() {
                    // Remove formatting on focus for easier editing
                    this.value = unformatNumber(this.value);
                });
                
                // Format existing value if any
                if (input.value) {
                    input.value = formatNumber(input.value);
                }
            });
        }

        function handleNumberInput(e) {
            const input = e.target;
            let value = input.value;
            
            console.log('HandleNumberInput called. Original value:', value);
            
            // Remove all non-digit characters except dots and commas
            value = value.replace(/[^\d.,]/g, '');
            
            // Convert to number and format
            const numericValue = unformatNumber(value);
            if (numericValue !== '') {
                const formattedValue = formatNumber(numericValue);
                console.log('Formatted value:', formattedValue);
                input.value = formattedValue;
            }
        }

        function formatNumber(value) {
            // Convert to string and remove any existing formatting
            let num = value.toString().replace(/[^\d]/g, '');
            
            if (num === '') return '';
            
            // Add thousand separators
            return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function unformatNumber(value) {
            if (typeof value !== 'string') value = value.toString();
            return value.replace(/\./g, '');
        }

        // Global functions for forms that need manual formatting
        window.formatCurrency = formatNumber;
        window.unformatCurrency = unformatNumber;
        
        // Form submission handler
        function setupFormSubmissionHandlers() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    console.log('Penjual form submission detected, converting formatted numbers...');
                    
                    const priceInputs = form.querySelectorAll('input[name="harga"], input[name*="price"], input[id="harga"]');
                    
                    priceInputs.forEach(input => {
                        if (input.value && input.value.includes('.')) {
                            const originalValue = input.value;
                            input.value = unformatNumber(input.value);
                            console.log(`Penjual converted ${originalValue} → ${input.value}`);
                        }
                    });
                });
            });
        }
        
        // Enhanced navigation debugging
        console.log('Layout loaded, navigation should work normally');
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up navigation debugging...');
            
            // Add click listeners to all nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach((link, index) => {
                link.addEventListener('click', function(e) {
                    console.log(`Navigation click #${index}:`, {
                        text: this.textContent.trim(),
                        href: this.href,
                        target: e.target,
                        currentURL: window.location.href
                    });
                    
                    // Special debugging for orders and payouts
                    if (this.href.includes('/orders') || this.href.includes('/payouts')) {
                        console.warn('⚠️ Clicking problematic link:', this.href);
                        
                        // Let the navigation proceed but log everything
                        setTimeout(() => {
                            console.log('After navigation attempt, current URL:', window.location.href);
                        }, 100);
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
