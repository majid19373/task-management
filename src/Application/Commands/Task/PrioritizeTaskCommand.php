<?php

namespace Src\Application\Commands\Task;

final readonly class PrioritizeTaskCommand
{

    public function __construct(
        public int    $id,
        public string $priority,
    )
    {}
}
