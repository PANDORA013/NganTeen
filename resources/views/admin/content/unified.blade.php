@extends('layouts.admin')

@section('title', 'Website Management')

@section('content')
<!-- Website Management Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Website Management</h1>
        <p class="text-muted mb-0">Control all website content and settings from here</p>
    </div>
    <div>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary">
            <i class="fas fa-external-link-alt me-1"></i> View Live Site
        </a>
    </div>
</div>

<!-- Management Tabs -->
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="contentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                    <i class="fas fa-chart-pie me-2"></i>Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="homepage-tab" data-bs-toggle="tab" data-bs-target="#homepage" type="button" role="tab">
                    <i class="fas fa-home me-2"></i>Homepage
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="testimonials-tab" data-bs-toggle="tab" data-bs-target="#testimonials" type="button" role="tab">
                    <i class="fas fa-comments me-2"></i>Testimonials
                    <span class="badge bg-primary ms-1">{{ $stats['testimonials_count'] ?? 0 }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="help-tab" data-bs-toggle="tab" data-bs-target="#help" type="button" role="tab">
                    <i class="fas fa-question-circle me-2"></i>Help Center
                    <span class="badge bg-info ms-1">{{ $stats['help_articles_count'] ?? 0 }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab">
                    <i class="fas fa-envelope me-2"></i>Messages
                    @php
                        $unreadCount = \Illuminate\Support\Facades\Storage::exists('admin/contact-messages.json') 
                            ? count(array_filter(json_decode(\Illuminate\Support\Facades\Storage::get('admin/contact-messages.json'), true) ?: [], fn($msg) => $msg['status'] === 'unread'))
                            : 0;
                    @endphp
                    @if($unreadCount > 0)
                        <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                    @else
                        <span class="badge bg-success ms-1">{{ $stats['contact_messages_count'] ?? 0 }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                    <i class="fas fa-cogs me-2"></i>Settings
                </button>
            </li>
        </ul>
    </div>
    
    <div class="card-body">
        <div class="tab-content" id="contentTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-star fa-2x text-primary mb-3"></i>
                                <h4>{{ $stats['features_count'] ?? 6 }}</h4>
                                <p class="text-muted mb-0">Homepage Features</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-comments fa-2x text-success mb-3"></i>
                                <h4>{{ $stats['testimonials_count'] ?? 0 }}</h4>
                                <p class="text-muted mb-0">Testimonials</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <i class="fas fa-question-circle fa-2x text-info mb-3"></i>
                                <h4>{{ $stats['help_articles_count'] ?? 0 }}</h4>
                                <p class="text-muted mb-0">Help Articles</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-envelope fa-2x text-warning mb-3"></i>
                                <h4>{{ $stats['contact_messages_count'] ?? 0 }}</h4>
                                <p class="text-muted mb-0">Contact Messages</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5>Quick Actions</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-primary" onclick="showTab('homepage-tab')">
                                    <i class="fas fa-edit me-2"></i>Edit Homepage Content
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-success" onclick="showTab('testimonials-tab')">
                                    <i class="fas fa-plus me-2"></i>Add New Testimonial
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-info" onclick="showTab('help-tab')">
                                    <i class="fas fa-plus me-2"></i>Add Help Article
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-warning" onclick="showTab('messages-tab')">
                                    <i class="fas fa-envelope me-2"></i>Check Messages
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Homepage Tab -->
            <div class="tab-pane fade" id="homepage" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5>Homepage Content Management</h5>
                    <button class="btn btn-primary" onclick="loadHomepageEditor()">
                        <i class="fas fa-edit me-2"></i>Edit Content
                    </button>
                </div>
                <div id="homepageContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                        <p class="text-muted mt-2">Loading homepage content...</p>
                    </div>
                </div>
            </div>
            
            <!-- Testimonials Tab -->
            <div class="tab-pane fade" id="testimonials" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5>Testimonials Management</h5>
                    <button class="btn btn-success" onclick="addNewTestimonial()">
                        <i class="fas fa-plus me-2"></i>Add Testimonial
                    </button>
                </div>
                <div id="testimonialsContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                        <p class="text-muted mt-2">Loading testimonials...</p>
                    </div>
                </div>
            </div>
            
            <!-- Help Center Tab -->
            <div class="tab-pane fade" id="help" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5>Help Center Management</h5>
                    <button class="btn btn-info" onclick="addNewHelpArticle()">
                        <i class="fas fa-plus me-2"></i>Add Article
                    </button>
                </div>
                <div id="helpContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                        <p class="text-muted mt-2">Loading help articles...</p>
                    </div>
                </div>
            </div>
            
            <!-- Messages Tab -->
            <div class="tab-pane fade" id="messages" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5>Contact Messages</h5>
                    <div>
                        <button class="btn btn-outline-secondary" onclick="markAllAsRead()">
                            <i class="fas fa-check-double me-2"></i>Mark All Read
                        </button>
                        <button class="btn btn-danger" onclick="clearAllMessages()">
                            <i class="fas fa-trash me-2"></i>Clear All
                        </button>
                    </div>
                </div>
                <div id="messagesContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                        <p class="text-muted mt-2">Loading contact messages...</p>
                    </div>
                </div>
            </div>
            
            <!-- Settings Tab -->
            <div class="tab-pane fade" id="settings" role="tabpanel">
                <div class="row">
                    <div class="col-md-8">
                        <h5>Website Settings</h5>
                        <form id="websiteSettingsForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Site Title</label>
                                    <input type="text" class="form-control" name="site_title" value="NganTeen - Food Ordering Platform">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Site Tagline</label>
                                    <input type="text" class="form-control" name="site_tagline" value="Pesan Makanan Favorit Anda">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Site Description</label>
                                    <textarea class="form-control" name="site_description" rows="3">Platform pemesanan makanan online yang menghubungkan pembeli dengan warung makanan lokal.</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact Email</label>
                                    <input type="email" class="form-control" name="contact_email" value="contact@nganteen.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact Phone</label>
                                    <input type="text" class="form-control" name="contact_phone" value="+62 812-3456-7890">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Site Logo</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" name="site_logo" accept="image/*">
                                        <button class="btn btn-outline-secondary" type="button">Upload</button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Settings
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <h5>Quick Settings</h5>
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Maintenance Mode</span>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="maintenanceMode">
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>User Registration</span>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="userRegistration" checked>
                                </div>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Email Notifications</span>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showTab(tabId) {
    document.getElementById(tabId).click();
}

function loadHomepageEditor() {
    window.location.href = '{{ route("admin.content.features") }}';
}

function addNewTestimonial() {
    window.location.href = '{{ route("admin.content.testimonials") }}';
}

function addNewHelpArticle() {
    window.location.href = '{{ route("admin.content.help-center") }}';
}

function markAllAsRead() {
    if (confirm('Mark all messages as read?')) {
        // AJAX call to mark all as read
        fetch('{{ route("admin.content.contact-messages") }}/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

function clearAllMessages() {
    if (confirm('Are you sure you want to clear all messages? This action cannot be undone.')) {
        fetch('{{ route("admin.content.contact-messages") }}/clear-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

// Load content for active tab
document.addEventListener('DOMContentLoaded', function() {
    // Load initial content based on active tab
    const activeTab = document.querySelector('.nav-link.active').getAttribute('data-bs-target');
    
    if (activeTab === '#messages') {
        loadMessages();
    } else if (activeTab === '#testimonials') {
        loadTestimonials();
    } else if (activeTab === '#help') {
        loadHelpArticles();
    } else if (activeTab === '#homepage') {
        loadHomepageContent();
    }
});

// Tab event listeners
document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function(e) {
        const target = e.target.getAttribute('data-bs-target');
        
        if (target === '#messages') {
            loadMessages();
        } else if (target === '#testimonials') {
            loadTestimonials();
        } else if (target === '#help') {
            loadHelpArticles();
        } else if (target === '#homepage') {
            loadHomepageContent();
        }
    });
});

function loadMessages() {
    fetch('{{ route("admin.content.contact-messages") }}/ajax')
        .then(response => response.text())
        .then(html => {
            document.getElementById('messagesContent').innerHTML = html;
        });
}

function loadTestimonials() {
    fetch('{{ route("admin.content.testimonials") }}/ajax')
        .then(response => response.text())
        .then(html => {
            document.getElementById('testimonialsContent').innerHTML = html;
        });
}

function loadHelpArticles() {
    fetch('{{ route("admin.content.help-center") }}/ajax')
        .then(response => response.text())
        .then(html => {
            document.getElementById('helpContent').innerHTML = html;
        });
}

function loadHomepageContent() {
    fetch('{{ route("admin.content.features") }}/ajax')
        .then(response => response.text())
        .then(html => {
            document.getElementById('homepageContent').innerHTML = html;
        });
}
</script>
@endpush

@push('styles')
<style>
.nav-tabs .nav-link {
    border-radius: 8px 8px 0 0;
    border: none;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.nav-tabs .nav-link:hover {
    border: none;
    color: #495057;
}

.tab-content {
    min-height: 400px;
}

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.list-group-item {
    border: none;
    border-bottom: 1px solid #e9ecef;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}
</style>
@endpush
