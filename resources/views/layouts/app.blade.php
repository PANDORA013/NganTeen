<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Real-time notification meta tags -->
    @auth
        <meta name="user-id" content="{{ auth()->id() }}">
        <meta name="user-role" content="{{ auth()->user()->role }}">
    @endauth
    
    <meta name="current-area" content="{{ request()->get('area', 'Kampus A') }}">

    <title>{{ config('app.name', 'NganTeen') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
        }
        
        html, body {
            height: 100%;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8fafc;
            display: flex;
            flex-direction: column;
        }
        
        .content-wrapper {
            flex: 1 0 auto;
            width: 100%;
            padding-bottom: 2rem;
        }
        
        .footer {
            flex-shrink: 0;
            background-color: #212529;
            color: white;
            padding: 2rem 0;
            margin-top: auto;
            width: 100%;
        }
        
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .card {
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0,0,0,.125);
            border-radius: 0.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-custom {
            background-color: var(--primary);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            color: white;
        }
        
        .btn-custom:hover {
            background-color: var(--primary-dark);
            color: white;
        }
        
        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
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
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fas fa-utensils me-2"></i>NganTeen
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                @auth
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pembeli.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pembeli.menu.index') }}">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pembeli.orders.index') }}">Riwayat Pesanan</a>
                    </li>
                </ul>
                @else
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pembeli.menu.index') }}">Menu</a>
                    </li>
                </ul>
                @endauth

                <div class="d-flex">
                    @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <div class="d-flex">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                    </div>
                    @endauth
                </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <main id="main-content" class="py-4 container" role="main">
            @yield('content')
        </main>
    </div>

    <footer class="footer mt-auto bg-dark text-white pt-4 pb-3">
        <div class="container">
            <div class="row mb-2">
                <div class="col-12 text-center">
                    <h5 class="mb-1">
                        <i class="fas fa-utensils me-2"></i>NganTeen
                    </h5>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0 text-muted small">
                        &copy; {{ date('Y') }} NganTeen. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <style>
        .bg-gradient-primary {
            background: linear-gradient(45deg, #4e73df, #224abe);
        }
        .footer {
            background-color: #2c3e50;
            background-image: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
    </style>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Modal Error Handling -->
    <script>
        // Wait for Bootstrap to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced modal initialization with error handling
            initializeModals();
            
            // Initialize number formatting
            initializeNumberFormatting();
            
            // Setup form submission handlers
            setupFormSubmissionHandlers();
        });
        
        function initializeModals() {
            try {
                // Check if Bootstrap Modal is available
                if (typeof bootstrap === 'undefined' || typeof bootstrap.Modal === 'undefined') {
                    console.warn('Bootstrap Modal not available. Modals may not work properly.');
                    return;
                }
                
                // Initialize all modal triggers with error handling
                const modalTriggers = document.querySelectorAll('[data-bs-toggle="modal"]');
                console.log('Found modal triggers:', modalTriggers.length);
                
                modalTriggers.forEach((trigger, index) => {
                    try {
                        const targetId = trigger.getAttribute('data-bs-target');
                        if (targetId) {
                            const targetModal = document.querySelector(targetId);
                            if (targetModal) {
                                // Pre-initialize modal with safe configuration
                                const modalInstance = new bootstrap.Modal(targetModal, {
                                    backdrop: true,
                                    keyboard: true,
                                    focus: true
                                });
                                
                                console.log(`Modal ${index + 1} initialized successfully:`, targetId);
                                
                                // Add event listeners with error handling
                                trigger.addEventListener('click', function(e) {
                                    try {
                                        e.preventDefault();
                                        modalInstance.show();
                                    } catch (error) {
                                        console.error('Error showing modal:', error);
                                        // Fallback: try direct show
                                        try {
                                            targetModal.style.display = 'block';
                                            targetModal.classList.add('show');
                                        } catch (fallbackError) {
                                            console.error('Fallback modal show failed:', fallbackError);
                                        }
                                    }
                                });
                                
                            } else {
                                console.warn(`Modal target not found: ${targetId}`);
                            }
                        }
                    } catch (error) {
                        console.error(`Error initializing modal ${index + 1}:`, error);
                    }
                });
                
                // Global modal error handler
                document.addEventListener('show.bs.modal', function(e) {
                    console.log('Modal showing:', e.target.id);
                });
                
                document.addEventListener('shown.bs.modal', function(e) {
                    console.log('Modal shown:', e.target.id);
                });
                
                document.addEventListener('hide.bs.modal', function(e) {
                    console.log('Modal hiding:', e.target.id);
                });
                
                document.addEventListener('hidden.bs.modal', function(e) {
                    console.log('Modal hidden:', e.target.id);
                });
                
            } catch (error) {
                console.error('Global modal initialization error:', error);
            }
        }

        // Number formatting functions
        function initializeNumberFormatting() {
            const priceInputs = document.querySelectorAll('input[name="harga"], input[name*="price"], input[id="harga"], input[placeholder*="harga"], input[placeholder*="price"]');
            
            console.log('App layout - Number formatting initialized. Found inputs:', priceInputs.length);
            
            priceInputs.forEach((input, index) => {
                console.log(`App layout - Applying formatting to input ${index + 1}:`, input.name, input.id, input.placeholder);
                
                input.setAttribute('inputmode', 'numeric');
                
                input.addEventListener('input', handleNumberInput);
                input.addEventListener('blur', handleNumberInput);
                input.addEventListener('focus', function() {
                    this.value = unformatNumber(this.value);
                });
                
                if (input.value) {
                    input.value = formatNumber(input.value);
                }
            });
        }

        function handleNumberInput(e) {
            const input = e.target;
            let value = input.value;
            
            console.log('App layout - HandleNumberInput called. Original value:', value);
            
            value = value.replace(/[^\d.,]/g, '');
            const numericValue = unformatNumber(value);
            
            if (numericValue !== '') {
                const formattedValue = formatNumber(numericValue);
                console.log('App layout - Formatted value:', formattedValue);
                input.value = formattedValue;
            }
        }

        function formatNumber(value) {
            let num = value.toString().replace(/[^\d]/g, '');
            if (num === '') return '';
            return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function unformatNumber(value) {
            if (typeof value !== 'string') value = value.toString();
            return value.replace(/\./g, '');
        }

        // Global functions for manual formatting
        window.formatCurrency = formatNumber;
        window.unformatCurrency = unformatNumber;
        
        // Setup form submission handlers
        function setupFormSubmissionHandlers() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    console.log('App form submission detected, converting formatted numbers...');
                    
                    const priceInputs = form.querySelectorAll('input[name="harga"], input[name*="price"], input[id="harga"]');
                    
                    priceInputs.forEach(input => {
                        if (input.value && input.value.includes('.')) {
                            const originalValue = input.value;
                            input.value = unformatNumber(input.value);
                            console.log(`App converted ${originalValue} → ${input.value}`);
                        }
                    });
                });
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
