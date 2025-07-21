<?php

namespace App\DTO\Task;

use App\DTO\BaseDTO;

final class NewTaskDTO extends BaseDTO
{
    public function __construct(
        public int $board_id,
        public string $title,
        public ?string $description = null,
        public ?string $deadline = null,
    )
    {}
}
