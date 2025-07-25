<?php

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        EnsureFrontendRequestsAreStateful::class;
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckUserRole::class,
        ]);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'Auth' => \App\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions)  {

        // $exceptions->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
        //     return response()->json([
        //             'message' => 'You are not authorized to access this resource.',
        //        //'responseStatus'  => 403,
        //     ],403);
        // });
        // $app->singleton(ExceptionHandler::class, Handler::class);

        // comment here
        // $exceptions->renderable(function (Throwable $e, $request) {
        //     $handler = app(Handler::class);
        //     return $handler->render($request, $e);
        // });
    })
    ->create();
