<?php

namespace Src\Application\Commands\Task;

final readonly class ChangeDeadlineTaskCommand
{

    public function __construct(
        public int    $id,
        public string $deadline,
    )
    {}
}
