<?php

namespace Src\application\DTO\Subtask;

final class NewSubtask
{
    public function __construct(
        public string $title,
        public int $taskId,
        public ?string $description = null,
        public ?string $deadline = null,
    )
    {}
}
