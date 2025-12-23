<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <form action="<?php echo e(route('doRegister')); ?>" method="post">
            <?php echo csrf_field(); ?>
            Username: <input type="text" name="username" id="username"> <br>
            Password: <input type="password" name="password" id="password"> <br>
            Email: <input type="email" name="email" id="email"> <br>
            Role:
            <select name="role" id="role">
                <option value="user">Pembeli</option>
                <option value="seller">Penjual</option>
            </select>
            <br>
            <button type="submit">Register</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.template', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\ProjectLelangGame\resources\views/auth/register.blade.php ENDPATH**/ ?>