@extends('layouts.pembeli')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
            <span class="text-2xl mr-2">📦</span> Riwayat Pesanan
        </h2>
        
        @forelse($orders as $order)
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-4 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Pesanan #{{ $order->id }}</h3>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-4 py-2 rounded-full {{ $order->getStatusColorClass() }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        @if($order->canBeCancelled())
                            <form action="{{ route('order.cancel', $order) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-100 text-red-800 rounded-full hover:bg-red-200 transition-colors"
                                        onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                            <div class="flex items-center">
                                <span class="text-lg mr-2">🍽️</span>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $item->menu->nama_menu }}</p>
                                    <p class="text-sm text-gray-500">{{ $item->jumlah }}x @ Rp{{ number_format($item->menu->harga) }}</p>
                                </div>
                            </div>
                            <p class="font-medium text-gray-800">Rp{{ number_format($item->subtotal) }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-800">Total Pembayaran</span>
                        <span class="text-lg font-bold text-primary">Rp{{ number_format($order->total_harga) }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <div class="text-4xl mb-4">🍽️</div>
                <h3 class="text-xl font-medium text-gray-600 mb-2">Belum Ada Pesanan</h3>
                <p class="text-gray-500 mb-4">Anda belum memiliki riwayat pesanan</p>
                <a href="{{ route('menu.index') }}" class="btn-primary inline-block">
                    Mulai Pesan
                </a>
            </div>
        @endforelse

        @if($orders->hasPages())
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection