<?php

use App\Exceptions\Auth\LoginException;
use App\Exceptions\Auth\RegistrationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        api: __DIR__.'/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Login Exception & Registration Exception
        $exceptions->render(function (LoginException|RegistrationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['auth' => $e->getMessage()])->withInput();
        });

        // Model not found => 404
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Resource not found',
                ], 404);
            }

            return response('Not Found', 404);
        });

        // Authorization (policy/gate) => 403
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden',
                ], 403);
            }

            return back()->withErrors(['auth' => 'Você não tem permissão para executar esta ação.']);
        });

        // Authentication (not logged in) => 401 or redirect to login
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated',
                ], 401);
            }

            return redirect()->guest(route('login'));
        });

        // Validation => 422 with error bag
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        });

        // Throttle => 429 Too Many Requests
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Too Many Requests',
                ], 429);
            }

            return back()->with('toast', [
                'title' => 'Muitas requisições',
                'message' => 'Tente novamente em instantes.',
            ]);
        });

        // Generic Http exceptions
        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            $status = $e->getStatusCode();
            $payload = [
                'message' => $e->getMessage() ?: 'HTTP error',
                'status' => $status,
            ];

            if ($request->expectsJson()) {
                return response()->json($payload, $status);
            }

            // Fallback plain response to avoid relying on custom error views
            return response($payload['message'], $status);
        });

        // Optional: force JSON rendering for API routes
        // $exceptions->shouldRenderJsonWhen(fn (Request $r) => $r->is('api/*'));
    })
    ->create();
