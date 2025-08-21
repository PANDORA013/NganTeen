@extends('layouts.app')

@section('title', 'Checkout Global')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
                    <p class="text-gray-600">Bayar semua pesanan dari berbagai warung sekaligus</p>
                </div>
                <a href="{{ route('cart.index') }}" 
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    ← Kembali ke Keranjang
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Pesanan</h3>
                    
                    @foreach($cartByWarung as $warungName => $items)
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $warungName }}
                            </h4>
                            
                            <div class="space-y-3">
                                @foreach($items as $item)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            @if($item->menu->gambar_menu)
                                                <img src="{{ asset('storage/' . $item->menu->gambar_menu) }}" 
                                                     alt="{{ $item->menu->nama_menu }}"
                                                     class="w-12 h-12 object-cover rounded-lg">
                                            @else
                                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->menu->nama_menu }}</p>
                                                <p class="text-sm text-gray-500">Rp {{ number_format($item->menu->harga) }} x {{ $item->jumlah }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="text-right">
                                            <p class="font-medium text-gray-900">Rp {{ number_format($item->menu->harga * $item->jumlah) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <div class="flex justify-between font-medium text-gray-900">
                                    <span>Subtotal {{ $warungName }}:</span>
                                    <span>Rp {{ number_format($items->sum(function($item) { return $item->menu->harga * $item->jumlah; })) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Payment Method -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Metode Pembayaran</h3>
                    
                    <form id="checkoutForm" method="POST" action="{{ route('global.checkout.process') }}">
                        @csrf
                        
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="payment_method" value="qris" class="mr-3" checked>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">QRIS</p>
                                            <p class="text-sm text-gray-500">Bayar dengan scan QR Code</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 opacity-50">
                                <label class="flex items-center cursor-not-allowed">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="mr-3" disabled>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">Transfer Bank</p>
                                            <p class="text-sm text-gray-500">Segera hadir</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4 opacity-50">
                                <label class="flex items-center cursor-not-allowed">
                                    <input type="radio" name="payment_method" value="ewallet" class="mr-3" disabled>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">E-Wallet</p>
                                            <p class="text-sm text-gray-500">OVO, GoPay, DANA (Segera hadir)</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Total & Checkout -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow sticky top-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Pembayaran</h3>
                    
                    <div class="space-y-3 mb-6">
                        @foreach($warungGroups as $warungId => $items)
                            @php $warung = $items->first()->warung; @endphp
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ Str::limit($warung->nama_warung, 20) }}</span>
                                <span class="text-gray-900">Rp {{ number_format($subtotalPerWarung[$warungId]) }}</span>
                            </div>
                        @endforeach
                        
                        <div class="border-t border-gray-200 pt-3 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal Menu</span>
                                <span class="text-gray-900">Rp {{ number_format($grandTotal) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Platform Fee ({{ $paymentSetting->platform_fee_percentage }}%)</span>
                                <span class="text-gray-900">Rp {{ number_format($platformFee) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payment Fee</span>
                                <span class="text-gray-900">Rp {{ number_format($paymentFee) }}</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-lg font-medium">
                                <span class="text-gray-900">Total Bayar</span>
                                <span class="text-green-600 font-bold">Rp {{ number_format($grossAmount) }}</span>
                            </div>
                        </div>
                        
                        <!-- Payment Method Info -->
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center text-sm text-blue-700">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Pembayaran menggunakan QRIS {{ $paymentSetting->merchant_name }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Checkout Button -->
                    <button type="submit" form="checkoutForm" 
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-300 font-medium">
                        Bayar Sekarang
                    </button>
                    
                    <!-- Security Info -->
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Pembayaran aman dan terenkripsi
                        </div>
                    </div>
                    
                    <!-- Order Info -->
                    <div class="mt-4 space-y-2 text-xs text-gray-500">
                        <p>• Setelah pembayaran berhasil, pesanan akan diteruskan ke masing-masing warung</p>
                        <p>• Anda akan menerima konfirmasi dan detail order melalui email</p>
                        <p>• Dana akan ditransfer ke warung setelah admin memverifikasi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-1/2 transform -translate-y-1/2 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Memproses Pembayaran</h3>
            <p class="text-sm text-gray-500 mt-2">Mohon tunggu, pesanan Anda sedang diproses...</p>
        </div>
    </div>
</div>

<script>
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading modal
    document.getElementById('loadingModal').classList.remove('hidden');
    
    // Simulate processing delay then submit
    setTimeout(() => {
        this.submit();
    }, 1500);
});

// Handle payment method selection
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Update UI based on selected payment method if needed
        console.log('Selected payment method:', this.value);
    });
});
</script>
@endsection
