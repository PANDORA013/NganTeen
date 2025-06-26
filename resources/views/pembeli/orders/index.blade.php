@extends('layouts.pembeli')

@section('content')
    <!-- Orders List -->
    @if($orders->isNotEmpty())
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Pesanan #{{ $order->id }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                            
                            <div>
                                @php
                                    $statusClass = [
                                        'selesai' => 'bg-green-100 text-green-800',
                                        'batal' => 'bg-red-100 text-red-800',
                                    ][$order->status] ?? 'bg-gray-100 text-gray-800';
                                    
                                    $statusText = [
                                        'selesai' => 'Selesai',
                                        'batal' => 'Dibatalkan',
                                    ][$order->status] ?? $order->status;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ $order->updated_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                @if($order->status === 'batal' && $order->cancellation_reason)
                                    <div class="mt-2 text-sm text-red-600">
                                        <strong>Alasan Pembatalan:</strong> {{ $order->cancellation_reason }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            <h4 class="font-medium text-gray-700 mb-2">Item Pesanan:</h4>
                            <div class="space-y-2">
                                @foreach($order->orderItems as $item)
                                    <div class="flex justify-between items-center py-2 px-4 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $item->menu->nama_menu ?? 'Menu tidak tersedia' }}</p>
                                            <p class="text-sm text-gray-500">{{ $item->quantity ?? 1 }}x @ Rp{{ number_format($item->menu->harga ?? 0) }}</p>
                                        </div>
                                        <p class="font-medium text-gray-800">Rp{{ number_format(($item->menu->harga ?? 0) * ($item->quantity ?? 1)) }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-right">
                                <p class="text-lg font-bold text-gray-800">Total: Rp{{ number_format($order->total_harga) }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Pesanan:</span>
                                <strong class="text-lg">Rp{{ number_format($order->orderItems->sum(function($item) {
                                    return ($item->menu->harga ?? 0) * ($item->quantity ?? 1);
                                })) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-8 bg-gray-50 rounded-lg p-4 border border-gray-200">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
@endsection
