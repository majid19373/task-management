<?php

namespace App\DTO\SubTask;

final class NewSubTaskDTO
{
    public function __construct(
        public int $boardId,
        public string $title,
        public int $taskId,
        public ?string $description = null,
        public ?string $deadline = null,
    )
    {}
}
