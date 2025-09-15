<?php

namespace Src\Application\Queries\Task;

final readonly class ListTaskQuery
{
    public function __construct(
        public string $boardId,
        public ?string $priority = null,
        public ?string $status = null,
    )
    {}
}
