<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserRole::class,
        ]);

        // Se registra en el grupo 'web' (no global) para que solo corra en
        // requests con sesión HTTP. El middleware ya verifica Auth::check()
        // internamente, pero limitarlo al grupo 'web' lo excluye de peticiones
        // de consola y de cualquier grupo 'api' que se añada en el futuro.
        $middleware->appendToGroup('web', \App\Http\Middleware\CerrarJornadasVencidas::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();