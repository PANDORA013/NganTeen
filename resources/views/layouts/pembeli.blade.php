<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pembeli - {{ config('app.name', 'NganTeen') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/pembeli.css') }}" rel="stylesheet">
    
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
    </style>
</head>
<body class="d-flex flex-column h-100">
    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('pembeli.dashboard') }}">
                <i class="fas fa-utensils me-2"></i>NganTeen
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                        <a class="nav-link {{ request()->routeIs('pembeli.menu.*') ? 'active' : '' }}" 
                           href="{{ route('pembeli.menu.index') }}">
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
                    <li class="nav-item me-3 position-relative">
                        <a class="nav-link {{ request()->routeIs('pembeli.cart.*') ? 'active' : '' }}" 
                           href="{{ route('pembeli.cart.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i> Keranjang
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                            @endphp
                            @if($cartCount > 0)
                                <span class="badge rounded-pill bg-danger">{{ $cartCount }}</span>
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
    <main id="main-content" class="flex-shrink-0" role="main">
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show menu-item" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show menu-item" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> 
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-2 mb-md-0">
                        <i class="fas fa-utensils me-2 text-primary"></i>
                        <span class="fw-bold">NganTeen</span>
                    </div>
                    <small class="text-muted">© {{ date('Y') }} NganTeen. Semua hak dilindungi.</small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="social-links">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize modals with error handling
            initializeModals();
            
            // Initialize number formatting
            initializeNumberFormatting();
            
            // Setup form submission handlers
            setupFormSubmissionHandlers();
        });
        
        function initializeModals() {
            try {
                if (typeof bootstrap === 'undefined' || typeof bootstrap.Modal === 'undefined') {
                    console.warn('Bootstrap Modal not available in pembeli layout.');
                    return;
                }
                
                const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"]');
                console.log('Pembeli layout - Found modal triggers:', modalTriggers.length);
                
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
                                
                                console.log(`Pembeli modal ${index + 1} initialized:`, targetId);
                            }
                        }
                    } catch (error) {
                        console.error(`Error initializing pembeli modal ${index + 1}:`, error);
                    }
                });
                
            } catch (error) {
                console.error('Pembeli modal initialization error:', error);
            }
        }
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize number formatting
            initializeNumberFormatting();
            
            // Setup form submission handlers
            setupFormSubmissionHandlers();
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Add loading state to forms
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>' + submitBtn.textContent;
                        submitBtn.disabled = true;
                    }
                });
            });
        });
        
        // Number formatting functions
        function initializeNumberFormatting() {
            // Find all price inputs and apply formatting
            const priceInputs = document.querySelectorAll('input[name*="harga"], input[name*="price"], input[type="number"][placeholder*="harga"], input[type="number"][placeholder*="price"]');
            
            priceInputs.forEach(input => {
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
            
            // Remove all non-digit characters except dots and commas
            value = value.replace(/[^\d.,]/g, '');
            
            // Convert to number and format
            const numericValue = unformatNumber(value);
            if (numericValue !== '') {
                input.value = formatNumber(numericValue);
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
        
        // Setup form submission handlers
        function setupFormSubmissionHandlers() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    console.log('Pembeli form submission detected, converting formatted numbers...');
                    
                    const priceInputs = form.querySelectorAll('input[name="harga"], input[name*="price"], input[id="harga"]');
                    
                    priceInputs.forEach(input => {
                        if (input.value && input.value.includes('.')) {
                            const originalValue = input.value;
                            input.value = unformatNumber(input.value);
                            console.log(`Pembeli converted ${originalValue} → ${input.value}`);
                        }
                    });
                });
            });
        }
        
        // Global utility functions
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container') || createToastContainer();
            const toast = createToast(message, type);
            toastContainer.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Remove toast after it's hidden
            toast.addEventListener('hidden.bs.toast', function() {
                toast.remove();
            });
        }
        
        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
            return container;
        }
        
        function createToast(message, type) {
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="toast-header">
                    <i class="fas fa-${type === 'success' ? 'check-circle text-success' : 'exclamation-circle text-danger'} me-2"></i>
                    <strong class="me-auto">NganTeen</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">${message}</div>
            `;
            return toast;
        }

        // Global functions for forms that need manual formatting
        window.formatCurrency = formatNumber;
        window.unformatCurrency = unformatNumber;
    </script>
    @stack('scripts')
</body>
</html>
