<?php

namespace Src\Application\Queries\Task;

use Src\Application\Queries\QueryInterface;

final readonly class PaginateTaskQuery implements QueryInterface
{
    public function __construct(
        public int $boardId,
        public ?int $page = 0,
        public ?int $perPage = 10,
        public ?string $priority = null,
        public ?string $status = null,
    )
    {}
}
