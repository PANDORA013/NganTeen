<?php $__env->startSection('title', __('Profile')); ?>

<?php $__env->startSection('content'); ?>
    <div class="py-4">
        <div class="container">
            <h1 class="h3 mb-4"><?php echo e(__('Profile')); ?></h1>



            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <?php echo $__env->make('profile.partials.update-profile-information-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>

                <?php if(auth()->user()->isPenjual()): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><?php echo e(__('QRIS Payment Method')); ?></h5>
                        </div>
                        <div class="card-body">
                            <?php echo $__env->make('profile.partials.qris-upload-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <?php echo e(__('Anda login sebagai pembeli. Fitur QRIS hanya tersedia untuk penjual.')); ?>

                    </div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><?php echo e(__('Update Password')); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php echo $__env->make('profile.partials.update-password-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><?php echo e(__('Delete Account')); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php echo $__env->make('profile.partials.delete-user-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user()->isPenjual() ? 'layouts.penjual' : 'layouts.pembeli', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\NganTeen-main\resources\views/profile/edit.blade.php ENDPATH**/ ?>