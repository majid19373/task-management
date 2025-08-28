<?php

namespace Src\application\DTO\Task;

final class TaskFilter
{
    public function __construct(
        public int $boardId,
        public bool $isPaginated = true,
        public ?int $page = 0,
        public ?int $perPage = 10,
        public ?string $priority = null,
        public ?string $status = null,
    )
    {}
}
