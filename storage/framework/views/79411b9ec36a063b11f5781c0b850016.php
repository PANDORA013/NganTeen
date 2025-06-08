<![CDATA[<h2 class="text-xl font-semibold mb-4">Upload / Edit Menu</h2>

<div class="bg-white p-6 rounded-2xl shadow-md w-full max-w-md mx-auto">
    <form action="<?php echo e(isset($menuEdit) ? route('penjual.menu.update', $menuEdit->id) : route('penjual.menu.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($menuEdit)): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <?php if(isset($menuEdit) && $menuEdit->gambar): ?>
            <img src="<?php echo e(asset('storage/' . $menuEdit->gambar)); ?>" class="w-full h-48 object-cover rounded-lg mb-4" alt="Foto Menu">
        <?php endif; ?>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Foto Menu</label>
            <input type="file" name="gambar" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Nama Menu</label>
            <input type="text" name="nama_menu" value="<?php echo e(old('nama_menu', $menuEdit->nama_menu ?? '')); ?>" required
                class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Harga</label>
            <input type="number" name="harga" value="<?php echo e(old('harga', $menuEdit->harga ?? '')); ?>" required
                class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stok" value="<?php echo e(old('stok', $menuEdit->stok ?? '')); ?>" required
                class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Area Kampus</label>
            <select name="area_kampus" required class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
                <option value="">Pilih Area</option>
                <option value="Kampus A" <?php echo e((old('area_kampus', $menuEdit->area_kampus ?? '') == 'Kampus A') ? 'selected' : ''); ?>>Kampus A</option>
                <option value="Kampus B" <?php echo e((old('area_kampus', $menuEdit->area_kampus ?? '') == 'Kampus B') ? 'selected' : ''); ?>>Kampus B</option>
                <option value="Kampus C" <?php echo e((old('area_kampus', $menuEdit->area_kampus ?? '') == 'Kampus C') ? 'selected' : ''); ?>>Kampus C</option>
            </select>
        </div>

        <div class="flex justify-between">
            <?php if(isset($menuEdit)): ?>
                <a href="<?php echo e(route('penjual.menu.index')); ?>" class="text-sm text-red-500 hover:underline">Batal</a>
            <?php endif; ?>
            <button type="submit"
                class="ml-auto bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                <?php echo e(isset($menuEdit) ? 'Update Menu' : 'Upload Menu'); ?>

            </button>
        </div>
    </form>
</div>]]>
<?php /**PATH C:\xampp\htdocs\NganTeen\resources\views/penjual/partials/form-menu.blade.php ENDPATH**/ ?>