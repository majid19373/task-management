<?php

namespace Src\Application\Queries\Board;

use Src\Application\Queries\QueryInterface;

final readonly class GetBoardQuery implements QueryInterface
{
    public function __construct(
        public int $id,
    )
    {}
}
