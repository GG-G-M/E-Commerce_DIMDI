<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register your middleware aliases here
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'delivery' => \App\Http\Middleware\DeliveryMiddleware::class,
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
            'noDeliveryStore' => \App\Http\Middleware\BlockDeliveryFromStore::class,
            'logAdminActions' => \App\Http\Middleware\LogAdminActions::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
