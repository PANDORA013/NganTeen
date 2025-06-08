<!-- Edit Menu Form -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center mb-6">
        <span class="text-2xl mr-3">✏️</span>
        <h2 class="text-xl font-semibold text-gray-800">{{ isset($menuEdit) ? 'Edit Menu' : 'Tambah Menu Baru' }}</h2>
    </div>
    
    <form action="{{ isset($menuEdit) ? route('penjual.menu.update', $menuEdit->id) : route('penjual.menu.store') }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($menuEdit))
            @method('PUT')
        @endif

        @if(isset($menuEdit) && $menuEdit->gambar)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $menuEdit->gambar) }}" 
                     class="w-full h-48 object-cover rounded-lg" 
                     alt="{{ $menuEdit->nama_menu }}">
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Menu</label>
                <input type="file" name="gambar" accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-500 
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-orange-50 file:text-orange-700
                              hover:file:bg-orange-100"
                file:rounded-full file:border-0
                file:text-sm file:font-semibold             />
                <p class="mt-1 text-sm text-gray-500">Format yang diizinkan: JPEG, PNG, JPG. Ukuran maksimal: 2MB</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Menu</label>
                <input type="text" name="nama_menu" 
                       value="{{ old('nama_menu', $menuEdit->nama_menu ?? '') }}" 
                       required
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm 
                              focus:ring-orange-500 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                <div class="relative rounded-lg shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" name="harga" 
                           value="{{ old('harga', $menuEdit->harga ?? '') }}" 
                           required min="0"
                           class="pl-12 block w-full rounded-lg border-gray-300 shadow-sm
                                  focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                <input type="number" name="stok" 
                       value="{{ old('stok', $menuEdit->stok ?? '') }}" 
                       required min="0"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                              focus:ring-orange-500 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Area Kampus</label>
                <select name="area_kampus" required 
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                               focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Pilih Area</option>
                    <option value="Kampus A" {{ (old('area_kampus', $menuEdit->area_kampus ?? '') == 'Kampus A') ? 'selected' : '' }}>
                        Kampus A
                    </option>
                    <option value="Kampus B" {{ (old('area_kampus', $menuEdit->area_kampus ?? '') == 'Kampus B') ? 'selected' : '' }}>
                        Kampus B
                    </option>
                    <option value="Kampus C" {{ (old('area_kampus', $menuEdit->area_kampus ?? '') == 'Kampus C') ? 'selected' : '' }}>
                        Kampus C
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Warung</label>
                <input type="text" name="nama_warung" 
                       value="{{ old('nama_warung', $menuEdit->nama_warung ?? '') }}" 
                       required
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                              focus:ring-orange-500 focus:border-orange-500">
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            @if(isset($menuEdit))
                <button type="button" onclick="window.history.back()"
                        class="px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 
                               hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Batal
                </button>
            @endif
            <button type="submit"
                    class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg
                           hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                {{ isset($menuEdit) ? 'Update Menu' : 'Upload Menu' }}
            </button>
        </div>
    </form>
</div>
