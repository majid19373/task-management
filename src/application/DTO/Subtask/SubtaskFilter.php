<?php

namespace Src\application\DTO\Subtask;

final class SubtaskFilter
{
    public function __construct(
        public int $taskId,
        public bool $isPaginated = true,
        public ?int $page = 1,
        public ?int $perPage = 10,
    )
    {}
}
