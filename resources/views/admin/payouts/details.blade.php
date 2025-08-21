<div class="row">
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Payout Information</h6>
        <table class="table table-borderless">
            <tr>
                <td class="font-weight-bold">Payout Number:</td>
                <td>{{ $payout->payout_number }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Amount:</td>
                <td class="text-success font-weight-bold">Rp {{ number_format($payout->amount) }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Status:</td>
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
            </tr>
            <tr>
                <td class="font-weight-bold">Method:</td>
                <td>{{ ucfirst($payout->method ?? 'Bank Transfer') }}</td>
            </tr>
            @if($payout->reference_number)
            <tr>
                <td class="font-weight-bold">Reference Number:</td>
                <td><code>{{ $payout->reference_number }}</code></td>
            </tr>
            @endif
        </table>
    </div>
    
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Warung & Owner Details</h6>
        <table class="table table-borderless">
            <tr>
                <td class="font-weight-bold">Warung:</td>
                <td>{{ $payout->warung->nama_warung }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Owner:</td>
                <td>{{ $payout->warung->owner->name }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Email:</td>
                <td>{{ $payout->warung->owner->email }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Phone:</td>
                <td>{{ $payout->warung->owner->phone ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>
</div>

@if($payout->bank_name || $payout->account_number || $payout->account_name)
<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-primary mb-3">Bank Account Information</h6>
        <table class="table table-borderless">
            @if($payout->bank_name)
            <tr>
                <td class="font-weight-bold" style="width: 150px;">Bank Name:</td>
                <td>{{ $payout->bank_name }}</td>
            </tr>
            @endif
            @if($payout->account_number)
            <tr>
                <td class="font-weight-bold">Account Number:</td>
                <td><code>{{ $payout->account_number }}</code></td>
            </tr>
            @endif
            @if($payout->account_name)
            <tr>
                <td class="font-weight-bold">Account Name:</td>
                <td>{{ $payout->account_name }}</td>
            </tr>
            @endif
        </table>
    </div>
</div>
@endif

<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-primary mb-3">Timeline</h6>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-marker bg-primary"></div>
                <div class="timeline-content">
                    <h6 class="mb-1">Payout Created</h6>
                    <p class="text-muted mb-0">{{ $payout->created_at->format('M j, Y H:i') }}</p>
                </div>
            </div>
            
            @if($payout->processed_at)
            <div class="timeline-item">
                <div class="timeline-marker bg-info"></div>
                <div class="timeline-content">
                    <h6 class="mb-1">Processing Started</h6>
                    <p class="text-muted mb-0">{{ $payout->processed_at->format('M j, Y H:i') }}</p>
                </div>
            </div>
            @endif
            
            @if($payout->completed_at)
            <div class="timeline-item">
                <div class="timeline-marker bg-success"></div>
                <div class="timeline-content">
                    <h6 class="mb-1">Payout Completed</h6>
                    <p class="text-muted mb-0">{{ $payout->completed_at->format('M j, Y H:i') }}</p>
                </div>
            </div>
            @endif
            
            @if($payout->failed_at)
            <div class="timeline-item">
                <div class="timeline-marker bg-danger"></div>
                <div class="timeline-content">
                    <h6 class="mb-1">Payout Failed</h6>
                    <p class="text-muted mb-0">{{ $payout->failed_at->format('M j, Y H:i') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if($payout->notes)
<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-primary mb-3">Notes</h6>
        <div class="bg-light p-3 rounded">
            {{ $payout->notes }}
        </div>
    </div>
</div>
@endif

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 0;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid white;
}

.timeline-content h6 {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 4px;
}

.timeline-content p {
    font-size: 12px;
}
</style>
