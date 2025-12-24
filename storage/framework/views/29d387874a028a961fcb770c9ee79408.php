<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Masuk | LelangGame</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/Logo/LogoWarna.png')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/palette.css')); ?>">
</head>

<body class="bg-light">
    <div class="container-fluid">
        <a href="<?php echo e(route('user.home')); ?>">
            <button type="submit" class="btn btn-outline-danger rounded-5 px-3 my-3">
                <i class="bi bi-caret-left-fill"></i> Kembali Ke Beranda
            </button>
        </a>
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i> <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="<?php echo e(asset('images/Logo/LogoWarna-RemoveBg.png')); ?>" alt="LelangGame Logo"
                                width="100">
                            <h1 class="card-title text-center mb-4 fw-bold">LelangGame</h1>
                        </div>
                        <form action="<?php echo e(route('do-login')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <label>Alamat Email : </label>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping"><i
                                        class="bi bi-envelope-at-fill"></i></span>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="Email" autocomplete="off" required>
                            </div>
                            <label>Password : </label>
                            <div class="input-group flex-nowrap mb-3">
                                <span class="input-group-text" id="addon-wrapping"><i class="bi bi-key-fill"></i></span>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Password" autocomplete="off" required>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" name="remember"
                                        id="remember">
                                    <label class="form-check-label" for="remember">
                                        Ingat Saya
                                    </label>
                                </div>
                                <a href="#" class="text-decoration-none text-secondary">Lupa Password?</a>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-5 my-3">Masuk</button>
                            </div>
                            <hr>
                            <div class="text-center my-3">Belum Punya Akun? <a href="<?php echo e(route('register')); ?>">Daftar
                                    Sekarang</a></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous">
    </script>
</body>

</html>
<?php /**PATH D:\xampp\htdocs\ProjectLelangGame\resources\views/pages/auth/login.blade.php ENDPATH**/ ?>