<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <?php echo csrf_field(); ?>
        <form action="<?php echo e(route('doLogin')); ?>" method="post">
            Username: <input type="text" name="username" id="username"> <br>
            Password: <input type="password" name="password" id="password"> <br>
            <button type="submit">Login</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.template', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\ProjectLelangGame\resources\views/auth/login.blade.php ENDPATH**/ ?>