@extends('layouts.penjual')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-money-bill-wave me-2 text-success"></i>
                        Kelola Pencairan
                    </h1>
                    <p class="text-muted mb-0">Kelola pencairan dana dari penjualan Anda</p>
                </div>
                <a href="{{ route('penjual.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Balance & Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Saldo Saat Ini</h6>
                            <h4 class="mb-0">Rp {{ number_format($currentBalance, 0, ',', '.') }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-wallet fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Dicairkan</h6>
                            <h4 class="mb-0">Rp {{ number_format($totalPaid, 0, ',', '.') }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Sedang Diproses</h6>
                            <h4 class="mb-0">Rp {{ number_format($pendingAmount, 0, ',', '.') }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Transaksi</h6>
                            <h4 class="mb-0">{{ $payouts->total() }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-list fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Payout Form -->
    @if($currentBalance >= 10000)
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-plus-circle me-2 text-primary"></i>
                Ajukan Pencairan Dana
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('penjual.payouts.request') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label for="amount" class="form-label">Jumlah Pencairan</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" name="amount" 
                               min="10000" max="{{ $currentBalance }}" 
                               placeholder="Minimal Rp 10.000">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text">
                        Minimal pencairan: Rp 10.000 | Maksimal: Rp {{ number_format($currentBalance, 0, ',', '.') }}
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Ajukan Pencairan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Saldo minimum untuk pencairan adalah Rp 10.000. Saldo Anda saat ini: <strong>Rp {{ number_format($currentBalance, 0, ',', '.') }}</strong>
    </div>
    @endif

    <!-- Payout History -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-history me-2 text-secondary"></i>
                Riwayat Pencairan
            </h5>
        </div>
        <div class="card-body">
            @if($payouts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Diproses</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payouts as $payout)
                            <tr>
                                <td>
                                    {{ $payout->requested_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <strong class="text-success">
                                        Rp {{ number_format($payout->amount, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    @switch($payout->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Menunggu</span>
                                            @break
                                        @case('processing')
                                            <span class="badge bg-info">Diproses</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success">Selesai</span>
                                            @break
                                        @case('failed')
                                            <span class="badge bg-danger">Gagal</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($payout->status) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    @if($payout->processed_at)
                                        {{ $payout->processed_at->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payout->notes)
                                        <small class="text-muted">{{ $payout->notes }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $payouts->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted mb-2">Belum ada riwayat pencairan</h5>
                    <p class="text-muted">Riwayat pencairan Anda akan muncul di sini</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format input amount
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
});
</script>
@endpush
