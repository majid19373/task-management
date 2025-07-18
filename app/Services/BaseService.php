<?php

namespace App\Services;


use App\DTO\ServicesResultDTO;
use Exception;

abstract class BaseService
{
    protected function successResult(mixed $data = null, ?string $message = null, ?int $statusCode = null): ServicesResultDTO
    {
        return ServicesResultDTO::success(data: $data, message: $message, statusCode: $statusCode);
    }

    /**
     * @throws Exception
     */
    protected function throwException(?string $message = null)
    {
        throw new Exception($message);
    }
}
