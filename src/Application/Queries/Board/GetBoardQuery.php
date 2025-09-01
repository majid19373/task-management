<?php

namespace Src\Application\Queries\Board;

final readonly class GetBoardQuery
{
    public function __construct(
        public int $id,
    )
    {}
}
