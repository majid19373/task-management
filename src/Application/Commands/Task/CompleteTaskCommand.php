<?php

namespace Src\Application\Commands\Task;

final readonly class CompleteTaskCommand
{
    public function __construct(
        public string $id,
    )
    {}
}
