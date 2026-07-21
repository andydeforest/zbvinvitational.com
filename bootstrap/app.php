<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function ($exceptions) {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            $expectsJson = $request->expectsJson() || $request->is('api/*');

            if ($exception instanceof AuthenticationException) {
                if ($expectsJson) {
                    return response()->json([
                        'message' => 'Unauthenticated.',
                    ], 401);
                }

                return redirect()->guest(route('login'));
            }

            if ($expectsJson) {
                if ($exception instanceof ValidationException) {
                    return null;
                }

                if ($exception instanceof TokenMismatchException) {
                    return response()->json([
                        'message' => 'Your session has expired. Please refresh the page and try again.',
                    ], 419);
                }

                $status = $exception instanceof HttpExceptionInterface
                    ? $exception->getStatusCode()
                    : 500;

                $message = $status >= 500
                    ? (config('app.debug') ? $exception->getMessage() : 'Server Error')
                    : ($exception->getMessage() ?: (Response::$statusTexts[$status] ?? 'Request failed.'));

                return response()->json([
                    'message' => $message,
                ], $status);
            }

            // bail out and let default behavior handle certain exceptions
            if ($exception instanceof ValidationException || ! $exception instanceof HttpExceptionInterface) {
                return null;
            }

            // handle http errors on the inertia level
            return Inertia::render('Error/Index', ['status' => $response->getStatusCode()])
                ->toResponse($request)
                ->setStatusCode($response->getStatusCode());
        });
    })->create();
