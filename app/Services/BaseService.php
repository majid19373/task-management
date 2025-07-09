<?php

namespace App\Services;


use App\DTO\ServicesResultDTO;

abstract class BaseService
{
    protected function successResult(mixed $data = null, ?string $message = null, ?int $statusCode = null): ServicesResultDTO
    {
        return ServicesResultDTO::success(data: $data, message: $message, statusCode: $statusCode);
    }

    protected function errorResult(?string $message = null, ?int $statusCode = null): ServicesResultDTO
    {
        return ServicesResultDTO::error(message: $message, statusCode: $statusCode);
    }
}
