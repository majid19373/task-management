<?php

namespace Src\Application\Commands\Subtask;

final class AddSubtaskCommand
{
    public function __construct(
        public string  $title,
        public string  $taskId,
        public ?string $description = null,
        public ?string $deadline = null,
    )
    {}
}
