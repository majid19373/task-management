<?php

namespace Src\application\DTO\Task;

final class NewTask
{
    public function __construct(
        public int $boardId,
        public string $title,
        public ?string $description = null,
        public ?string $deadline = null,
    )
    {}
}
