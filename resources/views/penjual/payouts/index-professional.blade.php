@extends('layouts.penjual')

@section('styles')
<link href="{{ asset('css/professional-penjual.css') }}" rel="stylesheet">
<style>
    /* Professional Payouts Page Styles */
    .payout-hero {
        background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
        border-radius: 1rem;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .payout-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="money-pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23money-pattern)"/></svg>');
    }
    
    .payout-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .balance-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        border: 1px solid #e5e7eb;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .balance-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(135deg, #059669, #047857);
    }
    
    .balance-card.current::before {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }
    
    .balance-card.pending::before {
        background: linear-gradient(135deg, #d97706, #b45309);
    }
    
    .balance-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    
    .balance-label {
        font-size: 1rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .payout-form-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        border: 1px solid #e5e7eb;
        margin-bottom: 2rem;
    }
    
    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .form-icon {
        width: 2.5rem;
        height: 2.5rem;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .amount-input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .amount-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        transition: all 0.3s ease;
    }
    
    .amount-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
    .currency-symbol {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.25rem;
        font-weight: 600;
        color: #6b7280;
    }
    
    .amount-suggestions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    
    .amount-chip {
        padding: 0.5rem 1rem;
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .amount-chip:hover {
        background: #e5e7eb;
        color: #1f2937;
    }
    
    .amount-chip.active {
        background: #2563eb;
        border-color: #2563eb;
        color: white;
    }
    
    .payout-requirements {
        background: #eff6ff;
        border: 1px solid #93c5fd;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .requirements-title {
        font-weight: 600;
        color: #1e40af;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .requirements-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .requirements-list li {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
        color: #1e40af;
    }
    
    .requirements-list li:last-child {
        margin-bottom: 0;
    }
    
    .requirement-check {
        width: 1.25rem;
        height: 1.25rem;
        background: #2563eb;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }
    
    .payout-history {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    
    .history-header {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .history-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }
    
    .payout-item {
        padding: 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .payout-item:last-child {
        border-bottom: none;
    }
    
    .payout-details {
        flex: 1;
    }
    
    .payout-amount {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .payout-date {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }
    
    .payout-id {
        font-size: 0.75rem;
        color: #9ca3af;
        font-family: monospace;
    }
    
    .payout-status {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .status-completed {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .status-processing {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .empty-history {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
    }
    
    .empty-icon {
        width: 3rem;
        height: 3rem;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #9ca3af;
        margin: 0 auto 1rem;
    }
    
    .bank-info {
        background: #f8fafc;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .bank-title {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .bank-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .bank-field {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .bank-field:last-child {
        border-bottom: none;
    }
    
    .bank-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .bank-value {
        font-size: 0.875rem;
        color: #1f2937;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .payout-hero {
            padding: 1.5rem;
        }
        
        .payout-summary {
            grid-template-columns: 1fr;
        }
        
        .amount-suggestions {
            justify-content: center;
        }
        
        .payout-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .bank-details {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Professional Page Header -->
    <div class="professional-page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1><i class="fas fa-money-bill-wave me-3"></i>Pencairan Dana</h1>
                    <p class="mb-0">Kelola pencairan dana ke rekening bank Anda</p>
                    <nav aria-label="breadcrumb" class="mt-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('penjual.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Pencairan Dana</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="text-white">
                        <div class="h4 mb-0">Rp {{ number_format($currentBalance, 0, ',', '.') }}</div>
                        <small>Saldo Tersedia</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Professional Alerts -->
    @if(session('success'))
    <div class="professional-alert professional-alert-success">
        <i class="fas fa-check-circle"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="professional-alert professional-alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    @if($errors->any())
    <div class="professional-alert professional-alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Professional Balance Summary -->
    @if(isset($payoutStats))
    <div class="payout-summary professional-fade-in">
        <div class="balance-card current">
            <div class="balance-value">Rp {{ number_format($payoutStats['current_balance'], 0, ',', '.') }}</div>
            <div class="balance-label">Saldo Tersedia</div>
        </div>
        <div class="balance-card pending">
            <div class="balance-value">Rp {{ number_format($payoutStats['pending_payouts'], 0, ',', '.') }}</div>
            <div class="balance-label">Pencairan Pending</div>
        </div>
        <div class="balance-card">
            <div class="balance-value">Rp {{ number_format($payoutStats['total_payouts'], 0, ',', '.') }}</div>
            <div class="balance-label">Total Dicairkan</div>
        </div>
        <div class="balance-card">
            <div class="balance-value">Rp {{ number_format($payoutStats['this_month_payouts'], 0, ',', '.') }}</div>
            <div class="balance-label">Bulan Ini</div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-6">
            <!-- Professional Payout Form -->
            <div class="payout-form-card professional-slide-up">
                <div class="form-title">
                    <div class="form-icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    Ajukan Pencairan Dana
                </div>

                <!-- Bank Information -->
                @if($warung->bank_name)
                <div class="bank-info">
                    <div class="bank-title">
                        <i class="fas fa-university"></i>
                        Informasi Rekening Bank
                    </div>
                    <div class="bank-details">
                        <div class="bank-field">
                            <span class="bank-label">Bank:</span>
                            <span class="bank-value">{{ $warung->bank_name }}</span>
                        </div>
                        <div class="bank-field">
                            <span class="bank-label">Nomor Rekening:</span>
                            <span class="bank-value">{{ $warung->bank_account_number }}</span>
                        </div>
                        <div class="bank-field">
                            <span class="bank-label">Nama Pemilik:</span>
                            <span class="bank-value">{{ $warung->bank_account_name }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Payout Requirements -->
                <div class="payout-requirements">
                    <div class="requirements-title">
                        <i class="fas fa-info-circle"></i>
                        Syarat Pencairan Dana
                    </div>
                    <ul class="requirements-list">
                        <li>
                            <div class="requirement-check">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>Minimal pencairan Rp 50.000</div>
                        </li>
                        <li>
                            <div class="requirement-check">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>Saldo tersedia mencukupi</div>
                        </li>
                        <li>
                            <div class="requirement-check">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>Informasi bank sudah lengkap</div>
                        </li>
                        <li>
                            <div class="requirement-check">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>Proses 1-3 hari kerja</div>
                        </li>
                    </ul>
                </div>

                @if($warung->bank_name && $currentBalance >= 50000)
                <form action="{{ route('penjual.payouts.request') }}" method="POST" id="payoutForm">
                    @csrf
                    
                    <div class="amount-input-group">
                        <label for="amount" class="professional-label">Jumlah Pencairan</label>
                        <input type="number" 
                               id="amount" 
                               name="amount" 
                               class="amount-input professional-focus-visible" 
                               placeholder="0"
                               min="50000" 
                               max="{{ $currentBalance }}"
                               required>
                        <span class="currency-symbol">Rp</span>
                    </div>

                    <div class="amount-suggestions">
                        <button type="button" class="amount-chip" onclick="setAmount(50000)">Rp 50.000</button>
                        <button type="button" class="amount-chip" onclick="setAmount(100000)">Rp 100.000</button>
                        <button type="button" class="amount-chip" onclick="setAmount(250000)">Rp 250.000</button>
                        <button type="button" class="amount-chip" onclick="setAmount(500000)">Rp 500.000</button>
                        <button type="button" class="amount-chip" onclick="setAmount({{ $currentBalance }})">Semua Saldo</button>
                    </div>

                    <div class="professional-form-group">
                        <label for="notes" class="professional-label">Catatan (Opsional)</label>
                        <textarea id="notes" name="notes" class="professional-input" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-professional-primary w-100">
                        <i class="fas fa-paper-plane me-2"></i>Ajukan Pencairan
                    </button>
                </form>
                @else
                <div class="professional-alert professional-alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        @if(!$warung->bank_name)
                            Silakan lengkapi informasi rekening bank terlebih dahulu.
                            <a href="{{ route('penjual.warung.edit') }}" class="fw-bold text-decoration-none">Lengkapi Sekarang</a>
                        @elseif($currentBalance < 50000)
                            Saldo minimum untuk pencairan adalah Rp 50.000. Saldo Anda saat ini: Rp {{ number_format($currentBalance, 0, ',', '.') }}
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Professional Payout History -->
            <div class="payout-history professional-slide-up">
                <div class="history-header">
                    <h3 class="history-title">Riwayat Pencairan</h3>
                    <a href="#" class="btn btn-professional-outline btn-sm">
                        <i class="fas fa-download me-2"></i>Export
                    </a>
                </div>

                @if($payouts->count() > 0)
                    @foreach($payouts as $payout)
                    <div class="payout-item">
                        <div class="payout-details">
                            <div class="payout-amount">Rp {{ number_format($payout->amount, 0, ',', '.') }}</div>
                            <div class="payout-date">
                                <i class="fas fa-calendar me-1"></i>{{ $payout->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="payout-id">ID: {{ $payout->id }}</div>
                        </div>
                        <div class="payout-status status-{{ $payout->status }}">
                            @switch($payout->status)
                                @case('pending')
                                    <i class="fas fa-clock me-1"></i>Pending
                                    @break
                                @case('processing')
                                    <i class="fas fa-spinner me-1"></i>Diproses
                                    @break
                                @case('completed')
                                    <i class="fas fa-check me-1"></i>Berhasil
                                    @break
                                @case('rejected')
                                    <i class="fas fa-times me-1"></i>Ditolak
                                    @break
                                @default
                                    {{ ucfirst($payout->status) }}
                            @endswitch
                        </div>
                    </div>
                    @endforeach

                    <!-- Professional Pagination -->
                    <div class="d-flex justify-content-center p-3">
                        {{ $payouts->links() }}
                    </div>
                @else
                    <div class="empty-history">
                        <div class="empty-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="fw-bold mb-2">Belum Ada Riwayat Pencairan</div>
                        <div>Riwayat pencairan dana akan muncul di sini</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Professional Loading Overlay -->
<div id="loadingOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5); z-index: 9999;">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="text-center text-white">
            <div class="professional-loading mb-3"></div>
            <div>Memproses pencairan...</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const payoutForm = document.getElementById('payoutForm');
    
    // Professional amount formatting
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            // Remove non-numeric characters
            let value = this.value.replace(/[^\d]/g, '');
            
            // Format with thousands separator
            if (value) {
                this.value = parseInt(value).toLocaleString('id-ID');
            }
            
            // Update chip active states
            updateChipStates(parseInt(value) || 0);
        });
        
        amountInput.addEventListener('blur', function() {
            // Remove formatting for form submission
            this.value = this.value.replace(/[^\d]/g, '');
        });
        
        amountInput.addEventListener('focus', function() {
            // Remove formatting for editing
            this.value = this.value.replace(/[^\d]/g, '');
        });
    }
    
    // Professional form submission
    if (payoutForm) {
        payoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            
            // Show loading overlay
            document.getElementById('loadingOverlay').classList.remove('d-none');
            
            // Submit form after delay
            setTimeout(() => {
                this.submit();
            }, 1000);
        });
    }
    
    // Professional amount validation
    function validateAmount(amount) {
        const maxAmount = {{ $currentBalance }};
        const minAmount = 50000;
        
        if (amount < minAmount) {
            showAlert('Jumlah minimum pencairan adalah Rp ' + minAmount.toLocaleString('id-ID'), 'warning');
            return false;
        }
        
        if (amount > maxAmount) {
            showAlert('Jumlah tidak boleh melebihi saldo tersedia', 'danger');
            return false;
        }
        
        return true;
    }
    
    function updateChipStates(currentAmount) {
        document.querySelectorAll('.amount-chip').forEach(chip => {
            const chipAmount = chip.textContent.replace(/[^\d]/g, '');
            if (parseInt(chipAmount) === currentAmount) {
                chip.classList.add('active');
            } else {
                chip.classList.remove('active');
            }
        });
    }
    
    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `professional-alert professional-alert-${type} position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 10000; max-width: 400px;';
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : (type === 'danger' ? 'exclamation-circle' : 'info-circle')}"></i>
            <div>${message}</div>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            if (alertDiv.parentElement) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    // Make setAmount function available globally
    window.setAmount = function(amount) {
        if (amountInput) {
            amountInput.value = amount;
            updateChipStates(amount);
            
            // Validate amount
            if (!validateAmount(amount)) {
                amountInput.value = '';
                updateChipStates(0);
            }
        }
    };
    
    // Professional auto-refresh for payout status
    if (window.Echo) {
        window.Echo.private(`warung.{{ $warung->id }}`)
            .listen('PayoutStatusUpdated', (e) => {
                showAlert('Status pencairan #' + e.payout.id + ' telah diperbarui', 'success');
                
                // Auto-refresh after 2 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            });
    }
    
    // Professional keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+Enter - Submit form
        if (e.ctrlKey && e.key === 'Enter' && payoutForm) {
            e.preventDefault();
            payoutForm.dispatchEvent(new Event('submit'));
        }
        
        // Escape - Clear form
        if (e.key === 'Escape' && amountInput) {
            amountInput.value = '';
            updateChipStates(0);
        }
    });
});
</script>
@endsection
