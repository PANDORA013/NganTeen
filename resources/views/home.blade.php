@extends('layouts.guest')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-orange-600 via-red-500 to-pink-500 min-h-screen flex items-center overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-20 h-20 bg-white rounded-full"></div>
        <div class="absolute top-40 right-20 w-32 h-32 bg-white rounded-full"></div>
        <div class="absolute bottom-20 left-1/4 w-16 h-16 bg-white rounded-full"></div>
        <div class="absolute bottom-40 right-1/3 w-24 h-24 bg-white rounded-full"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="text-white">
                <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
                    <span class="text-sm font-medium">🎉 Platform #1 untuk Kuliner Kampus</span>
                </div>
                
                <h1 class="text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    Pesan Makanan <br>
                    <span class="text-yellow-300">Tanpa Antri</span>
                </h1>
                
                <p class="text-xl text-white/90 mb-8 leading-relaxed max-w-md">
                    Solusi cerdas untuk mahasiswa yang ingin menikmati makanan lezat tanpa repot mengantri di kantin
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <a href="{{ route('register') }}" 
                       class="bg-white text-orange-600 font-bold py-4 px-8 rounded-xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-2xl text-center">
                        <i class="fas fa-rocket mr-2"></i>Mulai Sekarang
                    </a>
                    <a href="{{ route('login') }}" 
                       class="border-2 border-white text-white font-bold py-4 px-8 rounded-xl hover:bg-white hover:text-orange-600 transition-all duration-300 text-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-white/20">
                    <div class="text-center">
                        <div class="text-2xl font-bold">500+</div>
                        <div class="text-sm text-white/70">Menu Tersedia</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">50+</div>
                        <div class="text-sm text-white/70">Warung Partner</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">1000+</div>
                        <div class="text-sm text-white/70">Pengguna Aktif</div>
                    </div>
                </div>
            </div>
            
            <!-- Right Content - Hero Image/Animation -->
            <div class="hidden lg:block relative">
                <div class="relative z-10 bg-white rounded-3xl shadow-2xl p-8 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-utensils text-3xl text-orange-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Pesan Mudah</h3>
                        <p class="text-gray-600">Pilih menu, bayar, dan ambil tanpa antri</p>
                    </div>
                </div>
                
                <!-- Floating Elements -->
                <div class="absolute -top-6 -right-6 w-16 h-16 bg-yellow-400 rounded-2xl flex items-center justify-center shadow-lg animate-bounce">
                    <i class="fas fa-star text-white text-xl"></i>
                </div>
                <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-green-400 rounded-2xl flex items-center justify-center shadow-lg animate-pulse">
                    <i class="fas fa-check text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Kenapa Pilih NganTeen?</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Platform yang dirancang khusus untuk memudahkan mahasiswa mendapatkan makanan favorit
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-clock text-2xl text-orange-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Hemat Waktu</h3>
                <p class="text-gray-600">Tidak perlu mengantri lama di kantin. Pesan online dan langsung ambil ketika siap.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-mobile-alt text-2xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Mudah Digunakan</h3>
                <p class="text-gray-600">Interface yang sederhana dan intuitif memudahkan siapa saja untuk memesan makanan.</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Harga Terjangkau</h3>
                <p class="text-gray-600">Berbagai pilihan makanan dengan harga yang ramah kantong mahasiswa.</p>
            </div>
            
            <!-- Feature 4 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-utensils text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Beragam Menu</h3>
                <p class="text-gray-600">Puluhan warung dengan ratusan pilihan menu dari berbagai daerah.</p>
            </div>
            
            <!-- Feature 5 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-shield-alt text-2xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Aman & Terpercaya</h3>
                <p class="text-gray-600">Sistem pembayaran yang aman dan penjual terverifikasi untuk kenyamanan Anda.</p>
            </div>
            
            <!-- Feature 6 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-headset text-2xl text-yellow-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Support 24/7</h3>
                <p class="text-gray-600">Tim dukungan siap membantu Anda kapan saja jika mengalami kendala.</p>
            </div>
        </div>
    </div>
</section>

