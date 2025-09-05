<?php

namespace Src\Application\Queries\Task;

use Src\Application\Queries\QueryInterface;

final readonly class ListTaskQuery implements QueryInterface
{
    public function __construct(
        public int $boardId,
        public ?string $priority = null,
        public ?string $status = null,
    )
    {}
}
