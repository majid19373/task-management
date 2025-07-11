<?php

namespace App\Exceptions;

use App\Tools\Responses;
use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    use Responses;
    private int $statusCode;
    private array $data;

    public function __construct(string $message = '', int $statusCode = 400, array $data = [])
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->data = $data;
    }
    public function render(): JsonResponse
    {
        logger()->error($this->getMessage());
        return $this->respondException(
            message:  $this->getMessage(),
            statusCode: $this->statusCode,
        );
    }
}
