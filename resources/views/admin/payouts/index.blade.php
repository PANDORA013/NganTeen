@extends('layouts.admin')

@section('title', 'Payouts Management')

@section('page-header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Payouts Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Payouts</li>
            </ol>
        </nav>
    </div>
    <div>
        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#createPayoutModal">
            <i class="fas fa-plus"></i> Create Payout
        </button>
        <button type="button" class="btn btn-success" onclick="processAllPayouts()">
            <i class="fas fa-play"></i> Process All Pending
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Payouts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_payouts']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Payouts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['pending_payouts']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Processing Payouts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['processing_payouts']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-spinner fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Completed Payouts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['completed_payouts']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Amount Statistics -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Payout Amount</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($stats['total_payout_amount']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-coins fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Amount</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($stats['pending_payout_amount']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Today's Payouts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($stats['today_payout_amount']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payouts Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">All Payouts</h6>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="statusFilter" data-bs-toggle="dropdown">
                    <i class="fas fa-filter"></i> Filter Status
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?status=all">All Status</a></li>
                    <li><a class="dropdown-item" href="?status=pending">Pending</a></li>
                    <li><a class="dropdown-item" href="?status=processing">Processing</a></li>
                    <li><a class="dropdown-item" href="?status=completed">Completed</a></li>
                    <li><a class="dropdown-item" href="?status=failed">Failed</a></li>
                </ul>
            </div>
            <button class="btn btn-sm btn-outline-secondary" onclick="exportPayouts()">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="payoutsTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Payout Number</th>
                        <th>Warung</th>
                        <th>Owner</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Method</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payouts as $payout)
                    <tr>
                        <td>
                            <span class="font-weight-bold text-primary">{{ $payout->payout_number }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    @if($payout->warung->foto)
                                        <img src="{{ asset('storage/' . $payout->warung->foto) }}" 
                                             alt="{{ $payout->warung->nama_warung }}" 
                                             class="rounded-circle" width="40" height="40"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-store text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-weight-bold">{{ $payout->warung->nama_warung }}</div>
                                    <div class="text-muted small">{{ $payout->warung->alamat }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="font-weight-bold">{{ $payout->warung->owner->name }}</div>
                                <div class="text-muted small">{{ $payout->warung->owner->email }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="font-weight-bold text-success">Rp {{ number_format($payout->amount) }}</span>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'completed' => 'success',
                                    'failed' => 'danger'
                                ];
                                $color = $statusColors[$payout->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst($payout->status) }}</span>
                        </td>
                        <td>
                            <div>
                                <div class="font-weight-bold">{{ ucfirst($payout->method ?? 'Bank Transfer') }}</div>
                                @if($payout->bank_name)
                                    <div class="text-muted small">{{ $payout->bank_name }}</div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="font-weight-bold">{{ $payout->created_at->format('M j, Y') }}</div>
                                <div class="text-muted small">{{ $payout->created_at->format('H:i') }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                @if($payout->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-info" 
                                            onclick="processPayout({{ $payout->id }})"
                                            title="Process Payout">
                                        <i class="fas fa-play"></i>
                                    </button>
                                @endif
                                
                                @if($payout->status === 'processing')
                                    <button type="button" class="btn btn-sm btn-success" 
                                            onclick="completePayout({{ $payout->id }})"
                                            title="Complete Payout">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                
                                @if(in_array($payout->status, ['pending', 'processing']))
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="failPayout({{ $payout->id }})"
                                            title="Mark as Failed">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="viewPayoutDetails({{ $payout->id }})"
                                        title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-money-bill-wave fa-3x mb-3"></i>
                                <h5>No Payouts Found</h5>
                                <p>No payouts have been created yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($payouts->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $payouts->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Payout Modal -->
<div class="modal fade" id="createPayoutModal" tabindex="-1" aria-labelledby="createPayoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPayoutModalLabel">Create New Payout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createPayoutForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="warung_id" class="form-label">Select Warung</label>
                        <select class="form-select" id="warung_id" name="warung_id" required>
                            <option value="">Choose Warung...</option>
                            @foreach(\App\Models\Warung::with(['owner', 'wallet'])->get() as $warung)
                            <option value="{{ $warung->id }}" data-balance="{{ $warung->wallet->balance ?? 0 }}">
                                {{ $warung->nama_warung }} - {{ $warung->owner->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="amount" class="form-label">Payout Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="amount" name="amount" required min="1" step="0.01">
                        </div>
                        <div class="form-text" id="balanceInfo"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Additional notes for this payout..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Payout</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payout Details Modal -->
<div class="modal fade" id="payoutDetailsModal" tabindex="-1" aria-labelledby="payoutDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payoutDetailsModalLabel">Payout Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="payoutDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#payoutsTable').DataTable({
        "pageLength": 25,
        "responsive": true,
        "order": [[ 6, "desc" ]], // Order by created date
        "columnDefs": [
            { "orderable": false, "targets": 7 } // Disable ordering on actions column
        ]
    });
    
    // Handle warung selection
    $('#warung_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        const balance = selectedOption.data('balance') || 0;
        
        if (balance > 0) {
            $('#balanceInfo').html(`<span class="text-success">Available balance: Rp ${balance.toLocaleString()}</span>`);
            $('#amount').attr('max', balance);
        } else {
            $('#balanceInfo').html(`<span class="text-danger">No available balance</span>`);
            $('#amount').attr('max', 0);
        }
    });
    
    // Handle create payout form
    $('#createPayoutForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.payouts.create") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#createPayoutModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('Error creating payout. Please try again.');
            }
        });
    });
});

// Process single payout
function processPayout(payoutId) {
    if (confirm('Are you sure you want to process this payout?')) {
        $.ajax({
            url: `{{ url('admin/payouts') }}/${payoutId}/process`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error processing payout. Please try again.');
            }
        });
    }
}

// Complete payout
function completePayout(payoutId) {
    if (confirm('Are you sure you want to complete this payout? This action cannot be undone.')) {
        $.ajax({
            url: `{{ url('admin/payouts') }}/${payoutId}/complete`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error completing payout. Please try again.');
            }
        });
    }
}

// Fail payout
function failPayout(payoutId) {
    if (confirm('Are you sure you want to mark this payout as failed?')) {
        $.ajax({
            url: `{{ url('admin/payouts') }}/${payoutId}/fail`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error failing payout. Please try again.');
            }
        });
    }
}

// Process all pending payouts
function processAllPayouts() {
    if (confirm('Are you sure you want to process all pending payouts?')) {
        $.ajax({
            url: '{{ route("admin.payouts.process-all") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error processing payouts. Please try again.');
            }
        });
    }
}

// View payout details
function viewPayoutDetails(payoutId) {
    $('#payoutDetailsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#payoutDetailsModal').modal('show');
    
    $.ajax({
        url: `{{ url('admin/payouts') }}/${payoutId}/details`,
        method: 'GET',
        success: function(response) {
            $('#payoutDetailsContent').html(response);
        },
        error: function() {
            $('#payoutDetailsContent').html('<div class="text-center text-danger">Error loading payout details.</div>');
        }
    });
}

// Export payouts
function exportPayouts() {
    window.location.href = '{{ route("admin.payouts.export") }}';
}
</script>
@endpush

@push('styles')
<style>
.table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    padding: 1rem 0.75rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.modal-lg {
    max-width: 800px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-header h6 {
    color: white !important;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
</style>
@endpush
