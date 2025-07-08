<?php

namespace App\Services;


use App\DTO\ServicesResultDTO;

abstract class BaseService
{
    protected function successResult(mixed $data, ?string $message, ?int $statusCode): ServicesResultDTO
    {
        return ServicesResultDTO::success(data: $data, message: $message, statusCode: $statusCode);
    }

    protected function errorResult(?string $message, ?int $statusCode): ServicesResultDTO
    {
        return ServicesResultDTO::error(message: $message, statusCode: $statusCode);
    }
}
