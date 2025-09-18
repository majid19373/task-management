<?php

namespace Src\Application\Commands\Subtask;

final readonly class CompleteSubtaskCommand
{

    public function __construct(
        public string $taskId,
        public string $subtaskId,
    )
    {}
}
