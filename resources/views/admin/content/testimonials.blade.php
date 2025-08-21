@extends('layouts.admin')

@section('title', 'Testimonials Management')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Testimonials Management</h1>
        <p class="page-subtitle">Kelola testimonial pelanggan untuk meningkatkan kredibilitas platform</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTestimonialModal">
            <i class="fas fa-plus me-2"></i>Tambah Testimonial
        </button>
    </div>
</div>

<!-- Testimonials Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #667eea; --card-bg-to: #764ba2;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ count($testimonials) }}</div>
                            <div class="stats-label">Total Testimonials</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-comments"></i>
                        </div>
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
                            <div class="stats-number">{{ collect($testimonials)->where('is_featured', true)->count() }}</div>
                            <div class="stats-label">Featured</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-star"></i>
                        </div>
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
                            <div class="stats-number">{{ collect($testimonials)->where('rating', 5)->count() }}</div>
                            <div class="stats-label">5 Star Reviews</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-medal"></i>
                        </div>
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
                            <div class="stats-number">{{ number_format(collect($testimonials)->avg('rating'), 1) }}</div>
                            <div class="stats-label">Average Rating</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials List -->
<div class="admin-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-comments me-2"></i>Daftar Testimonials
            </h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="refreshTestimonials()">
                    <i class="fas fa-sync me-1"></i>Refresh
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(empty($testimonials) || count($testimonials) === 0)
            <div class="text-center py-5">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada testimonial</h5>
                <p class="text-muted">Tambah testimonial pertama untuk ditampilkan di website</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTestimonialModal">
                    <i class="fas fa-plus me-2"></i>Tambah Testimonial
                </button>
            </div>
        @else
            <div class="row g-4">
                @foreach($testimonials as $index => $testimonial)
                <div class="col-md-6 col-lg-4">
                    <div class="testimonial-card">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        @if($testimonial['avatar'] ?? false)
                                            <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="testimonial-avatar me-3">
                                        @else
                                            <div class="testimonial-avatar-placeholder me-3">
                                                {{ strtoupper(substr($testimonial['name'], 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $testimonial['name'] }}</h6>
                                            <small class="text-muted">{{ $testimonial['position'] ?? 'Customer' }}</small>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editTestimonial({{ $index }})">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="toggleFeatured({{ $index }})">
                                                <i class="fas fa-star me-2"></i>
                                                {{ ($testimonial['is_featured'] ?? false) ? 'Unfeature' : 'Feature' }}
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteTestimonial({{ $index }})">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="rating mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= ($testimonial['rating'] ?? 5) ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span class="ms-2 small text-muted">({{ $testimonial['rating'] ?? 5 }}/5)</span>
                                </div>
                                
                                <blockquote class="blockquote">
                                    <p class="mb-0">"{{ $testimonial['message'] }}"</p>
                                </blockquote>
                                
                                @if($testimonial['is_featured'] ?? false)
                                    <div class="mt-3">
                                        <span class="badge bg-primary">
                                            <i class="fas fa-star me-1"></i>Featured
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Add Testimonial Modal -->
<div class="modal fade" id="addTestimonialModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Tambah Testimonial Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="testimonialForm" action="{{ route('admin.content.testimonials.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Customer *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Posisi/Jabatan</label>
                                <input type="text" class="form-control" name="position" placeholder="CEO, Student, dll">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Rating *</label>
                                <select class="form-select" name="rating" required>
                                    <option value="">Pilih Rating</option>
                                    <option value="5">⭐⭐⭐⭐⭐ (5 Stars)</option>
                                    <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                                    <option value="3">⭐⭐⭐ (3 Stars)</option>
                                    <option value="2">⭐⭐ (2 Stars)</option>
                                    <option value="1">⭐ (1 Star)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Pesan Testimonial *</label>
                                <textarea class="form-control" name="message" rows="4" required 
                                    placeholder="Masukkan testimonial customer..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">URL Avatar</label>
                                <input type="url" class="form-control" name="avatar" 
                                    placeholder="https://example.com/avatar.jpg">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured">
                                    <label class="form-check-label" for="isFeatured">
                                        Tampilkan sebagai Featured
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Testimonial
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Testimonial Modal -->
<div class="modal fade" id="editTestimonialModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Testimonial
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTestimonialForm">
                <div class="modal-body">
                    <input type="hidden" id="editIndex" name="index">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Customer *</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Posisi/Jabatan</label>
                                <input type="text" class="form-control" id="editPosition" name="position">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Rating *</label>
                                <select class="form-select" id="editRating" name="rating" required>
                                    <option value="5">⭐⭐⭐⭐⭐ (5 Stars)</option>
                                    <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                                    <option value="3">⭐⭐⭐ (3 Stars)</option>
                                    <option value="2">⭐⭐ (2 Stars)</option>
                                    <option value="1">⭐ (1 Star)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Pesan Testimonial *</label>
                                <textarea class="form-control" id="editMessage" name="message" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">URL Avatar</label>
                                <input type="url" class="form-control" id="editAvatar" name="avatar">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="editIsFeatured" name="is_featured">
                                    <label class="form-check-label" for="editIsFeatured">
                                        Tampilkan sebagai Featured
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Testimonial
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.testimonial-card .card {
    border: 1px solid #e3e6f0;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.testimonial-card .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.testimonial-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.testimonial-avatar-placeholder {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: bold;
    font-size: 16px;
}

.rating .fa-star {
    font-size: 0.9rem;
}

.blockquote {
    border-left: 4px solid #667eea;
    padding-left: 1rem;
    background: #f8f9fa;
    border-radius: 0 8px 8px 0;
    padding: 1rem;
    margin: 0;
}

.blockquote p {
    font-style: italic;
    line-height: 1.6;
    color: #6c757d;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
// Global testimonials data
let testimonialsData = @json($testimonials);

// Refresh testimonials
function refreshTestimonials() {
    location.reload();
}

// Edit testimonial
function editTestimonial(index) {
    const testimonial = testimonialsData[index];
    
    document.getElementById('editIndex').value = index;
    document.getElementById('editName').value = testimonial.name;
    document.getElementById('editPosition').value = testimonial.position || '';
    document.getElementById('editEmail').value = testimonial.email || '';
    document.getElementById('editRating').value = testimonial.rating || 5;
    document.getElementById('editMessage').value = testimonial.message;
    document.getElementById('editAvatar').value = testimonial.avatar || '';
    document.getElementById('editIsFeatured').checked = testimonial.is_featured || false;
    
    new bootstrap.Modal(document.getElementById('editTestimonialModal')).show();
}

// Toggle featured status
function toggleFeatured(index) {
    const testimonial = testimonialsData[index];
    const newStatus = !testimonial.is_featured;
    
    if (confirm(`${newStatus ? 'Feature' : 'Unfeature'} testimonial ini?`)) {
        // Update featured status via AJAX
        fetch(`/admin/content/testimonials/${index}/toggle-featured`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ is_featured: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengupdate status');
        });
    }
}

// Delete testimonial
function deleteTestimonial(index) {
    const testimonial = testimonialsData[index];
    
    if (confirm(`Hapus testimonial dari ${testimonial.name}?`)) {
        fetch(`/admin/content/testimonials/${index}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus testimonial');
        });
    }
}

// Handle form submissions
document.getElementById('testimonialForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('addTestimonialModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan testimonial');
    });
});

document.getElementById('editTestimonialForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const index = formData.get('index');
    
    fetch(`/admin/content/testimonials/${index}/update`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('editTestimonialModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate testimonial');
    });
});
</script>
@endpush
