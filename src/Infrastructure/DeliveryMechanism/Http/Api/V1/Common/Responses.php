<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Common;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait Responses
{
    private function jsonResponse(mixed $data, int $statusCode): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

    public function respond(mixed $data, ?string $message = null): JsonResponse
    {
        return $this->jsonResponse([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'message' => $message,
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function respondCreated(mixed $data = null, ?string $message = 'Successfully created.'): JsonResponse
    {
        return $this->jsonResponse([
            'status' => 'success',
            'status_code' => Response::HTTP_CREATED,
            'message' => $message,
            'data' => $data,
        ], Response::HTTP_CREATED);
    }

    public function respondUpdated(mixed $data = null, ?string $message = 'Successfully updated.'): JsonResponse
    {
        return $this->jsonResponse([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'message' => $message,
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function respondWithPagination(array $paginate, mixed $data = null, ?string $message = null): JsonResponse
    {
        return $this->jsonResponse([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'message' => $message,
            'data' => $data,
            'paginator' => $paginate,
        ], Response::HTTP_OK);
    }

    public function respondException(?string $message = null, ?int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return $this->jsonResponse([
            'message' => $message,
            'status' => 'error',
            'status_code' => $statusCode,
        ], $statusCode);
    }
}
