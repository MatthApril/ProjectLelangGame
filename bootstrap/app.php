<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withBroadcasting(
        __DIR__.'/../routes/channels.php',
        ['prefix' => 'api', 'middleware' => ['web', 'auth']],
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'check_role' => \App\Http\Middleware\CheckRole::class,
            'check_status' => \App\Http\Middleware\CheckStatus::class,
            'check_change_pwd_status' => \App\Http\Middleware\CheckChangePwdStatus::class,
            'check_forgot_pwd_status' => \App\Http\Middleware\CheckForgotPwdStatus::class,
            'check_expire_payment' => \App\Http\Middleware\CheckExpirePayment::class,
            'check_banned' => \App\Http\Middleware\CheckIsBanned::class,
        ]);
    })
    ->withCommands([
        App\Console\Commands\UpdateAuctionStatus::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
