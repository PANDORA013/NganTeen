@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Daftar Menu</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($menus as $menu)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($menu->image)
                <img src="{{ asset('storage/'.$menu->image) }}" alt="{{ $menu->nama_menu }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500">No image</span>
                </div>
            @endif
            <div class="p-4">
                <h3 class="font-bold text-xl mb-2">{{ $menu->nama_menu }}</h3>
                <p class="text-gray-600 mb-2">{{ Str::limit($menu->description ?? 'Tidak ada deskripsi', 100) }}</p>
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-orange-500 font-bold">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                        <p class="text-sm text-gray-500">Stok: {{ $menu->stok }}</p>
                        <p class="text-sm text-gray-500">{{ $menu->nama_warung }} - {{ $menu->area_kampus }}</p>
                    </div>
                    <a href="{{ route('menu.show', $menu) }}" class="bg-orange-500 text-white px-4 py-2 rounded-full hover:bg-orange-600 transition duration-300">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection