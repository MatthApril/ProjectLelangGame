<?php $__env->startSection('title', 'Verifikasi | LelangGame'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid d-flex align-items-center justify-content-center" style="height: 80vh">
        <div class="row">
            <form action="<?php echo e(route('verify.uid', $unique_id)); ?>" method="post">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="col-md-12 d-flex gap-2 text-nowrap">
                    <input type="number" class="form-control" name="otp" id="otp" placeholder="OTP!" required>
                    <button type="submit" class="btn btn-success">
                        Kirim <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </form>
        
            <form action="<?php echo e(route('verify.store')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="col-md-12 mt-3 d-grid">
                    <input type="hidden" name="type" value="<?php echo e($type); ?>">
                    <button type="submit" class="btn btn-outline-primary rounded-5 px-5">
                        <i class="bi bi-key-fill"></i> Resend OTP
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.templatepolosan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\ProjectLelangGame\resources\views/pages/verification/show.blade.php ENDPATH**/ ?>