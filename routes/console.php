<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('auction:update-status')
    ->everyMinute();

Schedule::command('shop:update-open-shop-status')
    ->everyMinute();

Schedule::command('payment:update-payment-status')
    ->everyMinute();
