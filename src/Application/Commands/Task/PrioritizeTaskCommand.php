<?php

namespace Src\Application\Commands\Task;

final readonly class PrioritizeTaskCommand
{

    public function __construct(
        public string $id,
        public string $priority,
    )
    {}
}
