<?php

namespace Src\Application\Queries\Board;

final readonly class PaginateBoardQuery
{
    public function __construct(
        public int  $userId,
        public ?int $page = 1,
        public ?int $perPage = 10,
    )
    {}
}
