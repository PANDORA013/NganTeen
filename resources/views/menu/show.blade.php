@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                @if($menu->image)
                    <img src="{{ asset('storage/'.$menu->image) }}" alt="{{ $menu->nama_menu }}" class="w-full h-96 object-cover">
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">No image</span>
                    </div>
                @endif
            </div>
            <div class="p-8 md:w-1/2">
                <div class="mb-4">
                    <h1 class="text-3xl font-bold mb-2">{{ $menu->nama_menu }}</h1>
                    <p class="text-gray-600">{{ $menu->description ?? 'Tidak ada deskripsi' }}</p>
                </div>
                
                <div class="mb-6">
                    <p class="text-2xl font-bold text-orange-500 mb-2">
                        Rp {{ number_format($menu->harga, 0, ',', '.') }}
                    </p>
                    <p class="text-gray-600">Stok: {{ $menu->stok }}</p>
                    <p class="text-gray-600">{{ $menu->nama_warung }} - {{ $menu->area_kampus }}</p>
                </div>

                @auth
                    @if(auth()->user()->role === 'pembeli' && $menu->stok > 0)
                        <form action="{{ route('pembeli.cart.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                            <div>
                                <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah</label>
                                <input type="number" name="jumlah" id="jumlah" min="1" max="{{ $menu->stok }}" value="1" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            </div>
                            <button type="submit" class="w-full bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600 transition duration-300">
                                Tambah ke Keranjang
                            </button>
                        </form>
                    @elseif($menu->stok === 0)
                        <div class="bg-gray-100 text-gray-600 px-6 py-3 rounded-full text-center">
                            Stok Habis
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block w-full bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600 transition duration-300 text-center">
                        Login untuk Memesan
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection