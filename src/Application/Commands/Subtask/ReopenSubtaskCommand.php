<?php

namespace Src\Application\Commands\Subtask;

final readonly class ReopenSubtaskCommand
{
    public function __construct(
        public int $taskId,
        public int $subtaskId,
    )
    {}
}
