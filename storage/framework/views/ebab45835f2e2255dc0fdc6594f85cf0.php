

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
            <span class="text-2xl mr-2">📦</span> Riwayat Pesanan
        </h2>
        
        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-4 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Pesanan #<?php echo e($order->id); ?></h3>
                        <p class="text-sm text-gray-500"><?php echo e($order->created_at->format('d M Y, H:i')); ?></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-4 py-2 rounded-full <?php echo e($order->getStatusColorClass()); ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                        <?php if($order->canBeCancelled()): ?>
                            <form action="<?php echo e(route('order.cancel', $order)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-100 text-red-800 rounded-full hover:bg-red-200 transition-colors"
                                        onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                    Batalkan
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="space-y-3">
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                            <div class="flex items-center">
                                <span class="text-lg mr-2">🍽️</span>
                                <div>
                                    <p class="font-medium text-gray-800"><?php echo e($item->menu->nama_menu); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo e($item->jumlah); ?>x @ Rp<?php echo e(number_format($item->menu->harga)); ?></p>
                                </div>
                            </div>
                            <p class="font-medium text-gray-800">Rp<?php echo e(number_format($item->subtotal)); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-800">Total Pembayaran</span>
                        <span class="text-lg font-bold text-primary">Rp<?php echo e(number_format($order->total_harga)); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-8">
                <div class="text-4xl mb-4">🍽️</div>
                <h3 class="text-xl font-medium text-gray-600 mb-2">Belum Ada Pesanan</h3>
                <p class="text-gray-500 mb-4">Anda belum memiliki riwayat pesanan</p>
                <a href="<?php echo e(route('menu.index')); ?>" class="btn-primary inline-block">
                    Mulai Pesan
                </a>
            </div>
        <?php endif; ?>

        <?php if($orders->hasPages()): ?>
            <div class="mt-6">
                <?php echo e($orders->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\NganTeen\resources\views\pembeli\riwayat.blade.php ENDPATH**/ ?>