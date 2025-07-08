<?php

namespace App\DTO;

use Illuminate\Http\Response as Res;

final class ServicesResultDTO
{
    public function __construct(
        public bool $success = false,
        public mixed $data = null,
        public ?string $message = null,
        public ?int $statusCode = null,
    ) {}

    public static function success(mixed $data, ?string $message, ?int $statusCode = Res::HTTP_OK): self
    {
        return new self(
            success: true,
            data: $data,
            message: $message,
            statusCode: $statusCode,
        );
    }

    public static function error(?string $message, ?int $statusCode = Res::HTTP_INTERNAL_SERVER_ERROR): self
    {
        return new self(
            success: false,
            message: $message,
            statusCode: $statusCode,
        );
    }
}
