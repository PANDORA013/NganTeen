@extends('layouts.admin')

@section('title', 'Website Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-gray-900">
                        <i class="fas fa-cog me-2 text-primary"></i>Website Settings
                    </h1>
                    <p class="text-muted mb-0">Kelola pengaturan umum website dan landing page</p>
                </div>
                <a href="{{ route('admin.content.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.content.website-settings.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <!-- Site Information -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-2"></i>Informasi Website
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Website</label>
                            <input type="text" class="form-control" name="site_name" 
                                   value="{{ $settings['site_name'] }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Website</label>
                            <textarea class="form-control" name="site_description" rows="3" required>{{ $settings['site_description'] }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hero Title</label>
                            <input type="text" class="form-control" name="hero_title" 
                                   value="{{ $settings['hero_title'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hero Subtitle</label>
                            <textarea class="form-control" name="hero_subtitle" rows="2" required>{{ $settings['hero_subtitle'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-address-book me-2"></i>Informasi Kontak
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Email Support</label>
                            <input type="email" class="form-control" name="contact_email" 
                                   value="{{ $settings['contact_email'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" name="contact_phone" 
                                   value="{{ $settings['contact_phone'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="contact_address" rows="2" required>{{ $settings['contact_address'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fab fa-facebook me-2"></i>Social Media
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Facebook URL</label>
                            <input type="url" class="form-control" name="facebook_url" 
                                   value="{{ $settings['facebook_url'] }}" placeholder="https://facebook.com/nganteen">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Instagram URL</label>
                            <input type="url" class="form-control" name="instagram_url" 
                                   value="{{ $settings['instagram_url'] }}" placeholder="https://instagram.com/nganteen">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Twitter URL</label>
                            <input type="url" class="form-control" name="twitter_url" 
                                   value="{{ $settings['twitter_url'] }}" placeholder="https://twitter.com/nganteen">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-chart-bar me-2"></i>Statistik Landing Page
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Total Menu</label>
                            <input type="number" class="form-control" name="statistics[menus]" 
                                   value="{{ $settings['statistics']['menus'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Warung</label>
                            <input type="number" class="form-control" name="statistics[warungs]" 
                                   value="{{ $settings['statistics']['warungs'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Pengguna</label>
                            <input type="number" class="form-control" name="statistics[users]" 
                                   value="{{ $settings['statistics']['users'] }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Preview Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-eye me-2"></i>Preview Landing Page
                    </h6>
                </div>
                <div class="card-body">
                    <div class="bg-gradient-to-br from-orange-600 via-red-500 to-pink-500 text-white p-5 rounded">
                        <div class="text-center">
                            <h1 class="display-4 font-weight-bold mb-3">{{ $settings['hero_title'] }}</h1>
                            <p class="lead mb-4">{{ $settings['hero_subtitle'] }}</p>
                            
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <h3>{{ number_format($settings['statistics']['menus']) }}+</h3>
                                    <small>Menu Tersedia</small>
                                </div>
                                <div class="col-md-4">
                                    <h3>{{ number_format($settings['statistics']['warungs']) }}+</h3>
                                    <small>Warung Partner</small>
                                </div>
                                <div class="col-md-4">
                                    <h3>{{ number_format($settings['statistics']['users']) }}+</h3>
                                    <small>Pengguna Aktif</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-external-link-alt me-2"></i>Lihat Landing Page Live
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
