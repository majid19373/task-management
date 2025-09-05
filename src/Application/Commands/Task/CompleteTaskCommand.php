<?php

namespace Src\Application\Commands\Task;

use Src\Application\Commands\CommandInterface;

final readonly class CompleteTaskCommand implements CommandInterface
{
    public function __construct(
        public int $id,
    )
    {}
}
