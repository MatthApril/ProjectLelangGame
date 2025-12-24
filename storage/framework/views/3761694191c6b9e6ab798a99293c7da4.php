<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LelangGame</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif;">
    <div style="max-width:600px; margin:40px auto; background:#ffffff; padding:30px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:25px;">
            <img src="https://res.cloudinary.com/ds4kbzrdd/image/upload/v1766537270/LogoWarna-RemoveBg_atsck7.png" alt="LelangGame Logo" height="56" style="display:block; vertical-align:middle;">
            <h1 style="margin:0; font-size:30px; font-weight:800; line-height:56px; color:#1e293b; vertical-align:middle;">
                LelangGame
            </h1>
        </div>

        <!-- Content -->
        <h4 style="margin:0 0 10px; font-size:18px; color:#334155;">
            Hai User!
        </h4>

        <p style="font-size:15px; color:#475569; line-height:1.6; margin-bottom:20px;">
            Terima kasih telah mendaftar di <strong>LelangGame</strong>.
            Untuk menyelesaikan proses pendaftaran, silakan gunakan kode OTP berikut untuk memverifikasi alamat email Anda.
        </p>

        <!-- OTP -->
        <div style="text-align:center; margin:30px 0;">
            <span style="display:inline-block; background:#2563eb; color:#ffffff; font-size:32px; font-weight:bold; letter-spacing:6px; padding:15px 30px; border-radius:8px;">
                <?php echo e($otp); ?>

            </span>
        </div>

        <p style="font-size:14px; color:#64748b;">
            Jangan berikan kode ini kepada siapapun, termasuk admin dari LelangGame. Jika terjadi masalah, segera hubungi admin LelangGame melalui email di <i>testinggdg12345@gmail.com</i>
        </p>
    </div>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\ProjectLelangGame\resources\views/emails/otp.blade.php ENDPATH**/ ?>