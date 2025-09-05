<?php

namespace Src\Application\Queries\Task;

use Src\Application\Queries\QueryInterface;

final readonly class FindTaskQuery implements QueryInterface
{
    public function __construct(
        public int $id,
    )
    {}
}
