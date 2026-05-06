<?php

use App\Http\Middleware\AutenticarApiInventario;
use App\Http\Middleware\CheckHasTenant;
use App\Http\Middleware\ForceHttps;
use App\Http\Middleware\InitializeTenancyFromApi;
use App\Http\Middleware\InitializeTenancyFromUser;
use App\Http\Middleware\InitializeTenant;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/central.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Force HTTPS in production and trust proxy headers
        $middleware->append(ForceHttps::class);

        $middleware->alias([
            'initializeTenant' => InitializeTenant::class,
            'checkHasTenant' => CheckHasTenant::class,
            'initializeTenancyFromUser' => InitializeTenancyFromUser::class,
            'initializeTenancyFromApi' => InitializeTenancyFromApi::class,
            'auth.api.inventario' => AutenticarApiInventario::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
