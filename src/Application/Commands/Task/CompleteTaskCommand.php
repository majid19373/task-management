<?php

namespace Src\Application\Commands\Task;

final readonly class CompleteTaskCommand
{
    public function __construct(
        public int $id,
    )
    {}
}
