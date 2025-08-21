@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Edit User</h1>
        <p class="text-muted mb-0">Update user information</p>
    </div>
    <div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
    </div>
</div>

<!-- Edit User Form -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="pembeli" {{ old('role', $user->role) == 'pembeli' ? 'selected' : '' }}>Buyer (Pembeli)</option>
                                <option value="penjual" {{ old('role', $user->role) == 'penjual' ? 'selected' : '' }}>Seller (Penjual)</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Leave password fields empty to keep current password
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update User
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Current User Info</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Current Role</h6>
                    @php
                        $roleColors = [
                            'admin' => 'primary',
                            'pembeli' => 'success',
                            'penjual' => 'warning'
                        ];
                        $roleLabels = [
                            'admin' => 'Admin',
                            'pembeli' => 'Buyer (Pembeli)',
                            'penjual' => 'Seller (Penjual)'
                        ];
                    @endphp
                    <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }}">
                        {{ $roleLabels[$user->role] ?? ucfirst($user->role) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <h6>Member Since</h6>
                    <p class="text-muted">{{ $user->created_at->format('M j, Y') }}</p>
                </div>
                
                <div class="mb-3">
                    <h6>Last Activity</h6>
                    <p class="text-muted">{{ $user->updated_at->diffForHumans() }}</p>
                </div>
                
                @if($user->email_verified_at)
                <div class="mb-3">
                    <h6>Email Status</h6>
                    <span class="badge bg-success">Verified</span>
                </div>
                @else
                <div class="mb-3">
                    <h6>Email Status</h6>
                    <span class="badge bg-warning">Unverified</span>
                </div>
                @endif
            </div>
        </div>
        
        @if($user->role == 'penjual')
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Seller Statistics</h5>
            </div>
            <div class="card-body">
                @php
                    $warungs = $user->warungs ?? collect();
                    $totalOrders = $warungs->sum(function($warung) {
                        return $warung->globalOrderItems->count();
                    });
                @endphp
                
                <div class="mb-2">
                    <strong>Total Warungs:</strong> {{ $warungs->count() }}
                </div>
                <div class="mb-2">
                    <strong>Total Orders:</strong> {{ $totalOrders }}
                </div>
            </div>
        </div>
        @endif
        
        @if($user->role == 'pembeli')
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Buyer Statistics</h5>
            </div>
            <div class="card-body">
                @php
                    $orders = $user->globalOrders ?? collect();
                    $totalSpent = $orders->where('payment_status', 'paid')->sum('total_amount');
                @endphp
                
                <div class="mb-2">
                    <strong>Total Orders:</strong> {{ $orders->count() }}
                </div>
                <div class="mb-2">
                    <strong>Total Spent:</strong> Rp {{ number_format($totalSpent) }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
}

.alert-info {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    border: 1px solid #bbdefb;
    color: #1976d2;
}
</style>
@endpush
