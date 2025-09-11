<?php

namespace Src\Application\Queries\Task;

final readonly class FindTaskQuery
{
    public function __construct(
        public int $id,
    )
    {}
}
