<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        // Register app middleware here.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e) {
            // Let Laravel keep the right status for expected errors (404, 403, 422, 401, 419, ...).
            if ($e instanceof HttpExceptionInterface
                || $e instanceof ValidationException
                || $e instanceof AuthenticationException
                || $e instanceof AuthorizationException
                || $e instanceof ModelNotFoundException
                || $e instanceof TokenMismatchException
                || $e instanceof HttpResponseException) {
                return null;
            }

            // Laravel already reports the exception to the log; the response stays generic.
            return response()->json(['error' => 'Something went wrong.'], 500);
        });
    })->create();
