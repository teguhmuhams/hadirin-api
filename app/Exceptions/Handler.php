<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): JsonResponse
    {
        // This will replace the default Laravel exception page with a JSON response containing the error details
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    // public function render($request, Throwable $e): JsonResponse
    // {
    //     // If the request wants JSON (AJAX/Api call)
    //     if ($request->expectsJson()) {
    //         return response()->json([
    //             'message' => $e->getMessage(),
    //             'status'  => $e->getCode(),
    //         ]);
    //     }

    // Default to the parent class' implementation of handler


    private function handleApiException(Request $request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof \Illuminate\Http\Exceptions\HttpResponseException) {
            return $exception->getResponse();
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        // Define the response elements
        $statusCode = 500;
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        }

        $response = [];

        $response['message'] = $exception->getMessage();

        if (config('app.debug')) {
            $response['exception'] = get_class($exception);
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
            $response['trace'] = $exception->getTrace();
        }

        return response()->json($response, $statusCode);
    }
}
