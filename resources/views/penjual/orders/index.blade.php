@extends('layouts.app')

@section('content')
<div class="container max-w-6xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <span class="text-2xl mr-2">📋</span> Kelola Pesanan
        </h2>

        @if($orders->isEmpty())
            <div class="text-center py-12">
                <div class="text-5xl mb-4">📭</div>
                <h3 class="text-xl font-medium text-gray-600 mb-2">Belum Ada Pesanan</h3>
                <p class="text-gray-500">Belum ada pesanan yang masuk untuk menu Anda</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">
                                    Pesanan #{{ $order->id }} - {{ $order->user->name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="px-4 py-2 rounded-full {{ $order->getStatusColorClass() }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                @if($order->status === 'pending')
                                    <form action="{{ route('penjual.orders.update-status', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="processing">
                                        <button type="submit" class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors">
                                            Proses Pesanan
                                        </button>
                                    </form>
                                @elseif($order->status === 'processing')
                                    <form action="{{ route('penjual.orders.update-status', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="px-4 py-2 bg-green-100 text-green-800 rounded-full hover:bg-green-200 transition-colors">
                                            Selesaikan
                                        </button>
                                    </form>
                                @endif

                                @if(in_array($order->status, ['pending', 'processing']))
                                    <form action="{{ route('penjual.orders.update-status', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" 
                                                class="px-4 py-2 bg-red-100 text-red-800 rounded-full hover:bg-red-200 transition-colors"
                                                onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            <h4 class="font-medium text-gray-700 mb-2">Item Pesanan:</h4>
                            <div class="space-y-2">
                                @foreach($order->items as $item)
                                    @if($item->menu->user_id === Auth::id())
                                        <div class="flex justify-between items-center py-2 px-4 bg-gray-50 rounded-lg">
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $item->menu->nama_menu }}</p>
                                                <p class="text-sm text-gray-500">{{ $item->jumlah }}x @ Rp{{ number_format($item->menu->harga) }}</p>
                                            </div>
                                            <p class="font-medium text-gray-800">Rp{{ number_format($item->subtotal) }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection