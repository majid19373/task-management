<?php

namespace App\Rules\Task;

use App\Enums\TaskStatusEnum;
use App\Tools\ExceptionsTrait;
use Exception;

final class CheckStatus
{
    use ExceptionsTrait;

    /**
     * @throws Exception
     */
    public function validate(?string $status): void
    {
        if ($status && !in_array($status, TaskStatusEnum::toArray())) {
            $this->throwException(
                message: 'Status task is not valid.',
            );
        }
    }
}
