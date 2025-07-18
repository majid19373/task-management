<?php

namespace App\Rules\Task;

use App\Enums\TaskPriorityEnum;
use App\Tools\ExceptionsTrait;
use Exception;

final class CheckPriority
{
    use ExceptionsTrait;

    /**
     * @throws Exception
     */
    public function validate(?string $priority): void
    {
        if ($priority && !in_array($priority, TaskPriorityEnum::toArray())) {
            $this->throwException(
                message: 'Priority task is not valid.',
            );
        }
    }
}
