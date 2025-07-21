<?php

namespace App\ValueObjects\Task;

use App\Tools\ExceptionsTrait;
use Carbon\Carbon;
use Exception;

final class TaskDeadline
{
    use ExceptionsTrait;
    private ?Carbon $deadline = null;

    /**
     * @throws Exception
     */
    public function __construct(?string $deadline)
    {
        if ($deadline) {
            $this->deadline = Carbon::make($deadline);

            if (!$this->deadline->isFuture()) {
                $this->throwException(
                    message: 'The deadline field must be a valid date',
                );
            }
        }
    }

    public function getDeadline(): ?Carbon
    {
        return $this->deadline;
    }
}
