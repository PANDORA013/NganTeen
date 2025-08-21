@extends('layouts.admin')

@section('title', 'Kelola Payout - Admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-wallet me-2"></i>Kelola Payout
            </h1>
            <p class="text-muted">Proses pencairan dana untuk warung</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
        <!-- Pending Payout -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Payout
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['pending_amount']) }}
                            </div>
                            <small class="text-muted">{{ $stats['pending_count'] }} transaksi</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Payout -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed Payout
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['completed_amount']) }}
                            </div>
                            <small class="text-muted">{{ $stats['completed_count'] }} transaksi</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Warung -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Warung
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_warungs'] }}
                            </div>
                            <small class="text-muted">dengan saldo aktif</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-bolt me-2"></i>Aksi Cepat
            </h6>
            <div class="d-flex">
                <button onclick="processAllPayouts()" 
                        class="btn btn-success btn-sm me-2" {{ $stats['pending_count'] == 0 ? 'disabled' : '' }}
                    <i class="fas fa-cogs me-1"></i>Proses Semua ({{ $stats['pending_count'] }})
                </button>
                <button onclick="showCreatePayoutModal()" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Buat Payout Manual
                </button>
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted small">Kelola semua permintaan payout dari warung secara efisien</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filter Payout
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label font-weight-bold">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Processed</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="warung_id" class="form-label font-weight-bold">Warung</label>
                    <select name="warung_id" id="warung_id" class="form-select">
                        <option value="">Semua Warung</option>
                        @foreach($warungs as $warung)
                            <option value="{{ $warung->id }}" {{ request('warung_id') == $warung->id ? 'selected' : '' }}>
                                {{ $warung->nama_warung }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label font-weight-bold">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payouts Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Daftar Payout ({{ $payouts->total() }} total)
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Warung</th>
                            <th>Jumlah</th>
                            <th>Rekening</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $payout)
                            <tr>
                                <td>
                                    <div class="font-weight-bold">{{ $payout->warung->nama_warung }}</div>
                                    <small class="text-muted">{{ $payout->warung->nama_pemilik }}</small>
                                </td>
                                <td>
                                    <div class="font-weight-bold">Rp {{ number_format($payout->amount) }}</div>
                                    @if($payout->admin_fee > 0)
                                        <small class="text-muted">Fee: Rp {{ number_format($payout->admin_fee) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $payout->bank_name }}</div>
                                    <small class="text-muted">{{ $payout->account_number }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $payout->status === 'completed' ? 'bg-success' : 
                                           ($payout->status === 'pending' ? 'bg-warning' : 
                                           ($payout->status === 'processed' ? 'bg-info' : 'bg-danger')) }}">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">{{ $payout->created_at->format('d M Y, H:i') }}</div>
                                    @if($payout->processed_at)
                                        <small class="text-muted">Processed: {{ $payout->processed_at->format('d M Y, H:i') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($payout->status === 'pending')
                                        <button onclick="processPayout('{{ $payout->id }}')" class="btn btn-info btn-sm me-2">
                                            <i class="fas fa-cog me-1"></i>Proses
                                        </button>
                                    @endif
                                    @if($payout->status === 'processed')
                                        <button onclick="completePayout('{{ $payout->id }}')" class="btn btn-success btn-sm me-2">
                                            <i class="fas fa-check me-1"></i>Complete
                                        </button>
                                        <button onclick="failPayout('{{ $payout->id }}')" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times me-1"></i>Gagal
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-wallet fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada payout ditemukan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($payouts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $payouts->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Payout Modal -->
<div class="modal fade" id="createPayoutModal" tabindex="-1" aria-labelledby="createPayoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPayoutModalLabel">
                    <i class="fas fa-plus me-2"></i>Buat Payout Manual
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createPayoutForm" method="POST" action="{{ route('admin.payouts.create') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="warung_id" class="form-label font-weight-bold">Warung</label>
                        <select name="warung_id" id="warung_id" required class="form-select">
                            <option value="">Pilih Warung</option>
                            @foreach($warungs as $warung)
                                @if($warung->wallet && $warung->wallet->available_balance > 0)
                                    <option value="{{ $warung->id }}" data-balance="{{ $warung->wallet->available_balance }}">
                                        {{ $warung->nama_warung }} (Saldo: Rp {{ number_format($warung->wallet->available_balance) }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label font-weight-bold">Jumlah</label>
                        <input type="number" name="amount" id="amount" min="10000" class="form-control" required>
                        <small class="form-text text-muted">Minimum Rp 10.000</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Buat Payout
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showCreatePayoutModal() {
    var modal = new bootstrap.Modal(document.getElementById('createPayoutModal'));
    modal.show();
}

function processPayout(payoutId) {
    if (confirm('Apakah Anda yakin ingin memproses payout ini?')) {
        fetch(`/admin/payouts/${payoutId}/process`, {
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
                alert('Gagal memproses payout: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function completePayout(payoutId) {
    if (confirm('Apakah Anda yakin payout ini sudah berhasil ditransfer?')) {
        fetch(`/admin/payouts/${payoutId}/complete`, {
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
                alert('Gagal menyelesaikan payout: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function failPayout(payoutId) {
    if (confirm('Apakah Anda yakin ingin menandai payout ini sebagai gagal?')) {
        fetch(`/admin/payouts/${payoutId}/fail`, {
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
                alert('Gagal menggagalkan payout: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function processAllPayouts() {
    if (confirm('Apakah Anda yakin ingin memproses semua payout pending?')) {
        fetch('/admin/payouts/process-all', {
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
                alert('Gagal memproses semua payout: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

// Update amount max when warung is selected
document.querySelector('select[name="warung_id"]').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const balance = selectedOption.getAttribute('data-balance');
    const amountInput = document.querySelector('input[name="amount"]');
    
    if (balance) {
        amountInput.max = balance;
        amountInput.placeholder = `Maksimal Rp ${parseInt(balance).toLocaleString()}`;
    }
});
</script>
@endsection
