<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        });
        $this->renderable(function (NotFoundHttpException $exception) {
            return response()->json([
                   'success' => false,
                   'message' => $exception->getMessage(),
              ], 404);
        });
        $this->renderable(function (ModelNotFoundException $exception) {
            return response()->json([
                   'success' => false,
                   'message' => $exception->getMessage(),
              ], 404);
        });

    }
}
