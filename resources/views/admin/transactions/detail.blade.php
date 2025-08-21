@extends('layouts.admin')

@section('title', 'Transaction Detail')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Transaction Detail</h1>
        <p class="page-subtitle">Detail informasi transaksi #TXN-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.transactions') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Transactions
        </a>
        <button type="button" class="btn btn-outline-primary" onclick="downloadReceipt()">
            <i class="fas fa-download me-2"></i>Download Receipt
        </button>
        <button type="button" class="btn btn-primary" onclick="printTransaction()">
            <i class="fas fa-print me-2"></i>Print
        </button>
    </div>
</div>

<!-- Transaction Overview -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #667eea; --card-bg-to: #764ba2;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                            <div class="stats-label">Transaction Amount</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-money-bill-wave"></i>
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
                            <div class="stats-number">Rp {{ number_format($transaction->admin_fee ?? 0, 0, ',', '.') }}</div>
                            <div class="stats-label">Admin Fee</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-percentage"></i>
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
                            <div class="stats-number">Rp {{ number_format($transaction->amount - ($transaction->admin_fee ?? 0), 0, ',', '.') }}</div>
                            <div class="stats-label">Net Amount</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-calculator"></i>
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
                            @switch($transaction->status)
                                @case('pending')
                                    <div class="stats-number text-warning">PENDING</div>
                                    @break
                                @case('completed')
                                    <div class="stats-number text-success">COMPLETED</div>
                                    @break
                                @case('failed')
                                    <div class="stats-number text-danger">FAILED</div>
                                    @break
                                @case('refunded')
                                    <div class="stats-number text-info">REFUNDED</div>
                                    @break
                                @default
                                    <div class="stats-number">{{ strtoupper($transaction->status) }}</div>
                            @endswitch
                            <div class="stats-label">Status</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Transaction Information -->
    <div class="col-lg-8">
        <div class="admin-card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exchange-alt me-2"></i>Transaction Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-medium">Transaction ID:</td>
                                <td>#TXN-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">External ID:</td>
                                <td>{{ $transaction->transaction_id ?? 'System Generated' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Order Number:</td>
                                <td>
                                    @if($transaction->globalOrder)
                                        <a href="{{ route('admin.orders.detail', $transaction->globalOrder->order_number) }}" class="text-decoration-none">
                                            #{{ $transaction->globalOrder->order_number }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Payment Method:</td>
                                <td>{{ $transaction->payment_method ?? 'Not specified' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-medium">Created:</td>
                                <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Updated:</td>
                                <td>{{ $transaction->updated_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Processed:</td>
                                <td>{{ $transaction->processed_at ? $transaction->processed_at->format('d M Y, H:i') : 'Not processed' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Status:</td>
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
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        @if($transaction->globalOrder)
        <div class="admin-card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>Order Details
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Warung</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->globalOrder->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->menu && $item->menu->gambar)
                                            <img src="{{ asset('storage/' . $item->menu->gambar) }}" alt="{{ $item->menu_name }}" class="item-thumb me-3">
                                        @else
                                            <div class="item-placeholder me-3">
                                                {{ strtoupper(substr($item->menu_name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-medium">{{ $item->menu_name }}</div>
                                            @if($item->notes)
                                                <small class="text-muted">Notes: {{ $item->notes }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $item->warung->nama_warung ?? 'Unknown' }}</div>
                                    <small class="text-muted">{{ $item->warung->lokasi ?? '' }}</small>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end">Total Amount:</th>
                                <th>Rp {{ number_format($transaction->globalOrder->total_amount, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Transaction Notes -->
        @if($transaction->notes)
        <div class="admin-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-sticky-note me-2"></i>Transaction Notes
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $transaction->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Customer & Actions -->
    <div class="col-lg-4">
        <!-- Customer Information -->
        @if($transaction->globalOrder && $transaction->globalOrder->buyer)
        <div class="admin-card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Customer Information
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-lg me-3">
                        @if($transaction->globalOrder->buyer->profile_photo)
                            <img src="{{ asset('storage/' . $transaction->globalOrder->buyer->profile_photo) }}" alt="{{ $transaction->globalOrder->buyer->name }}" class="avatar-img">
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr($transaction->globalOrder->buyer->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $transaction->globalOrder->buyer->name }}</h6>
                        <p class="text-muted mb-0">{{ $transaction->globalOrder->buyer->email }}</p>
                    </div>
                </div>
                
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="fw-medium">Phone:</td>
                        <td>{{ $transaction->globalOrder->buyer->phone ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Customer Since:</td>
                        <td>{{ $transaction->globalOrder->buyer->created_at->format('M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-medium">Total Orders:</td>
                        <td>{{ $transaction->globalOrder->buyer->globalOrders->count() }}</td>
                    </tr>
                </table>

                <div class="d-grid gap-2">
                    <a href="mailto:{{ $transaction->globalOrder->buyer->email }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-envelope me-1"></i>Send Email
                    </a>
                    @if($transaction->globalOrder->buyer->phone)
                    <a href="tel:{{ $transaction->globalOrder->buyer->phone }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-phone me-1"></i>Call Customer
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Transaction Actions -->
        <div class="admin-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>Transaction Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($transaction->status === 'pending')
                    <button class="btn btn-success" onclick="processTransaction()">
                        <i class="fas fa-check me-1"></i>Mark as Completed
                    </button>
                    <button class="btn btn-warning" onclick="failTransaction()">
                        <i class="fas fa-times me-1"></i>Mark as Failed
                    </button>
                    @endif
                    
                    @if($transaction->status === 'completed')
                    <button class="btn btn-info" onclick="refundTransaction()">
                        <i class="fas fa-undo me-1"></i>Process Refund
                    </button>
                    @endif
                    
                    <button class="btn btn-outline-primary" onclick="downloadReceipt()">
                        <i class="fas fa-download me-1"></i>Download Receipt
                    </button>
                    
                    <button class="btn btn-outline-secondary" onclick="addNote()">
                        <i class="fas fa-sticky-note me-1"></i>Add Note
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-sticky-note me-2"></i>Add Transaction Note
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addNoteForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Note *</label>
                        <textarea class="form-control" name="note" rows="4" placeholder="Add a note about this transaction..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.item-thumb {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 6px;
}

.item-placeholder {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-weight: bold;
    font-size: 14px;
}

.avatar-lg {
    width: 60px;
    height: 60px;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
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
    font-size: 18px;
}

.stats-card:hover {
    transform: translateY(-5px);
}

@media print {
    .page-actions, .btn, .dropdown {
        display: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function processTransaction() {
    if (confirm('Are you sure you want to mark this transaction as completed?')) {
        fetch(`/admin/transactions/{{ $transaction->id }}/process`, {
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
            alert('Failed to process transaction');
        });
    }
}

function failTransaction() {
    if (confirm('Are you sure you want to mark this transaction as failed?')) {
        fetch(`/admin/transactions/{{ $transaction->id }}/fail`, {
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

function refundTransaction() {
    if (confirm('Are you sure you want to process a refund for this transaction?')) {
        fetch(`/admin/transactions/{{ $transaction->id }}/refund`, {
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
            alert('Failed to process refund');
        });
    }
}

function downloadReceipt() {
    window.open(`/admin/transactions/{{ $transaction->id }}/receipt`, '_blank');
}

function printTransaction() {
    window.print();
}

function addNote() {
    new bootstrap.Modal(document.getElementById('addNoteModal')).show();
}

// Handle add note form submission
document.getElementById('addNoteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/admin/transactions/{{ $transaction->id }}/add-note`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('addNoteModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to add note');
    });
});
</script>
@endpush
