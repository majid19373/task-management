<?php

namespace App\DTO\Task;

final class TaskFilterDTO
{
    public function __construct(
        public int $boardId,
        public bool $isPaginated = true,
        public int $perPage = 10,
        public ?string $priority = null,
        public ?string $status = null,
    )
    {}
}
