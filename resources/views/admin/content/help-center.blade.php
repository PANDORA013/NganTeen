@extends('layouts.admin')

@section('title', 'Help Center Management')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Help Center Management</h1>
        <p class="page-subtitle">Kelola artikel bantuan dan FAQ untuk membantu pengguna platform</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addArticleModal">
            <i class="fas fa-plus me-2"></i>Tambah Artikel
        </button>
    </div>
</div>

<!-- Help Center Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #667eea; --card-bg-to: #764ba2;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ count($helpArticles) }}</div>
                            <div class="stats-label">Total Articles</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-book"></i>
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
                            <div class="stats-number">{{ collect($helpArticles)->where('category', 'FAQ')->count() }}</div>
                            <div class="stats-label">FAQ Articles</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-question-circle"></i>
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
                            <div class="stats-number">{{ collect($helpArticles)->where('category', 'Tutorial')->count() }}</div>
                            <div class="stats-label">Tutorials</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-graduation-cap"></i>
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
                            <div class="stats-number">{{ collect($helpArticles)->where('is_featured', true)->count() }}</div>
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
</div>

<!-- Category Filter -->
<div class="admin-card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-outline-primary category-filter active" data-category="all">
                <i class="fas fa-list me-1"></i>Semua ({{ count($helpArticles) }})
            </button>
            <button class="btn btn-outline-info category-filter" data-category="FAQ">
                <i class="fas fa-question-circle me-1"></i>FAQ ({{ collect($helpArticles)->where('category', 'FAQ')->count() }})
            </button>
            <button class="btn btn-outline-success category-filter" data-category="Tutorial">
                <i class="fas fa-graduation-cap me-1"></i>Tutorial ({{ collect($helpArticles)->where('category', 'Tutorial')->count() }})
            </button>
            <button class="btn btn-outline-warning category-filter" data-category="Troubleshooting">
                <i class="fas fa-tools me-1"></i>Troubleshooting ({{ collect($helpArticles)->where('category', 'Troubleshooting')->count() }})
            </button>
            <button class="btn btn-outline-secondary category-filter" data-category="General">
                <i class="fas fa-info-circle me-1"></i>General ({{ collect($helpArticles)->where('category', 'General')->count() }})
            </button>
        </div>
    </div>
</div>

