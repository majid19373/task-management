<?php

namespace App\DTO\Board;

use App\DTO\BaseDTO;

final class BoardDTO extends BaseDTO
{
    public function __construct(
        public string|int|null $id = parent::NOT_PERSISTED,
        public string|int|null $user_id = parent::NOT_PERSISTED,
        public ?string $name = parent::NOT_PERSISTED,
        public ?string $description = parent::NOT_PERSISTED,
    )
    {}
}
