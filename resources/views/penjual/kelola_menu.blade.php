@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
            <span class="text-3xl mr-3">🍽️</span>
            Kelola Menu
        </h2>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <ul class="list-disc list-inside text-sm text-red-800">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif    <!-- Form Tambah Menu -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="flex items-center mb-6">
            <span class="text-2xl mr-3">📝</span>
            <h3 class="text-xl font-semibold text-gray-800">Tambah Menu Baru</h3>
        </div>

        <form action="{{ route('penjual.menu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama_menu" class="block text-sm font-medium text-gray-700">Nama Menu</label>
                    <input type="text" name="nama_menu" id="nama_menu" required 
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <div class="mt-1 relative rounded-lg">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="harga" id="harga" required min="0"
                               class="pl-12 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>

                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" name="stok" id="stok" required min="0"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="nama_warung" class="block text-sm font-medium text-gray-700">Nama Warung</label>
                    <input type="text" name="nama_warung" id="nama_warung" required
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="area_kampus" class="block text-sm font-medium text-gray-700">Area Kampus</label>
                    <select name="area_kampus" id="area_kampus" required 
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Pilih Area</option>
                        <option value="Kampus A">Kampus A</option>
                        <option value="Kampus B">Kampus B</option>
                        <option value="Kampus C">Kampus C</option>
                    </select>
                </div>

                <div>
                    <label for="gambar" class="block text-sm font-medium text-gray-700">Foto Menu</label>
                    <input type="file" name="gambar" id="gambar" accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-orange-50 file:text-orange-700
                                  hover:file:bg-orange-100
                                  focus:outline-none">
                    <p class="mt-1 text-sm text-gray-500">Format yang diizinkan: JPEG, PNG, JPG. Ukuran maksimal: 2MB</p>
                </div>
            </div>            <div class="flex justify-end mt-8">
                <button type="submit" 
                        class="px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Upload Menu
                </button>
            </div>
        </form>
    </div>    <!-- Daftar Menu -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <span class="text-2xl mr-3">📋</span>
                <h3 class="text-xl font-semibold text-gray-800">Daftar Menu</h3>
            </div>
            <span class="text-sm text-gray-500">{{ $menus->count() }} menu</span>
        </div>

        @if($menus->isEmpty())
            <div class="text-center py-12">
                <div class="text-5xl mb-4">🍽️</div>
                <h3 class="text-xl font-medium text-gray-600 mb-2">Belum Ada Menu</h3>
                <p class="text-gray-500">Mulai tambahkan menu warung Anda menggunakan form di atas</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($menus as $menu)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="aspect-w-16 aspect-h-9 rounded-t-xl overflow-hidden">
                            @if($menu->gambar)
                                <img src="{{ asset('storage/' . $menu->gambar) }}" 
                                     alt="{{ $menu->nama_menu }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $menu->nama_menu }}</h4>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between items-baseline">
                                    <span class="text-sm text-gray-600">Harga</span>
                                    <span class="font-semibold text-orange-600">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Stok</span>
                                    <span class="font-medium {{ $menu->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $menu->stok }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Area</span>
                                    <span class="text-gray-800">{{ $menu->area_kampus }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Warung</span>
                                    <span class="text-gray-800">{{ $menu->nama_warung }}</span>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-2 pt-4 border-t">
                                <button type="button" 
                                        class="px-3 py-1.5 text-sm font-medium text-orange-700 bg-orange-50 rounded-lg hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                                        data-bs-toggle="modal" data-bs-target="#editMenu{{ $menu->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('penjual.menu.destroy', $menu) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Yakin ingin menghapus menu ini?')"
                                            class="px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

<!-- Modal Edit Menu -->
@foreach($menus as $menu)
<div class="modal fade" id="editMenu{{ $menu->id }}" tabindex="-1" aria-labelledby="editMenuLabel{{ $menu->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white rounded-xl shadow-lg">
            <div class="modal-header p-8 border-b">
                <div>
                    <h5 class="text-2xl font-semibold text-gray-800 mb-1">Edit Menu</h5>
                    <p class="text-sm text-gray-500">Perbarui informasi menu makanan Anda</p>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('penjual.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')                <div class="modal-body p-8">
                    <div class="space-y-6">
                        @if($menu->gambar)
                        <div>
                            <div class="relative rounded-xl overflow-hidden shadow-sm">
                                <img src="{{ asset('storage/' . $menu->gambar) }}" 
                                     alt="{{ $menu->nama_menu }}" 
                                     class="w-full h-56 object-cover">
                            </div>
                        </div>
                        @endif
                          <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Menu</label>
                            <input type="file" name="gambar" accept="image/*"
                                   class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-gray-50 file:text-gray-700
                                          hover:file:bg-gray-100
                                          cursor-pointer focus:outline-none">
                            <p class="mt-2 text-sm text-gray-500">Format: JPG, PNG. Maksimal ukuran 2MB.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Menu</label>
                            <input type="text" name="nama_menu" value="{{ $menu->nama_menu }}" required
                                   class="w-full rounded-md border-gray-300 px-4 py-2 focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="Masukkan nama menu">
                        </div>                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="harga" value="{{ $menu->harga }}" required min="0"
                                       class="pl-12 w-full rounded-md border-gray-300 px-4 py-2 focus:ring-2 focus:ring-black focus:border-black"
                                       placeholder="Masukkan harga menu">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                            <input type="number" name="stok" value="{{ $menu->stok }}" required min="0"
                                   class="w-full rounded-md border-gray-300 px-4 py-2 focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="Masukkan jumlah stok">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Area Kampus</label>
                            <select name="area_kampus" required 
                                    class="w-full rounded-md border-gray-300 px-4 py-2 focus:ring-2 focus:ring-black focus:border-black">
                                <option value="">Pilih area kampus</option>
                                <option value="Kampus A" {{ $menu->area_kampus == 'Kampus A' ? 'selected' : '' }}>Kampus A</option>
                                <option value="Kampus B" {{ $menu->area_kampus == 'Kampus B' ? 'selected' : '' }}>Kampus B</option>
                                <option value="Kampus C" {{ $menu->area_kampus == 'Kampus C' ? 'selected' : '' }}>Kampus C</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Warung</label>
                            <input type="text" name="nama_warung" value="{{ $menu->nama_warung }}" required
                                   class="w-full rounded-md border-gray-300 px-4 py-2 focus:ring-2 focus:ring-black focus:border-black"
                                   placeholder="Masukkan nama warung">
                        </div>
                    </div>
                </div>                <div class="modal-footer px-8 py-6 flex justify-end space-x-4 border-t bg-gray-50">
                    <button type="button" 
                            class="px-6 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition-colors duration-200" 
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-black text-white text-sm font-medium rounded-md hover:bg-gray-800 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach