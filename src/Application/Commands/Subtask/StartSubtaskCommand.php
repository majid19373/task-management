<?php

namespace Src\Application\Commands\Subtask;

final readonly class StartSubtaskCommand
{

    public function __construct(
        public string $taskId,
        public string $subtaskId,
    )
    {}
}
