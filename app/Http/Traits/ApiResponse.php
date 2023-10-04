<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Throwable;

/**
 * Trait for returning API response
 *
 * @package \App\Http\Traits
 */
trait ApiResponse
{
    /**
     * @param array $data
     * @return array
     */
    private function prepareResponseData(array $data = []) : array
    {
        $responseData = [
            'success' => $data['success'],
            'message' => $data['message'] ?? null,
        ];

        if (isset($data['data'])) {
            $responseData['data'] = $data['data'];
        }

        if (isset($data['meta'])) {
            $responseData['meta'] = $data['meta'];
        }

        if (isset($data['errors'])) {
            $responseData['errors'] = $data['errors'];
        }

        if (config('app.debug') && isset($data['exception'])) {
            $responseData['exception'] = $data['exception'];
        }

        return $responseData;
    }

    /**
     * Return generic JSON response with the given data.
     *
     * @param array $data
     * @param int   $statusCode
     * @param array $headers
     * @return JsonResponse
     */
    public function apiResponse(array $data = [], int $statusCode = 200, array $headers = []) : JsonResponse
    {
        $responseData = $this->prepareResponseData($data);

        return response()->json($responseData, $statusCode, $headers);
    }

    /**
     * @param string|null          $message
     * @param int                  $statusCode
     * @param Throwable|array|null $exception
     * @param array                $headers
     * @return JsonResponse
     */
    public function respondError(?string $message = null, int $statusCode = 400, $exception = null, array $headers = []) : JsonResponse
    {
        $responseData = [
            'success' => false,
            'message' => $message ?? __('api.common.error'),
        ];

        if ($exception instanceof Throwable) {
            $responseData['exception'] = get_exception_data($exception);
        } else {
            if (is_array($exception) && !empty($exception['error'])) {
                $exception['error'] = utf8_encode($exception['error']);
            }

            $responseData['exception'] = $exception;
        }

        return $this->apiResponse($responseData, $statusCode, $headers
        );
    }

    /**
     * @param JsonResource $resource
     * @param string|null  $message
     * @param array        $additionalMeta
     * @return JsonResponse
     */
    public function respondWithResource(JsonResource $resource, ?string $message = null, array $additionalMeta = []) : JsonResponse
    {
        $responseData = [
            'success' => true,
            'message' => $message ?? __('api.common.success'),
            'data'    => $resource,
        ];

        if (!empty($additionalMeta)) {
            $responseData['meta'] = $additionalMeta;
        }

        return $this->apiResponse($responseData);
    }

    /**
     * @param ResourceCollection $collection
     * @param string|null        $message
     * @param array              $additionalMeta
     * @return JsonResponse
     */
    public function respondWithCollection(ResourceCollection $collection, ?string $message = null, array $additionalMeta = []) : JsonResponse
    {
        $collectionData = array_from_object($collection->response()->getData());

        $responseData = [
            'success' => true,
            'message' => $message ?? __('api.common.success'),
        ];

        // Prepare 'meta' data
        if (!empty($collectionData['meta']) || !empty($collection->additionalMeta) || !empty($additionalMeta)) {
            $collectionData['meta'] = $collectionData['meta'] ?? [];

            // Remove unnecessary 'meta' parameters
            if (isset($collectionData['meta'])) {
                unset($collectionData['meta']['links'], $collectionData['meta']['path']);
            }

            // Add additional 'meta' from collection
            if (isset($collection->additionalMeta) && is_array($collection->additionalMeta)) {
                array_overwrite($collectionData['meta'], $collection->additionalMeta);;
            }

            // Add custom additional 'meta'
            if (!empty($additionalMeta)) {
                array_overwrite($collectionData['meta'], $additionalMeta);
            }
        } else {
            unset($collectionData['meta']);
        }

        return $this->apiResponse(
            array_merge($responseData, $collectionData)
        );
    }

    /**
     * @param mixed       $data
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondWithData($data, ?string $message = null) : JsonResponse
    {
        return $this->apiResponse([
            'success' => true,
            'message' => $message ?? __('api.common.success'),
            'data'    => $data,
        ]);
    }

    /**
     * @param mixed       $data
     * @param string|null $message
     * @return mixed
     */
    public function respondWithFile($data, ?string $message = null)
    {
        if (file_exists($data)) {
            return response()->download($data, basename($data), [
                'Content-Type'        => mime_content_type($data),
                'Content-Disposition' => 'attachment; filename="' . basename($data) . '"',
            ]);
        }

        if (base64_encode(base64_decode($data, true)) === $data) {
            return $this->respondWithData($data, $message);
        }

        return $this->respondFailure();
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondSuccess(?string $message = null) : JsonResponse
    {
        return $this->apiResponse([
            'success' => true,
            'message' => $message ?? __('api.common.success'),
        ]);
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondFailure(?string $message = null) : JsonResponse
    {
        return $this->respondError(
            $message ?? __('exceptions.http_400_msg')
        );
    }

    /**
     * @param array       $errors
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondValidationErrors(array $errors = [], ?string $message = null) : JsonResponse
    {
        $data = [
            'success' => false,
            'message' => $message ?? __('exceptions.http_422_msg'),
        ];

        if (!empty($errors)) {
            $data['errors'] = $errors;
        }

        return $this->apiResponse($data, 422);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function respondCreated(array $data = []) : JsonResponse
    {
        return $this->apiResponse($data, 201);
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondNoContent(?string $message) : JsonResponse
    {
        return $this->apiResponse([
            'success' => false,
            'message' => $message ?? __('exceptions.http_204_msg')
        ]);
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondUnauthorized(?string $message = null) : JsonResponse
    {
        return $this->respondError(
            $message ?? __('exceptions.http_401_msg'),
            401
        );
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondForbidden(?string $message = null) : JsonResponse
    {
        return $this->respondError(
            $message ?? __('exceptions.http_403_msg'),
            403
        );
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondNotFound(?string $message = null) : JsonResponse
    {
        return $this->respondError(
            $message ?? __('exceptions.http_404_item_msg'),
            404
        );
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondInternalError(?string $message = null) : JsonResponse
    {
        return $this->respondError(
            $message ?? __('exceptions.http_500_msg'),
            500
        );
    }
}
