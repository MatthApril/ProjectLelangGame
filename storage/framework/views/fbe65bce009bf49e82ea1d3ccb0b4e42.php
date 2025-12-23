<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="<?php echo e(route('verify.uid', $unique_id)); ?>" method="post">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <input type="number" name="otp" id="otp" placeholder="Enter OTP!">
        <button type="submit">
            Submit
        </button>
    </form>

    <a href="!#">
        Resend OTP
    </a>
</body>

</html>
<?php /**PATH D:\xampp\htdocs\ProjectLelangGame\resources\views/pages/verification/show.blade.php ENDPATH**/ ?>