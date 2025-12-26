<?php $__env->startSection('title', 'Verifikasi | LelangGame'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('login_failed')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i> <?php echo e(session('login_failed')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="container-fluid d-flex align-items-center justify-content-center" style="height: 80vh">
        <form action="<?php echo e(route('verify.store')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="type" value="register">
            <button type="submit" class="btn btn-outline-primary rounded-5 px-5 py-3">
                <i class="bi bi-envelope-at-fill"></i> Kirimkan Kode Verifikasi Ke Email Saya
            </button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.templatepolosan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\ProjectLelangGame\resources\views/pages/verification/index.blade.php ENDPATH**/ ?>