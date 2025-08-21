@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">User Management</h1>
        <p class="page-subtitle">Kelola semua pengguna dan warung dalam platform NganTeen</p>
    </div>
    <div class="page-actions">
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-plus me-2"></i>Kelola Users
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-user-plus me-2"></i>Tambah User Baru</a></li>
                <li><a class="dropdown-item" href="#" onclick="exportUsers()"><i class="fas fa-download me-2"></i>Export Data User</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" onclick="bulkActions()"><i class="fas fa-tasks me-2"></i>Bulk Actions</a></li>
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
                            <div class="stats-number">{{ $stats['total_users'] ?? 0 }}</div>
                            <div class="stats-label">Total Users</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white">
                            <i class="fas fa-arrow-up me-1"></i>{{ $stats['new_users_this_month'] ?? 0 }} user baru bulan ini
                        </small>
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
                            <div class="stats-number">{{ $stats['penjual_users'] ?? 0 }}</div>
                            <div class="stats-label">Warung Owners</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-store"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white">
                            <i class="fas fa-check me-1"></i>{{ $stats['active_warungs'] ?? 0 }} warung aktif
                        </small>
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
                            <div class="stats-number">{{ $stats['active_today'] ?? 0 }}</div>
                            <div class="stats-label">Active Today</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white">
                            <i class="fas fa-clock me-1"></i>{{ $stats['active_today'] ?? 0 }} aktif hari ini
                        </small>
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
                            <div class="stats-number">{{ $stats['admin_users'] ?? 0 }}</div>
                            <div class="stats-label">Admin Users</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white">
                            <i class="fas fa-shield-alt me-1"></i>dengan akses penuh
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="admin-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Filter by Role</label>
                        <select class="form-select" name="role">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="penjual" {{ request('role') == 'penjual' ? 'selected' : '' }}>Penjual</option>
                            <option value="pembeli" {{ request('role') == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Filter by Activity</label>
                        <select class="form-select" name="status">
                            <option value="">Semua Activity</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Search Users</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Cari nama, email, atau username..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Users Table -->
<div class="admin-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-users me-2"></i>Daftar Pengguna
            </h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="refreshTable()">
                    <i class="fas fa-sync me-1"></i>Refresh
                </button>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-cog me-1"></i>Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="exportSelected()">Export Selected</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkDelete()">Delete Selected</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkActivate()">Activate Selected</a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkDeactivate()">Deactivate Selected</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="usersTable">
                <thead class="table-light">
                    <tr>
                        <th width="40">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Activity</th>
                        <th>Warung</th>
                        <th>Join Date</th>
                        <th>Last Active</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $user->id }}" name="selected_users[]">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40">
                                    @else
                                        <div class="avatar-placeholder">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'penjual' ? 'warning' : 'primary') }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $isActive = $user->last_login_at && $user->last_login_at->diffInDays(now()) <= 7;
                            @endphp
                            <span class="badge bg-{{ $isActive ? 'success' : 'secondary' }}">
                                {{ $isActive ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            @if($user->warung)
                                <a href="{{ route('admin.warungs.detail', $user->warung->id) }}" class="text-decoration-none">
                                    {{ $user->warung->nama_warung }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $user->created_at->format('d M Y') }}</small>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'Never' }}
                            </small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="viewUser({{ $user->id }})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning" onclick="editUser({{ $user->id }})" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <button class="btn btn-outline-danger" onclick="deleteUser({{ $user->id }})" title="Delete User">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <h5>Belum ada data user</h5>
                                <p>Data user akan muncul di sini setelah ada yang mendaftar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection

@push('styles')
<style>
.user-avatar {
    position: relative;
}

.avatar-placeholder {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: bold;
    font-size: 14px;
}

.table th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.table tbody tr:hover {
    background-color: rgba(0,0,0,0.02);
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.form-check-input:checked {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}

.dropdown-menu {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="selected_users[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// User actions
function viewUser(userId) {
    window.location.href = `/admin/users/${userId}`;
}

function editUser(userId) {
    window.location.href = `/admin/users/${userId}/edit`;
}

function deleteUser(userId) {
    if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
        // Implement delete logic
        console.log('Deleting user:', userId);
    }
}

function refreshTable() {
    location.reload();
}

function exportSelected() {
    const selected = document.querySelectorAll('input[name="selected_users[]"]:checked');
    if (selected.length === 0) {
        alert('Pilih user yang akan diekspor');
        return;
    }
    console.log('Exporting selected users...');
}

function bulkDelete() {
    const selected = document.querySelectorAll('input[name="selected_users[]"]:checked');
    if (selected.length === 0) {
        alert('Pilih user yang akan dihapus');
        return;
    }
    if (confirm(`Hapus ${selected.length} user yang dipilih?`)) {
        console.log('Bulk deleting users...');
    }
}

function bulkActivate() {
    const selected = document.querySelectorAll('input[name="selected_users[]"]:checked');
    if (selected.length === 0) {
        alert('Pilih user yang akan diaktifkan');
        return;
    }
    console.log('Bulk activating users...');
}

function bulkDeactivate() {
    const selected = document.querySelectorAll('input[name="selected_users[]"]:checked');
    if (selected.length === 0) {
        alert('Pilih user yang akan dinonaktifkan');
        return;
    }
    console.log('Bulk deactivating users...');
}
</script>
@endpush


