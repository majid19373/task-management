<?php

namespace App\Rules\Task;

use App\Enums\TaskStatusEnum;
use InvalidArgumentException;

final class CheckStatus
{
    /**
     * @throws InvalidArgumentException
     */
    public function validate(?string $status): void
    {
        if ($status && !in_array($status, TaskStatusEnum::toArray())) {
            throw new InvalidArgumentException(
                message: 'Status task is not valid.',
            );
        }
    }
}
