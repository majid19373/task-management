<?php

namespace Src\Application\Commands\Subtask;

use Src\Application\Contracts\CommandInterface;

final class AddSubtaskCommand implements CommandInterface
{
    public function __construct(
        public string  $title,
        public string  $taskId,
        public ?string $description = null,
        public ?string $deadline = null,
    )
    {}
}
