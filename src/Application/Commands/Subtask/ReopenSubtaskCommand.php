<?php

namespace Src\Application\Commands\Subtask;

use Src\Application\Contracts\CommandInterface;

final readonly class ReopenSubtaskCommand implements CommandInterface
{
    public function __construct(
        public int $taskId,
        public int $subtaskId,
    )
    {}
}
