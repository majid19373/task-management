<?php

namespace App\Services;


use App\DTO\ServicesResultDTO;
use App\Exceptions\ApiException;
use Exception;
use Illuminate\Http\Response as Res;

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

    /**
     * @throws Exception
     */
    protected function throwException(?string $message = null, ?int $statusCode = null)
    {
        throw new ApiException($message, $statusCode);
    }

    /**
     * @throws Exception
     */
    protected function throwExceptionIfNotStore(mixed $data, ?string $message = 'Store failed!'): void
    {
        if (!$data || !$data->id) {
            $this->throwException(
                message: $message,
                statusCode: Res::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * @throws Exception
     */
    protected function throwExceptionIfNotUpdate(bool $isUpdate, ?string $message = 'Update failed!'): void
    {
        if (!$isUpdate) {
            $this->throwException(
                message: $message,
                statusCode: Res::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}
