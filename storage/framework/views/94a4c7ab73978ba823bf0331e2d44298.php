

<?php $__env->startSection('content'); ?>
<div class="container max-w-6xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <span class="text-3xl mr-3">🍽️</span>
                <h2 class="text-2xl font-bold text-gray-800">Kelola Menu</h2>
            </div>
            <p class="text-gray-600 mb-6">Tambah, edit, atau hapus menu yang Anda jual</p>
            <a href="<?php echo e(route('penjual.menu.index')); ?>" 
               class="inline-block bg-orange-500 text-white py-2 px-6 rounded-lg font-semibold hover:bg-orange-600 transition-colors">
                Kelola Menu
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <span class="text-3xl mr-3">📋</span>
                <h2 class="text-2xl font-bold text-gray-800">Kelola Pesanan</h2>
            </div>
            <p class="text-gray-600 mb-6">Lihat dan kelola pesanan yang masuk dari pembeli</p>
            <a href="<?php echo e(route('penjual.orders.index')); ?>" 
               class="inline-block bg-orange-500 text-white py-2 px-6 rounded-lg font-semibold hover:bg-orange-600 transition-colors">
                Lihat Pesanan
            </a>
        </div>
    </div>

    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-6">
                <span class="text-3xl mr-3">📊</span>
                <h2 class="text-2xl font-bold text-gray-800">Ringkasan</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-4 bg-orange-50 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Total Menu</h3>
                    <p class="text-3xl font-bold text-orange-500"><?php echo e(Auth::user()->menus()->count()); ?></p>
                </div>
                
                <div class="p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Pesanan Aktif</h3>
                    <p class="text-3xl font-bold text-blue-500">
                        <?php echo e(Auth::user()->menus()->whereHas('orderItems.order', function($query) {
                            $query->whereIn('status', ['pending', 'processing']);
                        })->count()); ?>

                    </p>
                </div>
                
                <div class="p-4 bg-green-50 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Pesanan Selesai</h3>
                    <p class="text-3xl font-bold text-green-500">
                        <?php echo e(Auth::user()->menus()->whereHas('orderItems.order', function($query) {
                            $query->where('status', 'completed');
                        })->count()); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\NganTeen\resources\views/penjual/dashboard.blade.php ENDPATH**/ ?>