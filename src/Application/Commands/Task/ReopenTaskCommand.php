<?php

namespace Src\Application\Commands\Task;

final readonly class ReopenTaskCommand
{
    public function __construct(
        public string $id,
    )
    {}
}
