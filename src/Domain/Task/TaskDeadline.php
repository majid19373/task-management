<?php

namespace Src\Domain\Task;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\{Column, Embeddable};
use DomainException;

#[Embeddable]
final class TaskDeadline
{
    #[Column(name: "deadline", type: 'string', nullable: true)]
    private DateTimeImmutable $value;

    public function __construct(string $value)
    {
        $value = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);
        if(!$value){
            throw new DomainException('The deadline field must be a valid date.');
        }
        $this->value = $value;
    }

    public function value(): DateTimeImmutable
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }

    public function isFuture(DateTimeImmutable $currentDate): bool
    {
        return $this->value > $currentDate;
    }
}
