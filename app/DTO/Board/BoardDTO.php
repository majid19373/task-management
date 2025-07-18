<?php

namespace App\DTO\Board;

use App\DTO\BaseDTO;

final class BoardDTO extends BaseDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $user_id = null,
        public ?string $name = null,
        public ?string $description = null,
    )
    {}
}
