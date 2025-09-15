<?php

namespace Src\Application\Queries\Task;

final readonly class FindTaskQuery
{
    public function __construct(
        public string $id,
    )
    {}
}
