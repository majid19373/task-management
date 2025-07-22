<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Response;

final class ServicesResultDTO
{
    public function __construct(
        public bool $success,
        public mixed $data,
        public ?string $message,
        public ?int $statusCode,
    ) {}

    public static function success(mixed $data, ?string $message, ?int $statusCode = Response::HTTP_OK): self
    {
        return new self(
            success: true,
            data: $data,
            message: $message,
            statusCode: $statusCode,
        );
    }
}
