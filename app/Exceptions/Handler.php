<?php

namespace App\Exceptions;

use Throwable;
use CustomException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        CustomException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if($request->wantsJson()) {
            if ($exception instanceof ValidationException) {
                return $this->convertValidationExceptionToResponse($exception, $request);
            }
            if ($exception instanceof ModelNotFoundException) {
                $modelName = strtolower(class_basename($exception->getModel()));
                return response()->errorResponse("Unable to find any {$modelName} with the specified ID", [], 404);
            }
            if ($exception instanceof AuthenticationException) {
                return $this->unauthenticated($request, $exception);
            }
            if ($exception instanceof AuthorizationException) {
                return response()->errorResponse($exception->getMessage(), [], 403);
            }
            if ($exception instanceof MethodNotAllowedException) {
                $method = $request->method();
                return response()->errorResponse("{$method} request method is not supported on this endpoint", [], 403);
            }
            if ($exception instanceof NotFoundHttpException) {
                return response()->errorResponse("The requested endpoint does not exist", [], 404);
            }
            if ($exception instanceof HttpException) {
                return response()->errorResponse($exception->getMessage(), [], $exception->getStatusCode());
            }
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->wantsJson()
            ? response()->errorResponse($exception->getMessage(), $exception, 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }

        return $request->expectsJson()
                    ? response()->errorResponse("One or more fields are invalid", $e->errors(), $e->status)
                    : $this->invalid($request, $e);
    }
}