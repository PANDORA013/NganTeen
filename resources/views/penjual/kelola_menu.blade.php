@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-orange-100 rounded-lg">
                <span class="text-2xl">🍽️</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Kelola Menu</h2>
                <p class="text-sm text-gray-600">Tambah, edit, dan kelola menu warung Anda</p>
            </div>
        </div>
        <div class="text-sm text-gray-500">
            Total Menu: <span class="font-semibold text-gray-700">{{ $menus->count() }}</span>
        </div>
    </div>

    <!-- Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg animate-fade-in">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg animate-fade-in">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-red-800 mb-2">Terdapat beberapa kesalahan:</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Tambah Menu -->
    <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
        <div class="flex items-center space-x-4 mb-8">
            <div class="p-3 bg-orange-50 rounded-full">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-semibold text-gray-800">Tambah Menu Baru</h3>
                <p class="text-sm text-gray-600">Isi informasi menu yang akan ditambahkan</p>
            </div>
        </div>

        <form action="{{ route('penjual.menu.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label for="nama_menu" class="block text-sm font-medium text-gray-700 mb-1">Nama Menu</label>
                        <input type="text" name="nama_menu" id="nama_menu" value="{{ old('nama_menu') }}" required 
                               placeholder="Masukkan nama menu"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm 
                                      focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                      placeholder:text-gray-400">
                    </div>

                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required min="0"
                                   placeholder="0"
                                   class="pl-12 block w-full rounded-lg border-gray-300 shadow-sm
                                          focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                          placeholder:text-gray-400">
                        </div>
                    </div>

                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" name="stok" id="stok" value="{{ old('stok') }}" required min="0"
                               placeholder="Masukkan jumlah stok"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                                      focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                      placeholder:text-gray-400">
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="nama_warung" class="block text-sm font-medium text-gray-700 mb-1">Nama Warung</label>
                        <input type="text" name="nama_warung" id="nama_warung" value="{{ old('nama_warung') }}" required
                               placeholder="Masukkan nama warung"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                                      focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                      placeholder:text-gray-400">
                    </div>

                    <div>
                        <label for="area_kampus" class="block text-sm font-medium text-gray-700 mb-1">Area Kampus</label>
                        <select name="area_kampus" id="area_kampus" required 
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                                       focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Pilih Area Kampus</option>
                            <option value="Kampus A" {{ old('area_kampus') == 'Kampus A' ? 'selected' : '' }}>Kampus A</option>
                            <option value="Kampus B" {{ old('area_kampus') == 'Kampus B' ? 'selected' : '' }}>Kampus B</option>
                            <option value="Kampus C" {{ old('area_kampus') == 'Kampus C' ? 'selected' : '' }}>Kampus C</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Menu</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-orange-500 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                                          stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="gambar" class="relative cursor-pointer rounded-md font-medium text-orange-600 hover:text-orange-500">
                                        <span>Upload foto</span>
                                        <input id="gambar" name="gambar" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t">
                <button type="reset" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Reset
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500
                               transition duration-150 ease-in-out">
                    Tambah Menu
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Menu -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-orange-50 rounded-full">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">Daftar Menu</h3>
                    <p class="text-sm text-gray-600">Kelola menu yang sudah ditambahkan</p>
                </div>
            </div>
        </div>

        @if($menus->isEmpty())
            <div class="text-center py-12">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum Ada Menu</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai tambahkan menu warung Anda menggunakan form di atas</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($menus as $menu)
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition duration-200">
                        <div class="aspect-w-16 aspect-h-9">
                            @if($menu->gambar)
                                <img src="{{ asset('storage/' . $menu->gambar) }}" 
                                     alt="{{ $menu->nama_menu }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="text-lg font-semibold text-gray-800">{{ $menu->nama_menu }}</h4>
                                <span class="rounded-full px-2 py-1 text-xs font-medium {{ $menu->stok > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $menu->stok > 0 ? 'Tersedia' : 'Habis' }}
                                </span>
                            </div>
                            
                            <dl class="grid grid-cols-2 gap-4 text-sm mb-6">
                                <div>
                                    <dt class="font-medium text-gray-500">Harga</dt>
                                    <dd class="mt-1 text-gray-900">Rp {{ number_format($menu->harga, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Stok</dt>
                                    <dd class="mt-1 text-gray-900">{{ $menu->stok }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Area</dt>
                                    <dd class="mt-1 text-gray-900">{{ $menu->area_kampus }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Warung</dt>
                                    <dd class="mt-1 text-gray-900">{{ $menu->nama_warung }}</dd>
                                </div>
                            </dl>
                            
                            <div class="flex justify-end space-x-3 pt-4 border-t">
                                <button type="button" 
                                        class="px-3 py-1.5 text-sm font-medium text-orange-700 bg-orange-50 rounded-lg hover:bg-orange-100 
                                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors"
                                        data-bs-toggle="modal" data-bs-target="#editMenu{{ $menu->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('penjual.menu.destroy', $menu) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Yakin ingin menghapus menu ini?')"
                                            class="px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 
                                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
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

<!-- Modal Edit Menu -->
@foreach($menus as $menu)
<div class="modal fade" id="editMenu{{ $menu->id }}" tabindex="-1" aria-labelledby="editMenuLabel{{ $menu->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white rounded-xl shadow-lg">
            <div class="modal-header p-6 border-b">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-orange-50 rounded-full">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h5 class="text-xl font-semibold text-gray-800">Edit Menu</h5>
                        <p class="text-sm text-gray-500">Perbarui informasi menu warung Anda</p>
                    </div>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-500 transition-colors" data-bs-dismiss="modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('penjual.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                  <div class="modal-body p-6">
                    <div class="space-y-6">
                        @if($menu->gambar)
                            <div class="mb-4">
                                <div class="relative rounded-lg overflow-hidden shadow-sm">
                                    <img src="{{ asset('storage/' . $menu->gambar) }}" 
                                         alt="{{ $menu->nama_menu }}" 
                                         class="w-full h-48 object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="button" onclick="document.getElementById('gambar-{{ $menu->id }}').click()" 
                                                class="px-4 py-2 bg-white text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">
                                            Ganti Foto
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Menu</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-orange-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="gambar-{{ $menu->id }}" class="relative cursor-pointer rounded-md font-medium text-orange-600 hover:text-orange-500">
                                            <span>Upload foto</span>
                                            <input id="gambar-{{ $menu->id }}" name="gambar" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu</label>
                                <input type="text" name="nama_menu" value="{{ $menu->nama_menu }}" required
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                                              focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="harga" value="{{ $menu->harga }}" required min="0"
                                           class="pl-12 block w-full rounded-lg border-gray-300 shadow-sm
                                                  focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                                <input type="number" name="stok" value="{{ $menu->stok }}" required min="0"
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                                              focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Area Kampus</label>
                                <select name="area_kampus" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                                               focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    <option value="">Pilih Area Kampus</option>
                                    <option value="Kampus A" {{ $menu->area_kampus == 'Kampus A' ? 'selected' : '' }}>Kampus A</option>
                                    <option value="Kampus B" {{ $menu->area_kampus == 'Kampus B' ? 'selected' : '' }}>Kampus B</option>
                                    <option value="Kampus C" {{ $menu->area_kampus == 'Kampus C' ? 'selected' : '' }}>Kampus C</option>
                                </select>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Warung</label>
                                <input type="text" name="nama_warung" value="{{ $menu->nama_warung }}" required
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                                              focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                        </div>
                    </div>
                </div>                <div class="modal-footer px-6 py-4 bg-gray-50 flex justify-between items-center rounded-b-xl">
                    <div>
                        @if($menu->gambar)
                        <button type="button" onclick="if(confirm('Hapus foto menu ini?')) document.getElementById('hapus_gambar-{{ $menu->id }}').value = '1'"
                                class="text-sm text-red-600 hover:text-red-700 font-medium">
                            Hapus Foto
                        </button>
                        <input type="hidden" id="hapus_gambar-{{ $menu->id }}" name="hapus_gambar" value="0">
                        @endif
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                                data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection