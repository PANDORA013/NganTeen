@extends('layouts.app')

@section('title', 'Pembayaran Order #' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pembayaran Order</h1>
                    <p class="text-gray-600">Order #{{ $order->order_number }}</p>
                </div>
                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                    Menunggu Pembayaran
                </span>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Payment Instructions -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Petunjuk Pembayaran</h3>
                
                <!-- Step-by-step -->
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                        <div>
                            <p class="font-medium text-gray-900">Scan QRIS Code</p>
                            <p class="text-sm text-gray-600">Gunakan aplikasi mobile banking atau e-wallet untuk scan QRIS di sebelah kanan</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium">2</div>
                        <div>
                            <p class="font-medium text-gray-900">Bayar Sesuai Nominal</p>
                            <p class="text-sm text-gray-600">Pastikan nominal pembayaran sesuai dengan total: <span class="font-semibold text-green-600">Rp {{ number_format($order->gross_amount) }}</span></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium">3</div>
                        <div>
                            <p class="font-medium text-gray-900">Upload Bukti Pembayaran</p>
                            <p class="text-sm text-gray-600">Screenshot bukti transfer dan upload di form bawah ini</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form action="{{ route('global.payment.confirm', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="mt-6">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                Bukti Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG. Maksimal 2MB</p>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition duration-200">
                            Konfirmasi Pembayaran
                        </button>
                    </div>
                </form>
                
                <!-- Alternative Payment -->
                @if($paymentSetting->isBankTransferAvailable())
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">Alternatif: Transfer Bank</h4>
                    <div class="text-sm space-y-1">
                        <p><span class="font-medium">Bank:</span> {{ $paymentSetting->bank_name }}</p>
                        <p><span class="font-medium">Rekening:</span> {{ $paymentSetting->bank_account_number }}</p>
                        <p><span class="font-medium">Atas Nama:</span> {{ $paymentSetting->bank_account_name }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- QRIS Code & Order Summary -->
            <div class="space-y-6">
                <!-- QRIS Code -->
                @if($paymentSetting->isQrisAvailable())
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">QRIS {{ $paymentSetting->merchant_name }}</h3>
                    
                    <div class="mb-4">
                        <img src="{{ $paymentSetting->qris_image_url }}" 
                             alt="QRIS {{ $paymentSetting->merchant_name }}"
                             class="mx-auto max-w-64 w-full border border-gray-200 rounded-lg">
                    </div>
                    
                    <div class="bg-green-50 p-3 rounded-lg">
                        <p class="text-lg font-bold text-green-700">Rp {{ number_format($order->gross_amount) }}</p>
                        <p class="text-sm text-green-600">Total yang harus dibayar</p>
                    </div>
                </div>
                @else
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500">QRIS belum tersedia</p>
                    <p class="text-sm text-gray-400">Gunakan transfer bank di bawah</p>
                </div>
                @endif

                <!-- Order Summary -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Order</h3>
                    
                    <div class="space-y-3">
                        @foreach($warungGroups as $warungId => $items)
                            @php $warung = $items->first()->warung; @endphp
                            <div class="border-b border-gray-100 pb-3">
                                <h4 class="font-medium text-gray-800">{{ $warung->nama_warung }}</h4>
                                <div class="mt-2 space-y-1">
                                    @foreach($items as $item)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ $item->quantity }}x {{ $item->menu_name }}</span>
                                        <span class="text-gray-900">Rp {{ number_format($item->subtotal) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Totals -->
                        <div class="pt-3 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal Menu</span>
                                <span class="text-gray-900">Rp {{ number_format($order->total_amount) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Platform Fee</span>
                                <span class="text-gray-900">Rp {{ number_format($order->platform_fee) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payment Fee</span>
                                <span class="text-gray-900">Rp {{ number_format($order->payment_fee) }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2">
                                <div class="flex justify-between font-semibold">
                                    <span class="text-gray-900">Total Bayar</span>
                                    <span class="text-green-600">Rp {{ number_format($order->gross_amount) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timer -->
                <div class="bg-orange-50 border border-orange-200 p-4 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-orange-800">Batas Waktu Pembayaran</p>
                            <p class="text-xs text-orange-600" id="countdown">24 jam dari sekarang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto refresh payment status (check every 30 seconds)
let checkInterval = setInterval(function() {
    fetch(`/payment/status/{{ $order->order_number }}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'paid') {
                clearInterval(checkInterval);
                window.location.href = '/pembeli/orders';
            }
        })
        .catch(error => console.log('Payment check error:', error));
}, 30000);

// Payment form handling
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Memproses...';
});
</script>
@endsection
