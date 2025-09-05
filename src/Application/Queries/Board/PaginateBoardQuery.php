<?php

namespace Src\Application\Queries\Board;

use Src\Application\Queries\QueryInterface;

final readonly class PaginateBoardQuery implements QueryInterface
{
    public function __construct(
        public ?int $page = 0,
        public ?int $perPage = 10,
    )
    {}
}
