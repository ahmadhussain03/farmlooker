<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function(ValidationException $ex, Request $request){
            if ($request->is('api/*')) {
                return response()->error($ex->errors(), $ex->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        });

        $this->renderable(function(ModelNotFoundException $ex, Request $request){
            if ($request->is('api/*')) {
                return response()->error(null, $ex->getMessage(), Response::HTTP_NOT_FOUND);
            }
        });


        $this->renderable(function(HttpException $ex, Request $request){
            if ($request->is('api/*')) {

                return response()->error(null, $ex->getMessage(), $ex->getStatusCode());
            }
        });


        $this->renderable(function(AuthenticationException $ex, Request $request){
            if ($request->is('api/*')) {
                return response()->error(null, $ex->getMessage(), 401);
            }
        });

        // $this->renderable(function(Exception $ex, Request $request){
        //     if ($request->is('api/*')) {

        //         return response()->error(null, $ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        //     }
        // });
    }
}
