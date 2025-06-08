@extends('layouts.app')

@section('content')
<div class="container max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <span class="text-2xl mr-2">🛒</span> Keranjang Belanja
        </h2>

        @if($keranjang->count() > 0)
            <div class="space-y-4">
                @foreach($keranjang as $item)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                        <div class="flex items-center flex-1">
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-800">{{ $item->menu->nama_menu }}</h3>
                                <p class="text-sm text-gray-600">{{ $item->menu->nama_warung }} - {{ $item->menu->area_kampus }}</p>
                                <p class="text-orange-500 font-medium">Rp {{ number_format($item->menu->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button type="button" onclick="decrementQuantity(this)" 
                                            class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-l-lg">
                                        -
                                    </button>
                                    <input type="number" name="jumlah" value="{{ $item->jumlah }}" min="1" max="{{ $item->menu->stok }}"
                                           class="w-16 text-center border-x border-gray-300 py-1"
                                           onchange="this.form.submit()">
                                    <button type="button" onclick="incrementQuantity(this)"
                                            class="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-r-lg">
                                        +
                                    </button>
                                </div>
                            </form>

                            <div class="text-right min-w-[100px]">
                                <p class="font-semibold text-gray-800">
                                    Rp {{ number_format($item->menu->harga * $item->jumlah, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Stok: {{ $item->menu->stok }}
                                </p>
                            </div>

                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700"
                                        onclick="return confirm('Yakin ingin menghapus item ini?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <div class="mt-6 border-t border-gray-200 pt-6">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-lg font-semibold text-gray-800">Total:</span>
                        <span class="text-2xl font-bold text-orange-500">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>

                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-orange-500 text-white py-3 px-6 rounded-lg font-semibold hover:bg-orange-600 transition-colors">
                            Checkout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-5xl mb-4">🛒</div>
                <h3 class="text-xl font-medium text-gray-600 mb-2">Keranjang Kosong</h3>
                <p class="text-gray-500 mb-6">Belum ada item yang ditambahkan ke keranjang</p>                <a href="{{ route('pembeli.menu.index') }}" class="inline-block bg-orange-500 text-white py-2 px-6 rounded-lg font-semibold hover:bg-orange-600 transition-colors">
                    Lihat Menu
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function incrementQuantity(button) {
    const input = button.parentNode.querySelector('input');
    const max = parseInt(input.getAttribute('max'));
    const currentValue = parseInt(input.value);
    if (currentValue < max) {
        input.value = currentValue + 1;
        input.form.submit();
    }
}

function decrementQuantity(button) {
    const input = button.parentNode.querySelector('input');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
        input.form.submit();
    }
}
</script>
@endpush
@endsection