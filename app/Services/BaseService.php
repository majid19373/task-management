<?php

namespace App\Services;


use App\DTO\ServicesResultDTO;
use App\Tools\ExceptionsTrait;
use Exception;

abstract class BaseService
{
    use ExceptionsTrait;

    protected function successResult(mixed $data = null, ?string $message = null, ?int $statusCode = null): ServicesResultDTO
    {
        return ServicesResultDTO::success(data: $data, message: $message, statusCode: $statusCode);
    }
}
