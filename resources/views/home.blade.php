@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-orange-500 to-yellow-500">
    <div class="container mx-auto px-4 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Selamat Datang di NganTeen</h1>
            <p class="text-xl text-white mb-8">Temukan makanan dan minuman favoritmu dengan harga terjangkau</p>
            <a href="{{ route('menu.index') }}" class="bg-white text-orange-500 font-bold py-3 px-8 rounded-full hover:bg-orange-100 transition duration-300">
                Lihat Menu
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold text-center mb-8">Menu Populer</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach(\App\Models\Menu::take(6)->get() as $menu)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($menu->image)
                <img src="{{ asset('storage/'.$menu->image) }}" alt="{{ $menu->name }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500">No image</span>
                </div>
            @endif
            <div class="p-4">
                <h3 class="font-bold text-xl mb-2">{{ $menu->name }}</h3>
                <p class="text-gray-600 mb-2">{{ Str::limit($menu->description, 100) }}</p>
                <div class="flex justify-between items-center">
                    <span class="text-orange-500 font-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                    <a href="{{ route('menu.show', $menu) }}" class="bg-orange-500 text-white px-4 py-2 rounded-full hover:bg-orange-600 transition duration-300">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="text-center mt-8">
        <a href="{{ route('menu.index') }}" class="text-orange-500 font-bold hover:text-orange-600 transition duration-300">
            Lihat Semua Menu →
        </a>
    </div>
</div>
@endsection
