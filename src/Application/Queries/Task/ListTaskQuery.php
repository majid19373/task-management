<?php

namespace Src\Application\Queries\Task;

final readonly class ListTaskQuery
{
    public function __construct(
        public int $boardId,
        public ?string $priority = null,
        public ?string $status = null,
    )
    {}
}
