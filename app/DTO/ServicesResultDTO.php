<?php

namespace App\DTO;

use Illuminate\Http\Response as Res;

final class ServicesResultDTO
{
    public function __construct(
        public bool $success,
        public mixed $data,
        public ?string $message,
        public ?int $statusCode,
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
}
