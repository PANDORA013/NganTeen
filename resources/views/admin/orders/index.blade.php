@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Orders Management</h1>
        <p class="page-subtitle">Kelola semua pesanan dari pengguna platform</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-outline-primary" onclick="exportOrders()">
            <i class="fas fa-download me-2"></i>Export Orders
        </button>
        <button type="button" class="btn btn-primary" onclick="refreshOrders()">
            <i class="fas fa-sync me-2"></i>Refresh
        </button>
    </div>
</div>

<!-- Orders Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="admin-card stats-card" style="--card-bg-from: #667eea; --card-bg-to: #764ba2;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ number_format($stats['total_orders']) }}</div>
                            <div class="stats-label">Total Orders</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-shopping-cart"></i>
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
                            <div class="stats-number">{{ number_format($stats['pending_orders']) }}</div>
                            <div class="stats-label">Pending Orders</div>
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
        <div class="admin-card stats-card" style="--card-bg-from: #4facfe; --card-bg-to: #00f2fe;">
            <div class="card-body">
                <div class="stats-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                            <div class="stats-label">Total Revenue</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-money-bill"></i>
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
                            <div class="stats-number">{{ number_format($stats['today_orders']) }}</div>
                            <div class="stats-label">Today's Orders</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Filters -->
<div class="admin-card mb-4">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <label class="form-label">Status Filter</label>
                <select class="form-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
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
                    <input type="text" class="form-control" id="searchOrders" placeholder="Search by order ID, customer name...">
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

<!-- Orders Table -->
<div class="admin-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-shopping-cart me-2"></i>Orders List
            </h5>
            <div class="d-flex gap-2">
                <span class="badge bg-primary">{{ $orders->total() }} orders</span>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Warung</th>
                            <th>Items</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="order-row" data-status="{{ $order->payment_status }}">
                            <td>
                                <strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <div class="avatar-placeholder">
                                            {{ strtoupper(substr($order->buyer->name ?? 'U', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $order->buyer->name ?? 'Unknown' }}</div>
                                        <small class="text-muted">{{ $order->buyer->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $warungs = $order->items->pluck('warung.nama')->unique();
                                @endphp
                                @if($warungs->count() > 1)
                                    <span class="badge bg-info">{{ $warungs->count() }} Warungs</span>
                                @else
                                    {{ $warungs->first() ?? 'Unknown' }}
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $order->items->count() }} items</span>
                                @if($order->items->count() > 0)
                                    <small class="d-block text-muted">
                                        {{ $order->items->first()->menu->nama_menu ?? 'Unknown Item' }}
                                        @if($order->items->count() > 1)
                                            + {{ $order->items->count() - 1 }} more
                                        @endif
                                    </small>
                                @endif
                            </td>
                            <td>
                                <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($order->payment_status) {
                                        'paid' => 'success',
                                        'pending' => 'warning',
                                        'failed' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $order->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="viewOrder({{ $order->id }})">
                                            <i class="fas fa-eye me-2"></i>View Details
                                        </a></li>
                                        @if($order->payment_status === 'pending')
                                        <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'paid')">
                                            <i class="fas fa-check me-2"></i>Mark as Paid
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'failed')">
                                            <i class="fas fa-times me-2"></i>Mark as Failed
                                        </a></li>
                                        @endif
                                        <li><a class="dropdown-item" href="mailto:{{ $order->buyer->email ?? '' }}">
                                            <i class="fas fa-envelope me-2"></i>Contact Customer
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteOrder({{ $order->id }})">
                                            <i class="fas fa-trash me-2"></i>Delete Order
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
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No orders found</h5>
                <p class="text-muted">Orders will appear here when customers make purchases</p>
            </div>
        @endif
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-shopping-cart me-2"></i>Order Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- Order details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printOrder()">
                    <i class="fas fa-print me-1"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.avatar-sm {
    width: 35px;
    height: 35px;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: bold;
    font-size: 14px;
}

.order-row {
    transition: all 0.3s ease;
}

.order-row:hover {
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
document.getElementById('statusFilter').addEventListener('change', filterOrders);
document.getElementById('dateFilter').addEventListener('change', filterOrders);
document.getElementById('searchOrders').addEventListener('input', filterOrders);

function filterOrders() {
    const statusFilter = document.getElementById('statusFilter').value;
    const searchTerm = document.getElementById('searchOrders').value.toLowerCase();
    
    document.querySelectorAll('.order-row').forEach(row => {
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
    document.getElementById('searchOrders').value = '';
    filterOrders();
}

function refreshOrders() {
    location.reload();
}

function exportOrders() {
    // Implement export functionality
    alert('Export functionality will be implemented');
}

function viewOrder(orderId) {
    // Load order details via AJAX
    fetch(`/admin/orders/${orderId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('orderDetailsContent').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('orderDetailsModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load order details');
        });
}

function updateOrderStatus(orderId, status) {
    if (confirm(`Are you sure you want to mark this order as ${status}?`)) {
        fetch(`/admin/orders/${orderId}/status`, {
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
            alert('Failed to update order status');
        });
    }
}

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        fetch(`/admin/orders/${orderId}`, {
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
            alert('Failed to delete order');
        });
    }
}

function printOrder() {
    window.print();
}
</script>
@endpush
