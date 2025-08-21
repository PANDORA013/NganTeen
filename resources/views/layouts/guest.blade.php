<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NganTeen') }} - Platform Kuliner Kampus</title>
        
        <!-- Meta Tags for SEO -->
        <meta name="description" content="NganTeen adalah platform kuliner kampus yang memudahkan mahasiswa memesan makanan tanpa antri. Hemat waktu dengan sistem pre-order yang praktis.">
        <meta name="keywords" content="kuliner kampus, pesan makanan online, kantin kampus, makanan mahasiswa, NganTeen">
        <meta name="author" content="NganTeen">
        
        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="NganTeen - Platform Kuliner Kampus">
        <meta property="og:description" content="Pesan makanan kampus tanpa antri. Platform #1 untuk kuliner mahasiswa.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Vite Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                /* Landing Page Theme */
                --primary-orange: #f97316;
                --primary-red: #ef4444;
                --primary-pink: #ec4899;
                --primary-yellow: #f59e0b;
                
                /* Authentication Theme (for auth pages) */
                --auth-primary: #22c55e;
                --auth-primary-dark: #16a34a;
                --auth-primary-light: #dcfce7;
                --auth-primary-50: #f0fdf4;
                --auth-primary-100: #dcfce7;
                --auth-primary-600: #16a34a;
                --auth-primary-700: #15803d;
                
                /* Supporting Colors */
                --auth-secondary: #64748b;
                --auth-success: #22c55e;
                --auth-danger: #ef4444;
                --auth-warning: #f59e0b;
                --auth-info: #0ea5e9;
                
                /* Text Colors */
                --auth-text-primary: #1e293b;
                --auth-text-secondary: #64748b;
                --auth-text-muted: #94a3b8;
                
                /* Border and Shadow */
                --auth-border: #e2e8f0;
                --auth-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
                --auth-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
                
                /* Spacing */
                --auth-border-radius: 0.75rem;
                --auth-border-radius-sm: 0.5rem;
            }
            
            /* Landing Page Styles */
            .landing-page body {
                font-family: 'Inter', sans-serif;
                background: white;
                color: #1e293b;
                overflow-x: hidden;
            }
            
            /* Custom animations */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            
            .float-animation {
                animation: float 6s ease-in-out infinite;
            }
            
            /* Smooth scrolling */
            html {
                scroll-behavior: smooth;
            }
            
            /* Custom gradient */
            .gradient-text {
                background: linear-gradient(45deg, var(--primary-orange), var(--primary-red), var(--primary-pink));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            /* Loading animation */
            .pulse-slow {
                animation: pulse 3s infinite;
            }

            /* Authentication Page Styles */
            .auth-page body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, var(--auth-primary-50) 0%, var(--auth-primary-100) 100%);
                color: var(--auth-text-primary);
                min-height: 100vh;
                position: relative;
                overflow-x: hidden;
            }

            /* Background Pattern */
            body::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: 
                    radial-gradient(circle at 20% 50%, rgba(34, 197, 94, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(34, 197, 94, 0.08) 0%, transparent 50%),
                    radial-gradient(circle at 40% 80%, rgba(34, 197, 94, 0.06) 0%, transparent 50%);
                pointer-events: none;
                z-index: -1;
            }

            .auth-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem 1rem;
            }

            .auth-card {
                background: white;
                border-radius: var(--auth-border-radius);
                box-shadow: var(--auth-shadow-lg);
                overflow: hidden;
                width: 100%;
                max-width: 450px;
                border: 1px solid var(--auth-border);
                position: relative;
            }

            .auth-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, var(--auth-primary) 0%, var(--auth-primary-600) 100%);
            }

            .auth-header {
                text-align: center;
                padding: 2.5rem 2rem 1.5rem;
                border-bottom: 1px solid var(--auth-border);
                background: linear-gradient(135deg, white 0%, var(--auth-primary-50) 100%);
            }

            .auth-logo {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 4rem;
                height: 4rem;
                background: linear-gradient(135deg, var(--auth-primary) 0%, var(--auth-primary-600) 100%);
                border-radius: 50%;
                margin-bottom: 1rem;
                box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);
            }

            .auth-logo i {
                font-size: 1.75rem;
                color: white;
            }

            .auth-title {
                font-size: 1.75rem;
                font-weight: 700;
                color: var(--auth-text-primary);
                margin-bottom: 0.5rem;
                background: linear-gradient(135deg, var(--auth-primary) 0%, var(--auth-primary-600) 100%);
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .auth-subtitle {
                color: var(--auth-text-secondary);
                font-size: 0.95rem;
                margin: 0;
            }

            .auth-body {
                padding: 2rem;
            }

            .auth-form .form-group {
                margin-bottom: 1.5rem;
            }

            .auth-form .form-label {
                font-weight: 600;
                color: var(--auth-text-primary);
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .auth-form .form-label i {
                color: var(--auth-primary);
                width: 1rem;
            }

            .auth-form .form-control {
                border: 2px solid var(--auth-border);
                border-radius: var(--auth-border-radius-sm);
                padding: 0.875rem 1rem;
                font-size: 0.95rem;
                transition: all 0.3s ease;
                background-color: white;
            }

            .auth-form .form-control:focus {
                border-color: var(--auth-primary);
                box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
                outline: none;
            }

            .auth-form .form-control.is-invalid {
                border-color: var(--auth-danger);
                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
            }

            .auth-form .invalid-feedback {
                color: var(--auth-danger);
                font-size: 0.875rem;
                margin-top: 0.25rem;
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }

            .auth-form .invalid-feedback i {
                font-size: 0.75rem;
            }

            .auth-form .form-check {
                margin: 1.5rem 0;
            }

            .auth-form .form-check-input:checked {
                background-color: var(--auth-primary);
                border-color: var(--auth-primary);
            }

            .auth-form .form-check-input:focus {
                border-color: var(--auth-primary);
                box-shadow: 0 0 0 0.25rem rgba(34, 197, 94, 0.25);
            }

            .auth-form .form-check-label {
                color: var(--auth-text-secondary);
                font-size: 0.9rem;
            }

            .btn-auth-primary {
                background: linear-gradient(135deg, var(--auth-primary) 0%, var(--auth-primary-600) 100%);
                border: none;
                color: white;
                padding: 0.875rem 2rem;
                border-radius: var(--auth-border-radius-sm);
                font-weight: 600;
                font-size: 0.95rem;
                width: 100%;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .btn-auth-primary::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
                transition: left 0.5s ease;
            }

            .btn-auth-primary:hover::before {
                left: 100%;
            }

            .btn-auth-primary:hover {
                background: linear-gradient(135deg, var(--auth-primary-600) 0%, var(--auth-primary-700) 100%);
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4);
                color: white;
            }

            .btn-auth-primary:active {
                transform: translateY(0);
                box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
            }

            .btn-outline-secondary {
                background: transparent;
                border: 2px solid var(--auth-secondary);
                color: var(--auth-secondary);
                padding: 0.875rem 2rem;
                border-radius: var(--auth-border-radius-sm);
                font-weight: 600;
                font-size: 0.95rem;
                width: 100%;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            .btn-outline-secondary:hover {
                background: var(--auth-secondary);
                color: white;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
            }

            .auth-link {
                color: var(--auth-primary);
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .auth-link:hover {
                color: var(--auth-primary-600);
                text-decoration: underline;
                text-underline-offset: 2px;
            }

            .auth-footer {
                text-align: center;
                padding: 1.5rem 2rem;
                background: linear-gradient(135deg, var(--auth-primary-50) 0%, white 100%);
                border-top: 1px solid var(--auth-border);
            }

            .auth-footer p {
                margin: 0;
                color: var(--auth-text-secondary);
                font-size: 0.9rem;
            }

            .alert-auth {
                border: none;
                border-left: 4px solid transparent;
                border-radius: var(--auth-border-radius-sm);
                padding: 1rem 1.25rem;
                margin-bottom: 1.5rem;
                position: relative;
                font-size: 0.9rem;
            }

            .alert-auth.alert-success {
                background: linear-gradient(135deg, var(--auth-primary-50) 0%, var(--auth-primary-100) 100%);
                border-left-color: var(--auth-success);
                color: var(--auth-primary-700);
            }

            .alert-auth.alert-danger {
                background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
                border-left-color: var(--auth-danger);
                color: #991b1b;
            }

            .role-selector {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
                margin-top: 0.5rem;
            }

            .role-option {
                position: relative;
            }

            .role-option input[type="radio"] {
                position: absolute;
                opacity: 0;
                width: 100%;
                height: 100%;
                margin: 0;
                cursor: pointer;
            }

            .role-option label {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 1rem 0.75rem;
                border: 2px solid var(--auth-border);
                border-radius: var(--auth-border-radius-sm);
                cursor: pointer;
                transition: all 0.3s ease;
                background: white;
                margin: 0;
            }

            .role-option input[type="radio"]:checked + label {
                border-color: var(--auth-primary);
                background: linear-gradient(135deg, var(--auth-primary-50) 0%, var(--auth-primary-100) 100%);
                color: var(--auth-primary-700);
            }

            .role-option label i {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
                color: var(--auth-text-muted);
                transition: color 0.3s ease;
            }

            .role-option input[type="radio"]:checked + label i {
                color: var(--auth-primary);
            }

            .role-option label span {
                font-weight: 600;
                font-size: 0.9rem;
            }

            /* Responsive Design */
            @media (max-width: 576px) {
                .auth-container {
                    padding: 1rem;
                }
                
                .auth-header {
                    padding: 2rem 1.5rem 1.25rem;
                }
                
                .auth-body {
                    padding: 1.5rem;
                }
                
                .auth-footer {
                    padding: 1.25rem 1.5rem;
                }
                
                .role-selector {
                    grid-template-columns: 1fr;
                }
            }

            /* Loading States */
            .btn-loading {
                position: relative;
                pointer-events: none;
            }

            .btn-loading::after {
                content: '';
                position: absolute;
                width: 16px;
                height: 16px;
                margin: auto;
                border: 2px solid transparent;
                border-top-color: currentColor;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            @keyframes spin {
                0% { transform: translate(-50%, -50%) rotate(0deg); }
                100% { transform: translate(-50%, -50%) rotate(360deg); }
            }
        </style>
    </head>
    <body class="{{ request()->is('/') ? 'landing-page' : 'auth-page' }}">
        @if(request()->is('/'))
        <!-- Landing Page Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md shadow-sm transition-all duration-300" id="navbar">
            <div class="container mx-auto px-6">
                <div class="flex justify-between items-center py-4">
                    <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-utensils text-white text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold gradient-text">NganTeen</span>
                    </div>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#home" class="text-gray-700 hover:text-orange-500 transition-colors duration-300 font-medium">Beranda</a>
                        <a href="#features" class="text-gray-700 hover:text-orange-500 transition-colors duration-300 font-medium">Fitur</a>
                        <a href="#menu" class="text-gray-700 hover:text-orange-500 transition-colors duration-300 font-medium">Menu</a>
                        <a href="#how-it-works" class="text-gray-700 hover:text-orange-500 transition-colors duration-300 font-medium">Cara Kerja</a>
                    </div>
                    
                    <!-- Auth Buttons -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('login') }}" 
                           class="text-gray-700 hover:text-orange-500 transition-colors duration-300 font-medium">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-orange-500 text-white px-6 py-2 rounded-xl hover:bg-orange-600 transition-all duration-300 font-medium shadow-lg">
                            Daftar
                        </a>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button class="md:hidden text-gray-700 hover:text-orange-500 transition-colors duration-300" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                
                <!-- Mobile Menu -->
                <div class="md:hidden hidden pb-4" id="mobile-menu">
                    <div class="space-y-4">
                        <a href="#home" class="block text-gray-700 hover:text-orange-500 transition-colors duration-300 font-medium">Beranda</a>
                        <a href="#features" class="block text-gray-700 hover:text-orange-500 transition-colors duration-300 font-medium">Fitur</a>
                        <a href="#menu" class="block text-gray-700 hover:text-orange-500 transition-colors duration-300 font-medium">Menu</a>
                        <a href="#how-it-works" class="block text-gray-700 hover:text-orange-500 transition-colors duration-300 font-medium">Cara Kerja</a>
                        <div class="pt-4 space-y-2">
                            <a href="{{ route('login') }}" 
                               class="block text-center text-gray-700 hover:text-orange-500 transition-colors duration-300 font-medium">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" 
                               class="block bg-orange-500 text-white px-6 py-2 rounded-xl hover:bg-orange-600 transition-all duration-300 font-medium text-center">
                                Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main id="home">
            @yield('content')
        </main>

        <!-- Footer for Landing Page -->
        <footer class="bg-gray-900 text-white">
            <div class="container mx-auto px-6 py-16">
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div>
                        <div class="flex items-center space-x-2 mb-6">
                            <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-red-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-utensils text-white text-sm"></i>
                            </div>
                            <span class="text-xl font-bold">NganTeen</span>
                        </div>
                        <p class="text-gray-400 mb-6 leading-relaxed">
                            Platform kuliner kampus yang memudahkan mahasiswa memesan makanan tanpa antri. 
                            Solusi cerdas untuk kebutuhan kuliner Anda.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-orange-500 rounded-lg flex items-center justify-center transition-colors duration-300">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-orange-500 rounded-lg flex items-center justify-center transition-colors duration-300">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-orange-500 rounded-lg flex items-center justify-center transition-colors duration-300">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-bold mb-6">Menu Cepat</h3>
                        <ul class="space-y-3">
                            <li><a href="#features" class="text-gray-400 hover:text-white transition-colors duration-300">Fitur</a></li>
                            <li><a href="#menu" class="text-gray-400 hover:text-white transition-colors duration-300">Menu Populer</a></li>
                            <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition-colors duration-300">Cara Kerja</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors duration-300">Daftar</a></li>
                        </ul>
                    </div>
                    
                    <!-- For Users -->
                    <div>
                        <h3 class="text-lg font-bold mb-6">Untuk Pengguna</h3>
                        <ul class="space-y-3">
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors duration-300">Daftar Pembeli</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors duration-300">Daftar Penjual</a></li>
                            <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors duration-300">Masuk</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">Bantuan</a></li>
                        </ul>
                    </div>
                    
                    <!-- Contact -->
                    <div>
                        <h3 class="text-lg font-bold mb-6">Kontak</h3>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center text-gray-400">
                                <i class="fas fa-envelope mr-3"></i>
                                support@nganteen.com
                            </li>
                            <li class="flex items-center text-gray-400">
                                <i class="fas fa-phone mr-3"></i>
                                +62 812-3456-7890
                            </li>
                            <li class="flex items-center text-gray-400">
                                <i class="fas fa-map-marker-alt mr-3"></i>
                                Kampus University
                            </li>
                            <li class="flex items-center text-gray-400">
                                <i class="fas fa-clock mr-3"></i>
                                24/7 Support
                            </li>
                        </ul>
                        
                        <!-- Quick Contact Form -->
                        <div class="bg-gray-800 rounded-lg p-4">
                            <h4 class="text-white font-bold mb-3">Kirim Pesan Cepat</h4>
                            <form id="quickContactForm" class="space-y-3">
                                <input type="email" name="email" placeholder="Email Anda" 
                                       class="w-full px-3 py-2 rounded text-gray-900 text-sm" required>
                                <textarea name="message" placeholder="Pesan Anda..." rows="3" 
                                          class="w-full px-3 py-2 rounded text-gray-900 text-sm" required></textarea>
                                <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded hover:bg-orange-600 transition-colors text-sm">
                                    <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Footer -->
                <div class="border-t border-gray-800 mt-12 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-gray-400 text-sm">
                            © {{ date('Y') }} NganTeen. All rights reserved.
                        </p>
                        <div class="flex space-x-6 mt-4 md:mt-0">
                            <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-300">Privacy Policy</a>
                            <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-300">Terms of Service</a>
                            <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-300">Cookie Policy</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        @else
        <!-- Authentication Pages Layout -->
        <div class="auth-container">
            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
        @endif

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if(request()->is('/'))
                // Landing Page JavaScript
                
                // Mobile menu toggle
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');
                
                if (mobileMenuButton && mobileMenu) {
                    mobileMenuButton.addEventListener('click', function() {
                        mobileMenu.classList.toggle('hidden');
                    });
                }
                
                // Navbar scroll effect
                const navbar = document.getElementById('navbar');
                if (navbar) {
                    window.addEventListener('scroll', function() {
                        if (window.scrollY > 100) {
                            navbar.classList.add('bg-white', 'shadow-lg');
                            navbar.classList.remove('bg-white/90');
                        } else {
                            navbar.classList.remove('bg-white', 'shadow-lg');
                            navbar.classList.add('bg-white/90');
                        }
                    });
                }
                
                // Smooth scroll for anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            const offsetTop = target.offsetTop - 80; // Account for fixed navbar
                            window.scrollTo({
                                top: offsetTop,
                                behavior: 'smooth'
                            });
                        }
                    });
                });
                
                // Add scroll animations
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };
                
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, observerOptions);
                
                // Observe elements for animation
                document.querySelectorAll('section > .container').forEach(el => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(30px)';
                    el.style.transition = 'all 0.6s ease-out';
                    observer.observe(el);
                });
                
                // Quick Contact Form
                const quickContactForm = document.getElementById('quickContactForm');
                if (quickContactForm) {
                    quickContactForm.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        
                        const formData = new FormData();
                        formData.append('name', 'Anonymous User');
                        formData.append('email', this.email.value);
                        formData.append('subject', 'Pesan dari Landing Page');
                        formData.append('message', this.message.value);
                        formData.append('_token', '{{ csrf_token() }}');
                        
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
                        submitBtn.disabled = true;
                        
                        try {
                            const response = await fetch('{{ route('contact.store') }}', {
                                method: 'POST',
                                body: formData
                            });
                            
                            const result = await response.json();
                            
                            if (result.success) {
                                this.reset();
                                alert('Pesan berhasil dikirim! Terima kasih telah menghubungi kami.');
                            } else {
                                throw new Error('Gagal mengirim pesan');
                            }
                        } catch (error) {
                            alert('Maaf, terjadi kesalahan. Silakan coba lagi nanti.');
                            console.error('Contact form error:', error);
                        } finally {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    });
                }
                
                @else
                // Authentication Pages JavaScript
                
                // Form validation and enhancement
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function() {
                        const submitBtn = form.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.classList.add('btn-loading');
                            submitBtn.disabled = true;
                        }
                    });
                });
                
                @endif

                // Number formatting for price/currency inputs
                initializeNumberFormatting();

                // Enhanced form validation
                const inputs = document.querySelectorAll('.form-control');
                inputs.forEach(input => {
                    input.addEventListener('blur', function() {
                        validateField(this);
                    });
                    
                    input.addEventListener('input', function() {
                        if (this.classList.contains('is-invalid')) {
                            validateField(this);
                        }
                    });
                });

                function validateField(field) {
                    const value = field.value.trim();
                    const type = field.type;
                    let isValid = true;
                    let message = '';

                    if (field.hasAttribute('required') && !value) {
                        isValid = false;
                        message = 'Field ini wajib diisi';
                    } else if (type === 'email' && value && !isValidEmail(value)) {
                        isValid = false;
                        message = 'Format email tidak valid';
                    } else if (field.name === 'password' && value && value.length < 8) {
                        isValid = false;
                        message = 'Password minimal 8 karakter';
                    }

                    toggleFieldValidation(field, isValid, message);
                }

                function isValidEmail(email) {
                    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                }

                function toggleFieldValidation(field, isValid, message) {
                    const feedback = field.parentNode.querySelector('.invalid-feedback');
                    
                    if (isValid) {
                        field.classList.remove('is-invalid');
                        if (feedback) feedback.style.display = 'none';
                    } else {
                        field.classList.add('is-invalid');
                        if (feedback) {
                            feedback.textContent = message;
                            feedback.style.display = 'block';
                        }
                    }
                }

                // Number formatting functions
                function initializeNumberFormatting() {
                    // Find all number inputs that should be formatted as currency
                    const numberInputs = document.querySelectorAll(
                        'input[name="harga"], input[name="price"], input[type="number"][data-currency="true"], .currency-input'
                    );
                    
                    numberInputs.forEach(input => {
                        // Format initial value if exists
                        if (input.value) {
                            input.value = formatNumber(input.value);
                        }
                        
                        // Add event listeners
                        input.addEventListener('input', handleNumberInput);
                        input.addEventListener('keypress', function(e) {
                            // Only allow numbers and backspace
                            if (!/[\d\b]/.test(String.fromCharCode(e.keyCode))) {
                                e.preventDefault();
                            }
                        });
                        
                        // Before form submission, convert back to plain number
                        const form = input.closest('form');
                        if (form) {
                            form.addEventListener('submit', function() {
                                input.value = unformatNumber(input.value);
                            });
                        }
                    });
                }

                function handleNumberInput(e) {
                    const input = e.target;
                    let value = input.value;
                    
                    // Remove any non-digit characters except dots for decimal
                    value = value.replace(/[^\d]/g, '');
                    
                    // Format the number
                    if (value) {
                        input.value = formatNumber(value);
                    }
                }

                function formatNumber(num) {
                    // Convert to string and remove any existing formatting
                    let cleanNum = num.toString().replace(/[^\d]/g, '');
                    
                    if (!cleanNum) return '';
                    
                    // Add thousand separators
                    return cleanNum.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                function unformatNumber(formattedNum) {
                    // Remove all dots and return plain number
                    return formattedNum.toString().replace(/\./g, '');
                }

                // Global functions for external use
                window.formatCurrency = formatNumber;
                window.unformatCurrency = unformatNumber;
            });
        </script>
    </body>
</html>
