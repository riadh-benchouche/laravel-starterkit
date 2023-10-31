<?php

namespace App\Traits\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponder
{
    /**
     * @param array|string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse(array|string $message, int $code): JsonResponse
    {
        return response()->json(
            [
                'error' => ['messages' => $message],
                'success' => false,
                'message' => $message,
                'code' => $code,
            ],
            $code
        );
    }

    /**
     * A Custom handler for validation exceptions that
     * provides a general error message along with the
     * validation errors (if there are any).
     *
     * @param array|string $messageBag
     * @param int $code
     * @return JsonResponse
     */
    protected function validationResponder(array|string $messageBag, int $code): JsonResponse
    {
        return response()->json(
            [
                'errors' => $messageBag,
                'message' => __('The given data was invalid.'),
                'success' => false,
                'code' => $code,
            ],
            $code
        );
    }

    /**
     * @param ResourceCollection $collection
     * @param int $code
     * @return JsonResponse
     */
    protected function showAll(ResourceCollection $collection, int $code = 200): JsonResponse
    {
        return $this->successResponse($collection, $code);
    }

    /**
     * @param $data
     * @param int $code
     * @return JsonResponse
     */
    private function successResponse($data, int $code): JsonResponse
    {
        return response()->json(
            ['message' => $data, 'success' => true, 'code' => $code],
            $code
        );
    }

    protected function showOne(array $data, int $code = 200, string $message = null): JsonResponse
    {
        return response()->json(
            ['data' => $data, 'success' => true, 'code' => $code, 'message' => $message],
            $code
        );
    }

    /**
     * @param $data
     * @param int $code
     * @param string|null $message
     * @return JsonResponse
     */
    protected function showOneFlat($data, int $code = 200, string $message = null): JsonResponse
    {
        $meta = ['success' => true, 'code' => $code, 'message' => $message];
        $response = array_merge($meta, $data);

        return response()->json(
            $response,
            $code
        );
    }

    /**
     * @param array|string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function showMessage(array|string $message, int $code = 200): JsonResponse
    {
        return $this->successResponse($message, $code);
    }
}
