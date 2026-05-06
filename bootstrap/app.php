<?php

use App\Http\Middleware\AutenticarApiInventario;
use App\Http\Middleware\CheckHasTenant;
use App\Http\Middleware\InitializeTenancyFromApi;
use App\Http\Middleware\InitializeTenancyFromUser;
use App\Http\Middleware\InitializeTenant;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/central.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust all proxies (Nginx reverse proxy) so Laravel detects HTTPS correctly
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO |
                Request::HEADER_X_FORWARDED_AWS_ELB
        );

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
