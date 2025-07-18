<?php

namespace App\DTO\Task;

use App\DTO\BaseDTO;

final class TaskFilterDTO extends BaseDTO
{
    public function __construct(
        public int $board_id,
        public bool $is_paginated = true,
        public int $per_page = 10,
        public ?string $priority = null,
        public ?string $status = null,
    )
    {}
}
