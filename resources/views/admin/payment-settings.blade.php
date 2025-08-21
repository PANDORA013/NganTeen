@extends('layouts.admin')

@section('title', 'Payment Settings')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-credit-card me-2"></i>Payment Settings
            </h1>
            <p class="text-muted">Kelola pengaturan pembayaran terpusat</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['total_revenue']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                Platform Fee Earned
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($stats['total_platform_fee']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Settlement
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['unsettled_orders'] }}
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
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_paid_orders'] + $stats['total_pending_orders'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment Settings Form -->
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>Payment Configuration
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payment.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Merchant Information -->
                        <div class="mb-4">
                            <h5 class="text-gray-800 mb-3">Merchant Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="merchant_name" class="form-label">Merchant Name</label>
                                    <input type="text" class="form-control" name="merchant_name" 
                                           value="{{ $setting->merchant_name }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="merchant_id" class="form-label">Merchant ID</label>
                                    <input type="text" class="form-control" name="merchant_id" 
                                           value="{{ $setting->merchant_id }}">
                                </div>
                            </div>
                        </div>

                        <!-- Fee Settings -->
                        <div class="mb-4">
                            <h5 class="text-gray-800 mb-3">Fee Configuration</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="platform_fee_percentage" class="form-label">Platform Fee (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="platform_fee_percentage" 
                                               value="{{ $setting->platform_fee_percentage }}" 
                                               step="0.01" min="0" max="50" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">Fee yang dipotong dari total order untuk platform</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="payment_fee_fixed" class="form-label">Payment Fee (Rp)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control" name="payment_fee_fixed" 
                                               value="{{ $setting->payment_fee_fixed }}" 
                                               min="0" required>
                                    </div>
                                    <small class="text-muted">Fee tetap untuk setiap transaksi</small>
                                </div>
                            </div>
                        </div>

                        <!-- QRIS Settings -->
                        <div class="mb-4">
                            <h5 class="text-gray-800 mb-3">QRIS Configuration</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="qris_image" class="form-label">QRIS Code Image</label>
                                    <input type="file" class="form-control" name="qris_image" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
                                </div>
                                <div class="col-md-6">
                                    @if($setting->qris_image_url)
                                        <label class="form-label">Current QRIS</label>
                                        <div class="border rounded p-2 text-center">
                                            <img src="{{ $setting->qris_image_url }}" 
                                                 alt="Current QRIS" class="img-thumbnail" style="max-width: 150px;">
                                        </div>
                                    @else
                                        <div class="border rounded p-4 text-center text-muted">
                                            <i class="fas fa-qrcode fa-2x mb-2"></i>
                                            <p class="mb-0">No QRIS uploaded</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Bank Transfer Settings -->
                        <div class="mb-4">
                            <h5 class="text-gray-800 mb-3">Bank Transfer (Alternative Payment)</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control" name="bank_name" 
                                           value="{{ $setting->bank_name }}" placeholder="e.g. BCA">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bank_account_number" class="form-label">Account Number</label>
                                    <input type="text" class="form-control" name="bank_account_number" 
                                           value="{{ $setting->bank_account_number }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bank_account_name" class="form-label">Account Name</label>
                                    <input type="text" class="form-control" name="bank_account_name" 
                                           value="{{ $setting->bank_account_name }}">
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.settlements') }}" class="btn btn-outline-primary">
                            <i class="fas fa-handshake me-1"></i>
                            Manage Settlements
                            @if($stats['unsettled_orders'] > 0)
                                <span class="badge badge-warning ms-2">{{ $stats['unsettled_orders'] }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-success">
                            <i class="fas fa-list me-1"></i>View All Orders
                        </a>
                        
                        <a href="{{ route('admin.transactions') }}" class="btn btn-outline-info">
                            <i class="fas fa-receipt me-1"></i>Transaction History
                        </a>
                    </div>
                </div>
            </div>

            <!-- Fee Calculator -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calculator me-2"></i>Fee Calculator
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="calc_amount" class="form-label">Order Amount (Rp)</label>
                        <input type="number" class="form-control" id="calc_amount" placeholder="100000">
                    </div>
                    
                    <div id="calc_result" class="d-none">
                        <div class="border rounded p-3 bg-light">
                            <div class="small mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Order Amount:</span>
                                    <span id="calc_order_amount">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Platform Fee:</span>
                                    <span id="calc_platform_fee">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Payment Fee:</span>
                                    <span id="calc_payment_fee">Rp 0</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Customer Pays:</span>
                                    <span id="calc_gross_amount" class="text-success">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Warung Gets:</span>
                                    <span id="calc_net_amount" class="text-primary">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Fee Calculator
document.getElementById('calc_amount').addEventListener('input', function() {
    const amount = parseFloat(this.value) || 0;
    
    if (amount > 0) {
        const platformFeePercent = {{ $setting->platform_fee_percentage }};
        const paymentFee = {{ $setting->payment_fee_fixed }};
        
        const platformFee = (amount * platformFeePercent) / 100;
        const grossAmount = amount + platformFee + paymentFee;
        const netAmount = amount - platformFee;
        
        document.getElementById('calc_order_amount').textContent = 'Rp ' + amount.toLocaleString('id-ID');
        document.getElementById('calc_platform_fee').textContent = 'Rp ' + platformFee.toLocaleString('id-ID');
        document.getElementById('calc_payment_fee').textContent = 'Rp ' + paymentFee.toLocaleString('id-ID');
        document.getElementById('calc_gross_amount').textContent = 'Rp ' + grossAmount.toLocaleString('id-ID');
        document.getElementById('calc_net_amount').textContent = 'Rp ' + netAmount.toLocaleString('id-ID');
        
        document.getElementById('calc_result').classList.remove('d-none');
    } else {
        document.getElementById('calc_result').classList.add('d-none');
    }
});
</script>
@endsection
