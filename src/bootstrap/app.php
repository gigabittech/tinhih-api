<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use function Pest\Laravel\instance;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: '',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'verifyWorkspaceSetup' => \App\Http\Middleware\WorkspaceSetupMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {

            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }

            if ($e instanceof RouteNotFoundException) {
                return response()->json([
                    'message' => 'Route Not Found or Token expired',
                ], 404);
            }

            if ($e instanceOf ValidationException) {
                return response()->json([
                    'message' => 'Validation Error',
                    'errors' => $e->errors(),
                ], 422);
            }

            if( $e instanceOf NotFoundHttpException){
                return response()->json([
                    'message' => 'Not found',
                    'error' => $e->getMessage(),
                ], 404);
            }

            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage(),
            ], 500);
        });
    })->create();
