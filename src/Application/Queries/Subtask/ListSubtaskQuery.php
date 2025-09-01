<?php

namespace Src\Application\Queries\Subtask;

final readonly class ListSubtaskQuery
{
    public function __construct(
        public int $taskId,
    )
    {}
}
