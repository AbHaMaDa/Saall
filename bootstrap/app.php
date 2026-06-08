<?php

use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Visitor;



$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register your middleware alias
        $middleware->alias([
            'guest' => RedirectIfAuthenticated::class,
        ]);
        $middleware->web(append: [
            Visitor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

if ($storagePath = $_SERVER['APP_STORAGE_PATH'] ?? getenv('APP_STORAGE_PATH')) {
    $app->useStoragePath($storagePath);
}

return $app;
