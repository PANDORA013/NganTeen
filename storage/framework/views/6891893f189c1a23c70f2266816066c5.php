

<?php $__env->startSection('content'); ?>
<div class="container max-w-6xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <span class="text-3xl mr-3">🛒</span>
                <h2 class="text-2xl font-bold text-gray-800">Keranjang Saya</h2>
            </div>
            <p class="text-gray-600 mb-6">Lihat dan kelola item di keranjang belanja Anda</p>
            <a href="<?php echo e(route('cart.index')); ?>" 
               class="inline-block bg-orange-500 text-white py-2 px-6 rounded-lg font-semibold hover:bg-orange-600 transition-colors">
                Lihat Keranjang
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <span class="text-3xl mr-3">📝</span>
                <h2 class="text-2xl font-bold text-gray-800">Riwayat Pesanan</h2>
            </div>
            <p class="text-gray-600 mb-6">Lihat status dan riwayat pesanan Anda</p>            <a href="<?php echo e(route('pembeli.orders.index')); ?>" class="inline-block bg-orange-500 text-white py-2 px-6 rounded-lg font-semibold hover:bg-orange-600 transition-colors">
                Lihat Pesanan
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center mb-6">
            <span class="text-3xl mr-3">🍽️</span>
            <h2 class="text-2xl font-bold text-gray-800">Menu Tersedia</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo e($menu->nama_menu); ?></h3>
                        <div class="text-sm text-gray-600 mb-4">
                            <p class="mb-1">Warung: <?php echo e($menu->nama_warung); ?></p>
                            <p class="mb-1">Area: <?php echo e($menu->area_kampus); ?></p>
                            <p class="mb-1">Stok: <?php echo e($menu->stok); ?></p>
                            <p class="font-semibold text-orange-500">Rp <?php echo e(number_format($menu->harga, 0, ',', '.')); ?></p>
                        </div>
                        <?php if($menu->stok > 0): ?>
                            <form action="<?php echo e(route('cart.store')); ?>" method="POST" class="flex gap-2">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="menu_id" value="<?php echo e($menu->id); ?>">
                                <input type="number" name="jumlah" value="1" min="1" max="<?php echo e($menu->stok); ?>"
                                       class="w-20 px-2 py-1 border rounded-lg focus:ring-2 focus:ring-orange-500">
                                <button type="submit" 
                                        class="flex-grow bg-orange-500 text-white py-1 px-4 rounded-lg hover:bg-orange-600 transition-colors">
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        <?php else: ?>
                            <button disabled class="w-full bg-gray-300 text-gray-500 py-1 px-4 rounded-lg cursor-not-allowed">
                                Stok Habis
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\NganTeen\resources\views\pembeli\dashboard.blade.php ENDPATH**/ ?>