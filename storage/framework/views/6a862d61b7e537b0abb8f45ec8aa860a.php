

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Daftar Menu</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php if($menu->image): ?>
                <img src="<?php echo e(asset('storage/'.$menu->image)); ?>" alt="<?php echo e($menu->nama_menu); ?>" class="w-full h-48 object-cover">
            <?php else: ?>
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500">No image</span>
                </div>
            <?php endif; ?>
            <div class="p-4">
                <h3 class="font-bold text-xl mb-2"><?php echo e($menu->nama_menu); ?></h3>
                <p class="text-gray-600 mb-2"><?php echo e(Str::limit($menu->description ?? 'Tidak ada deskripsi', 100)); ?></p>
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-orange-500 font-bold">Rp <?php echo e(number_format($menu->harga, 0, ',', '.')); ?></span>
                        <p class="text-sm text-gray-500">Stok: <?php echo e($menu->stok); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($menu->nama_warung); ?> - <?php echo e($menu->area_kampus); ?></p>
                    </div>
                    <a href="<?php echo e(route('menu.show', $menu)); ?>" class="bg-orange-500 text-white px-4 py-2 rounded-full hover:bg-orange-600 transition duration-300">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\NganTeen\resources\views\menu\index.blade.php ENDPATH**/ ?>