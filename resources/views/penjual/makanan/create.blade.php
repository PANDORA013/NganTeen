@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Tambah Makanan</h2>
    
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('penjual.makanan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium">Nama Makanan</label>
            <input type="text" name="name" value="{{ old('name') }}" 
                class="w-full border p-2 rounded @error('name') border-red-500 @enderror" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Keterangan Singkat</label>
            <textarea name="description" class="w-full border p-2 rounded @error('description') border-red-500 @enderror" 
                rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price') }}" 
                class="w-full border p-2 rounded @error('price') border-red-500 @enderror" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Estimasi Waktu (menit)</label>
            <input type="number" name="estimated_time" value="{{ old('estimated_time') }}" 
                class="w-full border p-2 rounded @error('estimated_time') border-red-500 @enderror" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Lokasi</label>
            <select name="location" class="w-full border p-2 rounded @error('location') border-red-500 @enderror" required>
                @foreach($locations as $location)
                    <option value="{{ $location }}" {{ old('location') == $location ? 'selected' : '' }}>
                        {{ $location }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Status</label>
            <select name="status" class="w-full border p-2 rounded @error('status') border-red-500 @enderror">
                <option value="Ready Stok" {{ old('status') == 'Ready Stok' ? 'selected' : '' }}>Ready Stok</option>
                <option value="Stok Empty" {{ old('status') == 'Stok Empty' ? 'selected' : '' }}>Stok Empty</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Foto Makanan</label>
            <input type="file" name="photo" accept="image/*" 
                class="w-full border p-2 rounded @error('photo') border-red-500 @enderror" required>
        </div>

        <div class="flex justify-between">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Simpan
            </button>
            <a href="{{ route('penjual.makanan.index') }}" 
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection