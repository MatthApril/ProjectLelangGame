<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';
require __DIR__.'/seller.php';
