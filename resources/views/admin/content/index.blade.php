@extends('layouts.admin')

@section('title', 'Content Management')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Content Management</h1>
        <p class="page-subtitle">Kelola semua konten website NganTeen dengan mudah dan efisien</p>
    </div>
    <div class="page-actions">
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-plus me-2"></i>Tambah Konten
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('admin.content.features') }}"><i class="fas fa-star me-2"></i>Kelola Features</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.content.testimonials') }}"><i class="fas fa-comment me-2"></i>Tambah Testimonial</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.content.help-center') }}"><i class="fas fa-question me-2"></i>Tambah Artikel Bantuan</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('admin.content.website-settings') }}"><i class="fas fa-cog me-2"></i>Website Settings</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #667eea; --card-bg-to: #764ba2;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ $stats['features_count'] ?? 0 }}</div>
                            <div class="stats-label">Landing Features</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.content.features') }}" class="text-white text-decoration-none">
                            <small><i class="fas fa-arrow-right me-1"></i>Kelola Features</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #f093fb; --card-bg-to: #f5576c;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ $stats['testimonials_count'] ?? 0 }}</div>
                            <div class="stats-label">Testimonials</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.content.testimonials') }}" class="text-white text-decoration-none">
                            <small><i class="fas fa-arrow-right me-1"></i>Kelola Testimonials</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #4facfe; --card-bg-to: #00f2fe;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ $stats['help_articles_count'] ?? 0 }}</div>
                            <div class="stats-label">Help Articles</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.content.help-center') }}" class="text-white text-decoration-none">
                            <small><i class="fas fa-arrow-right me-1"></i>Kelola Articles</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #43e97b; --card-bg-to: #38f9d7;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ $stats['contact_messages_count'] ?? 0 }}</div>
                            <div class="stats-label">Contact Messages</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.content.contact-messages') }}" class="text-white text-decoration-none">
                            <small><i class="fas fa-arrow-right me-1"></i>Lihat Messages</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Management Modules -->
<div class="row g-4">
    <div class="col-xl-4 col-md-6">
        <div class="admin-card">
            <div class="card-body text-center">
                <div class="mb-4">
                    <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
                <h5 class="card-title">Landing Page Features</h5>
                <p class="text-muted mb-4">Kelola fitur-fitur unggulan yang ditampilkan di halaman utama website</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.content.features') }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Kelola Features
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="admin-card">
            <div class="card-body text-center">
                <div class="mb-4">
                    <div class="feature-icon bg-success bg-opacity-10 text-success mx-auto">
                        <i class="fas fa-comments fa-2x"></i>
                    </div>
                </div>
                <h5 class="card-title">Testimonials</h5>
                <p class="text-muted mb-4">Tambah dan kelola testimonial dari pengguna untuk meningkatkan kredibilitas</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.content.testimonials') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Kelola Testimonials
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="admin-card">
            <div class="card-body text-center">
                <div class="mb-4">
                    <div class="feature-icon bg-info bg-opacity-10 text-info mx-auto">
                        <i class="fas fa-question-circle fa-2x"></i>
                    </div>
                </div>
                <h5 class="card-title">Help Center</h5>
                <p class="text-muted mb-4">Buat artikel bantuan dan FAQ untuk membantu pengguna menggunakan platform</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.content.help-center') }}" class="btn btn-info">
                        <i class="fas fa-book me-2"></i>Kelola Help Center
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="admin-card">
            <div class="card-body text-center">
                <div class="mb-4">
                    <div class="feature-icon bg-warning bg-opacity-10 text-warning mx-auto">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                </div>
                <h5 class="card-title">Contact Messages</h5>
                <p class="text-muted mb-4">Lihat dan balas pesan yang masuk melalui form kontak di website</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.content.contact-messages') }}" class="btn btn-warning">
                        <i class="fas fa-inbox me-2"></i>Lihat Messages
                        @if($stats['contact_messages_count'] > 0)
                            <span class="badge bg-danger ms-2">{{ $stats['contact_messages_count'] }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="admin-card">
            <div class="card-body text-center">
                <div class="mb-4">
                    <div class="feature-icon bg-purple bg-opacity-10 text-purple mx-auto">
                        <i class="fas fa-cogs fa-2x"></i>
                    </div>
                </div>
                <h5 class="card-title">Website Settings</h5>
                <p class="text-muted mb-4">Konfigurasi pengaturan umum website seperti kontak, sosial media, dan statistik</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.content.website-settings') }}" class="btn btn-purple">
                        <i class="fas fa-cog me-2"></i>Website Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="admin-card">
            <div class="card-body text-center">
                <div class="mb-4">
                    <div class="feature-icon bg-dark bg-opacity-10 text-dark mx-auto">
                        <i class="fas fa-external-link-alt fa-2x"></i>
                    </div>
                </div>
                <h5 class="card-title">Preview Website</h5>
                <p class="text-muted mb-4">Lihat tampilan website dari perspektif pengunjung untuk memastikan semua konten terlihat sempurna</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-dark">
                        <i class="fas fa-eye me-2"></i>Preview Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.feature-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}

.btn-purple {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
}

.btn-purple:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

.text-purple {
    color: #667eea !important;
}

.bg-purple {
    background-color: #667eea !important;
}

.admin-card {
    transition: all 0.3s ease;
}

.admin-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}
</style>
@endpush

