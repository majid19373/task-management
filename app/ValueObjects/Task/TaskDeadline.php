<?php

namespace App\ValueObjects\Task;

use Carbon\Carbon;
use InvalidArgumentException;
use DateTimeInterface;

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

    public function isFuture(DateTimeInterface $date): bool
    {
        return $this->deadline->greaterThan($date);
    }

    public function value(): ?Carbon
    {
        return $this->deadline;
    }
}
