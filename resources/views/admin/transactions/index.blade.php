@extends('layouts.admin')

@section('title', 'Transactions Management')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Transactions Management</h1>
        <p class="page-subtitle">Monitor dan kelola semua transaksi di platform</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-outline-primary" onclick="exportTransactions()">
            <i class="fas fa-download me-2"></i>Export Transactions
        </button>
        <button type="button" class="btn btn-primary" onclick="refreshTransactions()">
            <i class="fas fa-sync me-2"></i>Refresh
        </button>
    </div>
</div>

<!-- Transaction Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #667eea; --card-bg-to: #764ba2;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ number_format($stats['total_transactions']) }}</div>
                            <div class="stats-label">Total Transactions</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-exchange-alt"></i>
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
                            <div class="stats-number">{{ number_format($stats['completed_transactions']) }}</div>
                            <div class="stats-label">Completed</div>
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
                            <div class="stats-number">{{ number_format($stats['pending_transactions']) }}</div>
                            <div class="stats-label">Pending</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-clock"></i>
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
                            <div class="stats-number">Rp {{ number_format($stats['total_transaction_amount'], 0, ',', '.') }}</div>
                            <div class="stats-label">Total Amount</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Filters -->
<div class="admin-card mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <label class="form-label">Status Filter</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date Range</label>
                <select class="form-select" id="dateFilter">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="searchTransactions" placeholder="Search by transaction ID, customer, warung...">
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

<!-- Transactions Table -->
<div class="admin-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-exchange-alt me-2"></i>Transactions List
            </h5>
            <div class="d-flex gap-2">
                <span class="badge bg-primary">{{ $transactions->total() }} transactions</span>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($transactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Transaction ID</th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Warung</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Net Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr class="transaction-row" data-status="{{ $transaction->status }}">
                            <td>
                                <div class="fw-medium">#TXN-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</div>
                                <small class="text-muted">{{ $transaction->transaction_id ?? 'System Generated' }}</small>
                            </td>
                            <td>
                                <div class="fw-medium">#{{ $transaction->globalOrder->order_number ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $transaction->globalOrder->items->count() ?? 0 }} items</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <div class="avatar-placeholder">
                                            {{ strtoupper(substr($transaction->globalOrder->buyer->name ?? 'U', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $transaction->globalOrder->buyer->name ?? 'Unknown' }}</div>
                                        <small class="text-muted">{{ $transaction->globalOrder->buyer->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $transaction->warung->nama_warung ?? 'Multiple Warungs' }}</div>
                                @if($transaction->globalOrder && $transaction->globalOrder->items->count() > 1)
                                    <small class="text-muted">{{ $transaction->globalOrder->items->count() }} warungs</small>
                                @endif
                            </td>
                            <td>
                                <strong>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <span class="text-muted">Rp {{ number_format($transaction->admin_fee ?? 0, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <strong>Rp {{ number_format(($transaction->amount - ($transaction->admin_fee ?? 0)), 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @switch($transaction->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Completed</span>
                                        @break
                                    @case('failed')
                                        <span class="badge bg-danger">Failed</span>
                                        @break
                                    @case('refunded')
                                        <span class="badge bg-info">Refunded</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div>{{ $transaction->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.transactions.detail', $transaction) }}">
                                            <i class="fas fa-eye me-2"></i>View Details
                                        </a></li>
                                        @if($transaction->status === 'pending')
                                        <li><a class="dropdown-item" href="#" onclick="processTransaction({{ $transaction->id }})">
                                            <i class="fas fa-check me-2"></i>Mark as Completed
                                        </a></li>
                                        <li><a class="dropdown-item text-warning" href="#" onclick="failTransaction({{ $transaction->id }})">
                                            <i class="fas fa-times me-2"></i>Mark as Failed
                                        </a></li>
                                        @endif
                                        @if($transaction->status === 'completed')
                                        <li><a class="dropdown-item text-info" href="#" onclick="refundTransaction({{ $transaction->id }})">
                                            <i class="fas fa-undo me-2"></i>Process Refund
                                        </a></li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="downloadTransactionReceipt({{ $transaction->id }})">
                                            <i class="fas fa-download me-2"></i>Download Receipt
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
                {{ $transactions->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No transactions found</h5>
                <p class="text-muted">Transactions will appear here when payments are processed</p>
            </div>
        @endif
    </div>
</div>

<!-- Process Transaction Modal -->
<div class="modal fade" id="processTransactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check me-2"></i>Process Transaction
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to mark this transaction as completed?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    This action will trigger payout processing to the warung owner.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmProcessTransaction">
                    <i class="fas fa-check me-1"></i>Mark as Completed
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Refund Transaction Modal -->
<div class="modal fade" id="refundTransactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-undo me-2"></i>Process Refund
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="refundForm">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Refund Reason *</label>
                        <select class="form-select" name="refund_reason" required>
                            <option value="">Select reason...</option>
                            <option value="customer_request">Customer Request</option>
                            <option value="order_cancelled">Order Cancelled</option>
                            <option value="payment_issue">Payment Issue</option>
                            <option value="warung_unavailable">Warung Unavailable</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Refund Notes</label>
                        <textarea class="form-control" name="refund_notes" rows="3" placeholder="Additional notes about the refund..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This action cannot be undone. The refund will be processed immediately.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-undo me-1"></i>Process Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.transaction-row {
    transition: all 0.3s ease;
}

.transaction-row:hover {
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
</style>
@endpush

@push('scripts')
<script>
// Filter functionality
document.getElementById('statusFilter').addEventListener('change', filterTransactions);
document.getElementById('searchTransactions').addEventListener('input', filterTransactions);

function filterTransactions() {
    const statusFilter = document.getElementById('statusFilter').value;
    const searchTerm = document.getElementById('searchTransactions').value.toLowerCase();
    
    document.querySelectorAll('.transaction-row').forEach(row => {
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
    document.getElementById('dateFilter').value = '';
    document.getElementById('searchTransactions').value = '';
    filterTransactions();
}

function refreshTransactions() {
    location.reload();
}

function exportTransactions() {
    // Implement export functionality
    alert('Export functionality will be implemented');
}

let currentTransactionId = null;

function processTransaction(transactionId) {
    currentTransactionId = transactionId;
    new bootstrap.Modal(document.getElementById('processTransactionModal')).show();
}

function failTransaction(transactionId) {
    if (confirm('Are you sure you want to mark this transaction as failed?')) {
        fetch(`/admin/transactions/${transactionId}/fail`, {
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
            alert('Failed to update transaction status');
        });
    }
}

function refundTransaction(transactionId) {
    currentTransactionId = transactionId;
    new bootstrap.Modal(document.getElementById('refundTransactionModal')).show();
}

function downloadTransactionReceipt(transactionId) {
    window.open(`/admin/transactions/${transactionId}/receipt`, '_blank');
}

// Handle process transaction confirmation
document.getElementById('confirmProcessTransaction').addEventListener('click', function() {
    if (currentTransactionId) {
        fetch(`/admin/transactions/${currentTransactionId}/process`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('processTransactionModal')).hide();
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to process transaction');
        });
    }
});

// Handle refund form submission
document.getElementById('refundForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    if (currentTransactionId) {
        fetch(`/admin/transactions/${currentTransactionId}/refund`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('refundTransactionModal')).hide();
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to process refund');
        });
    }
});
</script>
@endpush
