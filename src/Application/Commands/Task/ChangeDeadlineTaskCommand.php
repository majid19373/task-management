<?php

namespace Src\Application\Commands\Task;

final readonly class ChangeDeadlineTaskCommand
{

    public function __construct(
        public string $id,
        public string $deadline,
    )
    {}
}
