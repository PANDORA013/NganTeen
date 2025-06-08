

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto p-6 bg-gray-100 min-h-screen">
    <h2 class="text-2xl font-bold mb-6 text-gray-700">Kelola Menu</h2>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form Tambah Menu -->
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-600">Tambah Menu Baru</h3>
        <form action="<?php echo e(route('penjual.menu.store')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label for="nama_menu" class="block font-semibold text-gray-600">Nama Menu</label>
                <input type="text" name="nama_menu" id="nama_menu" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="harga" class="block font-semibold text-gray-600">Harga (Rp)</label>
                <input type="number" name="harga" id="harga" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="stok" class="block font-semibold text-gray-600">Stok</label>
                <input type="number" name="stok" id="stok" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="area_kampus" class="block font-semibold text-gray-600">Area Kampus</label>
                <select name="area_kampus" id="area_kampus" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Pilih Area</option>
                    <option value="Kampus A">Kampus A</option>
                    <option value="Kampus B">Kampus B</option>
                    <option value="Kampus C">Kampus C</option>
                </select>
            </div>

            <div class="md:col-span-2 mb-4">
                <label for="nama_warung" class="block font-semibold text-gray-600">Nama Warung</label>
                <input type="text" name="nama_warung" id="nama_warung" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="md:col-span-2">
                <label for="gambar" class="block font-semibold text-gray-600">Foto Menu</label>
                <input type="file" name="gambar" id="gambar" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-sm text-gray-500">Upload gambar menu (JPG, PNG, maks. 2MB)</p>
            </div>            <div class="md:col-span-2 flex justify-end gap-2 mt-6">
                <button type="button" onclick="window.history.back()" 
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition-all focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition-all focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Upload Menu
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Menu -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-600">Daftar Menu</h3>

        <?php if($menus->isEmpty()): ?>
            <p class="text-gray-500">Belum ada menu yang ditambahkan.</p>
        <?php else: ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border rounded-xl overflow-hidden shadow-sm bg-white">
                        <?php if($menu->gambar): ?>
                            <img src="<?php echo e(asset('storage/' . $menu->gambar)); ?>" alt="<?php echo e($menu->nama_menu); ?>" class="w-full h-48 object-cover">
                        <?php else: ?>
                            <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        <?php endif; ?>
                        <div class="p-4">
                            <h4 class="text-xl font-bold text-gray-700"><?php echo e($menu->nama_menu); ?></h4>
                            <p class="text-gray-600">Harga: Rp<?php echo e(number_format($menu->harga)); ?></p>
                            <p class="text-gray-600">Stok: <?php echo e($menu->stok); ?></p>
                            <p class="text-gray-600">Area: <?php echo e($menu->area_kampus); ?></p>
                            <p class="text-gray-600 mb-4">Warung: <?php echo e($menu->nama_warung); ?></p>
                            
                            <div class="flex justify-end space-x-2">
                                <button type="button" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition" 
                                        data-bs-toggle="modal" data-bs-target="#editMenu<?php echo e($menu->id); ?>">
                                    Edit
                                </button>
                                <form action="<?php echo e(route('penjual.menu.destroy', $menu)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition"
                                            onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editMenu<?php echo e($menu->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content bg-white rounded-lg shadow-xl">
                                <div class="modal-header border-b border-gray-200 p-4">
                                    <h5 class="text-lg font-semibold text-gray-700">Edit Menu</h5>
                                    <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal">
                                        <span class="sr-only">Close</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <form action="<?php echo e(route('penjual.menu.update', $menu)); ?>" method="POST" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="p-4 space-y-4">
                                        <?php if($menu->gambar): ?>
                                            <div class="relative">
                                                <img src="<?php echo e(asset('storage/' . $menu->gambar)); ?>" alt="<?php echo e($menu->nama_menu); ?>" class="w-full h-40 object-cover rounded">
                                                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition">
                                                    <button type="button" class="bg-red-600 text-white px-3 py-1 rounded text-sm" 
                                                            onclick="if(confirm('Hapus gambar menu ini?')) document.getElementById('hapus_gambar<?php echo e($menu->id); ?>').value = '1';">
                                                        Hapus Gambar
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" name="hapus_gambar" id="hapus_gambar<?php echo e($menu->id); ?>" value="0">
                                        <?php endif; ?>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">Foto Menu</label>
                                            <input type="file" name="gambar" accept="image/*" 
                                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">Nama Menu</label>
                                            <input type="text" name="nama_menu" value="<?php echo e($menu->nama_menu); ?>" required
                                                   class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">Harga</label>
                                            <input type="number" name="harga" value="<?php echo e($menu->harga); ?>" required
                                                   class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">Stok</label>
                                            <input type="number" name="stok" value="<?php echo e($menu->stok); ?>" required
                                                   class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">Area Kampus</label>
                                            <select name="area_kampus" required
                                                    class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="Kampus A" <?php echo e($menu->area_kampus == 'Kampus A' ? 'selected' : ''); ?>>Kampus A</option>
                                                <option value="Kampus B" <?php echo e($menu->area_kampus == 'Kampus B' ? 'selected' : ''); ?>>Kampus B</option>
                                                <option value="Kampus C" <?php echo e($menu->area_kampus == 'Kampus C' ? 'selected' : ''); ?>>Kampus C</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">Nama Warung</label>
                                            <input type="text" name="nama_warung" value="<?php echo e($menu->nama_warung); ?>" required
                                                   class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 flex justify-end space-x-3 rounded-b-lg">
                                        <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-indigo-700 transition">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\NganTeen\resources\views\penjual\kelola_menu.blade.php ENDPATH**/ ?>