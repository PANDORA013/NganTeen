

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                <?php if($menu->image): ?>
                    <img src="<?php echo e(asset('storage/'.$menu->image)); ?>" alt="<?php echo e($menu->nama_menu); ?>" class="w-full h-96 object-cover">
                <?php else: ?>
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">No image</span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="p-8 md:w-1/2">
                <div class="mb-4">
                    <h1 class="text-3xl font-bold mb-2"><?php echo e($menu->nama_menu); ?></h1>
                    <p class="text-gray-600"><?php echo e($menu->description ?? 'Tidak ada deskripsi'); ?></p>
                </div>
                
                <div class="mb-6">
                    <p class="text-2xl font-bold text-orange-500 mb-2">
                        Rp <?php echo e(number_format($menu->harga, 0, ',', '.')); ?>

                    </p>
                    <p class="text-gray-600">Stok: <?php echo e($menu->stok); ?></p>
                    <p class="text-gray-600"><?php echo e($menu->nama_warung); ?> - <?php echo e($menu->area_kampus); ?></p>
                </div>

                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->role === 'pembeli' && $menu->stok > 0): ?>
                        <form action="<?php echo e(route('pembeli.cart.store')); ?>" method="POST" class="space-y-4">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="menu_id" value="<?php echo e($menu->id); ?>">
                            <div>
                                <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah</label>
                                <input type="number" name="jumlah" id="jumlah" min="1" max="<?php echo e($menu->stok); ?>" value="1" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            </div>
                            <button type="submit" class="w-full bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600 transition duration-300">
                                Tambah ke Keranjang
                            </button>
                        </form>
                    <?php elseif($menu->stok === 0): ?>
                        <div class="bg-gray-100 text-gray-600 px-6 py-3 rounded-full text-center">
                            Stok Habis
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="block w-full bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600 transition duration-300 text-center">
                        Login untuk Memesan
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\NganTeen\resources\views\menu\show.blade.php ENDPATH**/ ?>