<!-- Help Articles List -->
<div class="admin-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-book me-2"></i>Artikel Bantuan
            </h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="refreshArticles()">
                    <i class="fas fa-sync me-1"></i>Refresh
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(empty($helpArticles) || count($helpArticles) === 0)
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada artikel bantuan</h5>
                <p class="text-muted">Tambah artikel bantuan pertama untuk membantu pengguna</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addArticleModal">
                    <i class="fas fa-plus me-2"></i>Tambah Artikel
                </button>
            </div>
        @else
            <div class="row g-4" id="articlesContainer">
                @foreach($helpArticles as $index => $article)
                <div class="col-md-6 col-lg-4 article-item" data-category="{{ $article['category'] }}">
                    <div class="article-card">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-{{ $article['category'] == 'FAQ' ? 'info' : ($article['category'] == 'Tutorial' ? 'success' : ($article['category'] == 'Troubleshooting' ? 'warning' : 'secondary')) }} me-2">
                                                {{ $article['category'] }}
                                            </span>
                                            @if($article['is_featured'] ?? false)
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-star me-1"></i>Featured
                                                </span>
                                            @endif
                                        </div>
                                        <h6 class="card-title mb-2">{{ $article['title'] }}</h6>
                                        <p class="card-text text-muted small">{{ Str::limit($article['content'], 120) }}</p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editArticle({{ $index }})">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="toggleFeatured({{ $index }})">
                                                <i class="fas fa-star me-2"></i>
                                                {{ ($article['is_featured'] ?? false) ? 'Unfeature' : 'Feature' }}
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteArticle({{ $index }})">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="article-meta">
                                    <div class="d-flex justify-content-between align-items-center text-muted small">
                                        <span>
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ date('d M Y', strtotime($article['created_at'])) }}
                                        </span>
                                        <span>
                                            <i class="fas fa-eye me-1"></i>
                                            {{ $article['views'] ?? 0 }} views
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Add Article Modal -->
<div class="modal fade" id="addArticleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Tambah Artikel Bantuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="articleForm" action="{{ route('admin.content.help-center.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label class="form-label">Judul Artikel *</label>
                                <input type="text" class="form-control" name="title" required 
                                    placeholder="Masukkan judul artikel bantuan">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Kategori *</label>
                                <select class="form-select" name="category" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="FAQ">FAQ</option>
                                    <option value="Tutorial">Tutorial</option>
                                    <option value="Troubleshooting">Troubleshooting</option>
                                    <option value="General">General</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Konten Artikel *</label>
                                <textarea class="form-control" name="content" rows="8" required 
                                    placeholder="Tulis konten artikel bantuan..."></textarea>
                                <div class="form-text">Gunakan Markdown untuk formatting teks</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Tags</label>
                                <input type="text" class="form-control" name="tags" 
                                    placeholder="tag1, tag2, tag3">
                                <div class="form-text">Pisahkan dengan koma</div>
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
                        <i class="fas fa-save me-1"></i>Simpan Artikel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Article Modal -->
<div class="modal fade" id="editArticleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Artikel Bantuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editArticleForm">
                <div class="modal-body">
                    <input type="hidden" id="editIndex" name="index">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label class="form-label">Judul Artikel *</label>
                                <input type="text" class="form-control" id="editTitle" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Kategori *</label>
                                <select class="form-select" id="editCategory" name="category" required>
                                    <option value="FAQ">FAQ</option>
                                    <option value="Tutorial">Tutorial</option>
                                    <option value="Troubleshooting">Troubleshooting</option>
                                    <option value="General">General</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Konten Artikel *</label>
                                <textarea class="form-control" id="editContent" name="content" rows="8" required></textarea>
                                <div class="form-text">Gunakan Markdown untuk formatting teks</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Tags</label>
                                <input type="text" class="form-control" id="editTags" name="tags">
                                <div class="form-text">Pisahkan dengan koma</div>
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
                        <i class="fas fa-save me-1"></i>Update Artikel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.article-card .card {
    border: 1px solid #e3e6f0;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.article-card .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.category-filter {
    transition: all 0.3s ease;
}

.category-filter.active {
    background-color: var(--bs-primary);
    color: white;
    border-color: var(--bs-primary);
}

.article-meta {
    border-top: 1px solid #e9ecef;
    padding-top: 0.75rem;
    margin-top: 0.75rem;
}

.badge {
    font-size: 0.75rem;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.article-item {
    transition: all 0.3s ease;
}

.article-item.d-none {
    display: none !important;
}
</style>
@endpush

@push('scripts')
<script>
// Global articles data
let articlesData = @json($helpArticles);

// Category filtering
document.querySelectorAll('.category-filter').forEach(btn => {
    btn.addEventListener('click', function() {
        const category = this.dataset.category;
        
        // Update active button
        document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Filter articles
        document.querySelectorAll('.article-item').forEach(article => {
            if (category === 'all' || article.dataset.category === category) {
                article.classList.remove('d-none');
            } else {
                article.classList.add('d-none');
            }
        });
    });
});

// Refresh articles
function refreshArticles() {
    location.reload();
}

// Edit article
function editArticle(index) {
    const article = articlesData[index];
    
    document.getElementById('editIndex').value = index;
    document.getElementById('editTitle').value = article.title;
    document.getElementById('editCategory').value = article.category;
    document.getElementById('editContent').value = article.content;
    document.getElementById('editTags').value = article.tags ? article.tags.join(', ') : '';
    document.getElementById('editIsFeatured').checked = article.is_featured || false;
    
    new bootstrap.Modal(document.getElementById('editArticleModal')).show();
}

// Toggle featured status
function toggleFeatured(index) {
    const article = articlesData[index];
    const newStatus = !article.is_featured;
    
    if (confirm(`${newStatus ? 'Feature' : 'Unfeature'} artikel ini?`)) {
        fetch(`/admin/content/help-center/${index}/toggle-featured`, {
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

// Delete article
function deleteArticle(index) {
    const article = articlesData[index];
    
    if (confirm(`Hapus artikel "${article.title}"?`)) {
        fetch(`/admin/content/help-center/${index}`, {
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
            alert('Terjadi kesalahan saat menghapus artikel');
        });
    }
}

// Handle form submissions
document.getElementById('articleForm').addEventListener('submit', function(e) {
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
            bootstrap.Modal.getInstance(document.getElementById('addArticleModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan artikel');
    });
});

document.getElementById('editArticleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const index = formData.get('index');
    
    fetch(`/admin/content/help-center/${index}/update`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('editArticleModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate artikel');
    });
});
</script>
@endpush
