<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verification | LelangGame</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/Logo/LogoWarna.png')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/palette.css')); ?>">
</head>
<body>
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
            <button type="submit" class="btn btn-outline-primary rounded-5 px-5 py-3 fs-5">
                <i class="bi bi-envelope-at-fill"></i> Kirimkan Kode Verifikasi Ke Email Saya
            </button>
        </form>
    </div>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\ProjectLelangGame\resources\views/pages/verification/index.blade.php ENDPATH**/ ?>