<?php

namespace Src\Application\Commands\Task;

use Src\Application\Contracts\CommandInterface;

final readonly class StartTaskCommand implements CommandInterface
{

    public function __construct(
        public string $id,
    )
    {}
}
