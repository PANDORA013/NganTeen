@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Daftar Makanan</h1>
        <a href="{{ route('penjual.makanan.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Tambah Makanan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($foods as $food)
        <div class="bg-white shadow rounded-lg p-4">
            <img src="{{ asset($food->photo_url) }}" alt="{{ $food->name }}" class="w-full h-40 object-cover rounded">
            <h2 class="text-lg font-semibold mt-2">{{ $food->name }}</h2>
            <p class="text-sm text-gray-600">{{ $food->description }}</p>
            <p class="font-bold mt-1">Rp{{ number_format($food->price, 0, ',', '.') }}</p>
            <p class="text-sm">📍 {{ $food->location }}</p>
            <p class="text-sm mt-1">⏱️ {{ $food->estimated_time }} menit</p>
            <p class="text-sm mt-1">
                Status: 
                <span class="{{ $food->status == 'Ready Stok' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $food->status }}
                </span>
            </p>
            <div class="flex justify-between mt-4">
                <a href="{{ route('penjual.makanan.edit', $food->id) }}" class="text-blue-500 hover:underline">Edit</a>
                <form action="{{ route('penjual.makanan.destroy', $food->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus makanan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $foods->links() }}
    </div>
</div>
@endsection