<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Illuminate\Support\Str;
class Handler extends ExceptionHandler
{
    use ApiResponser;
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
        $this->renderable(function (Throwable $e, $request) {
            if ($e->getPrevious() instanceof ModelNotFoundException) {
                $model_name = strtolower(class_basename($e->getPrevious() ->getModel()));
                $model_name=ucwords($model_name);
                return $this->errorResponse("Does not exists any {$model_name} with the specified identifier", 404);
            }
            if ($e instanceof UnauthorizedException) {
                return $this->errorResponse($e->getMessage(), 403);
            }

            if ($e instanceof AuthorizationException) {
                return $this->errorResponse($e->getMessage(), 403);
            }
            if ($e instanceof RouteNotFoundException) {
                return $this->errorResponse("Unauthorized Access  ".$e->getMessage(), 403);
            }
            if ($e instanceof AccessDeniedHttpException) {
                return $this->errorResponse($e->getMessage(), 403);
            }

            if ($e instanceof NotFoundHttpException) {
                return $this->errorResponse("Url Not Found", 404);
            }   if ($e instanceof AuthenticationException) {
                return $this->errorResponse($e->getMessage(), 401);
            }


            if ($e instanceof MethodNotAllowedHttpException) {
                return $this->errorResponse("This method is invalid for this request", 405);
            }

            if ($e instanceof HttpException) {
                return $this->errorResponse($e->getMessage(), $e->getCode());
            }
//dd($e);
            if ($e instanceof QueryException) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1451) {
                    return $this->errorResponse("Cannot remove this resource. It is related to other resource", 409);
                }
                if ($errorCode == 1265) {
                    return $this->errorResponse("Please enter valid data type", 409);
                }    if ($errorCode == 1452) {
                    return $this->errorResponse("Please enter valid data", 409);
                }   if ($errorCode == 1264) {
                    return $this->errorResponse("Please enter valid data", 409);
                }

            }

            if (!config('app.debug')) {
                return $this->errorResponse("Unexpected error", 500);
            }
        });
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        return $this->errorResponse($errors, 422);
    }
    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
//    protected function unauthenticated($request, AuthenticationException $exception)
//    {
//        return $this->errorResponse("Unauthenticated", 401);
//    }
}

