<?php

namespace App\DTO\Board;

use App\DTO\BaseDTO;

final class NewBoardDTO extends BaseDTO
{
    public function __construct(
        public int $user_id,
        public string $name,
        public ?string $description = null,
    )
    {}
}
