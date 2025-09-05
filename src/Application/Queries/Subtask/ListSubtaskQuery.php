<?php

namespace Src\Application\Queries\Subtask;

use Src\Application\Queries\QueryInterface;

final readonly class ListSubtaskQuery implements QueryInterface
{
    public function __construct(
        public int $taskId,
    )
    {}
}
