<?php

namespace App\DTO\Subtask;

final class SubtaskFilterDTO
{
    public function __construct(
        public int $taskId,
        public bool $isPaginated = true,
        public int $perPage = 10,
    )
    {}
}