<!-- Menu Populer Section -->
@if(isset($menus) && $menus->count() > 0)
<section id="menu" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Menu Populer</h2>
            <p class="text-xl text-gray-600">Cicipi menu favorit yang paling disukai mahasiswa</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($menus as $menu)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                @if($menu->gambar)
                    <div class="h-48 bg-cover bg-center relative" style="background-image: url('{{ Storage::url($menu->gambar) }}')">
                        <div class="absolute top-4 right-4">
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-check-circle mr-1"></i>Tersedia
                            </span>
                        </div>
                    </div>
                @else
                    <div class="h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-utensils text-4xl text-gray-400 mb-2"></i>
                            <span class="text-gray-500 text-sm">Menu Image</span>
                        </div>
                    </div>
                @endif
                
                <div class="p-6">
                    <h3 class="font-bold text-xl text-gray-800 mb-2">{{ $menu->nama_menu }}</h3>
                    <p class="text-gray-600 mb-4 text-sm">{{ $menu->nama_warung }}</p>
                    
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-2xl font-bold text-orange-600">
                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                            </span>
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-box mr-1"></i>Stok: {{ $menu->stok }}
                            </div>
                        </div>
                        <a href="{{ route('register') }}" 
                           class="bg-orange-500 text-white px-6 py-2 rounded-xl hover:bg-orange-600 transition-all duration-300 font-medium">
                            <i class="fas fa-shopping-cart mr-1"></i>Pesan
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('register') }}" 
               class="inline-flex items-center bg-orange-500 text-white font-bold py-4 px-8 rounded-xl hover:bg-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-arrow-right mr-2"></i>Daftar untuk Lihat Semua Menu
            </a>
        </div>
    </div>
</section>
@endif

<!-- How It Works Section -->
<section id="how-it-works" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Cara Kerja NganTeen</h2>
            <p class="text-xl text-gray-600">Hanya 3 langkah mudah untuk mendapatkan makanan favorit Anda</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="relative mb-8">
                    <div class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center mx-auto shadow-2xl">
                        <span class="text-2xl font-bold text-white">1</span>
                    </div>
                    <div class="absolute top-10 left-1/2 w-px h-16 bg-orange-300 hidden md:block"></div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar & Pilih Menu</h3>
                <p class="text-gray-600">Buat akun gratis dan jelajahi ratusan menu dari berbagai warung di kampus</p>
            </div>
            
            <!-- Step 2 -->
            <div class="text-center">
                <div class="relative mb-8">
                    <div class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center mx-auto shadow-2xl">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                    <div class="absolute top-10 left-1/2 w-px h-16 bg-orange-300 hidden md:block"></div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Pesan & Bayar</h3>
                <p class="text-gray-600">Tambahkan ke keranjang, lakukan pembayaran, dan tunggu notifikasi pesanan siap</p>
            </div>
            
            <!-- Step 3 -->
            <div class="text-center">
                <div class="relative mb-8">
                    <div class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center mx-auto shadow-2xl">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Ambil & Nikmati</h3>
                <p class="text-gray-600">Datang ke warung sesuai jadwal, ambil pesanan Anda tanpa mengantri</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-orange-600 to-red-600">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">Siap Mulai Pengalaman Kuliner Tanpa Antri?</h2>
        <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
            Bergabunglah dengan ribuan mahasiswa yang sudah merasakan kemudahan memesan makanan di NganTeen
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="bg-white text-orange-600 font-bold py-4 px-8 rounded-xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-2xl">
                <i class="fas fa-user-plus mr-2"></i>Daftar Sebagai Pembeli
            </a>
            <a href="{{ route('register') }}" 
               class="border-2 border-white text-white font-bold py-4 px-8 rounded-xl hover:bg-white hover:text-orange-600 transition-all duration-300">
                <i class="fas fa-store mr-2"></i>Daftar Sebagai Penjual
            </a>
        </div>
        
        <div class="mt-12 text-white/80">
            <p class="text-sm">Sudah punya akun? <a href="{{ route('login') }}" class="underline hover:text-white">Masuk di sini</a></p>
        </div>
    </div>
</section>
@endsection
