<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            <?php echo e(__('Update Password')); ?>

        </h2>

        <p class="mt-1 text-sm text-gray-600">
            <?php echo e(__('Ensure your account is using a long, random password to stay secure.')); ?>

        </p>
    </header>

    <form method="POST" action="<?php echo e(route('password.update')); ?>" class="mt-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label"><?php echo e(__('Current Password')); ?></label>
            <input type="password" class="form-control <?php $__errorArgs = ['current_password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   id="update_password_current_password" name="current_password" required autocomplete="current-password">
            <?php $__errorArgs = ['current_password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label"><?php echo e(__('New Password')); ?></label>
            <input type="password" class="form-control <?php $__errorArgs = ['password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   id="update_password_password" name="password" required autocomplete="new-password">
            <?php $__errorArgs = ['password', 'updatePassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label"><?php echo e(__('Confirm Password')); ?></label>
            <input type="password" class="form-control" 
                   id="update_password_password_confirmation" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary"><?php echo e(__('Save')); ?></button>
            
            <?php if(session('status') === 'password-updated'): ?>
                <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                    <?php echo e(__('Password berhasil diperbarui.')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
    </form>
</section>
<?php /**PATH C:\xampp\htdocs\NganTeen-main\resources\views/profile/partials/update-password-form.blade.php ENDPATH**/ ?>