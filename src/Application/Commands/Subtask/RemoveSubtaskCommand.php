<?php

namespace Src\Application\Commands\Subtask;

final readonly class RemoveSubtaskCommand
{
    public function __construct(
        public string $taskId,
        public string $subtaskId,
    )
    {}
}
