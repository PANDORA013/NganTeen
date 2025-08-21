@extends('layouts.admin')

@section('title', 'Kelola Order - Admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Kelola Order</h1>
                    <p class="text-gray-600">Pantau dan kelola semua order global</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    @extends('layouts.admin')

@section('title', 'Kelola Order - Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-shopping-cart me-2"></i>Kelola Order
            </h1>
            <p class="text-muted">Pantau dan kelola semua order global</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>
    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filter Order
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="payment_status" class="form-label font-weight-bold">Status Pembayaran</label>
                    <select name="payment_status" id="payment_status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label font-weight-bold">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label font-weight-bold">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
            </form>
        </div>

    <!-- Orders Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Daftar Order ({{ $orders->total() }} total)
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Pembeli</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <div class="font-weight-bold">{{ $order->order_number }}</div>
                                    <small class="text-muted">{{ $order->items->count() }} item(s)</small>
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $order->buyer->name }}</div>
                                    <small class="text-muted">{{ $order->buyer->email }}</small>
                                </td>
                                <td>
                                    <div class="small">
                                        @foreach($order->items->groupBy('warung.nama_warung') as $warungName => $items)
                                            <div class="mb-1">
                                                <span class="font-weight-bold">{{ $warungName }}:</span>
                                                @foreach($items as $item)
                                                    <span class="text-muted">{{ $item->menu_name }} ({{ $item->quantity }}x)</span>{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-bold">Rp {{ number_format($order->total_amount) }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 
                                           ($order->payment_status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.detail', $order->order_number) }}" class="btn btn-info btn-sm me-2">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                    @if($order->payment_status === 'pending')
                                        <button onclick="markAsPaid('{{ $order->order_number }}')" class="btn btn-success btn-sm">
                                            <i class="fas fa-check me-1"></i>Mark Paid
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada order ditemukan</p>
                                    Tidak ada order ditemukan
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
</div>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">
                    <i class="fas fa-receipt me-2"></i>Detail Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="orderDetail">
                    <!-- Order details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function viewOrder(orderNumber) {
    fetch(`/admin/orders/${orderNumber}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('orderDetail').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-primary mb-3">Informasi Order</h6>
                        <div class="small mb-3">
                            <p><span class="font-weight-bold">Order Number:</span> ${data.order_number}</p>
                            <p><span class="font-weight-bold">Pembeli:</span> ${data.buyer.name}</p>
                            <p><span class="font-weight-bold">Email:</span> ${data.buyer.email}</p>
                            <p><span class="font-weight-bold">Total:</span> Rp ${data.total_amount.toLocaleString()}</p>
                            <p><span class="font-weight-bold">Status:</span> <span class="badge bg-${data.payment_status === 'paid' ? 'success' : (data.payment_status === 'pending' ? 'warning' : 'danger')}">${data.payment_status}</span></p>
                            <p><span class="font-weight-bold">Tanggal:</span> ${new Date(data.created_at).toLocaleDateString('id-ID')}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-primary mb-3">Items</h6>
                        <div class="small">
                            ${Object.entries(data.items_by_warung).map(([warung, items]) => `
                                <div class="border-left border-primary pl-3 mb-3">
                                    <p class="font-weight-bold text-primary">${warung}</p>
                                    ${items.map(item => `
                                        <p class="text-muted mb-1">${item.menu_name} - ${item.quantity}x @ Rp ${item.price.toLocaleString()}</p>
                                    `).join('')}
                                    <p class="font-weight-bold">Subtotal: Rp ${items.reduce((sum, item) => sum + (item.price * item.quantity), 0).toLocaleString()}</p>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
            
            // Show modal using Bootstrap
            var modal = new bootstrap.Modal(document.getElementById('orderModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail order');
        });
}

function markAsPaid(orderNumber) {
    if (confirm('Apakah Anda yakin ingin menandai order ini sebagai paid?')) {
        fetch(`/admin/orders/${orderNumber}/mark-paid`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal memperbarui status order');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}
</script>
@endsection
