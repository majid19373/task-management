<?php

namespace App\Tools;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as Res;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

trait Responses
{
    private function jsonResponse(mixed $data, int $statusCode): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

    public function respond(mixed $data): JsonResponse
    {
        return $this->jsonResponse([
            'status' => 'success',
            'status_code' => Res::HTTP_OK,
            'data' => $data,
        ], Res::HTTP_OK);
    }

    public function respondCreated(mixed $data = null, ?string $message = 'Successfully created.'): JsonResponse
    {
        return $this->jsonResponse([
            'status' => 'success',
            'status_code' => Res::HTTP_CREATED,
            'message' => $message,
            'data' => $data,
        ], Res::HTTP_CREATED);
    }

    public function respondUpdated(mixed $data = null, ?string $message = 'Successfully updated.'): JsonResponse
    {
        return $this->jsonResponse([
            'status' => 'success',
            'status_code' => Res::HTTP_OK,
            'message' => $message,
            'data' => $data,
        ], Res::HTTP_OK);
    }

    public function respondWithPagination(Paginator $paginate, mixed $data, ?string $message): JsonResponse
    {
        return $this->jsonResponse([
            'status' => 'success',
            'status_code' => Res::HTTP_OK,
            'message' => $message,
            'data' => $data,
            'paginator' => [
                'total_count' => $paginate->total(),
                'current_page' => $paginate->currentPage(),
                'limit' => $paginate->perPage(),
            ],
        ], Res::HTTP_OK);
    }

    public function respondException(string $message, ?int $statusCode = Res::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return $this->jsonResponse([
            'message' => $message,
            'status' => 'error',
            'status_code' => $statusCode,
        ], Res::HTTP_OK);
    }
}
