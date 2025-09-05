<?php

namespace Src\Application\Commands\Subtask;

use Src\Application\Commands\CommandInterface;

final class AddSubtaskCommand implements CommandInterface
{
    public function __construct(
        public string  $title,
        public int     $taskId,
        public ?string $description = null,
        public ?string $deadline = null,
    )
    {}
}
