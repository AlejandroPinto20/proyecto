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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'ensure.ranking' => \App\Http\Middleware\EnsureUserRanking::class,
            'log.access' => \App\Http\Middleware\LogAccess::class,
        ]);
        
        // Aplicar el middleware a todas las rutas autenticadas
        $middleware->web(append: [
            \App\Http\Middleware\EnsureUserRanking::class,
            \App\Http\Middleware\LogAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
