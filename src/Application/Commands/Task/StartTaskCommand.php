<?php

namespace Src\Application\Commands\Task;

final readonly class StartTaskCommand
{

    public function __construct(
        public string $id,
    )
    {}
}
