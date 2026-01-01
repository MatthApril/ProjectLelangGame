<?php

use Illuminate\Support\Facades\Route;

use Mews\Captcha\Facades\Captcha;

Route::get('/reload-captcha', function () {
    return response()->json(['captcha' => captcha_src()]);
});

Route::get('/check-php', function () {
    // This will print the PHP version and whether GD is loaded
    return [
        'php_version' => phpversion(),
        'gd_installed' => extension_loaded('gd'),
        'gd_info' => extension_loaded('gd') ? gd_info() : 'Not Installed',
    ];
});

Route::redirect('/', '/home');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';
require __DIR__.'/seller.php';
require __DIR__.'/payment.php';
require __DIR__.'/notifications.php';
