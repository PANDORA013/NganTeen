<!-- Daftar Menu -->
<div class="bg-white rounded-xl shadow-md p-6">
    <h2 class="text-xl font-semibold mb-6">Daftar Menu</h2>

    <?php if($menus->isEmpty()): ?>
        <div class="text-center py-8">
            <p class="text-gray-500">Belum ada menu yang ditambahkan.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-6 md:grid-cols-2">
            <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
                    <?php if($menu->gambar): ?>
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="<?php echo e(asset('storage/' . $menu->gambar)); ?>" 
                                alt="<?php echo e($menu->nama_menu); ?>" 
                                class="w-full h-48 object-cover">
                        </div>
                    <?php else: ?>
                        <div class="h-48 bg-gray-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2"><?php echo e($menu->nama_menu); ?></h3>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p>Harga: Rp<?php echo e(number_format($menu->harga, 0, ',', '.')); ?></p>
                            <p>Stok: <?php echo e($menu->stok); ?></p>
                            <p>Area: <?php echo e($menu->area_kampus); ?></p>
                            <p>Warung: <?php echo e($menu->nama_warung); ?></p>
                        </div>
                        
                        <div class="mt-4 flex justify-end space-x-2">
                            <a href="<?php echo e(route('penjual.menu.edit', $menu)); ?>" 
                               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Edit
                            </a>
                            <form action="<?php echo e(route('penjual.menu.destroy', $menu)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin menghapus menu ini?')"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\NganTeen\resources\views/penjual/partials/daftar-menu.blade.php ENDPATH**/ ?>