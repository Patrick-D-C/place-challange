<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            if ($this->isModelNotFound($e)) {
                return response()->json([
                    'message' => 'Registro não encontrado',
                ], 404);
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'message' => 'Recurso não encontrado',
                ], 404);
            }

            if ($e instanceof ValidationException) {
                return $this->convertValidationExceptionToResponse($e, $request);
            }
        }

        return parent::render($request, $e);
    }

    /**
     * Checks if the exception came from an Eloquent model lookup.
     */
    private function isModelNotFound(Throwable $e): bool
    {
        if ($e instanceof ModelNotFoundException) {
            return true;
        }

        return $e instanceof NotFoundHttpException
            && $e->getPrevious() instanceof ModelNotFoundException;
    }
}
