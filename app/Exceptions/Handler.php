<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Exceptions;

use App\Exceptions\Custom\SuperAdminDeletingException;
use App\Exceptions\Custom\SuperAdminUpdatingException;
use App\Exceptions\Custom\UserSelfDeletingException;
use App\Http\Traits\ApiResponse;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Exceptions Handler
 *
 * @package \App\Exceptions
 */
class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        UserSelfDeletingException::class,
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param Request   $request
     * @param Throwable $e
     * @return mixed
     */
    public function render($request, Throwable $e)
    {
        $exception = $this->prepareException($e);

        if ($request->expectsJson()) {
            switch (true) {
                case ($exception instanceof HttpResponseException):
                    return $exception->getResponse();
                    break;
                case ($exception instanceof AuthenticationException):
                    return $this->respondUnauthorized($exception->getMessage());
                    break;
                case ($exception instanceof AccessDeniedHttpException):
                    $previous = $exception->getPrevious();
                    $message  = ($previous && $previous->getCode() === -1) ? $exception->getMessage() : __('exceptions.http_403_msg');

                    return $this->respondForbidden($message);
                    break;
                case ($exception instanceof NotFoundHttpException):
                    $msgKey = $e instanceof ModelNotFoundException ? 'http_404_item_msg' : 'http_404_msg';

                    return $this->respondNotFound(__("exceptions.{$msgKey}"));
                    break;
                case ($exception instanceof ValidationException):
                    return $this->respondValidationErrors($exception->errors(), __('exceptions.http_422_msg'));
                    break;
                case ($exception instanceof MethodNotAllowedHttpException):
                    $message = __('exceptions.http_405_msg', [
                        'method'  => $request->getMethod(),
                        'methods' => $exception->getHeaders()['Allow']
                    ]);

                    return $this->respondError($message, $exception->getStatusCode());
                    break;
                case ($exception instanceof SuperAdminUpdatingException):
                case ($exception instanceof SuperAdminDeletingException):
                    return $this->respondError($exception->getMessage());
                    break;
                default:
                    $statusCode = $this->isHttpException($exception) ? $exception->getStatusCode() : 500;

                    return $this->respondError(
                        __("exceptions.http_{$statusCode}_msg"),
                        $statusCode,
                        get_exception_data($exception)
                    );
                    break;
            }
        } else {
            switch (true) {
                case ($exception instanceof AccessDeniedHttpException):
                    return redirect(RouteServiceProvider::HOME)
                        ->withErrors(__('exceptions.user_does_not_have_access'));
                    break;
                default:
                    return parent::render($request, $e);
                    break;
            }
        }
    }

    /**
     * Get formatted exception details
     *
     * @param Throwable $exception
     * @return array
     */
    protected function getExceptionData(Throwable $exception) : array
    {
        return function_exists('get_exception_data')
            ? get_exception_data($exception)
            : [
                'error' => $exception->getMessage(),
                'file'  => $exception->getFile(),
                'line'  => $exception->getLine(),
                'code'  => $exception->getCode(),
                'trace' => collect($exception->getTrace())->map(function ($trace) {
                    return Arr::except($trace, ['args']);
                })->all(),
            ];
    }
}
