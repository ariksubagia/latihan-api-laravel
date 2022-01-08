<?php

namespace App\Exceptions;

use Throwable;
use App\Exceptions\InputInvalid;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        // $this->renderable(function (NotFoundHttpException $e) {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => $e->getMessage(),
        //         'data' => NULL
        //     ], 404);
        // });

        $this->renderable(function (InputInvalid $e) {
            return response()->json([
                'status' => $e->getStatusCode(),
                'message' => $e->getMessage(),
                'data' => $e->getErrors()
            ], $e->getStatusCode());
        });

        $this->renderable(function (HttpException $e) {
            return response()->json([
                'status' => $e->getStatusCode(),
                'message' => $e->getMessage(),
                'data' => NULL
            ], $e->getStatusCode());
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
