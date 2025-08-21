@extends('layouts.admin')

@section('title', 'Settlement Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-handshake me-2"></i>Settlement Management
            </h1>
            <p class="text-muted">Kelola pembayaran ke warung mitra</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkSettlementModal">
                <i class="fas fa-check-double me-1"></i>Bulk Settlement
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Settlement
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pending_orders'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Amount
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['pending_amount']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Settled Today
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['settled_today']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Settled
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_settled'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filters & Search
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.settlements') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="warung_filter" class="form-label">Warung</label>
                        <select name="warung_id" id="warung_filter" class="form-select">
                            <option value="">All Warungs</option>
                            @foreach($warungs as $warung)
                                <option value="{{ $warung->id }}" 
                                        {{ request('warung_id') == $warung->id ? 'selected' : '' }}>
                                    {{ $warung->nama_warung }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status_filter" class="form-label">Status</label>
                        <select name="status" id="status_filter" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                Pending Settlement
                            </option>
                            <option value="settled" {{ request('status') == 'settled' ? 'selected' : '' }}>
                                Settled
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" name="date_from" id="date_from" 
                               class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" name="date_to" id="date_to" 
                               class="form-control" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="search" class="form-label">Search Order ID</label>
                        <input type="text" name="search" id="search" 
                               class="form-control" placeholder="Enter order ID..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.settlements') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Settlements Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Settlement Orders
            </h6>
            <div>
                @if($orders->where('is_settled', false)->count() > 0)
                    <button type="button" class="btn btn-sm btn-success" 
                            onclick="selectAllUnsettled()">
                        <i class="fas fa-check-square me-1"></i>Select All Unsettled
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Order ID</th>
                            <th>Warung</th>
                            <th>Customer</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Platform Fee</th>
                            <th>Net Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="{{ $order->is_settled ? 'table-light' : '' }}">
                                <td>
                                    @if(!$order->is_settled)
                                        <input type="checkbox" class="settlement-checkbox" 
                                               value="{{ $order->id }}" name="order_ids[]">
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold">#{{ $order->id }}</span>
                                    <br>
                                    <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($order->warung->logo)
                                            <img src="{{ asset('storage/' . $order->warung->logo) }}" 
                                                 class="rounded-circle me-2" width="32" height="32">
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $order->warung->nama_warung }}</div>
                                            <small class="text-muted">{{ $order->warung->owner_name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $order->customer_name }}</div>
                                    <small class="text-muted">{{ $order->customer_phone }}</small>
                                </td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="fw-bold text-success">
                                        Rp {{ number_format($order->total_amount) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-info">
                                        Rp {{ number_format($order->platform_fee) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary">
                                        Rp {{ number_format($order->net_amount) }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->is_settled)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Settled
                                        </span>
                                        @if($order->settled_at)
                                            <br><small class="text-muted">{{ $order->settled_at->format('d/m/Y') }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" data-bs-target="#orderDetailModal{{ $order->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if(!$order->is_settled)
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="settleOrder({{ $order->id }})">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No orders found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Selected Orders Actions -->
    <div id="bulkActions" class="card shadow d-none">
        <div class="card-body bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong><span id="selectedCount">0</span> orders selected</strong>
                    <span class="text-muted">| Total: Rp <span id="selectedTotal">0</span></span>
                </div>
                <div>
                    <button type="button" class="btn btn-success" onclick="settleBulkOrders()">
                        <i class="fas fa-check-double me-1"></i>Settle Selected Orders
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Settlement Modal -->
<div class="modal fade" id="bulkSettlementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Settlement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkSettlementForm">
                    @csrf
                    <div class="mb-3">
                        <label for="settlement_note" class="form-label">Settlement Note</label>
                        <textarea class="form-control" name="settlement_note" id="settlement_note" 
                                  rows="3" placeholder="Optional note for this settlement batch..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="settlement_method" class="form-label">Settlement Method</label>
                        <select class="form-select" name="settlement_method" id="settlement_method" required>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="e_wallet">E-Wallet</option>
                        </select>
                    </div>
                    <input type="hidden" name="order_ids" id="bulkOrderIds">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="processBulkSettlement()">
                    <i class="fas fa-check-double me-1"></i>Process Settlement
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Selection handling
let selectedOrders = [];

function updateBulkActions() {
    const selectedCount = selectedOrders.length;
    const bulkActions = document.getElementById('bulkActions');
    
    if (selectedCount > 0) {
        bulkActions.classList.remove('d-none');
        document.getElementById('selectedCount').textContent = selectedCount;
        
        // Calculate total
        let total = 0;
        selectedOrders.forEach(orderId => {
            const row = document.querySelector(`input[value="${orderId}"]`).closest('tr');
            const netAmountText = row.children[7].textContent.replace(/[^\d]/g, '');
            total += parseInt(netAmountText);
        });
        
        document.getElementById('selectedTotal').textContent = total.toLocaleString('id-ID');
    } else {
        bulkActions.classList.add('d-none');
    }
}

// Checkbox handling
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.settlement-checkbox');
    
    selectAll?.addEventListener('change', function() {
        selectedOrders = [];
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            if (this.checked) {
                selectedOrders.push(checkbox.value);
            }
        });
        updateBulkActions();
    });
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                selectedOrders.push(this.value);
            } else {
                selectedOrders = selectedOrders.filter(id => id !== this.value);
            }
            
            const allChecked = selectedOrders.length === checkboxes.length;
            if (selectAll) selectAll.checked = allChecked;
            
            updateBulkActions();
        });
    });
});

function selectAllUnsettled() {
    selectedOrders = [];
    document.querySelectorAll('.settlement-checkbox').forEach(checkbox => {
        checkbox.checked = true;
        selectedOrders.push(checkbox.value);
    });
    document.getElementById('selectAll').checked = true;
    updateBulkActions();
}

function settleOrder(orderId) {
    if (confirm('Are you sure you want to settle this order?')) {
        fetch(`/admin/settlements/${orderId}/settle`, {
            method: 'POST',
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
            alert('An error occurred while settling the order.');
        });
    }
}

function settleBulkOrders() {
    if (selectedOrders.length === 0) {
        alert('Please select orders to settle.');
        return;
    }
    
    document.getElementById('bulkOrderIds').value = selectedOrders.join(',');
    const modal = new bootstrap.Modal(document.getElementById('bulkSettlementModal'));
    modal.show();
}

function processBulkSettlement() {
    const form = document.getElementById('bulkSettlementForm');
    const formData = new FormData(form);
    
    fetch('/admin/settlements/bulk', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('bulkSettlementModal'));
            modal.hide();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing bulk settlement.');
    });
}
</script>
@endsection
