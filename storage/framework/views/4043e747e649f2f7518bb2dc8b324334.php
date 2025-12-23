<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <form action="<?php echo e(route('verify.store')); ?>" method="post">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="type" value="register">
        <button type="submit">
            Send OTP to your email
        </button>
    </form>

</body>

</html>
<?php /**PATH D:\xampp\htdocs\ProjectLelangGame\resources\views/pages/verification/index.blade.php ENDPATH**/ ?>