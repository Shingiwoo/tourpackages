<?php

use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CancelWaitingPayments;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        CancelWaitingPayments::class, // Daftarkan command di sini
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // 'roles' => \App\Http\Middleware\RoleMiddleware::class,
            'roles' => \App\Http\Middleware\RoleMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'TrustProxies' => \App\Http\Middleware\TrustProxies::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withSchedule(function (Schedule $schedule) {
        // Jalankan setiap jam
        // $schedule->command(CancelWaitingPayments::class)->hourly();
        // Atau, jalankan setiap hari pada waktu tertentu:
        $schedule->command(CancelWaitingPayments::class)->dailyAt('03:00');
    })->create();
