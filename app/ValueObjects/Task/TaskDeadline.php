<?php

namespace App\ValueObjects\Task;

use Carbon\Carbon;
use InvalidArgumentException;

final class TaskDeadline
{
    private Carbon $deadline;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $deadline)
    {
        $this->deadline = Carbon::make($deadline);
    }

    public function value(): ?Carbon
    {
        return $this->deadline;
    }
}
