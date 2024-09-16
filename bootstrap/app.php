<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'custom-auth' => \Zus1\LaravelAuth\Middleware\CustomAuth::class,
            'custom-authorize' => \Zus1\LaravelAuth\Middleware\CustomAuthorize::class,
            'inject-user-parent' => \App\Http\Middleware\InjectUserParentMiddleware::class,
            'inject-event' => \App\Http\Middleware\EventInjectMiddleware::class,
            'inject-event-parent' => \App\Http\Middleware\EventInjectParentMiddleware::class,
            'inject-message-recipient' => \App\Http\Middleware\MessageInjectRecipientParentMiddleware::class,
            'inject-media-owner'=> \App\Http\Middleware\InjectMediaOwnerMiddleware::class
        ]);
        $middleware->appendToGroup('api', [
            \App\Http\Middleware\ConvertResponseKeysToSnakeCase::class,
        ]);
        $middleware->priority([
            //...$middleware->getGlobalMiddleware(),
            \App\Http\Middleware\InjectUserParentMiddleware::class,
            \App\Http\Middleware\EventInjectMiddleware::class,
            \App\Http\Middleware\EventInjectParentMiddleware::class,
            \App\Http\Middleware\MessageInjectRecipientParentMiddleware::class,
            \App\Http\Middleware\InjectMediaOwnerMiddleware::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            //...$middleware->getMiddlewareGroups()['api'],
            //...$middleware->getMiddlewareGroups()['web'],
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
