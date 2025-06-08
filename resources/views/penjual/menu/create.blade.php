<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-6">Tambah Menu Baru</h2>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('penjual.menu.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div>
                            <x-input-label for="nama_menu" :value="__('Nama Menu')" />
                            <x-text-input id="nama_menu" name="nama_menu" type="text" class="mt-1 block w-full" 
                                :value="old('nama_menu')" required autofocus />
                        </div>

                        <div>
                            <x-input-label for="harga" :value="__('Harga')" />
                            <x-text-input id="harga" name="harga" type="number" class="mt-1 block w-full" 
                                :value="old('harga')" required min="0" />
                        </div>

                        <div>
                            <x-input-label for="stok" :value="__('Stok')" />
                            <x-text-input id="stok" name="stok" type="number" class="mt-1 block w-full" 
                                :value="old('stok')" required min="0" />
                        </div>

                        <div>
                            <x-input-label for="area_kampus" :value="__('Area Kampus')" />
                            <select id="area_kampus" name="area_kampus" required
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Pilih Area Kampus</option>
                                <option value="Kampus A" {{ old('area_kampus') == 'Kampus A' ? 'selected' : '' }}>Kampus A</option>
                                <option value="Kampus B" {{ old('area_kampus') == 'Kampus B' ? 'selected' : '' }}>Kampus B</option>
                                <option value="Kampus C" {{ old('area_kampus') == 'Kampus C' ? 'selected' : '' }}>Kampus C</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="nama_warung" :value="__('Nama Warung')" />
                            <x-text-input id="nama_warung" name="nama_warung" type="text" class="mt-1 block w-full" 
                                :value="old('nama_warung')" required />
                        </div>

                        <div>
                            <x-input-label for="gambar" :value="__('Foto Menu')" />                            <input type="file" id="gambar" name="gambar" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-medium
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100" />
                            <p class="mt-1 text-sm text-gray-500">
                                Format yang diizinkan: JPEG, PNG, JPG. Ukuran maksimal: 2MB
                            </p>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6 gap-4">
                            <button type="button" onclick="window.history.back()" 
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Batal
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Upload Menu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
