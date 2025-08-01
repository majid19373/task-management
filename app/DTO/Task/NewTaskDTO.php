<?php

namespace App\DTO\Task;

final class NewTaskDTO
{
    public function __construct(
        public int $boardId,
        public string $title,
        public ?string $description = null,
        public ?string $deadline = null,
    )
    {}
}
