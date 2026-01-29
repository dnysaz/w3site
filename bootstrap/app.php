<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckPackage;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckMaintenanceMode;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 1. Mendaftarkan alias middleware
        $middleware->alias([
            'CheckPackage' => CheckPackage::class,
            'admin'        => AdminMiddleware::class,
            'CheckMaintenance' => CheckMaintenanceMode::class,
        ]);

        // 2. KECUALIKAN ROUTE CALLBACK DARI CSRF
        $middleware->validateCsrfTokens(except: [
            'api/midtrans-callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();