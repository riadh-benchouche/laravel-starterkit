<?php

namespace App\Exceptions;

use App\Traits\Api\ApiResponder;
use Carbon\Exceptions\InvalidFormatException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\InvalidBase64Data;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponder;

    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        'fcm_token'
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

    public function render($request, Throwable $e): \Illuminate\Http\Response|JsonResponse|RedirectResponse|Response
    {
        if ($request->expectsJson()) {
            if ($e instanceof AuthenticationException) {
                return $this->unauthenticated($request, $e);
            }

            if ($e instanceof ValidationException) {
                return $this->convertValidationExceptionToResponse(
                    $e,
                    $request
                );
            }

            if ($e instanceof ModelNotFoundException) {
                return $this->convertModelNotFoundExceptionToResponse($e);
            }

            if ($e instanceof AuthorizationException) {
                return $this->errorResponse($e->getMessage(), 403);
            }

            if ($e instanceof NotFoundHttpException) {
                return $this->errorResponse('URL cannot found', 404);
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                return $this->errorResponse(
                    'the method for the request is invalid ',
                    405
                );
            }

            if ($e instanceof QueryException) {
                return $this->convertQueryExceptionToResponse($e);
            }

            if ($e instanceof HttpException) {
                return $this->errorResponse(
                    $e->getMessage(),
                    $e->getStatusCode()
                );
            }

            if ($e instanceof FileDoesNotExist) {
                return $this->errorResponse(
                    $e->getMessage(),
                    500
                );
            }

            if ($e instanceof FileIsTooBig) {
                return $this->errorResponse(
                    $e->getMessage(),
                    500
                );
            }

            if ($e instanceof InvalidBase64Data) {
                return $this->errorResponse(
                    $e->getMessage(),
                    500
                );
            }

            if ($e instanceof FileCannotBeAdded) {
                return $this->errorResponse(
                    $e->getMessage(),
                    500
                );
            }

            if ($e instanceof ClientException) {
                return $this->errorResponse($e->getMessage(), $e->getCode());
            }

            if ($e instanceof InvalidFormatException) {
                return $this->errorResponse($e->getMessage(), 422);
            }

            if (config('app.env') === 'production') {
                return $this->errorResponse(__('Internal error'), 500);
            }
        }

        return parent::render($request, $e);
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse|RedirectResponse|Response
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse|Response|RedirectResponse
    {
        return $request->expectsJson()
            ? $this->errorResponse('Unauthenticated', 401)
            : redirect()->guest(route('login'));
    }

    /**
     * @param ValidationException $e
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request): JsonResponse|RedirectResponse
    {
        return $request->expectsJson()
            ? $this->validationResponder($e->validator->errors()->toArray(), 422)
            : redirect()->back()->withErrors($e->validator->getMessageBag());
    }

    protected function convertModelNotFoundExceptionToResponse(
        ModelNotFoundException $e
    ): JsonResponse
    {
        $modelName = strtolower(class_basename($e->getModel()));

        return $this->errorResponse(
            "Does not exists any $modelName with the specified ID",
            404
        );
    }

    /**
     * @param QueryException $e
     * @return JsonResponse
     */
    protected function convertQueryExceptionToResponse(QueryException $e): JsonResponse
    {
        if ($e->errorInfo[1] === 1451) {
            return $this->errorResponse(
                'Cannot remove this resource permanently. it related with other resource',
                409
            );
        }

        if ($e->errorInfo[1] === 1062) {
            return $this->errorResponse(
                'Cannot create this resource. it duplicated',
                409
            );
        }
        if ($e->errorInfo[1] === 1054) {
            return $this->errorResponse(
                'Unknown column !' . $e->getMessage(),
                409
            );
        }

        return $this->errorResponse($e, 409);
    }
}
