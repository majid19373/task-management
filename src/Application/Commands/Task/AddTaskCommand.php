<?php

namespace Src\Application\Commands\Task;

final readonly class AddTaskCommand
{
    public function __construct(
        public string $boardId,
        public string $title,
        public ?string $description = null,
        public ?string $deadline = null,
    )
    {}
}
