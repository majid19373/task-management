<?php

namespace Src\Application\Commands\Subtask;

use Src\Application\Contracts\CommandInterface;

final readonly class CompleteSubtaskCommand implements CommandInterface
{

    public function __construct(
        public string $taskId,
        public string $subtaskId,
    )
    {}
}
