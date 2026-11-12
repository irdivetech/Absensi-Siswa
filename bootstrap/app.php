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
        // Global middleware — applied to every web request
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Apply no-cache headers to all authenticated pages
        $middleware->appendToGroup('web', \App\Http\Middleware\NoCacheHeaders::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\DetectMobile::class);

        // Route middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'nocache' => \App\Http\Middleware\NoCacheHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
