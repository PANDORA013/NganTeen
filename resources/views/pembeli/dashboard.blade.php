@extends('layouts.app')

@section('content')
<div class="container max-w-6xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <span class="text-3xl mr-3">🛒</span>
                <h2 class="text-2xl font-bold text-gray-800">Keranjang Saya</h2>
            </div>
            <p class="text-gray-600 mb-6">Lihat dan kelola item di keranjang belanja Anda</p>
            <a href="{{ route('cart.index') }}" 
               class="inline-block bg-orange-500 text-white py-2 px-6 rounded-lg font-semibold hover:bg-orange-600 transition-colors">
                Lihat Keranjang
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <span class="text-3xl mr-3">📝</span>
                <h2 class="text-2xl font-bold text-gray-800">Riwayat Pesanan</h2>
            </div>
            <p class="text-gray-600 mb-6">Lihat status dan riwayat pesanan Anda</p>            <a href="{{ route('pembeli.orders.index') }}" class="inline-block bg-orange-500 text-white py-2 px-6 rounded-lg font-semibold hover:bg-orange-600 transition-colors">
                Lihat Pesanan
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center mb-6">
            <span class="text-3xl mr-3">🍽️</span>
            <h2 class="text-2xl font-bold text-gray-800">Menu Tersedia</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($menus as $menu)
                <div class="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $menu->nama_menu }}</h3>
                        <div class="text-sm text-gray-600 mb-4">
                            <p class="mb-1">Warung: {{ $menu->nama_warung }}</p>
                            <p class="mb-1">Area: {{ $menu->area_kampus }}</p>
                            <p class="mb-1">Stok: {{ $menu->stok }}</p>
                            <p class="font-semibold text-orange-500">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                        </div>
                        @if($menu->stok > 0)
                            <form action="{{ route('cart.store') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                <input type="number" name="jumlah" value="1" min="1" max="{{ $menu->stok }}"
                                       class="w-20 px-2 py-1 border rounded-lg focus:ring-2 focus:ring-orange-500">
                                <button type="submit" 
                                        class="flex-grow bg-orange-500 text-white py-1 px-4 rounded-lg hover:bg-orange-600 transition-colors">
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full bg-gray-300 text-gray-500 py-1 px-4 rounded-lg cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection