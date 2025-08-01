<?php

namespace App\DTO\Subtask;

final class NewSubtaskDTO
{
    public function __construct(
        public string $title,
        public int $taskId,
        public ?string $description = null,
        public ?string $deadline = null,
    )
    {}
}
