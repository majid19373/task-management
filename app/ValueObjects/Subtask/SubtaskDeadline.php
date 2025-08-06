<?php

namespace App\ValueObjects\Subtask;

use Carbon\Carbon;
use InvalidArgumentException;

final class SubtaskDeadline
{
    private Carbon $deadline;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $deadline)
    {
        $this->deadline = Carbon::make($deadline);
    }

    public function isFuture(): bool
    {
        return $this->deadline->isFuture();
    }

    public function value(): ?Carbon
    {
        return $this->deadline;
    }
}
