<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verification | LelangGame</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/Logo/LogoWarna.png')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/palette.css')); ?>">
</head>

<body>

    <div class="container-fluid d-flex align-items-center justify-content-center" style="height: 80vh">
        <div class="row">
            <form action="{{ route('forgot-pwd.verify.uid', $unique_id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="col-md-12 d-flex gap-2 text-nowrap">
                    <input type="number" class="form-control" name="otp" id="otp" placeholder="OTP!"
                        required>
                    <button type="submit" class="btn btn-success">
                        Kirim <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </form>

            <form action="{{ route('forgot-pwd.resend') }}" method="post">
                @csrf
                <div class="col-md-12 mt-3 d-grid">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <button type="submit" class="btn btn-outline-primary rounded-5 px-5">
                        <i class="bi bi-key-fill"></i> Resend OTP
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
