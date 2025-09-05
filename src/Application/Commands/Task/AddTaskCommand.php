<?php

namespace Src\Application\Commands\Task;

use Src\Application\Commands\CommandInterface;

final readonly class AddTaskCommand implements CommandInterface
{
    public function __construct(
        public int $boardId,
        public string $title,
        public ?string $description = null,
        public ?string $deadline = null,
    )
    {}
}
