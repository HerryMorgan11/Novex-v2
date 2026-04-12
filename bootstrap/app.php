<?php

use App\Http\Middleware\CheckHasTenant;
use App\Http\Middleware\InitializeTenant;
use App\Http\Middleware\InitializeTenancyFromUser;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/central.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'initializeTenant' => InitializeTenant::class,
            'checkHasTenant' => CheckHasTenant::class,
            'initializeTenancyFromUser' => InitializeTenancyFromUser::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
