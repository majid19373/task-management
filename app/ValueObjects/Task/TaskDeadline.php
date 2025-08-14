<?php

namespace App\ValueObjects\Task;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use DomainException;

final class TaskDeadline
{
    private CarbonImmutable $deadline;

    private function __construct(CarbonImmutable $deadline)
    {
        $this->deadline = $deadline;
    }

    public static function createNew(string $date, DateTimeInterface $currentDate): TaskDeadline
    {
        $deadline = CarbonImmutable::make($date);
        if(!$deadline->greaterThan($currentDate)){
           throw new DomainException('The deadline field must be a valid date');
        }
        return new self($deadline);
    }

    public function value(): ?CarbonImmutable
    {
        return $this->deadline;
    }
}
