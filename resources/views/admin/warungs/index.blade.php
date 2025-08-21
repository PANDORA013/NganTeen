@extends('layouts.admin')

@section('title', 'Warungs Management')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Warungs Management</h1>
        <p class="page-subtitle">Kelola semua warung yang terdaftar di platform</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-outline-primary" onclick="exportWarungs()">
            <i class="fas fa-download me-2"></i>Export Warungs
        </button>
        <button type="button" class="btn btn-primary" onclick="refreshWarungs()">
            <i class="fas fa-sync me-2"></i>Refresh
        </button>
    </div>
</div>

<!-- Warungs Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #667eea; --card-bg-to: #764ba2;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ number_format($stats['total_warungs']) }}</div>
                            <div class="stats-label">Total Warungs</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-store"></i>
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
                            <div class="stats-number">{{ number_format($stats['active_warungs']) }}</div>
                            <div class="stats-label">Active Warungs</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
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
                            <div class="stats-number">{{ number_format($stats['total_menus']) }}</div>
                            <div class="stats-label">Total Menus</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-utensils"></i>
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
                            <div class="stats-number">{{ $stats['avg_menus_per_warung'] }}</div>
                            <div class="stats-label">Avg Menus/Warung</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Warung Filters -->
<div class="admin-card mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <label class="form-label">Status Filter</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="aktif">Active</option>
                    <option value="nonaktif">Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Sort By</label>
                <select class="form-select" id="sortFilter">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="name">Name A-Z</option>
                    <option value="revenue">Highest Revenue</option>
                    <option value="orders">Most Orders</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="searchWarungs" placeholder="Search by name, owner, location...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button class="btn btn-outline-primary" onclick="clearFilters()">
                        <i class="fas fa-times me-1"></i>Clear
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Warungs Table -->
<div class="admin-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-store me-2"></i>Warungs List
            </h5>
            <div class="d-flex gap-2">
                <span class="badge bg-primary">{{ $warungs->total() }} warungs</span>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($warungs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Warung</th>
                            <th>Owner</th>
                            <th>Location</th>
                            <th>Menus</th>
                            <th>Orders</th>
                            <th>Revenue</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($warungs as $warung)
                        <tr class="warung-row" data-status="{{ $warung->status }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="warung-image me-3">
                                        @if($warung->foto)
                                            <img src="{{ asset('storage/' . $warung->foto) }}" alt="{{ $warung->nama_warung }}" class="warung-thumb">
                                        @else
                                            <div class="warung-placeholder">
                                                {{ strtoupper(substr($warung->nama_warung, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $warung->nama_warung }}</div>
                                        <small class="text-muted">{{ Str::limit($warung->deskripsi, 40) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <div class="avatar-placeholder">
                                            {{ strtoupper(substr($warung->owner->name ?? 'U', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $warung->owner->name ?? 'Unknown' }}</div>
                                        <small class="text-muted">{{ $warung->owner->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $warung->lokasi ?? 'Not specified' }}</div>
                                @if($warung->jam_operasional)
                                    <small class="text-muted">{{ $warung->jam_operasional }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $warung->menus_count }} menus</span>
                                @if($warung->menus_count > 0)
                                    <small class="d-block text-muted">
                                        {{ $warung->menus->first()->nama_menu ?? 'Menu available' }}
                                        @if($warung->menus_count > 1)
                                            + {{ $warung->menus_count - 1 }} more
                                        @endif
                                    </small>
                                @endif
                            </td>
                            <td>
                                <strong>{{ number_format($warung->orders_count) }}</strong>
                                <small class="d-block text-muted">orders</small>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($warung->revenue ?? 0, 0, ',', '.') }}</strong>
                                <small class="d-block text-muted">total revenue</small>
                            </td>
                            <td>
                                @if($warung->status === 'aktif')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $warung->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $warung->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="viewWarung({{ $warung->id }})">
                                            <i class="fas fa-eye me-2"></i>View Details
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="editWarung({{ $warung->id }})">
                                            <i class="fas fa-edit me-2"></i>Edit Warung
                                        </a></li>
                                        @if($warung->status === 'aktif')
                                        <li><a class="dropdown-item" href="#" onclick="toggleWarungStatus({{ $warung->id }}, 'nonaktif')">
                                            <i class="fas fa-pause me-2"></i>Deactivate
                                        </a></li>
                                        @else
                                        <li><a class="dropdown-item" href="#" onclick="toggleWarungStatus({{ $warung->id }}, 'aktif')">
                                            <i class="fas fa-play me-2"></i>Activate
                                        </a></li>
                                        @endif
                                        <li><a class="dropdown-item" href="mailto:{{ $warung->owner->email ?? '' }}">
                                            <i class="fas fa-envelope me-2"></i>Contact Owner
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteWarung({{ $warung->id }})">
                                            <i class="fas fa-trash me-2"></i>Delete Warung
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="card-footer">
                {{ $warungs->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-store fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No warungs found</h5>
                <p class="text-muted">Warungs will appear here when sellers register their businesses</p>
            </div>
        @endif
    </div>
</div>

<!-- Warung Details Modal -->
<div class="modal fade" id="warungDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-store me-2"></i>Warung Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="warungDetailsContent">
                <!-- Warung details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printWarungDetails()">
                    <i class="fas fa-print me-1"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Warung Modal -->
<div class="modal fade" id="editWarungModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Warung
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editWarungForm">
                <div class="modal-body">
                    <input type="hidden" id="editWarungId" name="warung_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Warung *</label>
                                <input type="text" class="form-control" id="editWarungNama" name="nama_warung" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="editWarungLokasi" name="lokasi">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="editWarungDeskripsi" name="deskripsi" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Jam Operasional</label>
                                <input type="text" class="form-control" id="editWarungJam" name="jam_operasional" 
                                    placeholder="Contoh: 08:00 - 16:00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="editWarungStatus" name="status">
                                    <option value="aktif">Active</option>
                                    <option value="nonaktif">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Warung
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.warung-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}

.warung-placeholder {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-weight: bold;
    font-size: 16px;
}

.avatar-sm {
    width: 35px;
    height: 35px;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: bold;
    font-size: 14px;
}

.warung-row {
    transition: all 0.3s ease;
}

.warung-row:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.badge {
    font-size: 0.75rem;
}

.stats-card:hover {
    transform: translateY(-5px);
}
</style>
@endpush

@push('scripts')
<script>
// Filter functionality
document.getElementById('statusFilter').addEventListener('change', filterWarungs);
document.getElementById('searchWarungs').addEventListener('input', filterWarungs);

function filterWarungs() {
    const statusFilter = document.getElementById('statusFilter').value;
    const searchTerm = document.getElementById('searchWarungs').value.toLowerCase();
    
    document.querySelectorAll('.warung-row').forEach(row => {
        const status = row.dataset.status;
        const text = row.textContent.toLowerCase();
        
        const statusMatch = !statusFilter || status === statusFilter;
        const searchMatch = !searchTerm || text.includes(searchTerm);
        
        if (statusMatch && searchMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('sortFilter').value = 'newest';
    document.getElementById('searchWarungs').value = '';
    filterWarungs();
}

function refreshWarungs() {
    location.reload();
}

function exportWarungs() {
    // Implement export functionality
    alert('Export functionality will be implemented');
}

function viewWarung(warungId) {
    // Load warung details via AJAX
    fetch(`/admin/warungs/${warungId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('warungDetailsContent').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('warungDetailsModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load warung details');
        });
}

function editWarung(warungId) {
    // Load warung data for editing
    fetch(`/admin/warungs/${warungId}/edit`)
        .then(response => response.json())
        .then(data => {
            const warung = data.warung;
            document.getElementById('editWarungId').value = warung.id;
            document.getElementById('editWarungNama').value = warung.nama_warung;
            document.getElementById('editWarungLokasi').value = warung.lokasi || '';
            document.getElementById('editWarungDeskripsi').value = warung.deskripsi || '';
            document.getElementById('editWarungJam').value = warung.jam_operasional || '';
            document.getElementById('editWarungStatus').value = warung.status;
            
            new bootstrap.Modal(document.getElementById('editWarungModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load warung data');
        });
}

function toggleWarungStatus(warungId, status) {
    const action = status === 'aktif' ? 'activate' : 'deactivate';
    if (confirm(`Are you sure you want to ${action} this warung?`)) {
        fetch(`/admin/warungs/${warungId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: status })
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
            alert('Failed to update warung status');
        });
    }
}

function deleteWarung(warungId) {
    if (confirm('Are you sure you want to delete this warung? This action cannot be undone and will also delete all associated menus.')) {
        fetch(`/admin/warungs/${warungId}`, {
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
            alert('Failed to delete warung');
        });
    }
}

function printWarungDetails() {
    window.print();
}

// Handle edit warung form submission
document.getElementById('editWarungForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const warungId = formData.get('warung_id');
    
    fetch(`/admin/warungs/${warungId}/update`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('editWarungModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update warung');
    });
});
</script>
@endpush
